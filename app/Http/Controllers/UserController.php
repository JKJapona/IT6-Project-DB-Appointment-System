<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Using 'users' folder for views
        $users = User::with(['staff', 'patient'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $staff = Staff::all();
        $patients = Patient::all();
        return view('users.create', compact('staff', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:Patient,Staff,Admin',
            'staff_id' => 'required_if:role,Staff,Admin|nullable|exists:staff,staff_id',
            'patient_id' => 'required_if:role,Patient|nullable|exists:patients,patient_id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'staff_id' => $request->staff_id,
            'patient_id' => $request->patient_id,
        ]);

        return redirect()->route('user_accounts.index')->with('success', 'User account created successfully.');
    }

    // Parameter MUST be $user_account to match the 'user_accounts' resource route
    public function show(User $user_account)
    {
        return view('users.show', ['user' => $user_account]);
    }

    public function edit(User $user_account)
    {
        $staff = Staff::all();
        $patients = Patient::all();

        return view('users.edit', [
            'user' => $user_account, 
            'staff' => $staff, 
            'patients' => $patients
        ]);
    }

    public function update(Request $request, User $user_account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user_account->id,
            'role' => 'required|in:Patient,Staff,Admin',
            'staff_id' => 'required_if:role,Staff,Admin|nullable|exists:staff,staff_id',
            'patient_id' => 'required_if:role,Patient|nullable|exists:patients,patient_id',
        ]);

        $user_account->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'staff_id' => $request->staff_id,
            'patient_id' => $request->patient_id,
        ]);

        if ($request->filled('password')) {
            $user_account->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('user_accounts.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user_account)
    {
        $user_account->delete();
        return redirect()->route('user_accounts.index')->with('success', 'User deleted successfully.');
    }
}