<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationGroup = 'Manajemen Pesanan';
    protected static ?string $navigationLabel = 'Pesanan Online';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Status Pesanan')
                    ->options(function (Order $record = null) {
                        $options = [
                            'diproses' => 'Diproses',
                            'dikirim' => 'Dikirim',
                        ];
                        
                        if ($record) {
                            if ($record->status === 'pending') {
                                $options['pending'] = 'Belum Dibayar';
                            }
                            if ($record->status === 'selesai') {
                                $options['selesai'] = 'Selesai';
                            }
                        }
                        
                        return $options;
                    })
                    ->required()
                    ->disabled(fn (Order $record = null): bool => $record ? in_array($record->status, ['pending', 'selesai']) : false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->label('ID Pesanan')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Pelanggan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options(function (Order $record) {
                        $options = [
                            'diproses' => 'Diproses',
                            'dikirim' => 'Dikirim',
                        ];
                        
                        if ($record->status === 'pending') {
                            $options['pending'] = 'Belum Dibayar';
                        }
                        if ($record->status === 'selesai') {
                            $options['selesai'] = 'Selesai';
                        }
                        
                        return $options;
                    })
                    ->selectablePlaceholder(false)
                    ->disabled(fn (Order $record): bool => in_array($record->status, ['pending', 'selesai'])),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Ekspor ke')
                        ->exports([
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Pesanan')
                                ->withWriterType(\Maatwebsite\Excel\Excel::XLSX),
                            \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                                ->fromTable()
                                ->withFilename(date('Y-m-d') . ' - Pesanan')
                                ->withWriterType(\Maatwebsite\Excel\Excel::DOMPDF),
                        ]),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pelanggan')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')->label('Nama'),
                        Infolists\Components\TextEntry::make('user.email')->label('Email'),
                        Infolists\Components\TextEntry::make('shipping_address')->label('Alamat Pengiriman'),
                    ])->columns(3),
                    
                Infolists\Components\Section::make('Ringkasan Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_id')->label('ID Pesanan'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'diproses' => 'info',
                                'dikirim' => 'primary',
                                'selesai' => 'success',
                                'dibatalkan' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Belum Dibayar',
                                'diproses' => 'Diproses',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('total_price')->label('Total Harga')->money('IDR', locale: 'id'),
                        Infolists\Components\TextEntry::make('created_at')->label('Tanggal Pesan')->dateTime('d M Y H:i'),
                    ])->columns(4),

                Infolists\Components\Section::make('Item yang Dipesan')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')->label('Produk'),
                                Infolists\Components\TextEntry::make('quantity')->label('Jumlah'),
                                Infolists\Components\TextEntry::make('price')->label('Harga Satuan')->money('IDR', locale: 'id'),
                                Infolists\Components\TextEntry::make('subtotal')->label('Subtotal')->money('IDR', locale: 'id'),
                            ])
                            ->columns(4)
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('status', '!=', 'dibatalkan');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
