<?php

namespace App\Http\Controllers;

use App\Models\PatientSession;
use App\Models\Patient;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    /**
     * Display a listing of the sessions for a patient.
     */
    public function index(Request $request, Patient $patient)
{
    $search = $request->input('search');
    
    $query = $patient->sessions();
    
    // You can search by notes or date
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('notes', 'like', "%{$search}%")
              ->orWhereDate('date', 'like', "%{$search}%");
        });
    }
    
    $sessions = $query->latest('date')->paginate(10);
    
    return view('sessions.index', compact('patient', 'sessions', 'search'));
}

    /**
     * Show the form for creating a new session.
     */
    public function create(Patient $patient)
    {
        $medications = Medication::where('quantity_in_stock', '>', 0)->get();
        
        return view('sessions.create', compact('patient', 'medications'));
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        // Validation
        $validated = $request->validate([
            'date' => 'required|date',
            'weight' => 'nullable|numeric',
            'fats_rate' => 'nullable|numeric',
            'burn_rate' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'medications' => 'nullable|array',
            'medications.*.id' => 'nullable|exists:medications,id',
            'medications.*.quantity' => 'required_with:medications.*.id|integer|min:1',
        ]);
        
        DB::beginTransaction();
        
        try {
            $session = new PatientSession();
            $session->patient_id = $patient->id;
            $session->date = $validated['date'];
            $session->weight = $validated['weight'] ?? null;
            $session->fats_rate = $validated['fats_rate'] ?? null;
            $session->burn_rate = $validated['burn_rate'] ?? null;
            $session->notes = $validated['notes'] ?? null;
            $session->save();
            
            // Process medications only if they exist
            if (!empty($validated['medications'])) {
                $medicationData = [];
                
                foreach ($validated['medications'] as $med) {
                    if (!empty($med['id'])) {
                        $medicationData[$med['id']] = ['quantity' => $med['quantity']];
                        
                        // Update medication stock
                        $medication = Medication::find($med['id']);
                        if ($medication && $medication->quantity_in_stock >= $med['quantity']) {
                            $medication->quantity_in_stock -= $med['quantity'];
                            $medication->save();
                        } else if ($medication && $medication->quantity_in_stock < $med['quantity']) {
                            throw new \Exception("Insufficient stock for medication: " . $medication->name);
                        }
                    }
                }
                
                // Only attach medications if there are any to attach
                if (!empty($medicationData)) {
                    $session->medications()->attach($medicationData);
                }
            }
            
            DB::commit();
            
            return redirect()->route('patients.sessions.index', $patient->id)
                ->with('success', 'Session created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['general' => 'An error occurred while creating the session. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified session.
     */
    public function show(Patient $patient, PatientSession $session)
    {
        // Check if the session belongs to the patient
        if ($session->patient_id !== $patient->id) {
            abort(404);
        }
        
        return view('sessions.show', compact('patient', 'session'));
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(Patient $patient, PatientSession $session)
    {
        // Check if the session belongs to the patient
        if ($session->patient_id !== $patient->id) {
            abort(404);
        }
        
        $medications = Medication::get();
        $sessionMedications = $session->medications;
        
        return view('sessions.edit', compact('patient', 'session', 'medications', 'sessionMedications'));
    }

    /**
     * Update the specified session in storage.
     */
    public function update(Request $request, Patient $patient, PatientSession $session)
    {
        // Validation
        $validated = $request->validate([
            'date' => 'required|date',
            'weight' => 'nullable|numeric',
            'fats_rate' => 'nullable|numeric',
            'burn_rate' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'medications' => 'nullable|array',
            'medications.*.id' => 'nullable|exists:medications,id',
            'medications.*.quantity' => 'required_with:medications.*.id|integer|min:1',
            'medications.*.original_id' => 'nullable|exists:medications,id',
            'medications.*.original_quantity' => 'nullable|integer',
        ]);
        
        DB::beginTransaction();
        
        try {
            $session->date = $validated['date'];
            $session->weight = $validated['weight'] ?? null;
            $session->fats_rate = $validated['fats_rate'] ?? null;
            $session->burn_rate = $validated['burn_rate'] ?? null;
            $session->notes = $validated['notes'] ?? null;
            $session->save();
            
            // Get current medications to calculate stock adjustments
            $currentMeds = $session->medications->keyBy('id')->map(function ($medication) {
                return $medication->pivot->quantity;
            })->toArray();
            
            // Handle medications
            if (isset($validated['medications']) && is_array($validated['medications'])) {
                $medicationData = [];
                
                // Process each medication in the form
                foreach ($validated['medications'] as $med) {
                    if (!empty($med['id'])) {
                        $medicationData[$med['id']] = ['quantity' => $med['quantity']];
                        
                        // Get the medication
                        $medication = Medication::find($med['id']);
                        
                        if ($medication) {
                            $originalId = isset($med['original_id']) ? $med['original_id'] : null;
                            $originalQuantity = isset($med['original_quantity']) ? $med['original_quantity'] : 0;
                            
                            // If this is not the same medication as before, or the quantity changed
                            if ($originalId != $med['id'] || $originalQuantity != $med['quantity']) {
                                // Return the old medication quantity to stock if it was a different medication
                                if ($originalId && $originalId != $med['id'] && isset($currentMeds[$originalId])) {
                                    $oldMedication = Medication::find($originalId);
                                    if ($oldMedication) {
                                        $oldMedication->quantity_in_stock += $currentMeds[$originalId];
                                        $oldMedication->save();
                                    }
                                    // Remove from current medications to prevent double counting
                                    unset($currentMeds[$originalId]);
                                }
                                
                                // If this is the same medication but quantity changed
                                if ($originalId == $med['id'] && isset($currentMeds[$originalId])) {
                                    // Calculate the difference in quantity
                                    $diff = $med['quantity'] - $currentMeds[$originalId];
                                    
                                    // If using more, check if we have enough stock
                                    if ($diff > 0 && $medication->quantity_in_stock < $diff) {
                                        throw new \Exception("Insufficient stock for {$medication->name}. Only {$medication->quantity_in_stock} available.");
                                    }
                                    
                                    // Update stock based on the difference
                                    $medication->quantity_in_stock -= $diff;
                                    $medication->save();
                                    
                                    // Remove from current medications to prevent double counting
                                    unset($currentMeds[$originalId]);
                                } 
                                // If this is a new medication or replacing a different one
                                else if ($originalId != $med['id']) {
                                    // Check if we have enough stock
                                    if ($medication->quantity_in_stock < $med['quantity']) {
                                        throw new \Exception("Insufficient stock for {$medication->name}. Only {$medication->quantity_in_stock} available.");
                                    }
                                    
                                    // Deduct the quantity from stock
                                    $medication->quantity_in_stock -= $med['quantity'];
                                    $medication->save();
                                }
                            } else {
                                // Medication and quantity unchanged - remove from the list to prevent returning to stock
                                unset($currentMeds[$med['id']]);
                            }
                        }
                    }
                }
                
                // Return any remaining medications to stock (medications that were removed)
                foreach ($currentMeds as $medId => $quantity) {
                    $oldMedication = Medication::find($medId);
                    if ($oldMedication) {
                        $oldMedication->quantity_in_stock += $quantity;
                        $oldMedication->save();
                    }
                }
                
                // Sync the medications
                $session->medications()->sync($medicationData);
                
            } else {
                // If no medications provided, return all current medications to stock and detach
                foreach ($session->medications as $medication) {
                    $medication->quantity_in_stock += $medication->pivot->quantity;
                    $medication->save();
                }
                
                $session->medications()->detach();
            }
            
            DB::commit();
            
            return redirect()->route('patients.sessions.show', [$patient->id, $session->id])
                ->with('success', 'Session updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->withErrors(['general' => 'An error occurred while updating the session. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified session from storage.
     */
    public function destroy(Patient $patient, PatientSession $session)
    {
        // Check if the session belongs to the patient
        if ($session->patient_id !== $patient->id) {
            abort(404);
        }
        
        $session->delete();
        
        return redirect()->route('patients.sessions.index', $patient->id)
            ->with('success', 'Session deleted successfully.');
    }
}