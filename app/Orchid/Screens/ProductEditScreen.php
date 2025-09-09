<?php

namespace App\Orchid\Screens;

use App\Models\Product;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProductEditScreen extends Screen
{
    public $product;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Product $product): iterable
    {
        return [
            'product' => $product
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->product->exists ? ' Editar Producto' : ' Nuevo Producto';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(' Nuevo Producto')
                ->icon('plus-circle')
                ->class('btn btn-success')
                ->method('createOrUpdate')
                ->canSee(!$this->product->exists),

            Button::make(' Actualizar Producto')
                ->icon('pencil')
                ->class('btn btn-info')
                ->method('createOrUpdate')
                ->canSee($this->product->exists),

            Button::make(' Eliminar Producto')
                ->icon('trash')
                ->class('btn btn-danger')
                ->method('remove')
                ->canSee($this->product->exists),

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

                    Input::make('product.code')
                        ->title('SKU')
                        ->placeholder('Ingrese el código SKU del producto')
                        ->required(),

                    Input::make('product.name')
                        ->title('Nombre del Producto')
                        ->placeholder('Ingrese el nombre del producto')
                        ->required(),

                ])->widthColumns('1fr 2fr'),

                Group::make([
                    Input::make('product.barcode')
                        ->title('Código de Barras')
                        ->placeholder('Ingrese el código de barras del producto'),

                    Input::make('product.unit')
                        ->title('Unidad de Medida')
                        ->placeholder('Ingrese la unidad de medida (e.g., kg, lt, pcs)')
                        ->required(),

                    Select::make('product.type')
                        ->title('Tipo de Producto')
                        ->options([
                            'good' => 'Bien',
                            'service' => 'Servicio',
                        ])
                        ->placeholder('Seleccione el tipo de producto')
                        ->required(),
                ]),

                Group::make([

                    Input::make('product.cost')
                        ->title('Costo')
                        ->type('number')
                        ->step('0.01')   // dos decimales
                        ->min(0),

                    Input::make('product.price')
                        ->title('Precio')
                        ->type('number')
                        ->step('0.01')   // dos decimales
                        ->min(0),

                    Input::make('product.stock')
                        ->title('Stock')
                        ->type('number')
                        ->step('0.01')   // dos decimales
                        ->min(0),
                ]),
            ])
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $this->product->fill($request->get('product'))->save();

        Alert::info('Producto creado de forma exitosa.');

        return redirect()->route('platform.products.list');
    }
}
