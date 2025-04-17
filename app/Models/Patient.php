<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'address', 
        'date_of_birth',
        'doctor_id'
    ];
    
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the patient's age based on birth date.
     */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        return floor($this->date_of_birth->diffInYears(Carbon::now()));
    }
    
    /**
     * Get the doctor/user that owns the patient.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
    /**
     * Get the sessions for the patient.
     */
    public function sessions()
    {
        return $this->hasMany(PatientSession::class);
    }
}