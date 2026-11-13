<?php

namespace App\Filament\Resources\PhysicalStoreLogs\Pages;

use App\Filament\Resources\PhysicalStoreLogs\PhysicalStoreLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhysicalStoreLogs extends ListRecords
{
    protected static string $resource = PhysicalStoreLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
