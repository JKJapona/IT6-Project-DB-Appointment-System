@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Patients</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Manage patient records and normalized medical histories.</p>
    </div>
    <a href="{{ route('patients.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Add New Patient
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>PhilHealth ID</th>
                <th>Birth Date / Gender</th>
                <th>Medical Records</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $patient->patient_id }}</span></td>
                <td>
                    <strong style="color: var(--primary-blue);">
                        {{ $patient->first_name }} {{ $patient->last_name }} 
                        {{ $patient->suffix !== 'None' ? $patient->suffix : '' }}
                    </strong>
                </td>
                <td>{{ $patient->philhealth_id ?? 'Unregistered' }}</td>
                <td>
                    <div style="font-size: 0.875rem;">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}</div>
                    <small style="color: var(--font-muted);">{{ $patient->gender }}</small>
                </td>
                <td>
                    <span class="badge {{ $patient->medical_histories_count > 0 ? 'badge-info' : 'badge-secondary' }}" style="font-size: 0.75rem;">
                        <i class="bi bi-clipboard2-pulse"></i> {{ $patient->medical_histories_count ?? 0 }} Records
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('patients.show', $patient->patient_id) }}" class="btn btn-secondary" title="View Details">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('patients.edit', $patient->patient_id) }}" class="btn btn-secondary" title="Edit Profile">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('patients.destroy', $patient->patient_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this patient? All related medical history and appointments will be removed.')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-people" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No patients found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection