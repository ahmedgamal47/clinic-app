<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientSession extends Model
{
    use HasFactory;
    
    protected $table = 'patient_sessions';
    
    protected $fillable = [
        'patient_id',
        'date',
        'weight',
        'fats_rate',
        'burn_rate',
        'notes'
    ];
    
    protected $casts = [
        'date' => 'datetime',
    ];
    
    /**
     * Get the patient that owns the session.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
    /**
     * Get the medications for the session.
     */
    public function medications()
    {
        return $this->belongsToMany(Medication::class, 'patient_session_medication', 'patient_session_id', 'medication_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}