<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Farm;
use App\Models\Field;
use App\Models\InputOutput;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FarmStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Input/Output Totals
        $totalInputs = InputOutput::where('type', 'input')->sum('quantity');
        $totalOutputs = InputOutput::where('type', 'output')->sum('quantity');

        return [
            Stat::make('Total Farms', Farm::count())
                ->description('Registered farm entities')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('success'),

            Stat::make('Total Fields', Field::count())
                ->description('Field units across all farms')
                ->descriptionIcon('heroicon-o-map')
                ->color('primary'),

            Stat::make('Land Coverage', Field::sum('size') . ' acres')
                ->description('Total area managed')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('warning'),

            Stat::make('Total Inputs Used', number_format($totalInputs) . ' units')
                ->description('Total quantity of inputs recorded')
                ->color('danger'),

            Stat::make('Total Outputs Produced', number_format($totalOutputs) . ' units')
                ->description('Total quantity of outputs recorded')
                ->color('success'),
        ];
    }
}
