<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('department')->get();
        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('schedules.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'max_capacity' => 'required|integer|min:1',
        ]);

        Schedule::create($request->all());

        return redirect()->route('schedules.index')->with('success', 'Schedule created successfully.');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $departments = Department::all();
        
        return view('schedules.edit', compact('schedule', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'max_capacity' => 'required|integer|min:1',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    public function show($id)
    {
        $schedule = Schedule::with('department')->findOrFail($id);
        return view('schedules.show', compact('schedule'));
    }

public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            
            return redirect()->route('schedules.index')->with('success', 'Schedule deleted.');
        } catch (QueryException $e) {
            // Handle Custom Trigger (45000) or Foreign Key Constraint (23000)
            if ($e->getCode() == '45000') {
                return back()->with('error', $this->getTriggerMessage($e));
            }

            if ($e->getCode() == '23000') {
                return back()->with('error', 'Cannot delete this schedule. It is currently linked to active patient appointments.');
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