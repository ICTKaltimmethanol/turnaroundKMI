<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceIn extends Model
{
        protected $table = 'presence_in';

    use HasFactory;

    protected $fillable = ['presence_in_status', 'latitude_in', 'longitude_in', 'presence_time', 'presence_date', 'employees_id'];

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
