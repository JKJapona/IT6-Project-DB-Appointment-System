@extends('layout')

@section('page_title')
    <a href="{{ route('invoices.index') }}" style="text-decoration: none; color: inherit;">Invoices</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Edit</span>
@endsection

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
            <div class="form-group form-group-full">
                <label>Reference Appointment & Patient</label>
                <input type="text" class="form-control" 
                    value="{{ $invoice->appointment->reference_number }} — {{ $invoice->appointment->patient->last_name }}, {{ $invoice->appointment->patient->first_name }} {{ $invoice->appointment->patient->middle_name }} {{ $invoice->appointment->patient->suffix !== 'None' ? $invoice->appointment->patient->suffix : '' }}" 
                    disabled style="background-color: var(--sidebar-accent); cursor: not-allowed;">
            </div>
        </div>

        <div class="form-row">

            {{-- Status --}}
            <div class="form-group">
                <label for="payment_status">Payment Status</label>
                <select name="payment_status" id="payment_status" class="form-control" required>
                    @foreach(['Unpaid', 'Paid', 'Partially Paid', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ old('payment_status', $invoice->payment_status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date Issued (Read-only for Edit) --}}
            <div class="form-group">
                <label>Date Issued</label>
                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($invoice->issued_date)->format('M d, Y - h:i A') }}" disabled style="background-color: var(--sidebar-accent);">
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