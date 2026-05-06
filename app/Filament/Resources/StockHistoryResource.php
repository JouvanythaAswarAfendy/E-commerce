<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockHistoryResource\Pages;
use App\Models\StockHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;


class StockHistoryResource extends Resource
{
    protected static ?string $model = StockHistory::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?string $navigationLabel = 'Riwayat Restok';

    protected static ?string $modelLabel = 'Riwayat Restok';
    protected static ?string $pluralModelLabel = 'Riwayat Restok';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Restok')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable()
                    ->description(fn (StockHistory $record): string => $record->productSize ? 'Ukuran: ' . $record->productSize->size : ''),
                Tables\Columns\TextColumn::make('stock_before')
                    ->label('Stok Sebelum')
                    ->numeric(),
                Tables\Columns\TextColumn::make('quantity_added')
                    ->label('Jumlah Ditambahkan')
                    ->numeric()
                    ->color('success')
                    ->prefix('+'),
                Tables\Columns\TextColumn::make('stock_after')
                    ->label('Stok Sesudah')
                    ->numeric()
                    ->weight('bold'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Read-only
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Ekspor ke')
                        ->exports([
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Riwayat Restok')
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Riwayat Restok')
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
            'index' => Pages\ListStockHistories::route('/'),
        ];
    }
}
