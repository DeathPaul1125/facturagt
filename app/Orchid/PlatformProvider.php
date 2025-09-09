<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Clientes')
                ->icon('bs.person-rolodex')
                ->title('Ventas')
                ->badge(function () {
                    return \App\Models\Customer::count();
                }, Color::INFO())
                ->route('platform.customer.list'),

            Menu::make('Facturas')
                ->icon('bs.file-earmark-text')
                ->badge(function () {
                    return \App\Models\InvoiceSale::count();
                }, Color::INFO())
                ->route('platform.invoicesale.list'),

            Menu::make('Proveedores')
                ->icon('bs.person-rolodex')
                ->title('Compras')
                ->badge(function () {
                    return \App\Models\Supplier::count();
                }, Color::WARNING())
                ->route('platform.supplier.list'),

            Menu::make('Facturas')
                ->icon('bs.file-earmark-text')
                ->badge(function () {
                    return \App\Models\InvoicePurchase::count();
                }, Color::WARNING())
                ->route('platform.invoicepurchases.list'),

            Menu::make('Almacenes')
                ->title('Almacenes')
                ->icon('bs.shop')
                ->badge(function () {
                    return \App\Models\Wharehouse::count();
                }, Color::SUCCES())
                ->route('platform.wharehouses.list'),

            Menu::make('Productos')
                ->icon('bs.box-seam')
                ->badge(function () {
                    return \App\Models\Product::count();
                }, Color::SUCCES())
                ->route('platform.products.list'),

            Menu::make('Movimientos de Stock')
                ->icon('bs.boombox-fill')
                ->badge(function () {
                    return \App\Models\StockMovements::count();
                }, Color::SUCCES())
                ->route('platform.movement-stocks.list'),

            Menu::make('Configuracion')
                ->title('Empresas')
                ->icon('bs.building')
                ->badge(function () {
                    return \App\Models\Company::count();
                    }, Color::PRIMARY())
                ->route('platform.companies.list'),



            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
