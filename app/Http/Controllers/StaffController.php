<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with('department')->get();
        return view('staffs.index', compact('staff'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('staffs.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'role' => 'required|in:Doctor,Nurse,Admissions Clerk,Admin',
            'department_id' => 'nullable|exists:departments,department_id',
            'specialization' => 'nullable|string|max:100',
        ]);

        Staff::create($request->all());

        // Use 'staff.index' to match your Route::resource('staff', ...)
        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }

    public function show($id)
    {
        $staffMember = Staff::with('department')->findOrFail($id);
        return view('staffs.show', compact('staffMember'));
    }

    public function edit($id)
    {
        $staffMember = Staff::findOrFail($id);
        $departments = Department::all();
        
        return view('staffs.edit', compact('staffMember', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'role' => 'required|in:Doctor,Nurse,Admissions Clerk,Admin',
            'department_id' => 'nullable|exists:departments,department_id',
            'specialization' => 'nullable|string|max:100',
        ]);

        $staffMember = Staff::findOrFail($id);
        $staffMember->update($request->all());

        return redirect()->route('staff.index')->with('success', 'Staff profile updated successfully.');
    }

    public function destroy($id)
{
    try {
        $staffMember = Staff::findOrFail($id);
        $staffMember->delete();
        
        return redirect()->route('staff.index')->with('success', 'Staff record deleted.');
    } catch (QueryException $e) {
        // If it's a trigger error (45000), try to get the custom message
        if ($e->getCode() == '45000') {
            return back()->with('error', $this->getTriggerMessage($e));
        }
        
        // If it's a foreign key error (23000)
        if ($e->getCode() == '23000') {
            return back()->with('error', 'Cannot delete this staff member. They are currently assigned to active appointments or schedules.');
        }
        
        throw $e;
    }
}

// Add the helper at the bottom of StaffController
private function getTriggerMessage(QueryException $e)
{
    if (preg_match('/1644 (.*?) \(Connection:/', $e->getMessage(), $matches)) {
        return $matches[1];
    }
    return "Database Restriction: " . ($e->errorInfo[2] ?? 'Operation blocked.');
}
}