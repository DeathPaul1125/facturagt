<?php

namespace App\Orchid\Screens;

use App\Models\Company;
use App\Orchid\Layouts\CompanyListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CompanyListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'companies' => Company::paginate(10),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Listado de Empresas';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Nueva Empresa')
                ->icon('pencil')
                ->route('platform.company.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            CompanyListLayout::class,
        ];
    }
}
