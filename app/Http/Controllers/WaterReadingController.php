<?php

namespace App\Http\Controllers;

use App\Models\WaterReading;
use App\Models\WaterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WaterReadingController extends Controller
{
    public function index(Request $request)
    {
        $query = WaterReading::with('waterService.apartment');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('waterService.apartment', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by water service
        if ($request->filled('water_service_id')) {
            $query->where('water_service_id', $request->water_service_id);
        }

        // Filter by paid status
        if ($request->filled('status')) {
            $query->where('is_paid', $request->status === 'paid');
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('reading_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('reading_date', '<=', $request->to_date);
        }

        // Filter by reading value range
        if ($request->filled('reading_min')) {
            $query->where('reading_value', '>=', $request->reading_min);
        }
        if ($request->filled('reading_max')) {
            $query->where('reading_value', '<=', $request->reading_max);
        }

        // Filter by cost range
        if ($request->filled('cost_min')) {
            $query->where('cost', '>=', $request->cost_min);
        }
        if ($request->filled('cost_max')) {
            $query->where('cost', '<=', $request->cost_max);
        }

        // Sorting
        $sortField = $request->get('sort', 'reading_date');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['apartment', 'meter_number', 'reading_date', 'reading_value', 'cost', 'is_paid'];
        
        if (in_array($sortField, $allowedSorts)) {
            if ($sortField === 'apartment') {
                $query->orderBy(
                    WaterService::select('apartments.name')
                        ->join('apartments', 'apartments.id', '=', 'water_services.apartment_id')
                        ->whereColumn('water_services.id', 'water_readings.water_service_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } elseif ($sortField === 'meter_number') {
                $query->orderBy(
                    WaterService::select('meter_number')->whereColumn('water_services.id', 'water_readings.water_service_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } else {
                $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->latest('reading_date');
        }

        $waterReadings = $query->paginate(10)->withQueryString();
        $waterServices = WaterService::with('apartment')->get();

        return view('water-readings.index', compact('waterReadings', 'waterServices'));
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
