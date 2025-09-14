<?php

namespace App\Orchid\Layouts;

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

            TD::make('invoice_number', 'Invoice Number')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('customer_name', 'Customer Name')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('total_amount', 'Total Amount')
                ->sort()
                ->filter(TD::FILTER_NUMERIC),
            TD::make('status', 'Status')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            TD::make('created_at', 'Created At')
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function ($invoice) {
                    return $invoice->created_at->toDateString();
                }),
        ];
    }
}
