<?php

namespace App\Filament\Resources\PhysicalStores\Pages;

use App\Filament\Resources\PhysicalStores\PhysicalStoreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhysicalStore extends EditRecord
{
    protected static string $resource = PhysicalStoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
