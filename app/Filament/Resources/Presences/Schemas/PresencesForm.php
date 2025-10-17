<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Components\TextInput;
use APP\Models\PresenceIn;
use APP\Models\PresenceOut;
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
                    ->relationship('presenceIn', 'presence_time')
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
