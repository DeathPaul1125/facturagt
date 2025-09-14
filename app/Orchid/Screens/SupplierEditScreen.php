<?php

namespace App\Orchid\Screens;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class SupplierEditScreen extends Screen
{
    public $supplier;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Supplier $supplier): iterable
    {
        return [
            'supplier' => $supplier
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->supplier->exists ? 'Editar Proveedor' : 'Nuevo Proveedor';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Crear Proveedor')
                ->icon('person-plus-fill')
                ->class('btn btn-success')
                ->method('createOrUpdate')
                ->canSee(!$this->supplier->exists),

            Button::make('Editar Proveedor')
                ->icon('pencil')
                ->class('btn btn-warning')
                ->method('createOrUpdate')
                ->canSee($this->supplier->exists),

            Button::make('Eliminar Proveedor')
                ->icon('trash')
                ->class('btn btn-danger')
                ->method('remove')
                ->canSee($this->supplier->exists),
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

                Input::make('supplier.nit')
                    ->title('NIT')
                    ->placeholder('Ingrese el NIT del proveedor')
                    ->required(),

                Input::make('supplier.name')
                    ->title('Nombre')
                    ->placeholder('Ingrese el nombre del proveedor')
                    ->required(),

                Input::make('supplier.address')
                    ->title('Dirección')
                    ->placeholder('Ingrese la dirección del proveedor'),

                Input::make('supplier.phone')
                    ->title('Teléfono 1')
                    ->placeholder('Ingrese el primer teléfono del proveedor'),

                Input::make('supplier.phone2')
                    ->title('Teléfono 2')
                    ->placeholder('Ingrese el segundo teléfono del proveedor'),

                Input::make('supplier.email')
                    ->title('Email')
                    ->placeholder('Ingrese el email del proveedor'),

            ])
        ];
    }
    /**
     * Create or update the supplier.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $data = $request->get('supplier') ?? $request->all();

        $this->supplier->fill($data)->save();

        Alert::info('Proveedor creado de forma correcta.');

        return redirect()->route('platform.supplier.list');
    }

    /**
     * Remove the supplier.
     *
     * @param Supplier $supplier
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Supplier $supplier)
    {
        $this->supplier->delete();

        Alert::warning('Proveedor eliminado de forma correcta.');

        return redirect()->route('platform.supplier.list');
    }
}
