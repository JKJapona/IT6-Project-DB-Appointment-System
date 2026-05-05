<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
                'philhealth_id' => 'nullable|string|max:20',
                'first_name' => 'required|string|max:50',
                'middle_name' => 'nullable|string|max:50',
                'last_name' => 'required|string|max:50',
                'suffix' => 'required|in:None,Jr,Sr,I,II,III,IV',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:Male,Female',
                'fathers_first_name' => 'nullable|string|max:50',
                'fathers_last_name' => 'nullable|string|max:50',
                'mothers_first_name' => 'nullable|string|max:50',
                'mothers_last_name' => 'nullable|string|max:50',
            ]);

        Patient::create($validatedData);

        return redirect('/patients')->with('success', 'Patient saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient->load('medicalHistories');
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
                'philhealth_id' => 'nullable|string|max:20',
                'first_name' => 'required|string|max:50',
                'middle_name' => 'nullable|string|max:50',
                'last_name' => 'required|string|max:50',
                'suffix' => 'required|in:None,Jr,Sr,I,II,III,IV',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:Male,Female',
                'fathers_first_name' => 'nullable|string|max:50',
                'fathers_last_name' => 'nullable|string|max:50',
                'mothers_first_name' => 'nullable|string|max:50',
                'mothers_last_name' => 'nullable|string|max:50',
        ]);

        $patient->update($validatedData);

        return redirect('/patients')->with('success', 'Patient updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect('/patients')->with('success', 'Patient deleted successfully');
    }
}
