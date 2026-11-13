<?php

namespace App\Filament\Resources\DeliveryShippings;

use App\Filament\Resources\DeliveryShippings\Pages\CreateDeliveryShipping;
use App\Filament\Resources\DeliveryShippings\Pages\EditDeliveryShipping;
use App\Filament\Resources\DeliveryShippings\Pages\ListDeliveryShippings;
use App\Filament\Resources\DeliveryShippings\Schemas\DeliveryShippingForm;
use App\Filament\Resources\DeliveryShippings\Tables\DeliveryShippingsTable;
use App\Models\DeliveryShipping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryShippingResource extends Resource
{
    use \App\Filament\Traits\HasFrozenStateControl;

    protected static ?string $model = DeliveryShipping::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'COD & Settlements';

    protected static string|\UnitEnum|null $navigationGroup = 'Logistics & Delivery';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'tracking_number';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'finance']);
    }

    public static function form(Schema $schema): Schema
    {
        return DeliveryShippingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryShippingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveryShippings::route('/'),
            'create' => CreateDeliveryShipping::route('/create'),
            'edit' => EditDeliveryShipping::route('/{record}/edit'),
        ];
    }
}
