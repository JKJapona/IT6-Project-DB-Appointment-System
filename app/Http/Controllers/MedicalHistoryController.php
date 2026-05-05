<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class MedicalHistoryController extends Controller
{
    /**
     * Display a listing of medical histories, likely filtered by patient.
     */
    public function index(Request $request)
    {
        $patientId = $request->query('patient_id');
        
        if ($patientId) {
            $histories = MedicalHistory::where('patient_id', $patientId)->with('patient')->get();
        } else {
            $histories = MedicalHistory::with('patient')->latest()->get();
        }

        return view('medical_histories.index', compact('histories'));
    }

    /**
     * Show the form for creating a new history record.
     */
    public function create(Request $request)
    {
        $patients = Patient::all();
        // Pre-select patient if coming from a specific patient's profile
        $selectedPatient = $request->query('patient_id'); 
        
        return view('medical_histories.create', compact('patients', 'selectedPatient'));
    }

    /**
     * Store the new medical record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'condition_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'date_recorded' => 'nullable|date',
        ]);

        MedicalHistory::create($request->all());

        return redirect()->route('patients.show', $request->patient_id)
            ->with('success', 'Medical condition added to patient history.');
    }

    /**
     * Show the form for editing the record.
     */
    public function edit($id)
    {
        $history = MedicalHistory::findOrFail($id);
        return view('medical_histories.edit', compact('history'));
    }

    /**
     * Update the medical history.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'condition_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'date_recorded' => 'nullable|date',
        ]);

        $history = MedicalHistory::findOrFail($id);
        $history->update($request->all());

        return redirect()->route('patients.show', $history->patient_id)
            ->with('success', 'Medical history updated.');
    }

    /**
     * Remove the history record.
     */
    public function destroy($id)
    {
        $history = MedicalHistory::findOrFail($id);
        $patientId = $history->patient_id;
        $history->delete();

        return redirect()->route('patients.show', $patientId)
            ->with('success', 'Medical history record removed.');
    }
}