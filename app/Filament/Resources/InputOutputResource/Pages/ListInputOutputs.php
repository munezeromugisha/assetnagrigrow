<?php

namespace App\Filament\Resources\InputOutputResource\Pages;

use App\Filament\Resources\InputOutputResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInputOutputs extends ListRecords
{
    protected static string $resource = InputOutputResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
