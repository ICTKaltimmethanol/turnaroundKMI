<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_time')
                    ->required()
                    ->numeric(),
                TextInput::make('presenceIn_id')
                    ->required()
                    ->numeric(),
                TextInput::make('presenceOut_id')
                    ->required()
                    ->numeric(),
                TextInput::make('employees_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
