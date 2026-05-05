<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException; // Required import

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('success', 'Department added successfully.');
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.show', compact('department'));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_name' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        $department = Department::findOrFail($id);
        $department->update($request->all());

        return redirect()->route('departments.index')->with('success', 'Department updated.');
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();
            
            return redirect()->route('departments.index')->with('success', 'Department removed.');
        } catch (QueryException $e) {
            // Handle Custom Trigger (45000) or Foreign Key Constraint (23000)
            if ($e->getCode() == '45000') {
                return back()->with('error', $this->getTriggerMessage($e));
            }

            if ($e->getCode() == '23000') {
                return back()->with('error', 'Cannot delete this department. It still contains active staff members or medical schedules.');
            }

            throw $e;
        }
    }

    /**
     * Helper to extract the custom message from the SQL Trigger Error
     */
    private function getTriggerMessage(QueryException $e)
    {
        if (preg_match('/1644 (.*?) \(Connection:/', $e->getMessage(), $matches)) {
            return $matches[1];
        }
        
        return "Database Restriction: " . ($e->errorInfo[2] ?? 'Operation blocked by system rules.');
    }
}