<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaseController extends Controller
{
    public function index()
    {
        $leases = Lease::with('apartment')->latest()->paginate(10);
        return view('leases.index', compact('leases'));
    }

    public function create()
    {
        $apartments = Apartment::all();
        return view('leases.create', compact('apartments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'tenant_name' => 'required|string|max:255',
            'tenant_phone' => 'required|string|max:255',
            'tenant_email' => 'required|email|max:255',
            'tenant_national_id' => 'nullable|string|max:255',
            'tenant_image' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'national_id_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'lease_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid,partial',
            'deposit_amount' => 'nullable|numeric|min:0',
            'documents' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        // Handle tenant image upload
        if ($request->hasFile('tenant_image')) {
            $validated['tenant_image'] = $request->file('tenant_image')->store('leases/tenant-images', 'private');
        }

        // Handle national ID image upload
        if ($request->hasFile('national_id_image')) {
            $validated['national_id_image'] = $request->file('national_id_image')->store('leases/national-ids', 'private');
        }

        // Handle lease document upload
        if ($request->hasFile('lease_document')) {
            $validated['lease_document'] = $request->file('lease_document')->store('leases/documents', 'private');
        }

        Lease::create($validated);

        return redirect()->route('leases.index')->with('success', 'Lease created successfully.');
    }

    public function show(Lease $lease)
    {
        $lease->load([
            'apartment' => fn ($query) => $query->with([
                'waterServices' => fn ($waterQuery) => $waterQuery
                    ->with(['waterReadings' => fn ($readingQuery) => $readingQuery->orderByDesc('reading_date')])
                    ->orderBy('registration_number'),
                'electricServices' => fn ($electricQuery) => $electricQuery
                    ->with(['electricReadings' => fn ($readingQuery) => $readingQuery->orderByDesc('reading_date')])
                    ->orderBy('registration_number'),
            ]),
        ]);
        return view('leases.show', compact('lease'));
    }

    public function edit(Lease $lease)
    {
        $apartments = Apartment::all();
        return view('leases.edit', compact('lease', 'apartments'));
    }

    public function update(Request $request, Lease $lease)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'tenant_name' => 'required|string|max:255',
            'tenant_phone' => 'required|string|max:255',
            'tenant_email' => 'required|email|max:255',
            'tenant_national_id' => 'nullable|string|max:255',
            'tenant_image' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'national_id_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'lease_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid,partial',
            'deposit_amount' => 'nullable|numeric|min:0',
            'documents' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        // Handle tenant image upload
        if ($request->hasFile('tenant_image')) {
            if ($lease->tenant_image) {
                Storage::disk('private')->delete($lease->tenant_image);
            }
            $validated['tenant_image'] = $request->file('tenant_image')->store('leases/tenant-images', 'private');
        }

        // Handle national ID image upload
        if ($request->hasFile('national_id_image')) {
            if ($lease->national_id_image) {
                Storage::disk('private')->delete($lease->national_id_image);
            }
            $validated['national_id_image'] = $request->file('national_id_image')->store('leases/national-ids', 'private');
        }

        // Handle lease document upload
        if ($request->hasFile('lease_document')) {
            if ($lease->lease_document) {
                Storage::disk('private')->delete($lease->lease_document);
            }
            $validated['lease_document'] = $request->file('lease_document')->store('leases/documents', 'private');
        }

        $lease->update($validated);

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully.');
    }

    public function destroy(Lease $lease)
    {
        // Delete associated files
        if ($lease->tenant_image) {
            Storage::disk('private')->delete($lease->tenant_image);
        }
        if ($lease->national_id_image) {
            Storage::disk('private')->delete($lease->national_id_image);
        }
        if ($lease->lease_document) {
            Storage::disk('private')->delete($lease->lease_document);
        }

        $lease->delete();

        return redirect()->route('leases.index')->with('success', 'Lease deleted successfully.');
    }
}
