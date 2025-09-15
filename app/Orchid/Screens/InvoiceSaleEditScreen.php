<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Models\InvoiceSale;
use App\Models\Product;
use Orchid\Alert\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class InvoiceSaleEditScreen extends Screen
{
    public $invoiceSale;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(InvoiceSale $invoiceSale): array
    {
        $this->invoiceSale = $invoiceSale;

        return [
            'invoice' => $invoiceSale,
            'lines'   =>  $invoiceSale->lines ? $invoiceSale->lines->toArray() : [
                ['product_id' => '', 'qty' => 1, 'price' => 'lines.price', 'subtotal' => 0],
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
        return $this->invoiceSale->exists ? 'Editar Factura' : 'Nueva Factura';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Guardar')
                ->icon('floppy')
                ->class('btn btn-success')
                ->method('createOrUpdate'),

            Button::make('Eliminar')
                ->icon('trash')
                ->class('btn btn-danger')
                ->method('remove')
                ->canSee($this->invoiceSale->exists),
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
            Layout::rows([

                Group::make([

                    //nit
                    Relation::make('invoice.nit')
                        ->title('NIT')
                        ->fromModel(Customer::class, 'nit')
                        ->required(),

                    Relation::make('invoice.customer_id')
                        ->title('Cliente')
                        ->fromModel(Customer::class, 'name')
                        ->required(),

                    Input::make('invoice.status')
                        ->title('Estado')
                        ->value($this->invoiceSale->status ?? 'No-Certificada')
                        ->readonly(),

                    Button::make('Certificar')
                        ->icon('cloud-upload')
                        ->method('certifyInvoice')
                        ->class('float-end')
                        ->canSee($this->invoiceSale->exists),
                ]),

                Group::make([

                DateTimer::make('invoice.date')
                    ->title('Fecha')
                    ->value(now())
                    ->required(),

                Input::make('invoice.serie_fel')
                    ->title('Serie')
                    ->readonly(),

                Input::make('invoice.number_fel')
                    ->title('Número de documento')
                    ->readonly(),

                Input::make('invoice.autorization_number_fel')
                    ->title('Número de documento')
                    ->readonly(),
                ]),

            ]),
            Layout::rows([

                Matrix::make('lines')
                    ->title('Detalle de factura')
                    ->columns([
                        'Producto' => 'product_id',
                        'Cantidad' => 'quantity',
                        'Precio Unitario' => 'unit_price',
                        'Total Línea' => 'total_price',
                    ])
                    ->fields([

                        'product_id' => Relation::make('lines[].product_id')
                            ->fromModel(Product::class, 'name')
                            ->title('Producto')
                            ->required(),

                        'quantity' => Input::make('lines[].quantity')
                            ->type('number')
                            ->required(),

                        'unit_price' => Input::make('lines[].unit_price')
                            ->type('number')
                            ->required(),

                        'total_price' => Input::make('lines[].total_price')
                            ->type('number')
                            ->readonly()
                            ->value(0),
                    ])
            ]),

            Layout::rows([
                Group::make([

                Input::make('invoice.total_amount')
                    ->title('Total')
                    ->type('number')
                    ->readonly(),

                Input::make('invoice.tax')
                    ->title('Impuesto')
                    ->type('number')
                    ->readonly(),

                ]),

            ]),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {

        $factura = $this->invoiceSale->exists
            ? $this->invoiceSale
            : new InvoiceSale();

        $factura->fill($request->get('invoice'));
        $factura->save();

        if ($this->invoiceSale->exists) {
            // Eliminar líneas existentes
            $factura->lines()->delete();
        }
        // Crear nuevas líneas, calculando total_price y sumando total_amount
        $totalAmount = 0;
        foreach ($request->get('lines') as $line) {
            $line['total_price'] = (floatval($line['quantity'] ?? 0)) * (floatval($line['unit_price'] ?? 0));
            $totalAmount += $line['total_price'];
            $factura->lines()->create($line);
        }
        $factura->total_amount = $totalAmount;
        $factura->save();

        Alert::info('Factura guardada correctamente.');

        return redirect()->route('platform.invoice.sale.list');
    }
    public function certifyInvoice()
    {
        Alert::info('Certificando la Factura.');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->invoiceSale->delete();

        Alert::info('Factura eliminada de forma correcta.');

        return redirect()->route('platform.invoice.sale.list');
    }
}
