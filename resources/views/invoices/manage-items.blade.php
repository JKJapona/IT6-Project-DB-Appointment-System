@extends('layout')

@section('page_title')
    <a href="{{ route('invoices.index') }}" style="text-decoration: none; color: inherit;">Invoices</a> 
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <a href="{{ route('invoices.show', $invoice->invoice_id) }}" style="text-decoration: none; color: inherit;">Details</a>
    <span style="margin: 0 8px; opacity: 0.5;">/</span> 
    <span style="color: var(--primary-blue);">Manage Items</span>
@endsection

@section('content')
<!-- Header Section -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem;">Manage Invoice Items</h1>
        <p style="color: var(--font-muted); font-size: 0.875rem; margin-top: 4px;">
            Invoice <strong>#{{ $invoice->invoice_id }}</strong> &bull; 
            Patient: <strong>{{ $invoice->appointment->patient->first_name }} {{ $invoice->appointment->patient->last_name }}</strong>
        </p>
    </div>
    <a href="{{ route('invoices.show', $invoice->invoice_id) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Details
    </a>
</div>

<!-- Add Item Form Section -->
<div class="details-card">
    <div class="info-title">
        <i class="bi bi-plus-circle"></i> Add New Service
    </div>
    
    <form action="{{ route('invoices.items.store', $invoice->invoice_id) }}" method="POST" style="margin-top: 20px;">
        @csrf
        <div class="form-row">
            <!-- Service Selection -->
            <div class="form-group" style="flex: 2;">
                <label for="service_id">Select Service</label>
                <select name="service_id" id="service_id" class="form-control" required>
                    <option value="" disabled selected>-- Search for a service --</option>
                    @foreach($services as $service)
                        <option value="{{ $service->service_id }}">
                            {{ $service->service_name }} — ₱{{ number_format($service->price, 2) }} ({{ $service->department->department_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div class="form-group" style="flex: 0.5; min-width: 100px;">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control">
            </div>

            <!-- Submit Button -->
            <div class="form-group" style="flex: 0.5; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="height: 42px; width: 100%;">
                    <i class="bi bi-plus-lg"></i> Add
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Current Items Table Section -->
<div class="table-container">
    <div style="padding: 16px; border-bottom: 1px solid var(--border-color); background: var(--sidebar-bg); display: flex; justify-content: space-between; align-items: center;">
        <span style="font-weight: 700; font-size: 0.9rem; color: var(--sidebar-fg);">Current Billing Items</span>
        <span class="badge badge-success">{{ $invoice->items->count() }} Item(s)</span>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Service Description</th>
                <th>Unit Price</th>
                <th style="text-align: center;">Qty</th>
                <th>Subtotal</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $item)
            <tr>
                <td>
                    <div style="font-weight: 600;">{{ $item->service->service_name }}</div>
                    <div style="font-size: 0.75rem; color: var(--font-muted);">{{ $item->service->department->department_name }}</div>
                </td>
                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td style="font-weight: 600;">₱{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                <td style="text-align: right;">
                    <form action="{{ route('invoices.items.destroy', [$invoice->invoice_id, $item->item_id]) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Remove this service from the invoice?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--font-muted);">
                    No services added yet.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($invoice->items->count() > 0)
        <tfoot>
            <tr style="background: var(--sidebar-accent);">
                <td colspan="3" style="text-align: right; font-weight: 700; padding: 16px;">Grand Total:</td>
                <td colspan="2" style="padding: 16px;">
                    <span style="font-size: 1.1rem; font-weight: 700; color: var(--primary-blue);">
                        ₱{{ number_format($invoice->total_amount, 2) }}
                    </span>
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection