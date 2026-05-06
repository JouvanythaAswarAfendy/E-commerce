<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class CategoryResource extends Resource
{
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?string $navigationLabel = 'Kategori';
    protected static ?string $modelLabel = 'Kategori';
    protected static ?string $pluralModelLabel = 'Kategori';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Checkbox::make('is_main_category')
                            ->label('Jadikan sebagai Kategori Utama')
                            ->live()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\Checkbox $component, ?Category $record) {
                                if ($record !== null) {
                                    $component->state($record->parent_id === null);
                                } else {
                                    $component->state(!request()->filled('parent_id'));
                                }
                            }),
                        Forms\Components\Select::make('parent_id')
                            ->label('Kategori Utama')
                            ->options(\App\Models\Category::query()->whereNull('parent_id', 'and', false)->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Pilih Kategori Utama')
                            ->default(fn () => request()->query('parent_id') ? (int) request()->query('parent_id') : null)
                            ->disabled(fn (\Filament\Forms\Get $get) => (bool) $get('is_main_category'))
                            ->required(fn (\Filament\Forms\Get $get) => ! ((bool) $get('is_main_category')))
                            ->validationMessages([
                                'required' => 'Kategori utama wajib dipilih untuk sub kategori',
                            ])
                            ->dehydrated(true)
                            ->dehydrateStateUsing(fn ($state, \Filament\Forms\Get $get) => $get('is_main_category') ? null : $state),
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        $parentId = Request::query('parent_id');

        return $table
            ->headerActions([
                Tables\Actions\Action::make('back')
                    ->label('Kembali ke Kategori Utama')
                    ->icon('heroicon-m-arrow-left')
                    ->url(fn () => static::getUrl('index'))
                    ->visible(fn () => Request::filled('parent_id')),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_products')
                    ->label('Total Produk')
                    ->getStateUsing(function (Category $record) {
                        // Hitung produk di kategori ini
                        $directCount = $record->products()->count();
                        
                        // Jika ini parent, tambahkan jumlah produk dari semua anak kategorinya
                        $childrenIds = $record->children()->pluck('id');
                        $childrenCount = \App\Models\Product::query()->whereIn('category_id', $childrenIds, 'and', false)->count();
                        
                        return $directCount + $childrenCount;
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view_children')
                    ->label('Sub Kategori')
                    ->icon('heroicon-m-list-bullet')
                    ->color('info')
                    ->url(fn (Category $record) => static::getUrl('index', ['parent_id' => $record->id]))
                    ->visible(fn (Category $record) => $record->parent_id === null),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Ekspor ke'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
