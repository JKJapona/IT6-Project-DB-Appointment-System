@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">User Accounts</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Access control for hospital staff and patients.</p>
    </div>
    <a href="{{ route('user_accounts.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Create New User
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Role</th>
                <th>Linked Profile</th>
                <th>Created</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td><strong>{{ $user->email }}</strong></td>
                <td>
                    <span class="badge {{ $user->role == 'Admin' ? 'badge-danger' : ($user->role == 'Staff' ? 'badge-primary' : 'badge-success') }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td>
                    @if($user->staff)
                        {{ $user->staff->first_name }} (Staff)
                    @elseif($user->patient)
                        {{ $user->patient->first_name }} (Patient)
                    @else
                        <span style="color: #ccc;">System Admin</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <a href="{{ route('user_accounts.show', $user->id) }}" class="btn btn-secondary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('user_accounts.edit', $user->id) }}" class="btn btn-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('user_accounts.destroy', $user->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" title="Delete" 
                                onclick="return confirm('Are you sure you want to delete this user account?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-shield-lock" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No user accounts found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection