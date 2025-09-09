<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\SupplierListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use App\Models\Customer;

class SupplierListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'suppliers' => Customer::paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Listado de Proveedores';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nuevo Proveedor')
                ->icon('person-plus')
                ->route('platform.supplier.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            SupplierListLayout::class,
        ];
    }
}
