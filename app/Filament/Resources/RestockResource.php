<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestockResource\Pages;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockHistory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class RestockResource extends Resource

{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Manajemen Stok';
    protected static ?string $navigationLabel = 'Restok';
    protected static ?string $modelLabel = 'Restok';
    protected static ?string $pluralModelLabel = 'Restok';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function (Builder $query) {
                $query->where(function ($q) {
                    $q->doesntHave('sizes')
                      ->where('stock', '<=', 5);
                })->orWhereHas('sizes', function ($q) {
                    $q->where('stock', '<=', 5);
                });
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Foto')
                    ->disk('public')
                    ->limit(1)
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->weight('bold')
                    ->description(fn (Product $record) => $record->sizes->count() > 0 
                        ? 'Memiliki variasi ukuran' 
                        : 'Tanpa variasi'),
                Tables\Columns\TextColumn::make('low_stock_details')
                    ->label('Detail Stok Rendah')
                    ->getStateUsing(function (Product $record) {
                        if ($record->sizes->count() > 0) {
                            return $record->sizes->where('stock', '<=', 5)
                                ->map(fn ($s) => "{$s->size}: {$s->stock}")
                                ->implode(', ');
                        }
                        return "Stok: {$record->stock}";
                    })
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'stok menipis' => 'warning',
                        'habis' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('restock')
                    ->label('Restok')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('product_size_id')
                            ->label('Pilih Ukuran')
                            ->options(fn (Product $record) => $record->sizes->where('stock', '<=', 5)->pluck('size', 'id'))
                            ->visible(fn (Product $record) => $record->sizes->count() > 0)
                            ->required(fn (Product $record) => $record->sizes->count() > 0),
                        Forms\Components\TextInput::make('quantity_added')
                            ->label('Jumlah Tambah Stok')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ])
                    ->action(function (Product $record, array $data) {
                        if ($data['product_size_id'] ?? null) {
                            $size = ProductSize::query()->find($data['product_size_id']);
                            $oldStock = $size->stock;
                            $size->increment('stock', $data['quantity_added'], []);
                            
                            StockHistory::query()->create([
                                'product_id' => $record->id,
                                'product_size_id' => $size->id,
                                'stock_before' => $oldStock,
                                'quantity_added' => $data['quantity_added'],
                                'stock_after' => $size->stock,
                            ]);
                        } else {
                            $oldStock = $record->stock;
                            $record->increment('stock', $data['quantity_added'], []);
                            
                            StockHistory::query()->create([
                                'product_id' => $record->id,
                                'stock_before' => $oldStock,
                                'quantity_added' => $data['quantity_added'],
                                'stock_after' => $record->stock,
                            ]);
                        }

                        // Update status otomatis
                        $totalStock = $record->sizes()->sum('stock') ?: $record->stock;
                        if ($totalStock > 5) {
                            $record->update(['status' => 'tersedia']);
                        } elseif ($totalStock > 0) {
                            $record->update(['status' => 'stok menipis']);
                        } else {
                            $record->update(['status' => 'habis']);
                        }
                        
                        Notification::make()
                            ->title('Berhasil restok produk')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Ekspor ke')
                        ->exports([
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Restok')
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Restok')
                                ->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF),
                        ]),
                ]),
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestocks::route('/'),
        ];
    }
}
