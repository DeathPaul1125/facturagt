<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use Orchid\Support\Color;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Support\Facades\Http;

class CustomerEditScreen extends Screen
{

    public $customer;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Customer $customer): iterable
    {
        return [
            'customer' => $customer
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->customer->exists ? 'Editar Cliente' : 'Nuevo Cliente';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Aquí puedes ver y gestionar todos los clientes registrados en el sistema.";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Nuevo')
                ->icon('person-plus-fill')
                ->class('btn btn-success')
                ->method('createOrUpdate')
                ->canSee(!$this->customer->exists),

            Button::make('Actualizar')
                ->icon('pencil')
                ->class('btn btn-info')
                ->method('createOrUpdate')
                ->canSee($this->customer->exists),

            Button::make('Eliminar')
                ->icon('trash')
                ->class('btn btn-danger')
                ->method('remove')
                ->canSee($this->customer->exists),
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

                Button::make('Consulta Nit')
                    ->method('consultaNit')
                    ->icon('search')
                    ->class('btn btn-warning')
                    ->style('margin-top: 30px; margin-bottom: 0px;'),

                Group::make([

                    Input::make('customer.nit')
                        ->title('NIT')
                        ->placeholder('NIT del cliente')
                        ->required(),

                    Input::make('customer.name')
                        ->title('Nombre')
                        ->placeholder('Nombre del cliente'),

                ])->widthColumns('1fr 1fr 2fr'),

                Input::make('customer.email')
                    ->title('Email')
                    ->placeholder('Email del cliente'),

                Input::make('customer.phone')
                    ->title('Teléfono')
                    ->placeholder('Teléfono del cliente'),

                Input::make('customer.address')
                    ->title('Dirección')
                    ->placeholder('Dirección del cliente'),

                Input::make('customer.phone2')
                    ->title('Teléfono 2')
                    ->placeholder('Segundo teléfono del cliente'),

                Button::make('Guardar')
                    ->icon('check')
                    ->class('btn btn-primary')
                    ->method('createOrUpdate')
                    ->type(Color::BASIC),
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
        $this->customer->fill($request->get('customer'))->save();

        Alert::info('You have successfully created a post.');

        return redirect()->route('platform.customer.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->customer->delete();

        Alert::info('You have successfully deleted the post.');

        return redirect()->route('platform.customer.list');
    }

    public function consultaNit(Request $request)
    {
        $nit = $request->get('customer')['nit'];

        $response = Http::withHeaders([
            'Authorization' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IkdULjAwMDA0NDY1Mzk0OC5DSVZFUk5FVFRFU1QiLCJuYmYiOjE3NTc0NDAxMDgsImV4cCI6MTc4ODU0NDEwOCwiaWF0IjoxNzU3NDQwMTA4LCJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQ5MjIwIiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdDo0OTIyMCJ9.kOBx0bE3pkj0YTZzFmTPtgUysPckGm7_GFaSjIY3hWI',
            'Content-Type'  => 'application/json',
        ])->get('https://felgttestaws.digifact.com.gt/gt.com.fel.api.v3/api/sharedInfo', [
            'NIT'      => '44653948',
            'DATA1'    => 'SHARED_GETINFONITcom',
            'DATA2'    => 'NIT|' . $nit,
            'USERNAME' => 'CIVERNETTEST',
        ]);
        $data = $response->json();

        $name = $data['RESPONSE'][0]['NOMBRE'] ?? '';

        Alert::info('Consulta realizada, el nombre es: ' . $name);

        return redirect()->back()->withInput([
            'customer' => [
                'nit' => $nit,
                'name' => $name
            ]
        ]);
    }
}
