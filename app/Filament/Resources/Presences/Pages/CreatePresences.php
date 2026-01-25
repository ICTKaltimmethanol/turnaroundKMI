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
    
public static function mutateFormDataBeforeCreate(array $data): array
{
    // Buat presenceIn otomatis
    $presenceIn = PresenceIn::create([
        'employees_id' => $data['employees_id'],
        'presence_date' => $data['presenceIn']['presence_date'] ?? now()->toDateString(),
        'presence_time' => $data['presenceIn']['presence_time'] ?? now()->format('H:i'),
    ]);
    $data['presenceIn_id'] = $presenceIn->id;

    // Buat presenceOut jika ada
    if (!empty($data['presenceOut']['presence_date']) && !empty($data['presenceOut']['presence_time'])) {
        $presenceOut = PresenceOut::create([
            'employees_id' => $data['employees_id'],
            'presence_date' => $data['presenceOut']['presence_date'],
            'presence_time' => $data['presenceOut']['presence_time'],
        ]);
        $data['presenceOut_id'] = $presenceOut->id;
    }

    return $data;
}

public static function mutateFormDataBeforeSave(array $data, $record): array
{
    // Jika edit, update presenceOut
    if (!empty($data['presenceOut']['presence_date']) && !empty($data['presenceOut']['presence_time'])) {
        if ($record->presenceOut) {
            $record->presenceOut->update([
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
