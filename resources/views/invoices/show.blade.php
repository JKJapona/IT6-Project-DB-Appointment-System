@extends('layout')

@section('page_title')
    <a href="{{ route('invoices.index') }}" style="text-decoration: none; color: inherit;">Invoices</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Details</span>
@endsection

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0;">Invoice Details</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">Statement of accounts and payment history.</p>
    </div>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>

<div class="details-card">
    <div class="details-header">
        <div>
            <span class="label-sm">Invoice Number</span>
            <h2 style="margin: 0; color: var(--primary-blue); font-size: 1.75rem;">#{{ $invoice->invoice_id }}</h2>
        </div>
        <div style="text-align: right;">
            <span class="label-sm" style="display: block; margin-bottom: 4px;">Payment Status</span>
            @php
                $status = $invoice->payment_status;
                $badgeStyle = 'font-size: 0.75rem; padding: 4px 12px; text-transform: uppercase;';
                
                $statusMap = [
                    'Paid' => ['class' => 'badge-success', 'icon' => 'bi-check-circle-fill'],
                    'Unpaid' => ['class' => 'badge-danger', 'icon' => 'bi-exclamation-circle-fill'],
                    'Partially Paid' => ['class' => 'badge-info', 'icon' => 'bi-pie-chart-fill'],
                    'Cancelled' => ['class' => 'badge-secondary', 'icon' => 'bi-x-circle-fill'],
                ];

                $current = $statusMap[$status] ?? ['class' => 'badge-secondary', 'icon' => 'bi-info-circle'];
            @endphp

            <span class="badge {{ $current['class'] }}" style="{{ $badgeStyle }}">
                <i class="bi {{ $current['icon'] }}"></i> {{ $status }}
            </span>
        </div>
    </div>

    <div class="details-grid">
        {{-- Left Column --}}
        <div>
            <div class="info-section">
                <div class="info-title">Patient & Appointment</div>
                <div class="info-content">
                    <strong>Patient Name:</strong> 
                    {{ $invoice->appointment->patient->last_name }}, 
                    {{ $invoice->appointment->patient->first_name }} 
                    {{ $invoice->appointment->patient->middle_name }} 
                    {{ $invoice->appointment->patient->suffix !== 'None' ? $invoice->appointment->patient->suffix : '' }}<br>
                    
                    <strong>Reference:</strong> 
                    <span style="font-family: monospace; color: var(--primary-blue);">{{ $invoice->appointment->reference_number }}</span><br>
                    
                    <strong>Department:</strong> 
                    {{ $invoice->appointment->schedule->department->department_name }}<br>
                    
                    <strong>Assigned Doctor:</strong> 
                    Dr. {{ $invoice->appointment->doctor->first_name }} {{ $invoice->appointment->doctor->last_name }}
                </div>
            </div>

            <div class="info-section">
                <div class="info-title">Billing Summary</div>
                <div class="meta-box" style="background: var(--sidebar-accent); border-color: var(--primary-blue);">
                    <span class="label-sm" style="display: block; margin-bottom: 4px;">Total Amount Due</span>
                    <div style="font-size: 1.5rem; color: var(--primary-blue); font-weight: 800;">
                        ₱{{ number_format($invoice->total_amount, 2) }}
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
                    <div style="margin-bottom: 8px;">
                        <i class="bi bi-clock"></i> <strong>Appointment Date:</strong> {{ \Carbon\Carbon::parse($invoice->appointment->schedule->schedule_date)->format('M d, Y') }}
                    </div>
                    <div style="font-size: 0.85rem; color: var(--font-muted);">
                        <i class="bi bi-cpu"></i> System Log: {{ $invoice->created_at ? $invoice->created_at->format('M d, Y - h:i A') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 12px;">
        <h3 style="margin: 0;">Services & Items</h3>
        <a href="{{ route('invoices.items.manage', $invoice->invoice_id) }}" class="btn btn-secondary" style="font-size: 0.875rem;">
            <i class="bi bi-list-check"></i> Manage Service
        </a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoice->items as $item)
                <tr>
                    <td>{{ $item->service->service_name }}</td>
                    <td>₱{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="text-align: right; font-weight: 600;">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--font-muted); padding: 24px;">
                        No services added to this invoice yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: var(--sidebar-accent); font-weight: 700;">
                    <td colspan="3" style="text-align: right;">Total Amount Due:</td>
                    <td style="text-align: right; color: var(--primary-blue); font-size: 1.1rem;">
                        ₱{{ number_format($invoice->total_amount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- UPDATED ACTIONS FOOTER -->
<div class="actions" style="display: flex; gap: 12px; align-items: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-color);">
    @if($invoice->payment_status !== 'Paid')
        <a href="{{ route('invoices.edit', $invoice->invoice_id) }}" class="btn btn-primary">
            <i class="bi bi-credit-card-2-back"></i> Process Payment
        </a>
    @endif

    <button onclick="window.print()" class="btn btn-secondary">
        <i class="bi bi-printer"></i> Print Record
    </button>

    <form action="{{ route('invoices.destroy', $invoice->invoice_id) }}" method="POST" style="margin-left: auto;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this invoice record?')">
            <i class="bi bi-trash"></i> Delete Invoice
        </button>
    </form>
</div>
@endsection