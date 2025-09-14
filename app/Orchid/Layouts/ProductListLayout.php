<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('code', 'Código')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('name', 'Nombre')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('description', 'Descripción')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('price', 'Precio')
                ->sort()
                ->filter(TD::FILTER_NUMERIC)
                ->render(function ($product) {
                    return '$' . number_format($product->price, 2);
                }),
            TD::make('stock', 'Stock')
                ->sort()
                ->filter(TD::FILTER_NUMERIC),
            TD::make('actions', 'Acciones')
                ->alignRight()
                ->render(function ($product) {
                    return \Orchid\Screen\Actions\Link::make('Editar')
                        ->class('btn btn-info')
                        ->route('platform.product.edit', $product);
                }),
        ];
    }
}
