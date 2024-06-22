<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersByMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Vendas / Mês';

    protected static ?string $pollingInterval = '120s';

    protected int | string | array $columnSpan = 'full';
   
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();
            
        return [
            'datasets' => [
                [
                    'label' => 'Total de Vendas / Mês',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' =>$data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
