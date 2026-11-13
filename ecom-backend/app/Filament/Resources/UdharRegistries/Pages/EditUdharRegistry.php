<?php

namespace App\Filament\Resources\UdharRegistries\Pages;

use App\Filament\Resources\UdharRegistries\UdharRegistryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUdharRegistry extends EditRecord
{
    protected static string $resource = UdharRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
