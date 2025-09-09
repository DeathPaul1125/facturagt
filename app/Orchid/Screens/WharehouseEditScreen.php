<?php

namespace App\Orchid\Screens;

use App\Models\Company;
use App\Models\Wharehouse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class WharehouseEditScreen extends Screen
{
    public $wharehouse;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Wharehouse $wharehouse): iterable
    {
        return [
            'wharehouse' => $wharehouse,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->wharehouse->exists ? 'Editar Almacen' : 'Nuevo Almacen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Crear Almacen')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->wharehouse->exists),
            Button::make('Actualizar')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->wharehouse->exists),
            Button::make('Eliminar')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->wharehouse->exists),
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

                    Input::make('wharehouse.name')
                        ->title('Nombre')
                        ->required(),

                    Input::make('wharehouse.address')
                        ->title('Direccion')
                        ->required(),

                    Input::make('wharehouse.phone')
                        ->title('Telefono')
                        ->type('tel'),

                    Input::make('wharehouse.phone2')
                        ->title('Telefono 2')
                        ->type('tel'),

                    Input::make('wharehouse.email')
                        ->title('Email')
                        ->type('email'),

                    Input::make('wharehouse.sat_code')
                        ->title('Codigo SAT'),

                    //agregamos el campo company_id con relacion
                    Relation::make('wharehouse.company_id')
                        ->title('Empresa')
                        ->fromModel(Company::class, 'name'),

                ])->widthColumns('1fr 2fr'),
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
        $data = $request->get('wharehouse') ?? $request->all();

        $this->wharehouse->fill($data)->save();

        Alert::info('Almacen creado de forma correcta.');

        return redirect()->route('platform.wharehouses.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->wharehouse->delete();

        Alert::info('You have successfully deleted the post.');

        return redirect()->route('platform.wharehouses.list');
    }
}
