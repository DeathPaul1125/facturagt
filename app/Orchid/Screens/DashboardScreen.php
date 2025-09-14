<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Models\InvoiceSale;
use App\Models\Product;
use App\Models\Supplier;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class DashboardScreen extends Screen
{
    const TEXT_EXAMPLE = 'Texto de ejemplo';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'table'   => [
                new Repository(['id' => 100, 'name' => self::TEXT_EXAMPLE, 'price' => 10.24, 'created_at' => '01.01.2020']),
                new Repository(['id' => 200, 'name' => self::TEXT_EXAMPLE, 'price' => 65.9, 'created_at' => '01.01.2020']),
                new Repository(['id' => 300, 'name' => self::TEXT_EXAMPLE, 'price' => 754.2, 'created_at' => '01.01.2020']),
                new Repository(['id' => 400, 'name' => self::TEXT_EXAMPLE, 'price' => 0.1, 'created_at' => '01.01.2020']),
                new Repository(['id' => 500, 'name' => self::TEXT_EXAMPLE, 'price' => 0.15, 'created_at' => '01.01.2020']),

            ],
            'metrics' => [
                'customers' => ['value' => number_format(Customer::count())],
                'products'  => ['value' => number_format(Product::count())],
                'invoices'  => ['value' => number_format(InvoiceSale::count())],
                'suppliers' => ['value' => number_format(Supplier::count())]
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Tablero Principal';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Bienvenido al panel de control de la FacturaGt lite.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [

            Layout::metrics([
                'Clientes'      => 'metrics.customers',
                'Proveedores'   => 'metrics.suppliers',
                'Productos'     => 'metrics.products',
                'Facturas'      => 'metrics.invoices',
            ]),

            Layout::table('table', [
                TD::make('id', 'ID')->sort()->filter(TD::FILTER_NUMERIC),
                TD::make('name', 'Name')->sort()->filter(TD::FILTER_TEXT),
                TD::make('price', 'Price')->sort()->filter(TD::FILTER_NUMERIC),
            ])
        ];
    }
}
