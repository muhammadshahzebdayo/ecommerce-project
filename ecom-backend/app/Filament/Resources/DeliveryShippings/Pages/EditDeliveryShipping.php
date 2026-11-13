<?php

namespace App\Filament\Resources\DeliveryShippings\Pages;

use App\Filament\Resources\DeliveryShippings\DeliveryShippingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryShipping extends EditRecord
{
    protected static string $resource = DeliveryShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
