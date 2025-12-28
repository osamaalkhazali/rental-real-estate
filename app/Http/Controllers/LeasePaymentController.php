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
    public function index(Request $request)
    {
        $query = LeasePayment::with(['lease.apartment']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('lease', function ($q) use ($search) {
                $q->where('tenant_name', 'like', "%{$search}%")
                    ->orWhereHas('apartment', function ($aq) use ($search) {
                        $aq->where('name', 'like', "%{$search}%");
                    });
            })->orWhere('month', 'like', "%{$search}%");
        }

        // Filter by lease
        if ($request->filled('lease_id')) {
            $query->where('lease_id', $request->lease_id);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', 'like', "%{$request->month}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('due_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('due_date', '<=', $request->to_date);
        }

        // Filter by payment date range
        if ($request->filled('payment_from')) {
            $query->where('payment_date', '>=', $request->payment_from);
        }
        if ($request->filled('payment_to')) {
            $query->where('payment_date', '<=', $request->payment_to);
        }

        // Filter by amount due range
        if ($request->filled('amount_due_min')) {
            $query->where('amount_due', '>=', $request->amount_due_min);
        }
        if ($request->filled('amount_due_max')) {
            $query->where('amount_due', '<=', $request->amount_due_max);
        }

        // Filter by amount paid range
        if ($request->filled('amount_paid_min')) {
            $query->where('amount_paid', '>=', $request->amount_paid_min);
        }
        if ($request->filled('amount_paid_max')) {
            $query->where('amount_paid', '<=', $request->amount_paid_max);
        }

        // Sorting
        $sortField = $request->get('sort', 'due_date');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['lease', 'month', 'due_date', 'payment_date', 'amount_due', 'amount_paid', 'status'];
        
        if (in_array($sortField, $allowedSorts)) {
            if ($sortField === 'lease') {
                $query->orderBy(
                    Lease::select('tenant_name')->whereColumn('leases.id', 'lease_payments.lease_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } else {
                $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->orderBy('due_date', 'desc');
        }

        $leasePayments = $query->paginate(15)->withQueryString();
        $leases = Lease::with('apartment')->get();

        return view('lease-payments.index', compact('leasePayments', 'leases'));
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
