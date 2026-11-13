<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FinancePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('finance')
            ->path('finance')
            ->brandName('J.G.D Finance Portal')
            ->login()
            ->profile(\App\Filament\Pages\EditProfile::class)
            ->colors([
                'primary' => Color::Blue,
            ])
            ->resources([
                \App\Filament\Resources\Expenses\ExpenseResource::class,
                \App\Filament\Resources\Employees\Employees\EmployeeResource::class,
                \App\Filament\Resources\PhysicalStores\PhysicalStoreResource::class,
                \App\Filament\Resources\PhysicalStoreLogs\PhysicalStoreLogResource::class,
                \App\Filament\Resources\UdharRegistries\UdharRegistryResource::class,
                \App\Filament\Resources\DeliveryPartners\DeliveryPartnerResource::class,
                \App\Filament\Resources\DeliveryShippings\DeliveryShippingResource::class,
                \App\Filament\Resources\Payrolls\PayrollResource::class,
            ])
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\BrandLedger::class,
            ])
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\RedirectIfNotFinanceOrAdmin::class,
            ]);
    }
}
