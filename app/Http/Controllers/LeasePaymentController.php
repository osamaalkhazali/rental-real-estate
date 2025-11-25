<?php

namespace App\Http\Controllers;

use App\Models\LeasePayment;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeasePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leasePayments = LeasePayment::with(['lease.apartment'])
            ->orderBy('due_date', 'desc')
            ->paginate(15);

        return view('lease-payments.index', compact('leasePayments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leases = Lease::with('apartment')->get();
        return view('lease-payments.create', compact('leases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'month' => 'required|string|max:255',
            'due_date' => 'required|date',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:paid,unpaid,partial,late',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('receipt_file')) {
            $validated['receipt_file'] = $request->file('receipt_file')->store('lease-payments', 'private');
        }

        LeasePayment::create($validated);

        return redirect()->route('lease-payments.index')
            ->with('success', 'Lease payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeasePayment $leasePayment)
    {
        $leasePayment->load(['lease.apartment']);
        return view('lease-payments.show', compact('leasePayment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeasePayment $leasePayment)
    {
        $leases = Lease::with('apartment')->get();
        return view('lease-payments.edit', compact('leasePayment', 'leases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeasePayment $leasePayment)
    {
        $validated = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'month' => 'required|string|max:255',
            'due_date' => 'required|date',
            'amount_due' => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'status' => 'required|in:paid,unpaid,partial,late',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('receipt_file')) {
            // Delete old receipt if exists
            if ($leasePayment->receipt_file) {
                Storage::disk('private')->delete($leasePayment->receipt_file);
            }
            $validated['receipt_file'] = $request->file('receipt_file')->store('lease-payments', 'private');
        }

        $leasePayment->update($validated);

        return redirect()->route('lease-payments.index')
            ->with('success', 'Lease payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeasePayment $leasePayment)
    {
        // Delete receipt file if exists
        if ($leasePayment->receipt_file) {
            Storage::disk('private')->delete($leasePayment->receipt_file);
        }

        $leasePayment->delete();

        return redirect()->route('lease-payments.index')
            ->with('success', 'Lease payment deleted successfully.');
    }
}
