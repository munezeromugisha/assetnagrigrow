<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\InputOutput;
use Filament\Widgets\ChartWidget;

class InputOutputTrendChart extends ChartWidget

{
    protected static ?string $heading = 'Inputs & Outputs Over Time';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = InputOutput::selectRaw('DATE(date) as date, type, SUM(quantity) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $dates = $data->pluck('date')->unique()->values();
        $inputs = [];
        $outputs = [];

        foreach ($dates as $date) {
            $inputs[] = $data->where('date', $date)->where('type', 'input')->sum('total');
            $outputs[] = $data->where('date', $date)->where('type', 'output')->sum('total');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Inputs',
                    'data' => $inputs,
                    'fill' => false,
                    'borderColor' => '#f59e0b',
                ],
                [
                    'label' => 'Outputs',
                    'data' => $outputs,
                    'fill' => false,
                    'borderColor' => '#10b981',
                ],
            ],
            'labels' => $dates->map(fn ($date) => \Carbon\Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
