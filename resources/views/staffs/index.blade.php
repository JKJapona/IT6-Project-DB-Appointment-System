@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Medical Staff Directory</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Registry of doctors, nurses, and administrative personnel.</p>
    </div>
    <a href="{{ route('staff.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Add Staff
    </a>
</div>

<div class="table-container">
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Role</th>
            <th>Department</th>
            <th style="text-align: right;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($staff as $s)
        <tr>
            <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $s->staff_id }}</span></td>
            <td><strong style="color: var(--primary-blue);">{{ $s->first_name }} {{ $s->last_name }}</strong></td>
            <td>
                <span class="badge" style="background: var(--sidebar-accent); color: var(--primary-blue); border: 1px solid var(--border-color);">
                    {{ $s->role }}
                </span>
            </td>
            <td>{{ $s->department->department_name ?? 'N/A' }}</td>
            <td style="text-align: right;">
                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                    <a href="{{ route('staff.show', $s->staff_id) }}" class="btn btn-secondary" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('staff.edit', $s->staff_id) }}" class="btn btn-secondary" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('staff.destroy', $s->staff_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this record?')" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center; padding: 40px; color: var(--font-muted);">
                <i class="bi bi-person-badge" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                No staff members found in the system.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection