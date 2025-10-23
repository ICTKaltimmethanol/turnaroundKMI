<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;
use Intervention\Image\Facades\Image;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Employee extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;
    
    use SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'employees_code',
        'full_name',
        'company_id',
        'position_id',
        'profile_img_path',
        'status',
    ];

    // Relasi Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Relasi Position
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Relasi PresenceIn
    public function presenceIns()
    {
        return $this->hasMany(PresenceIn::class, 'employees_id');
    }

    // Relasi PresenceOut
    public function presenceOuts()
    {
        return $this->hasMany(PresenceOut::class, 'employees_id');
    }

    // Relasi TimeOff
    public function timeOffs()
    {
        return $this->hasMany(TimeOff::class, 'employees_id');
    }

    // Relasi Presences (presence pivot)
    public function presences()
    {
        return $this->hasMany(Presences::class, 'employees_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class, 'employees_id');
    }


    // Relasi  QR Code
    public function qrCodes()
    {
        return $this->hasMany(QrCode::class, 'employees_id');
    }

    // Relasi EmployeesCard
    public function employeesCards()
    {
        return $this->hasMany(EmployeesCard::class, 'employees_id');
    }


    // Boot handle model events
    
    protected static function booted(): void
    {
        static::creating(function ($employee) {
            DB::transaction(function () use ($employee) {
                // Ambil kode terakhir dengan lock for update supaya aman
                $lastCode = DB::table('employees')
                    ->select('employees_code')
                    ->where('employees_code', 'like', 'TA-%')
                    ->orderBy('employees_code', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastCode) {
                    // Ambil nomor terakhir
                    $lastNumber = (int)substr($lastCode->employees_code, 3);
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }

                // Format dengan leading zero 3 digit
                $code = 'TA-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

                $employee->employees_code = $code;
            });
        });

        // Event created tetap sama (generate QR code dst)
        static::created(function ($employee) {
            $qrData = $employee->employees_code;

            $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);
            $imageContent = file_get_contents($qrImageUrl);

            $fileName = 'qrcodes/' . $qrData . '.png';
            Storage::disk('public')->put($fileName, $imageContent);

            $employee->qrCodes()->create([
                'code' => $qrData,
                'img_path' => $fileName,
            ]);
        });
    }
// protected static function booted(): void
// {
//     static::created(function ($employee) {
//         $qrData = $employee->employees_code;

//         // Buat URL QR code
//         $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);

//         // Ambil isi file dari URL
//         $imageContent = file_get_contents($qrImageUrl);

//         // Simpan ke storage/public/qrcodes/
//         $fileName = 'qrcodes/' . $qrData . '.png';
//         Storage::disk('public')->put($fileName, $imageContent);

//         // Simpan ke relasi QR Code
//         $employee->qrCodes()->create([
//             'code' => $qrData,
//             'img_path' => $fileName,
//         ]);
//     });
// }

    public function getQrCodeImgPathAttribute()
{
    return $this->qrCode?->img_path;
}



}
