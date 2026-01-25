<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model; 

use App\Models\Presences;
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

            /**
             * =========================
             * PRESENCE IN
             * =========================
             */
            $presenceIn = PresenceIn::create([
                'employees_id'  => $data['employees_id'],
                'presence_date' => $data['presenceIn']['presence_date'],
                'presence_time' => $data['presenceIn']['presence_time'],
            ]);

            /**
             * =========================
             * PRESENCES (PARENT)
             * =========================
             */
            $presence = Presences::create([
                'presenceIn_id' => $presenceIn->id,
                'employees_id'  => $data['employees_id'],
                'employees_company_id' => $data['employees_company_id'],
                'employees_position_id' => $data['employees_position_id'],

                // SNAPSHOT
                'employee_name' => $data['employee_name'],
                'employee_code' => $data['employee_code'],
                'company_name'  => $data['company_name'],
                'position_name' => $data['position_name'],

                'total_time' => $data['total_time'] ?? null,
            ]);

            /**
             * =========================
             * PRESENCE OUT (OPTIONAL)
             * =========================
             */
            if (
                ! empty($data['presenceOut']['presence_date']) &&
                ! empty($data['presenceOut']['presence_time'])
            ) {
                $presenceOut = PresenceOut::create([
                    'employees_id'  => $data['employees_id'],
                    'presence_date' => $data['presenceOut']['presence_date'],
                    'presence_time' => $data['presenceOut']['presence_time'],
                ]);

                $presence->update([
                    'presenceOut_id' => $presenceOut->id,
                ]);
            }

            return $presence; // ✅ HARUS Model
        });
    }
}
