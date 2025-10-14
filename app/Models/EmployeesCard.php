<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'background_path',
        'employees_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
