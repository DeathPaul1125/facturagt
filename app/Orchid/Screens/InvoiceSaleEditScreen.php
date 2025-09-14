<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Models\InvoiceSale;
use App\Models\InvoiceSaleLines;
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
use Orchid\Screen\TD;
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
        $this->exists = $invoiceSale->exists;

        return [
            'invoiceSale' => $invoiceSale,
            'lines' => $invoiceSale->lines
                ? $invoiceSale->lines->map(function ($line) {
                    return [
                        'product_id' => $line->product_id,
                        'product_name' => $line->product->name,
                        'quantity'   => $line->quantity,
                        'price'      => $line->price,
                        'subtotal'   => $line->subtotal,
                    ];
                })->toArray()
                : [],
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
                ->method('save'),
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

                    Relation::make('invoiceSale.customer_id')
                        ->title('Cliente')
                        ->fromModel(Customer::class, 'name'),

                    DateTimer::make('invoiceSale.date')
                        ->title('Fecha'),

                    Input::make('invoiceSale.total')
                        ->title('Total')
                        ->readonly(),
                ])->widthColumns('1fr 1fr 1fr'),

            ]),

            // Tabla de líneas
            Layout::rows([
                Matrix::make('lines')
                    ->title('Detalle de Productos')
                    ->columns([
                        'product_id' => 'Producto',
                        'quantity'   => 'Cantidad',
                        'price'      => 'Precio',
                        'subtotal'   => 'Subtotal',
                    ]),
            ]),
        ];
    }

    public function save(InvoiceSale $invoiceSale, Request $request)
    {
        dd('entro'); // 🔥 simple para ver si llega
        // Guardar encabezado
        $invoiceSale->fill($request->get('invoiceSale'))->save();

        // Borrar líneas anteriores
        $invoiceSale->lines()->delete();

        // Obtener nuevas líneas
        $lines = $request->get('lines', []);

        foreach ($lines as $line) {
            $product = Product::find($line['product_id']);

            if (!$product) {
                continue;
            }

            if ($product->stock < $line['quantity']) {
                Toast::error("No hay suficiente stock para {$product->name}");
                continue;
            }

            $invoiceSale->lines()->create([
                'product_id' => $product->id,
                'quantity'   => $line['quantity'],
                'price'      => $product->price,
                'subtotal'   => $product->price * $line['quantity'],
            ]);
        }

        Toast::info('Factura guardada con éxito.');
    }
}
