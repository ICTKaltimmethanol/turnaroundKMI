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

    /**
     * ISI FORM SAAT EDIT
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;

        if ($record->presenceIn) {
            $data['presenceIn'] = [
                'presence_date' => $record->presenceIn->presence_date,
                'presence_time' => $record->presenceIn->presence_time,
            ];
        }

        if ($record->presenceOut) {
            $data['presenceOut'] = [
                'presence_date' => $record->presenceOut->presence_date,
                'presence_time' => $record->presenceOut->presence_time,
            ];
        }

        return $data;
    }

    /**
     * SIMPAN RELASI SETELAH RECORD DISAVE (v4)
     */
    protected function afterSave(): void
    {
        $data   = $this->form->getState();
        $record = $this->record;

        /**
         * ===== PRESENCE IN (WAJIB ADA) =====
         */
        if (! empty($data['presenceIn'])) {
            $record->presenceIn()->update([
                'presence_date' => $data['presenceIn']['presence_date'],
                'presence_time' => $data['presenceIn']['presence_time'],
            ]);
        }

        /**
         * ===== PRESENCE OUT (OPTIONAL) =====
         */
        if (
            ! empty($data['presenceOut']['presence_date']) &&
            ! empty($data['presenceOut']['presence_time'])
        ) {
            if ($record->presenceOut) {
                // UPDATE
                $record->presenceOut()->update([
                    'presence_date' => $data['presenceOut']['presence_date'],
                    'presence_time' => $data['presenceOut']['presence_time'],
                ]);
            } else {
                // CREATE BARU
                $presenceOut = \App\Models\PresenceOut::create([
                    'employees_id'  => $record->employees_id,
                    'presence_date' => $data['presenceOut']['presence_date'],
                    'presence_time' => $data['presenceOut']['presence_time'],
                ]);

                // LINK KE PRESENCES
                $record->updateQuietly([
                    'presenceOut_id' => $presenceOut->id,
                ]);
            }
        }
    }
}