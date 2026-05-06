<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    #[\Livewire\Attributes\Url]
    public ?string $parent_id = null;

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        if ($this->parent_id) {
            return $query->where('parent_id', $this->parent_id);
        }

        return $query->whereNull('parent_id');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Kategori Baru')
                ->url(fn () => CategoryResource::getUrl('create', ['parent_id' => $this->parent_id])),
        ];
    }
}
