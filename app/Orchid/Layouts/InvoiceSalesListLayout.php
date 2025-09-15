<?php

namespace App\Orchid\Layouts;

use App\Models\InvoiceSale;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class InvoiceSalesListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'invoice_sales';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->sort()
                ->filter(TD::FILTER_NUMERIC),

            TD::make('customer.name', 'Cliente')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($invoiceSale) {
                    return optional($invoiceSale->customer)->name;
                }),

            TD::make('invoice_sales.total_amount', 'Total')
                ->sort()
                ->filter(TD::FILTER_NUMERIC)
                ->render(function ($invoiceSale) {
                    return number_format($invoiceSale->total_amount, 2);
                }),
                
            TD::make('status', 'Estado')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($invoiceSale) {
                    $colorClass = match ($invoiceSale->status) {
                        'No-Certificada' => 'text-warning',
                        'Anulada' => 'text-danger',
                        'Certificada' => 'text-success',
                        default => ''
                    };
                    return "<span class='{$colorClass}'>{$invoiceSale->status}</span>";
                }),

            TD::make('actions', 'Acciones')
                ->alignRight()
                ->render(function (InvoiceSale $invoiceSale) {
                    return Link::make('Editar')
                        ->class('btn btn-info')
                        ->route('platform.invoice.sale.edit', $invoiceSale);
                }),
        ];
    }
}
