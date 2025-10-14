<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{

    protected $table = 'qr_code';

    use HasFactory;

    protected $fillable = ['code', 'img_path', 'employees_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

}
