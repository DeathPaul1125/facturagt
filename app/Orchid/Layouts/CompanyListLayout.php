<?php

namespace App\Orchid\Layouts;

use App\Models\Company;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CompanyListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    public $target = 'companies';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Nombre')
                ->filter(TD::FILTER_TEXT)
                ->render(function (Company $company) {
                    return Link::make($company->name)
                        ->route('platform.company.edit', $company);
                }),
            TD::make('nit', 'NIT')->sort()->filter(TD::FILTER_TEXT),
            TD::make('email', 'Email')->sort()->filter(TD::FILTER_TEXT),
            TD::make('phone', 'Teléfono')->sort()->filter(TD::FILTER_TEXT),
            TD::make('city', 'Ciudad')->sort()->filter(TD::FILTER_TEXT),
            TD::make('country', 'País')->sort()->filter(TD::FILTER_TEXT),
        ];
    }
}
