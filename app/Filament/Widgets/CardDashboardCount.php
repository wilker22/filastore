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

    protected static ?int $sort = 1;

    public function getColumns() : int
    {
        return 2;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total de Clientes', Filament::getTenant()->members->count())
                    ->icon('heroicon-m-users')
                    ->description('Clientes ao longo do ano'),
            Stat::make('Total de Pedidos', $this->loadOrderFiltersAndQuery()->count())

        ];
    }


    public function loadOrderFiltersAndQuery()
    {
        return Order::loadWithTenat()
            ->when($this->filters['store_id'], fn($query) => $query->whereStoreId($this->filters['store_id']))
            ->when($this->filters['startDate'], fn($query) => $query->whereStoreId('>',$this->filters['startDate']))
            ->when($this->filters['endDate'], fn($query) => $query->whereStoreId('<',$this->filters['endDate']));

    }
}
