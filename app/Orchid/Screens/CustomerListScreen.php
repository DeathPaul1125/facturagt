<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Orchid\Layouts\CustomersListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CustomerListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'customers' => Customer::paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Listado de Clientes';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Aquí puedes ver y gestionar todos los clientes registrados en el sistema.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nuevo Cliente')
                ->icon('pencil')
                ->route('platform.customer.edit')
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
            CustomersListLayout::class,
        ];
    }
}
