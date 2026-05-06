<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\StockHistory;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class LowStockProductsTable extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Produk dengan Stok Menipis';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where(function (Builder $query) {
                        $query->where(function ($q) {
                            // Produk tanpa variasi yang stoknya rendah
                            $q->doesntHave('sizes')
                              ->where('stock', '<=', 5);
                        })->orWhereHas('sizes', function ($q) {
                            // Produk dengan variasi yang salah satu variasinya rendah
                            $q->where('stock', '<=', 5);
                        });
                    })
            )
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
            ])
            ->actions([
                Tables\Actions\Action::make('restock')
                    ->label('Restok Sekarang')
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
                            $size->stock += $data['quantity_added'];
                            $size->save();
                            
                            StockHistory::create([
                                'product_id' => $record->id,
                                'product_size_id' => $size->id,
                                'stock_before' => $oldStock,
                                'quantity_added' => $data['quantity_added'],
                                'stock_after' => $size->stock,
                            ]);
                        } else {
                            $oldStock = $record->stock;
                            $record->stock += $data['quantity_added'];
                            $record->save();
                            
                            StockHistory::create([
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
            ->emptyStateHeading('Semua stok aman')
            ->emptyStateDescription('Tidak ada produk dengan stok di bawah batas minimum (5).');
    }
}

