<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->prefix('Rs.'),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),

                Repeater::make('attributes')
                    ->relationship('attributes')
                    ->schema([
                        TextInput::make('name')
                            ->placeholder('e.g. Size, Color, Volume, Material')
                            ->required(),
                    ])
                    ->label('Product Attributes')
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->createItemButtonLabel('Add Attribute'),
            ]);
    }
}
