<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Resources\Pages\CreateRecord;
  use App\Models\PresenceIn;
use App\Models\PresenceOut;

class CreatePresences extends CreateRecord
{
    protected static string $resource = PresencesResource::class;



    public static function getLabel (): ?string 
    {
        return 'Tambah Presensi';
    }


protected function mutateFormDataBeforeCreate(array $data): array
{
    $presenceInData  = $data['presenceIn']  ?? null;
    $presenceOutData = $data['presenceOut'] ?? null;

    unset($data['presenceIn'], $data['presenceOut']);

    if ($presenceInData) {
        $presenceInData['employees_id'] ??= $data['employees_id'];

        $presenceIn = PresenceIn::create($presenceInData);
        $data['presenceIn_id'] = $presenceIn->id;
    }

    if (
        $presenceOutData &&
        ($presenceOutData['presence_date'] ?? null) &&
        ($presenceOutData['presence_time'] ?? null)
    ) {
        $presenceOutData['employees_id'] ??= $data['employees_id'];

        $presenceOut = PresenceOut::create($presenceOutData);
        $data['presenceOut_id'] = $presenceOut->id;
    }

    return $data;
}
    



}
