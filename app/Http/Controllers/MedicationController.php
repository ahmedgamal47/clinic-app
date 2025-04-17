<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Display a listing of the medications.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Medication::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%");
            });
        }
        
        $medications = $query->latest()->paginate(10);
        
        return view('medications.index', compact('medications', 'search'));
    }

    /**
     * Show the form for creating a new medication.
     */
    public function create()
    {
        return view('medications.create');
    }

    /**
     * Store a newly created medication in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity_in_stock' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
        ]);
        
        Medication::create($validated);
        
        return redirect()->route('medications.index')
            ->with('success', 'Medication added to inventory successfully.');
    }

    /**
     * Show the form for editing the specified medication.
     */
    public function edit(Medication $medication)
    {
        return view('medications.edit', compact('medication'));
    }

    /**
     * Update the specified medication in storage.
     */
    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity_in_stock' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
        ]);
        
        $medication->update($validated);
        
        return redirect()->route('medications.index')
            ->with('success', 'Medication updated successfully.');
    }

    /**
     * Remove the specified medication from storage.
     */
    public function destroy(Medication $medication)
    {
        // Check if medication is associated with any sessions
        if ($medication->sessions()->count() > 0) {
            return redirect()->route('medications.index')
                ->with('error', 'Cannot delete medication that has been prescribed to patients.');
        }
        
        $medication->delete();
        
        return redirect()->route('medications.index')
            ->with('success', 'Medication removed from inventory.');
    }
}