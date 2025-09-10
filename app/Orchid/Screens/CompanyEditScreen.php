<?php

namespace App\Orchid\Screens;

use App\Models\Company;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;

class CompanyEditScreen extends Screen
{
    public $company;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Company $company): array
    {
        //$company->load('attachments');
        return [
            'company' => $company,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->company->exists ? 'Editar Empresa' : 'Nueva Empresa';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Porfavor use unicamente una empresa";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Crear Empresa')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->company->exists),

            Button::make('Editar')
                ->icon('building-fill-gear')
                ->method('createOrUpdate')
                ->canSee($this->company->exists),

            Button::make('Eliminar')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->company->exists),
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

                    Input::make('nit')
                        ->title('NIT')
                        ->placeholder('NIT')
                        ->required()
                        ->value($this->company->nit),

                    Input::make('name')
                        ->title('Nombre de la Empresa')
                        ->placeholder('Nombre de la Empresa')
                        ->required()
                        ->value($this->company->name),

                ])->widthColumns('1fr 2fr'),

                Group::make([

                    Input::make('phone')
                        ->title('Telefono 1')
                        ->placeholder('Telefono 1')
                        ->value($this->company->phone),

                    Input::make('phone2')
                        ->title('Telefono 2')
                        ->placeholder('Telefono 2')
                        ->value($this->company->phone2),

                    Input::make('email')
                        ->title('Email')
                        ->placeholder('Email')
                        ->value($this->company->email),

                ])->widthColumns('1fr 1fr 2fr'),

                Group::make([
                    TextArea::make('address')
                        ->title('Direccion')
                        ->placeholder('Direccion')
                        ->value($this->company->address),
                ])->widthColumns('100%'),

                Group::make([

                    Input::make('country')
                        ->title('Pais')
                        ->placeholder('Pais')
                        ->value($this->company->country),

                    Input::make('state')
                        ->title('Departamento')
                        ->placeholder('Departamento')
                        ->value($this->company->state),

                    Input::make('city')
                        ->title('Ciudad')
                        ->placeholder('Ciudad')
                        ->value($this->company->city),

                    Input::make('zip')
                        ->title('Codigo Postal')
                        ->placeholder('Codigo Postal')
                        ->value($this->company->zip),

                ])->widthColumns('1fr 1fr 1fr 1fr'),

            ]),

            Layout::rows([
                Group::make([

                    Input::make('user_fel')
                        ->title('Usuario FEL')
                        ->placeholder('Usuario FEL')
                        ->value($this->company->user_fel),

                    Password::make('company.password_fel')
                        ->title('Password FEL')
                        ->placeholder('Password FEL')
                        ->value($this->company->password_fel),

                    RadioButtons::make('produccion')
                        ->options([
                            1 => 'Produccion',
                            0 => 'Desarrollo',
                        ])
                        ->help('Servidor de pruebas o produccion')
                    ->value($this->company->produccion),
                ]),

                Group::make([
                    TextArea::make('token_fel')
                        ->title('Token FEL')
                        ->placeholder('Token FEL')
                        ->value($this->company->token_fel)
                        ->rows(5)
                ])->widthColumns('1fr'),

            ])->title('Configuracion FEL'),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        // Si ya existe una empresa y no es la que se está editando, no permitir crear otra
        if (!isset($this->company->id) && Company::exists()) {
            Alert::warning('Solo se permite una empresa.');
            return redirect()->route('platform.companies.list');
        }

        $data = $request->get('company') ?? $request->all();
        $this->company->fill($data)->save();

        Alert::info('Empresa guardada correctamente.');
        return redirect()->route('platform.companies.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->company->delete();

        Alert::info('Compañia eliminada de forma correcta.');

        return redirect()->route('platform.companies.list');
    }
}
