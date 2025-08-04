<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\InputOutput;
use Filament\Widgets\ChartWidget;

class MonthlyInputOutputChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Input/Output';

    protected function getType(): string
    {
        return 'bar'; // Specifies the chart type as a bar chart
    }

    protected function getData(): array
    {
        $inputs = InputOutput::selectRaw('MONTH(date) as month, SUM(quantity) as total')
            ->where('type', 'input')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $outputs = InputOutput::selectRaw('MONTH(date) as month, SUM(quantity) as total')
            ->where('type', 'output')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'labels' => $inputs->pluck('month')->map(function ($month) {
                return date('F', mktime(0, 0, 0, $month, 1));
            })->toArray(),
            'datasets' => [
                [
                    'label' => 'Inputs',
                    'data' => $inputs->pluck('total')->toArray(),
                ],
                [
                    'label' => 'Outputs',
                    'data' => $outputs->pluck('total')->toArray(),
                ],
            ],
        ];
    }
}
