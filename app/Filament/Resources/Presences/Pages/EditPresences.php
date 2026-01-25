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

      protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        // ===== PRESENCE IN =====
        if ($record->presenceIn) {
            $data['presenceIn'] = [
                'presence_date' => $record->presenceIn->presence_date,
                'presence_time' => $record->presenceIn->presence_time,
            ];
        }

        // ===== PRESENCE OUT =====
        if ($record->presenceOut) {
            $data['presenceOut'] = [
                'presence_date' => $record->presenceOut->presence_date,
                'presence_time' => $record->presenceOut->presence_time,
            ];
        }

        return $data;
    }
}
