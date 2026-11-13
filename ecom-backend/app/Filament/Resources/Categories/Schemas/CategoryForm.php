<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->placeholder('None (Root Category)')
                    ->default(null),
                TextInput::make('sku_prefix')
                    ->label('SKU Prefix (e.g. GSH, LUG)')
                    ->placeholder('Auto-generated if empty')
                    ->maxLength(5)
                    ->dehydrateStateUsing(fn ($state) => Str::upper($state))
                    ->default(null),
            ]);
    }
}
