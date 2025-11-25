<?php

namespace App\Http\Controllers;

use App\Models\WaterReading;
use App\Models\WaterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WaterReadingController extends Controller
{
    public function index()
    {
        $waterReadings = WaterReading::with('waterService.apartment')
            ->latest('reading_date')
            ->paginate(10);
        return view('water-readings.index', compact('waterReadings'));
    }

    public function create(Request $request)
    {
        $waterServices = WaterService::where('is_active', true)
            ->with('apartment')
            ->get();
        $selectedServiceId = $request->query('water_service_id');
        $returnToService = $request->boolean('return_to_service');

        return view('water-readings.create', compact('waterServices', 'selectedServiceId', 'returnToService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'water_service_id' => 'required|exists:water_services,id',
            'reading_date' => 'required|date',
            'reading_value' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'image_path' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'is_paid' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('water-readings', 'private');
        }

        $validated['is_paid'] = $request->boolean('is_paid');

        $reading = WaterReading::create($validated);

        if ($request->boolean('return_to_service')) {
            return redirect()
                ->route('water-services.show', $reading->water_service_id)
                ->with('success', 'Water reading created successfully.');
        }

        return redirect()->route('water-readings.index')->with('success', 'Water reading created successfully.');
    }

    public function show(WaterReading $waterReading)
    {
        $waterReading->load('waterService.apartment');
        return view('water-readings.show', compact('waterReading'));
    }

    public function edit(WaterReading $waterReading)
    {
        $waterServices = WaterService::where('is_active', true)
            ->with('apartment')
            ->get();
        return view('water-readings.edit', compact('waterReading', 'waterServices'));
    }

    public function update(Request $request, WaterReading $waterReading)
    {
        $validated = $request->validate([
            'water_service_id' => 'required|exists:water_services,id',
            'reading_date' => 'required|date',
            'reading_value' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'image_path' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'is_paid' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($waterReading->image_path) {
                Storage::disk('private')->delete($waterReading->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('water-readings', 'private');
        }

        $validated['is_paid'] = $request->boolean('is_paid');

        $waterReading->update($validated);

        return redirect()->route('water-readings.index')->with('success', 'Water reading updated successfully.');
    }

    public function destroy(WaterReading $waterReading)
    {
        if ($waterReading->image_path) {
            Storage::disk('private')->delete($waterReading->image_path);
        }

        $waterReading->delete();

        return redirect()->route('water-readings.index')->with('success', 'Water reading deleted successfully.');
    }
}
