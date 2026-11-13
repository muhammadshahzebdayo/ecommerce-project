<?php

namespace App\Filament\Resources\DeliveryPartners\Schemas;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeliveryPartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('e.g. TCS, M&P, Leopards Courier'),
                TextInput::make('contact_person')
                    ->placeholder('e.g. Account Manager Name'),
                TextInput::make('phone')
                    ->tel()
                    ->placeholder('e.g. Phone or Helpline number'),
                Toggle::make('active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
