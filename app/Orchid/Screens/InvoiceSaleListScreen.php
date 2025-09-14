<?php

namespace App\Orchid\Screens;

use App\Models\InvoiceSale;
use App\Orchid\Layouts\InvoiceSalesListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class InvoiceSaleListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'invoice_sales' => InvoiceSale::paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Listado de Facturas de Venta';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Aquí puedes ver y gestionar todas las facturas de venta registradas en el sistema.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nueva Factura')
                ->icon('receipt-cutoff')
                ->route('platform.invoice.sale.edit')
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
            InvoiceSalesListLayout::class,
        ];
    }
}
