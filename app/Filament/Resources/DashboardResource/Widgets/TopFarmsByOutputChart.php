<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Farm;
use Filament\Widgets\ChartWidget;

class TopFarmsByOutputChart extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Farms by Output';

    protected function getData(): array
    {
        $farms = Farm::withSum('inputOutputs as total_output', 'quantity')
            ->whereHas('inputOutputs', function ($query) {
                $query->where('type', 'output');
            })
            ->orderByDesc('total_output')
            ->take(5)
            ->get();

        return [
            'labels' => $farms->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Output Quantity',
                    'data' => $farms->pluck('total_output')->toArray(),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Specifies the chart type as a bar chart
    }
}
