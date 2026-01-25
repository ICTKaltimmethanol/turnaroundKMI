<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\Presences;
use App\Models\PresenceIn;
use App\Models\PresenceOut;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

            // =========================
            // 1️⃣ CREATE PRESENCE IN
            // =========================
            if (empty($data['presenceIn'])) {
                throw new \Exception('Presence In wajib diisi');
            }

            $presenceIn = PresenceIn::create($data['presenceIn']);

            // =========================
            // 2️⃣ CREATE PRESENCES
            // =========================
            $presences = Presences::create([
                'presenceIn_id'         => $presenceIn->id,
                'presenceOut_id'        => null,
                'employees_id'          => $data['employees_id'],
                'employee_code'         => $data['employee_code'],
                'employee_name'         => $data['employee_name'],
                'company_name'          => $data['company_name'],
                'position_name'         => $data['position_name'],
                'employees_company_id'  => $data['employees_company_id'],
                'employees_position_id' => $data['employees_position_id'],
                'total_time'            => $data['total_time'] ?? null,
            ]);

            // =========================
            // 3️⃣ CREATE PRESENCE OUT (OPTIONAL)
            // =========================
            if (
                !empty($data['presenceOut']['presence_date']) &&
                !empty($data['presenceOut']['presence_time'])
            ) {
                $presenceOut = PresenceOut::create($data['presenceOut']);

                $presences->update([
                    'presenceOut_id' => $presenceOut->id,
                ]);
            }

            return $presences;
        });
    }
}
