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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {

            if (!empty($data['presence_out_date']) && !empty($data['presence_out_time'])) {

                if ($record->presenceOut) {
                    $record->presenceOut->update([
                        'presence_date' => $data['presence_out_date'],
                        'presence_time' => $data['presence_out_time'],
                    ]);
                } else {
                    $presenceOut = PresenceOut::create([
                        'employees_id' => $record->employees_id,
                        'presence_date' => $data['presence_out_date'],
                        'presence_time' => $data['presence_out_time'],
                    ]);

                    $record->update([
                        'presenceOut_id' => $presenceOut->id,
                    ]);
                }
            }

            return $record;
        });
    }

}
