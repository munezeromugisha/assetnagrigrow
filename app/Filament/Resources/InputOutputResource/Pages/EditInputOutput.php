<?php

namespace App\Filament\Resources\InputOutputResource\Pages;

use App\Filament\Resources\InputOutputResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInputOutput extends EditRecord
{
    protected static string $resource = InputOutputResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
