<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\PresenceOut;

class EditPresences extends EditRecord
{
    protected static string $resource = PresencesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    // instance method untuk update presenceOut
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['presenceOut']['presence_date']) && !empty($data['presenceOut']['presence_time'])) {
            if ($this->record->presenceOut) {
                $this->record->presenceOut->update([
                    'presence_date' => $data['presenceOut']['presence_date'],
                    'presence_time' => $data['presenceOut']['presence_time'],
                ]);
            } else {
                $presenceOut = PresenceOut::create([
                    'employees_id' => $data['employees_id'],
                    'presence_date' => $data['presenceOut']['presence_date'],
                    'presence_time' => $data['presenceOut']['presence_time'],
                ]);
                $data['presenceOut_id'] = $presenceOut->id;
            }
        }

        return $data;
    }
}
