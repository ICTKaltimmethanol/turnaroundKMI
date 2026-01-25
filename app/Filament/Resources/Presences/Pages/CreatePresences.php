<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePresences extends CreateRecord
{
    protected static string $resource = PresencesResource::class;



    public static function getLabel (): ?string 
    {
        return 'Tambah Presensi';
    }

  use App\Models\PresenceIn;
use App\Models\PresenceOut;

protected function mutateFormDataBeforeCreate(array $data): array
{
    $presenceInData  = $data['presenceIn']  ?? null;
    $presenceOutData = $data['presenceOut'] ?? null;

    unset($data['presenceIn'], $data['presenceOut']);

    // CREATE PRESENCE IN
    if ($presenceInData) {
        $presenceIn = PresenceIn::create($presenceInData);
        $data['presenceIn_id'] = $presenceIn->id;
    }

    // CREATE PRESENCE OUT (OPTIONAL)
    if (
        $presenceOutData &&
        ($presenceOutData['presence_date'] ?? null) &&
        ($presenceOutData['presence_time'] ?? null)
    ) {
        $presenceOut = PresenceOut::create($presenceOutData);
        $data['presenceOut_id'] = $presenceOut->id;
    }

    return $data;
}



}
