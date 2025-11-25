<?php

namespace App\Http\Controllers;

use App\Models\ElectricReading;
use App\Models\ElectricService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElectricReadingController extends Controller
{
    public function index()
    {
        $electricReadings = ElectricReading::with('electricService.apartment')
            ->latest('reading_date')
            ->paginate(10);
        return view('electric-readings.index', compact('electricReadings'));
    }

    public function create(Request $request)
    {
        $electricServices = ElectricService::where('is_active', true)
            ->with('apartment')
            ->get();
        $selectedServiceId = $request->query('electric_service_id');
        $returnToService = $request->boolean('return_to_service');

        return view('electric-readings.create', compact('electricServices', 'selectedServiceId', 'returnToService'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'electric_service_id' => 'required|exists:electric_services,id',
            'reading_date' => 'required|date',
            'reading_value' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'image_path' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'is_paid' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            $validated['image_path'] = $request->file('image_path')->store('electric-readings', 'private');
        }

        $validated['is_paid'] = $request->boolean('is_paid');

        $reading = ElectricReading::create($validated);

        if ($request->boolean('return_to_service')) {
            return redirect()
                ->route('electric-services.show', $reading->electric_service_id)
                ->with('success', 'Electric reading created successfully.');
        }

        return redirect()->route('electric-readings.index')->with('success', 'Electric reading created successfully.');
    }

    public function show(ElectricReading $electricReading)
    {
        $electricReading->load('electricService.apartment');
        return view('electric-readings.show', compact('electricReading'));
    }

    public function edit(ElectricReading $electricReading)
    {
        $electricServices = ElectricService::where('is_active', true)
            ->with('apartment')
            ->get();
        return view('electric-readings.edit', compact('electricReading', 'electricServices'));
    }

    public function update(Request $request, ElectricReading $electricReading)
    {
        $validated = $request->validate([
            'electric_service_id' => 'required|exists:electric_services,id',
            'reading_date' => 'required|date',
            'reading_value' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'image_path' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
            'is_paid' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($electricReading->image_path) {
                Storage::disk('private')->delete($electricReading->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('electric-readings', 'private');
        }

        $validated['is_paid'] = $request->boolean('is_paid');

        $electricReading->update($validated);

        return redirect()->route('electric-readings.index')->with('success', 'Electric reading updated successfully.');
    }

    public function destroy(ElectricReading $electricReading)
    {
        if ($electricReading->image_path) {
            Storage::disk('private')->delete($electricReading->image_path);
        }

        $electricReading->delete();

        return redirect()->route('electric-readings.index')->with('success', 'Electric reading deleted successfully.');
    }
}
