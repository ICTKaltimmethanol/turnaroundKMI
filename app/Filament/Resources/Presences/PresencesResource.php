<?php

namespace App\Filament\Resources\Presences;

use App\Filament\Resources\Presences\Pages\CreatePresences;
use App\Filament\Resources\Presences\Pages\EditPresences;
use App\Filament\Resources\Presences\Pages\ListPresences;
use App\Filament\Resources\Presences\Schemas\PresencesForm;
use App\Filament\Resources\Presences\Tables\PresencesTable;
use App\Models\Presences;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PresencesResource extends Resource
{
    protected static ?string $model = Presences::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;

    protected static ?string $navigationLabel = 'Data Presensi';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PresencesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresencesTable::configure($table);
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
            'index' => ListPresences::route('/'),
            'create' => CreatePresences::route('/create'),
            'edit' => EditPresences::route('/{record}/edit'),
  

        ];
    }
}
