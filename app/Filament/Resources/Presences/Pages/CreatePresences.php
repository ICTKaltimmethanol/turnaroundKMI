<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\PresenceIn;
use App\Models\PresenceOut;

class CreatePresences extends CreateRecord
{
    protected static string $resource = PresencesResource::class;

    public static function getLabel(): ?string
    {
        return 'Tambah Presensi';
    }

   protected function handleRecordCreation(array $data): Model
{
    return DB::transaction(function () use ($data) {

        // 1. Buat PresenceIn
        $presenceIn = PresenceIn::create([
            'employees_id'   => $data['employees_id'],
            'presence_date'  => $data['presence_in_date'],
            'presence_time'  => $data['presence_in_time'],
        ]);

        // 2. Buat Presences (seperti controller scan)
        return Presences::create([
            'presenceIn_id' => $presenceIn->id,
            'employees_id'  => $data['employees_id'],
            'employees_company_id' => $data['employees_company_id'],
            'employees_position_id' => $data['employees_position_id'],
            'employee_name' => $data['employee_name'],
            'employee_code' => $data['employee_code'],
            'company_name'  => $data['company_name'],
            'position_name' => $data['position_name'],
        ]);
    });
}

}
