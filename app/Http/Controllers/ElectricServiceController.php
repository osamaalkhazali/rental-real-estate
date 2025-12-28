<?php

namespace App\Http\Controllers;

use App\Models\ElectricService;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ElectricServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = ElectricService::with('apartment');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                    ->orWhere('meter_number', 'like', "%{$search}%")
                    ->orWhereHas('apartment', function ($aq) use ($search) {
                        $aq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by apartment
        if ($request->filled('apartment_id')) {
            $query->where('apartment_id', $request->apartment_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['apartment', 'registration_number', 'meter_number', 'is_active', 'created_at'];
        
        if (in_array($sortField, $allowedSorts)) {
            if ($sortField === 'apartment') {
                $query->orderBy(
                    Apartment::select('name')->whereColumn('apartments.id', 'electric_services.apartment_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } else {
                $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->latest();
        }

        $electricServices = $query->paginate(10)->withQueryString();
        $apartments = Apartment::orderBy('name')->get();

        return view('electric-services.index', compact('electricServices', 'apartments'));
    }

    public function create()
    {
        $apartments = Apartment::all();
        return view('electric-services.create', compact('apartments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'registration_number' => 'required|string|max:255',
            'meter_number' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        ElectricService::create($validated);

        return redirect()->route('electric-services.index')->with('success', 'Electric service created successfully.');
    }

    public function show(ElectricService $electricService)
    {
        $electricService->load([
            'apartment',
            'electricReadings' => fn ($query) => $query->latest('reading_date'),
        ]);
        return view('electric-services.show', compact('electricService'));
    }

    public function edit(ElectricService $electricService)
    {
        $apartments = Apartment::all();
        return view('electric-services.edit', compact('electricService', 'apartments'));
    }

    public function update(Request $request, ElectricService $electricService)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'registration_number' => 'required|string|max:255',
            'meter_number' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $electricService->update($validated);

        return redirect()->route('electric-services.index')->with('success', 'Electric service updated successfully.');
    }

    public function destroy(ElectricService $electricService)
    {
        $electricService->delete();

        return redirect()->route('electric-services.index')->with('success', 'Electric service deleted successfully.');
    }
}
