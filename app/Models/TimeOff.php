<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TimeOff extends Model
{
    use HasFactory;

     protected $table = 'time_off';

    protected $fillable = ['time_off_status', 'time_off_action', 'description', 'employees_id'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

}
