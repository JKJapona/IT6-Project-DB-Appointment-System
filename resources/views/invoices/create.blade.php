@extends('layout')

@section('page_title')
    <a href="{{ route('invoices.index') }}" style="text-decoration: none; color: inherit;">Invoices</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Add</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Generate Invoice</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Create a new billing record for a completed or confirmed appointment.</p>
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
    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf
        
        {{-- Appointment Selection --}}
        <div class="form-row">
            <div class="form-group form-group-full">
                <label for="appointment_id">Select Appointment (Ref # — Patient Name)</label>
                <select name="appointment_id" id="appointment_id" class="form-control" required>
                    <option value="" disabled selected>-- Select Appointment --</option>
                    @foreach($appointments as $app)
                        <option value="{{ $app->appointment_id }}" {{ old('appointment_id') == $app->appointment_id ? 'selected' : '' }}>
                            {{ $app->reference_number }} — {{ $app->patient->last_name }}, {{ $app->patient->first_name }} {{ $app->patient->suffix !== 'None' ? $app->patient->suffix : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="payment_status">Payment Status</label>
                <select name="payment_status" id="payment_status" class="form-control" required>
                    <option value="Unpaid" {{ old('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Partially Paid" {{ old('payment_status') == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="Paid" {{ old('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Cancelled" {{ old('payment_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>



        {{-- Form Actions --}}
        <div style="margin-top: 32px; border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="bi bi-receipt"></i> Generate Invoice Record
            </button>
        </div>
    </form>
</div>
@endsection