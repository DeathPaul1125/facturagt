<?php

namespace App\Orchid\Layouts;

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
            TD::make('email', 'Email')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('phone', 'Teléfono')
                ->sort()
                ->filter(TD::FILTER_TEXT),
        ];
    }
}
