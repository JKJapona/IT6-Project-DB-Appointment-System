<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Appointment;
use Illuminate\Http\Request;

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
            ->with('patient')
            ->where('status', '!=', 'Cancelled')
            ->get();
            
        return view('invoices.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,appointment_id|unique:invoices,appointment_id',
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Unpaid,Paid,Partially Paid,Cancelled',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice generated successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['appointment.patient', 'appointment.doctor'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Unpaid,Paid,Partially Paid,Cancelled',
        ]);

        $invoice->update($request->only(['total_amount', 'payment_status']));

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }
}