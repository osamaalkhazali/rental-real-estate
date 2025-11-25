<?php

namespace App\Http\Controllers;

use App\Models\WaterService;
use App\Models\Apartment;
use Illuminate\Http\Request;

class WaterServiceController extends Controller
{
    public function index()
    {
        $waterServices = WaterService::with('apartment')->latest()->paginate(10);
        return view('water-services.index', compact('waterServices'));
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
