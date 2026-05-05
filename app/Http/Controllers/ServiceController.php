<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ServiceController extends Controller
{
    /**
     * Display a listing of services with their parent departments.
     */
    public function index()
    {
        $services = Service::with('department')->get();
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new hospital service.
     */
    public function create()
    {
        $departments = Department::all();
        return view('services.create', compact('departments'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,department_id',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create($request->all());
        return redirect()->route('services.index')->with('success', 'Service added successfully.');
    }

    /**
     * Display the specific service details.
     */
    public function show($id)
    {
        $service = Service::with('department')->findOrFail($id);
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $departments = Department::all();
        return view('services.edit', compact('service', 'departments'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,department_id',
            'price' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            return redirect()->route('services.index')->with('success', 'Service removed.');
        } catch (QueryException $e) {
            // Error code 23000 handles foreign key constraints (e.g., if linked to Invoice_Items)
            if ($e->getCode() == '23000') {
                return back()->with('error', 'Cannot delete this service. It is currently linked to existing invoices.');
            }
            return back()->with('error', 'A database error occurred while deleting the service.');
        }
    }
}