<?php

namespace App\Filament\Resources\PhysicalStoreLogs\Pages;

use App\Filament\Resources\PhysicalStoreLogs\PhysicalStoreLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhysicalStoreLog extends EditRecord
{
    protected static string $resource = PhysicalStoreLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
