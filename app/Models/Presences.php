<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presences extends Model
{
    use HasFactory;

    protected $fillable = ['total_time', 'presenceIn_id', 'presenceOut_id', 'employees_id', 'employees_company_id', 'employees_position_id'];

    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'employees_company_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'employees_position_id');
    }

    public function presenceIn()
    {
        return $this->belongsTo(PresenceIn::class, 'presenceIn_id');
    }

    public function presenceOut()
    {
        return $this->belongsTo(PresenceOut::class, 'presenceOut_id');
    }

}
