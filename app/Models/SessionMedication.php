<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PatientSessionMedication extends Pivot
{
    protected $table = 'patient_session_medication';
    
    protected $fillable = [
        'patient_session_id',
        'medication_id',
        'quantity'
    ];
    
    /**
     * Boot function to decrease medication stock when assigned to a session
     */
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($sessionMedication) {
            $medication = Medication::find($sessionMedication->medication_id);
            $medication->quantity_in_stock -= $sessionMedication->quantity;
            $medication->save();
        });
    }
}