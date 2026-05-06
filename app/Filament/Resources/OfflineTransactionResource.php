<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfflineTransactionResource\Pages;
use App\Models\OfflineTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OfflineTransactionResource extends Resource
{
    protected static ?string $model = OfflineTransaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationLabel = 'Transaksi Offline';
    protected static ?string $modelLabel = 'Transaksi Offline';
    protected static ?string $pluralModelLabel = 'Transaksi Offline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')->schema([
                    Forms\Components\TextInput::make('transaction_code')
                        ->label('Kode Transaksi')
                        ->required()
                        ->default('OFF-' . strtoupper(uniqid()))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('total_price')
                        ->label('Total Harga')
                        ->required()
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Akan dihitung otomatis dari item di bawah jika diisi'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'selesai' => 'Selesai'
                        ])
                        ->default('selesai')
                        ->required(),
                    Forms\Components\Hidden::make('seller_id')
                        ->default(fn () => Auth::id()),
                ])->columns(2),

                Forms\Components\Section::make('Detail Produk (Keranjang)')->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->label('Item Produk')
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Produk')
                                ->relationship('product', 'name')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $product = \App\Models\Product::query()->find($state);
                                    if ($product) {
                                        $set('product_name', $product->name);
                                        $set('price', $product->price);
                                    }
                                }),
                            Forms\Components\TextInput::make('product_name')
                                ->label('Nama Produk (Simpanan)')
                                ->required(),
                            Forms\Components\TextInput::make('qty')
                                ->label('Jumlah')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('subtotal', $get('price') * $state)),
                            Forms\Components\TextInput::make('price')
                                ->label('Harga Satuan')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $get, callable $set) => $set('subtotal', $get('qty') * $state)),
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->readonly(),
                        ])
                        ->columns(5)
                        ->grid(1)
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'selesai' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
                                ->withFilename(date('Y-m-d') . ' - Transaksi Offline')
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Transaksi Offline')
                                ->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF),
                        ]),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOfflineTransactions::route('/'),
            'create' => Pages\CreateOfflineTransaction::route('/create'),
            'edit' => Pages\EditOfflineTransaction::route('/{record}/edit'),
        ];
    }
}
