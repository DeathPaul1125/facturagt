<?php

namespace App\Orchid\Layouts;

use App\Models\Wharehouse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class WharehouseListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'wharehouses';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('sat_code', 'Codigo SAT')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('name', 'Nombre')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('address', 'Direccion')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('phone', 'Telefono')
                ->sort()
                ->filter(TD::FILTER_NUMERIC),

            TD::make('actions', 'Acciones')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Wharehouse $wharehouse) {
                    return Link::make('Editar')
                        ->route('platform.wharehouse.edit', $wharehouse->id)
                        ->icon('pencil');
                }),
        ];
    }
}
