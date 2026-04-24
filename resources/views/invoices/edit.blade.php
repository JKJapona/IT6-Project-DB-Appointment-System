@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Edit Invoice #{{ $invoice->invoice_id }}</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Update billing amount or payment status for this record.</p>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-lg"></i> Cancel
    </a>
</div>

{{-- Validation Errors --}}
@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #dc2626; padding: 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.875rem;">
        <div style="font-weight: 700; margin-bottom: 8px;"><i class="bi bi-exclamation-triangle-fill"></i> Validation Errors</div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="details-card">
    <form action="{{ route('invoices.update', $invoice->invoice_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            {{-- Read-only Reference --}}
            <div class="form-group">
                <label>Reference Appointment</label>
                <input type="text" class="form-control" value="{{ $invoice->appointment->reference_number }} — {{ $invoice->appointment->patient->first_name }} {{ $invoice->appointment->patient->last_name }}" disabled style="background-color: var(--sidebar-accent); cursor: not-allowed;">
            </div>

            {{-- Amount --}}
            <div class="form-group">
                <label for="total_amount">Total Amount ($)</label>
                <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" value="{{ old('total_amount', $invoice->total_amount) }}" required>
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="payment_status">Payment Status</label>
                <select name="payment_status" id="payment_status" class="form-control" required>
                    @foreach(['Unpaid', 'Partially Paid', 'Paid', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('payment_status', $invoice->payment_status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Form Actions --}}
        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-check-circle"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection