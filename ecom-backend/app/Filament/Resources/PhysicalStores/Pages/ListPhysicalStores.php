<?php

namespace App\Filament\Resources\PhysicalStores\Pages;

use App\Filament\Resources\PhysicalStores\PhysicalStoreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhysicalStores extends ListRecords
{
    protected static string $resource = PhysicalStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
