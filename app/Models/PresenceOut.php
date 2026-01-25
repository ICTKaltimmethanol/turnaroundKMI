<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceOut extends Model
{
        protected $table = 'presence_out';

    use HasFactory;

    protected $fillable = ['latitude_out', 'longitude_out', 'presence_time', 'presence_date', 'employees_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

        public function presence()
    {
        return $this->belongsTo(Presences::class, 'presences_id');
    }

}
