<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produk')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Produk')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('parent_category_id')
                        ->label('Kategori Induk')
                        ->options(\App\Models\Category::query()->whereNull('parent_id')->pluck('name', 'id'))
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('category_id', null))
                        ->dehydrated(false)
                        ->afterStateHydrated(function (Forms\Components\Select $component, $record) {
                            if ($record && $record->category) {
                                $component->state($record->category->parent_id);
                            }
                        })
                        ->searchable(),
                    Forms\Components\Select::make('category_id')
                        ->label('Sub Kategori')
                        ->options(function (callable $get) {
                            $parentId = $get('parent_category_id');
                            if ($parentId) {
                                return \App\Models\Category::query()->where('parent_id', $parentId)->pluck('name', 'id');
                            }
                            return \App\Models\Category::query()->whereNotNull('parent_id')->pluck('name', 'id');
                        })
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('price')
                        ->label('Harga Utama')
                        ->required()
                        ->numeric()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('stock')
                        ->label('Stok Dasar (Opsional)')
                        ->numeric()
                        ->default(0)
                        ->disabled(fn (string $operation): bool => $operation !== 'create')
                        ->helperText(fn (string $operation): ?string => $operation === 'create' ? null : 'Hanya bisa diubah melalui fitur Restok'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'tersedia' => 'Tersedia',
                            'stok_menipis' => 'Stok Menipis',
                            'habis' => 'Habis',
                        ])
                        ->default('tersedia')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Hidden::make('created_by')
                        ->default(fn () => Auth::id()),
                ])->columns(2),
                
                Forms\Components\Section::make('Variasi Ukuran & Stok')->schema([
                    Forms\Components\Repeater::make('sizes')
                        ->label('Daftar Ukuran')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('size')
                                ->label('Ukuran')
                                ->options([
                                    'S' => 'S',
                                    'M' => 'M',
                                    'L' => 'L',
                                    'XL' => 'XL',
                                    'All Size' => 'All Size',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('price')
                                ->label('Harga (Opsional)')
                                ->numeric()
                                ->prefix('Rp')
                                ->helperText('Kosongkan jika sama dengan harga utama'),
                            Forms\Components\TextInput::make('stock')
                                ->label('Stok')
                                ->numeric()
                                ->default(0)
                                ->required()
                                ->disabled(fn (string $operation): bool => $operation !== 'create')
                                ->helperText(fn (string $operation): ?string => $operation === 'create' ? null : 'Hanya bisa diubah melalui fitur Restok'),
                        ])
                        ->columns(3)
                        ->grid(1)
                        ->itemLabel(fn (array $state): ?string => ($state['size'] ?? 'Ukuran') . ' - Rp ' . number_format($state['price'] ?? 0, 0, ',', '.')),
                ]),

                Forms\Components\Section::make('Gambar Produk')->schema([
                    Forms\Components\FileUpload::make('images')
                        ->label('Foto Produk')
                        ->image()
                        ->multiple()
                        ->directory('products')
                        ->disk('public')
                        ->visibility('public')
                        ->maxFiles(5)
                        ->columnSpanFull(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->limit(1),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable()
                    ->weight('bold')
                    ->description(function (Product $record) {
                        $lowStock = false;
                        if ($record->sizes->count() > 0) {
                            $lowStock = $record->sizes->where('stock', '<=', 5)->count() > 0;
                        } else {
                            $lowStock = $record->stock <= 5;
                        }
                        return $lowStock ? '⚠️ Stok Menipis' : null;
                    }),
                Tables\Columns\TextColumn::make('category.parent.name')
                    ->label('Kategori Utama')
                    ->sortable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Sub Kategori')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_stock')
                    ->label('Total Stok')
                    ->getStateUsing(function (Product $record) {
                        $sizeStock = $record->sizes()->sum('stock');
                        return $sizeStock > 0 ? $sizeStock : ($record->stock ?? 0);
                    })
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state <= 5 ? 'danger' : 'success')
                    ->description(fn (int $state): string => $state <= 5 ? 'Stok Menipis' : ''),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tersedia' => 'Tersedia',
                        'stok_menipis' => 'Stok Menipis',
                        'habis' => 'Habis',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'stok_menipis' => 'warning',
                        'habis' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Ekspor ke')
                        ->exports([
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Produk')
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Produk')
                                ->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF),
                        ]),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
