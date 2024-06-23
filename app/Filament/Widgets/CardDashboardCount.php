<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CardDashboardCount extends BaseWidget
{
    use InteractsWithPageFilters;


    protected static ?string $pollingInterval = null;


    protected static ?int $sort = 1;

    public function getColumns() : int
    {
        return 2;
    }

    protected function getStats(): array
    {

       // dd($this->filters);
        return [
            Stat::make('Total de Clientes', 
                        Filament::getTenant()->members->count())
                    ->icon('heroicon-m-users')
                    ->description('Clientes ao longo do ano')
                    ->descriptionColor('warning')
                    ->descriptionIcon('heroicon-m-users'),
            Stat::make('Total de Pedidos', $this->loadOrdersFiltersAndQuery()->count())

        ];
    }

    
    public function loadOrdersFiltersAndQuery()
    {
        return Order::loadWithTenat()
            ->when($this->filters['store_id'], fn($query) => $query->whereStoreId($this->filters['store_id']))
            ->when($this->filters['startDate'], fn($query) => $query->whereCreatedAt('>',$this->filters['startDate']))
            ->when($this->filters['endDate'], fn($query) => $query->whereCreatedAt('<',$this->filters['endDate']));

    }
}
