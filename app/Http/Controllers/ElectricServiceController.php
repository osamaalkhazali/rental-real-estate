<?php

namespace App\Http\Controllers;

use App\Models\ElectricService;
use App\Models\Apartment;
use Illuminate\Http\Request;

class ElectricServiceController extends Controller
{
    public function index()
    {
        $electricServices = ElectricService::with('apartment')->latest()->paginate(10);
        return view('electric-services.index', compact('electricServices'));
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
