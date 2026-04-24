@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Invoice Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Statement of accounts and payment history.</p>
    </div>
    <button onclick="window.print()" class="btn btn-secondary">
        <i class="bi bi-printer"></i> Print Record
    </button>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Invoice Number</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $invoice->invoice_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm">Payment Status</span>
            @if($invoice->payment_status == 'Paid')
                <span class="badge badge-success" style="font-size: 0.9rem; padding: 8px 16px;">
                    <i class="bi bi-check-all"></i> PAID IN FULL
                </span>
            @else
                <span class="badge badge-danger" style="font-size: 0.9rem; padding: 8px 16px;">
                    <i class="bi bi-clock-history"></i> PENDING PAYMENT
                </span>
            @endif
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Patient & Appointment</div>
                <div class="info-content">
                    <strong>Patient Name:</strong> {{ $invoice->appointment->patient->first_name }} {{ $invoice->appointment->patient->last_name }}<br>
                    <strong>Reference:</strong> <span style="font-family: monospace; color: var(--primary-blue);">{{ $invoice->appointment->reference_number }}</span><br>
                    <strong>Department:</strong> {{ $invoice->appointment->schedule->department->department_name }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Billing Summary</div>
                <div class="meta-box" style="background: var(--sidebar-accent); border-color: var(--primary-blue);">
                    <span class="label-sm" style="display: block; margin-bottom: 4px;">Total Amount Due</span>
                    <div style="font-size: 1.5rem; color: var(--primary-blue); font-weight: 800;">
                        ${{ number_format($invoice->total_amount, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Timeline & Logs</div>
                <div class="meta-box">
                    <div style="margin-bottom: 8px;">
                        <i class="bi bi-calendar-check"></i> <strong>Issued Date:</strong> {{ \Carbon\Carbon::parse($invoice->issued_date)->format('M d, Y') }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--font-muted);">
                        <i class="bi bi-cpu"></i> System Log: {{ $invoice->created_at ? $invoice->created_at->format('M d, Y - h:i A') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="actions" style="display: flex; gap: 12px; align-items: center;">
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
    
    @if($invoice->payment_status !== 'Paid')
        <a href="{{ route('invoices.edit', $invoice->invoice_id) }}" class="btn btn-primary">
            <i class="bi bi-credit-card-2-back"></i> Process Payment
        </a>
    @endif

    <form action="{{ route('invoices.destroy', $invoice->invoice_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice record?')">
            <i class="bi bi-trash"></i> Delete Invoice
        </button>
    </form>
</div>
@endsection