<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPresences extends EditRecord
{
    protected static string $resource = PresencesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
