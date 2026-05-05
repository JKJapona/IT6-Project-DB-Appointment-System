@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Billing & Invoices</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Track patient payments, issued invoices, and outstanding balances.</p>
    </div>

    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Create Invoice
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Ref #</th>
                <th>Patient</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Issued Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td><span style="color: var(--font-muted); font-weight: 600;">#{{ $invoice->invoice_id }}</span></td>
                <td><span class="badge" style="background: var(--sidebar-accent); color: var(--primary-blue);">{{ $invoice->appointment->reference_number }}</span></td>
                <td>
                    <strong style="color: var(--primary-blue);">
                        {{ $invoice->appointment->patient->last_name }}, 
                        {{ $invoice->appointment->patient->first_name }} 
                        {{ $invoice->appointment->patient->suffix !== 'None' ? $invoice->appointment->patient->suffix : '' }}
                    </strong>
                </td>
                <td><strong style="font-size: 1rem;">₱{{ number_format($invoice->total_amount, 2) }}</strong></td>
                <td>
                    @php 
                        $status = $invoice->payment_status;
                        $badgeClass = match($status) {
                            'Paid' => 'badge-success',
                            'Unpaid' => 'badge-danger',
                            'Partially Paid' => 'badge-info',
                            'Cancelled' => 'badge-secondary',
                            default => 'badge-secondary'
                        };
                        
                        $icon = match($status) {
                            'Paid' => 'bi-check-circle-fill',
                            'Unpaid' => 'bi-exclamation-circle-fill',
                            'Partially Paid' => 'bi-clock-history',
                            'Cancelled' => 'bi-x-circle-fill',
                            default => 'bi-question-circle'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}" style="text-transform: uppercase; font-size: 0.7rem;">
                        <i class="bi {{ $icon }}"></i> {{ $status }}
                    </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($invoice->issued_date)->format('M d, Y') }}</td>
                
                <td style="text-align: right;">
                    <div style="display: flex; gap: 4px; justify-content: flex-end;">
                        <a href="{{ route('invoices.show', $invoice->invoice_id) }}" class="btn btn-secondary" title="View">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('invoices.edit', $invoice->invoice_id) }}" class="btn btn-secondary" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form action="{{ route('invoices.destroy', $invoice->invoice_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?')" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    <i class="bi bi-receipt-cutoff" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    No billing records found in the system.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection