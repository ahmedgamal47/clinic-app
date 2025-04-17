<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    
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
        return $this->belongsToMany(Medication::class, 'session_medication')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}