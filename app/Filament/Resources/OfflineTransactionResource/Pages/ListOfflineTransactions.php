<?php

namespace App\Filament\Resources\OfflineTransactionResource\Pages;

use App\Filament\Resources\OfflineTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOfflineTransactions extends ListRecords
{
    protected static string $resource = OfflineTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Transaksi Offline Baru'),
        ];
    }
}
