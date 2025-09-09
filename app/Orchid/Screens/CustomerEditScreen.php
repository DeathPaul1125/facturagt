<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

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
            Button::make('Crear Cliente')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->customer->exists),

            Button::make('Actualizar Cliente')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->customer->exists),

            Button::make('Eliminar Cliente')
                ->icon('trash')
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

        ];
    }
}
