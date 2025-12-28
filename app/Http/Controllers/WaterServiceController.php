<?php

namespace App\Http\Controllers;

use App\Models\WaterService;
use App\Models\Apartment;
use Illuminate\Http\Request;

class WaterServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = WaterService::with('apartment');

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
                    Apartment::select('name')->whereColumn('apartments.id', 'water_services.apartment_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } else {
                $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->latest();
        }

        $waterServices = $query->paginate(10)->withQueryString();
        $apartments = Apartment::orderBy('name')->get();

        return view('water-services.index', compact('waterServices', 'apartments'));
    }

    public function create()
    {
        $apartments = Apartment::all();
        return view('water-services.create', compact('apartments'));
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

        WaterService::create($validated);

        return redirect()->route('water-services.index')->with('success', 'Water service created successfully.');
    }

    public function show(WaterService $waterService)
    {
        $waterService->load([
            'apartment',
            'waterReadings' => fn ($query) => $query->latest('reading_date'),
        ]);
        return view('water-services.show', compact('waterService'));
    }

    public function edit(WaterService $waterService)
    {
        $apartments = Apartment::all();
        return view('water-services.edit', compact('waterService', 'apartments'));
    }

    public function update(Request $request, WaterService $waterService)
    {
        $validated = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'registration_number' => 'required|string|max:255',
            'meter_number' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $waterService->update($validated);

        return redirect()->route('water-services.index')->with('success', 'Water service updated successfully.');
    }

    public function destroy(WaterService $waterService)
    {
        $waterService->delete();

        return redirect()->route('water-services.index')->with('success', 'Water service deleted successfully.');
    }
}
