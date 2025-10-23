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
}
