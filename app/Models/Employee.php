<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;
    
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
        static::created(function ($employee) {
            // Barcode content, misalnya pakai employees_code
            $barcodeData = $employee->employees_code;

            // Generate barcode image
            $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($barcodeData, $generator::TYPE_CODE_128);

            // Simpan gambar barcode ke storage
            $fileName = 'barcodes/' . $barcodeData . '.png';
            Storage::disk('public')->put($fileName, $barcode);

            // Simpan ke tabel qr_code
            $employee->qrCodes()->create([
                'code' => $barcodeData,
                'img_path' => $fileName,
            ]);
        });
    }

    public function getQrCodeImgPathAttribute()
{
    return $this->qrCode?->img_path;
}



}
