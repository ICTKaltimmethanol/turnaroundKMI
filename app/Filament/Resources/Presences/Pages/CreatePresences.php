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

    protected function mutateFormDataBeforeCreate(array $data): array
{
    dd($data);
}
  /*   protected function mutateFormDataBeforeCreate(array $data): array
{
    // === SIMPAN PRESENCE IN ===
    $presenceIn = \App\Models\PresenceIn::create([
        'employees_id'  => $data['employees_id'],
        'presence_date' => $data['presenceIn']['presence_date'],
        'presence_time' => $data['presenceIn']['presence_time'],
    ]);

    // === SIMPAN PRESENCE OUT (OPTIONAL) ===
    $presenceOutId = null;

    if (
        !empty($data['presenceOut']['presence_date']) &&
        !empty($data['presenceOut']['presence_time'])
    ) {
        $presenceOut = \App\Models\PresenceOut::create([
            'employees_id'  => $data['employees_id'],
            'presence_date' => $data['presenceOut']['presence_date'],
            'presence_time' => $data['presenceOut']['presence_time'],
        ]);

        $presenceOutId = $presenceOut->id;
    }

    // === KEMBALIKAN DATA UNTUK TABEL PRESENCES ===
    return [
        ...$data,
        'presenceIn_id'  => $presenceIn->id,
        'presenceOut_id' => $presenceOutId,
    ];
} */


}
