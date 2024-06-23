<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrdersByMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Vendas / Mês';

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth();
           // ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos por mês',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
