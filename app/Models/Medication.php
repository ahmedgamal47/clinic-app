<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'quantity_in_stock',
        'unit'
    ];
    
    /**
     * Get the sessions for the medication.
     */
    public function sessions()
    {
        return $this->belongsToMany(PatientSession::class, 'patient_session_medication', 'medication_id', 'patient_session_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}