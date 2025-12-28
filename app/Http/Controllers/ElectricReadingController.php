<?php

namespace App\Http\Controllers;

use App\Models\ElectricReading;
use App\Models\ElectricService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElectricReadingController extends Controller
{
    public function index(Request $request)
    {
        $query = ElectricReading::with('electricService.apartment');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('electricService.apartment', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by electric service
        if ($request->filled('electric_service_id')) {
            $query->where('electric_service_id', $request->electric_service_id);
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
                    ElectricService::select('apartments.name')
                        ->join('apartments', 'apartments.id', '=', 'electric_services.apartment_id')
                        ->whereColumn('electric_services.id', 'electric_readings.electric_service_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } elseif ($sortField === 'meter_number') {
                $query->orderBy(
                    ElectricService::select('meter_number')->whereColumn('electric_services.id', 'electric_readings.electric_service_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } else {
                $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->latest('reading_date');
        }

        $electricReadings = $query->paginate(10)->withQueryString();
        $electricServices = ElectricService::with('apartment')->get();

        return view('electric-readings.index', compact('electricReadings', 'electricServices'));
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
