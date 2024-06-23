<?php

namespace App\Filament\Pages;

use App\Models\Store;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as DashboardFilament;
use Filament\Pages\Dashboard\Concerns\HasFilters;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends DashboardFilament
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Select::make('store_id')->options(
                fn () => Store::pluck('name', 'id')
            ),
            DatePicker::make('startDate'),
            DatePicker::make('endDate'),
        ]);
    }
}