<?php

namespace App\Filament\Resources\DeliveryShippings\Pages;

use App\Filament\Resources\DeliveryShippings\DeliveryShippingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryShippings extends ListRecords
{
    protected static string $resource = DeliveryShippingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
