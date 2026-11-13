<?php

namespace App\Filament\Resources\UdharRegistries\Pages;

use App\Filament\Resources\UdharRegistries\UdharRegistryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUdharRegistries extends ListRecords
{
    protected static string $resource = UdharRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
