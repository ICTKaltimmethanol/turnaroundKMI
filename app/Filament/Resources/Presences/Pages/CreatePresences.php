<?php

namespace App\Filament\Resources\Presences\Pages;

use App\Filament\Resources\Presences\PresencesResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreatePresences extends CreateRecord
{
    protected static string $resource = PresencesResource::class;

    public static function getLabel(): ?string
    {
        return 'Tambah Presensi';
    }

    /**
     * Optional: rapikan data sebelum create
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total_time'] = $data['total_time'] ?? null;

        return $data;
    }

    /**
     * WAJIB custom create (karena ada relationship)
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            /* ===============================
             | 1. Ambil data relasi
             =============================== */
            $presenceIn  = $data['presenceIn']  ?? null;
            $presenceOut = $data['presenceOut'] ?? null;

            unset($data['presenceIn'], $data['presenceOut']);

            /* ===============================
             | 2. Create PRESENCES (parent)
             =============================== */
            $presence = static::getModel()::create($data);

            /* ===============================
             | 3. Create PRESENCE IN (WAJIB)
             =============================== */
            if ($presenceIn) {
                $in = $presence->presenceIn()->create([
                    ...$presenceIn,
                    'employees_id' => $data['employees_id'],
                ]);

                $presence->update([
                    'presenceIn_id' => $in->id,
                ]);
            }

            /* ===============================
             | 4. Create PRESENCE OUT (OPTIONAL)
             =============================== */
            if (
                $presenceOut &&
                ! empty($presenceOut['presence_date']) &&
                ! empty($presenceOut['presence_time'])
            ) {
                $out = $presence->presenceOut()->create([
                    ...$presenceOut,
                    'employees_id' => $data['employees_id'],
                ]);

                $presence->update([
                    'presenceOut_id' => $out->id,
                ]);
            }

            return $presence;
        });
    }
}
