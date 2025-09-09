<?php

namespace App\Orchid\Screens;

use App\Models\Wharehouse;
use App\Orchid\Layouts\WharehouseListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class WharehouseListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'wharehouses' => Wharehouse::paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Listado de Almacenes';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Crear Almacen')
                ->icon('pencil')
                ->route('platform.wharehouse.edit')
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
            WharehouseListLayout::class,
        ];
    }
}
