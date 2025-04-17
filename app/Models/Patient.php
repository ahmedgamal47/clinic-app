<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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