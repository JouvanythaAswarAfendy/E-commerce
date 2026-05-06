<?php

namespace App\Filament\Resources\OfflineTransactionResource\Pages;

use App\Filament\Resources\OfflineTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOfflineTransaction extends EditRecord
{
    protected static string $resource = OfflineTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
