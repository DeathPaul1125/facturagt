<?php

namespace App\Orchid\Layouts;

use App\Models\Customer;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CustomersListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'customers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('nit', 'NIT')
                ->sort()
                ->filter(TD::FILTER_NUMERIC),
            TD::make('name', 'Nombre')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('address', 'Direccion')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('phone', 'Telefono')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('phone2', 'Teléfono 2')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('actions', 'Acciones')
                ->alignRight()
                ->render(function (Customer $customer) {
                    return Link::make('Editar')
                        ->class('btn btn-info')
                        ->route('platform.customer.edit', $customer);
                }),
        ];
    }
}
