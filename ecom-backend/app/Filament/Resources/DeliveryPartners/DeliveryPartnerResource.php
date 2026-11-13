<?php

namespace App\Filament\Resources\DeliveryPartners;

use App\Filament\Resources\DeliveryPartners\Pages\CreateDeliveryPartner;
use App\Filament\Resources\DeliveryPartners\Pages\EditDeliveryPartner;
use App\Filament\Resources\DeliveryPartners\Pages\ListDeliveryPartners;
use App\Filament\Resources\DeliveryPartners\Schemas\DeliveryPartnerForm;
use App\Filament\Resources\DeliveryPartners\Tables\DeliveryPartnersTable;
use App\Models\DeliveryPartner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryPartnerResource extends Resource
{
    use \App\Filament\Traits\HasFrozenStateControl;

    protected static ?string $model = DeliveryPartner::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Courier Partners';

    protected static string|\UnitEnum|null $navigationGroup = 'Logistics & Delivery';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'finance']);
    }

    public static function form(Schema $schema): Schema
    {
        return DeliveryPartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryPartnersTable::configure($table);
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
            'index' => ListDeliveryPartners::route('/'),
            'create' => CreateDeliveryPartner::route('/create'),
            'edit' => EditDeliveryPartner::route('/{record}/edit'),
        ];
    }
}
