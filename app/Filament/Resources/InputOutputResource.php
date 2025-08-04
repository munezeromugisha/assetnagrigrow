<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InputOutputResource\Pages;
use App\Filament\Resources\InputOutputResource\RelationManagers;
use App\Models\InputOutput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InputOutputResource extends Resource
{
    protected static ?string $model = InputOutput::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('farm_id')
                    ->relationship('farm', 'name')
                    ->required(),

                Forms\Components\Select::make('field_id')
                    ->relationship('field', 'name')
                    ->nullable(),

                Forms\Components\Select::make('type')
                    ->options([
                        'input' => 'Input',
                        'output' => 'Output',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('quantity')->numeric()->required(),
                Forms\Components\TextInput::make('unit')->required(),
                Forms\Components\DatePicker::make('date')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('farm.name'),
                Tables\Columns\TextColumn::make('field.name')->label('Field')->toggleable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('quantity')->label('Qty'),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('date')->date(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'input' => 'Input',
                        'output' => 'Output',
                    ]),

                Tables\Filters\SelectFilter::make('farm_id')
                    ->relationship('farm', 'name')
                    ->label('Farm'),

                Tables\Filters\SelectFilter::make('field_id')
                    ->relationship('field', 'name')
                    ->label('Field'),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From'),
                        Forms\Components\DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Custom action for exporting records as a CSV file
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->action(function () {
                        // Fetch all InputOutput records
                        $records = InputOutput::all();

                        // Map records to an array format suitable for CSV
                        $csvData = $records->map(function ($record) {
                            return [
                                'Farm' => $record->farm->name, // Farm name
                                'Field' => $record->field->name ?? '', // Field name (nullable)
                                'Type' => $record->type, // Type of record (input/output)
                                'Name' => $record->name, // Name of the record
                                'Quantity' => $record->quantity, // Quantity value
                                'Unit' => $record->unit, // Unit of measurement
                                'Date' => $record->date->format('Y-m-d'), // Date formatted as Y-m-d
                            ];
                        });

                        // Generate a unique filename for the CSV
                        $fileName = 'input_outputs_' . now()->format('Y_m_d_H_i_s') . '.csv';
                        $filePath = storage_path('app/' . $fileName); // Path to store the file

                        // Open a file handle for writing the CSV
                        $handle = fopen($filePath, 'w');
                        fputcsv($handle, array_keys($csvData->first())); // Add CSV headers

                        // Write each record as a row in the CSV
                        foreach ($csvData as $row) {
                            fputcsv($handle, $row);
                        }

                        fclose($handle); // Close the file handle

                        // Return the file as a downloadable response and delete after sending
                        return response()->download($filePath)->deleteFileAfterSend();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInputOutputs::route('/'),
            'create' => Pages\CreateInputOutput::route('/create'),
            'edit' => Pages\EditInputOutput::route('/{record}/edit'),
        ];
    }
}
