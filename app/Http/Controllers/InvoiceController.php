<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['appointment.patient', 'appointment.doctor'])->latest()->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $appointments = Appointment::doesntHave('invoice')
            ->with(['patient', 'schedule.department'])
            ->where('status', '!=', 'Cancelled')
            ->get();
            
        // You also need services to display in your "Create Invoice" form
        $services = Service::with('department')->get();
            
        return view('invoices.create', compact('appointments', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id|unique:invoices,appointment_id',
            'payment_status' => 'required|in:Unpaid,Paid,Partially Paid,Cancelled',
            'services' => 'required|array|min:1', // Ensure at least one service is selected
            'services.*' => 'exists:services,service_id',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Create the Invoice header (initial amount 0)
            $invoice = Invoice::create([
                'appointment_id' => $request->appointment_id,
                'total_amount' => 0,
                'payment_status' => $request->payment_status,
            ]);

            $total = 0;

            // 2. Loop through selected services to create items
            foreach ($request->services as $service_id) {
                $service = Service::findOrFail($service_id);
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->invoice_id,
                    'service_id' => $service->service_id,
                    'quantity' => 1, // Defaulting to 1, or get from request if needed
                    'unit_price' => $service->price, // Snapshot of current price
                ]);

                $total += $service->price;
            }

            // 3. Update the Invoice with the final calculated total
            $invoice->update(['total_amount' => $total]);

            return redirect()->route('invoices.index')->with('success', 'Invoice and items generated successfully.');
        });
    }

    public function show($id)
    {
        // Now loading 'items.service' so you can list them in the invoice view
        $invoice = Invoice::with(['appointment.patient', 'appointment.doctor', 'items.service'])
            ->findOrFail($id);
            
        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        $services = Service::all();
        return view('invoices.edit', compact('invoice', 'services'));
    }

    public function update(Request $request, $id)
    {
        // Update logic here would ideally involve syncing items, 
        // but for now, we update status and recalculate if items changed.
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:Unpaid,Paid,Partially Paid,Cancelled',
        ]);

        $invoice->update($request->only(['payment_status']));

        return redirect()->route('invoices.index')->with('success', 'Invoice status updated.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        // Because of ON DELETE CASCADE in your SQL, items will be deleted automatically
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }

    public function manageItems($id) {
        $invoice = Invoice::with('items.service')->findOrFail($id);
        $services = Service::with('department')->get();
        
        return view('invoices.manage-items', compact('invoice', 'services'));
    }

    // Handle adding the item and recalculating the total
    public function storeItem(Request $request, $id) {
        $request->validate([
            'service_id' => 'required|exists:services,service_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $invoice = Invoice::findOrFail($id);
        $service = Service::findOrFail($request->service_id);

        DB::transaction(function () use ($invoice, $service, $request) {
            // Create the item
            InvoiceItem::create([
                'invoice_id' => $invoice->invoice_id,
                'service_id' => $service->service_id,
                'quantity' => $request->quantity,
                'unit_price' => $service->price,
            ]);

            // Recalculate Invoice Total
            $newTotal = InvoiceItem::where('invoice_id', $invoice->invoice_id)
                ->selectRaw('SUM(quantity * unit_price) as total')
                ->value('total');

            $invoice->update(['total_amount' => $newTotal]);
        });

        return redirect()->route('invoices.show', $id)->with('success', 'Service added.');
    }
    
    public function destroyItem($invoiceId, $itemId)
    {
        // 1. Find the specific item belonging to this invoice
        $item = \App\Models\InvoiceItem::where('invoice_id', $invoiceId)
            ->where('item_id', $itemId)
            ->firstOrFail();

        \Illuminate\Support\Facades\DB::transaction(function () use ($item, $invoiceId) {
            // 2. Delete the item
            $item->delete();

            // 3. Recalculate the invoice total immediately
            $newTotal = \App\Models\InvoiceItem::where('invoice_id', $invoiceId)
                ->selectRaw('SUM(quantity * unit_price) as total')
                ->value('total') ?? 0;

            // 4. Update the main invoice record
            \App\Models\Invoice::where('invoice_id', $invoiceId)->update([
                'total_amount' => $newTotal
            ]);
        });

        return redirect()->back()->with('success', 'Item removed and invoice total updated.');
    }
}