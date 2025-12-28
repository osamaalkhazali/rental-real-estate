<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Apartment::with('leases');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('apartment_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('location_text', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_available', $request->status === 'available');
        }

        // Filter by min rent
        if ($request->filled('min_rent')) {
            $query->where('rent_price', '>=', $request->min_rent);
        }

        // Filter by max rent
        if ($request->filled('max_rent')) {
            $query->where('rent_price', '<=', $request->max_rent);
        }

        // Filter by min area
        if ($request->filled('min_area')) {
            $query->where('square_meters', '>=', $request->min_area);
        }

        // Filter by max area
        if ($request->filled('max_area')) {
            $query->where('square_meters', '<=', $request->max_area);
        }

        // Filter by location text
        if ($request->filled('location_text')) {
            $query->where('location_text', 'like', "%{$request->location_text}%");
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['name', 'location_text', 'rent_price', 'square_meters', 'is_available', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $apartments = $query->paginate(10)->withQueryString();

        return view('apartments.index', compact('apartments'));
    }

    public function create()
    {
        return view('apartments.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateApartment($request);

        $apartment = Apartment::create($validated);

        $this->storeImages($request, $apartment);

        return redirect()->route('apartments.index')->with('success', 'Apartment created successfully.');
    }

    public function show(Apartment $apartment)
    {
        $apartment->load([
            'images',
            'leases' => fn ($query) => $query
                ->with(['leasePayments' => fn ($paymentQuery) => $paymentQuery->orderByDesc('due_date')])
                ->orderByDesc('start_date'),
            'waterServices' => fn ($query) => $query
                ->with(['waterReadings' => fn ($readingQuery) => $readingQuery->orderByDesc('reading_date')])
                ->orderBy('registration_number'),
            'electricServices' => fn ($query) => $query
                ->with(['electricReadings' => fn ($readingQuery) => $readingQuery->orderByDesc('reading_date')])
                ->orderBy('registration_number'),
        ]);

        return view('apartments.show', compact('apartment'));
    }

    public function edit(Apartment $apartment)
    {
        return view('apartments.edit', compact('apartment'));
    }

    public function update(Request $request, Apartment $apartment)
    {
        $validated = $this->validateApartment($request, $apartment);

        $apartment->update($validated);

        $this->storeImages($request, $apartment);

        return redirect()->route('apartments.index')->with('success', 'Apartment updated successfully.');
    }

    public function destroy(Apartment $apartment)
    {
        $apartment->delete();

        return redirect()->route('apartments.index')->with('success', 'Apartment deleted successfully.');
    }

    /**
     * Validate incoming apartment data against the expanded schema.
     */
    private function validateApartment(Request $request, ?Apartment $apartment = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_text' => 'required|string|max:255',
            'location_link' => 'required|url|max:255',
            'floor_plan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'ownership_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'important_files' => 'nullable|array',
            'important_files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'apartment_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('apartments', 'apartment_number')->ignore($apartment),
            ],
            'floor_number' => 'nullable|integer|min:0',
            'bedrooms' => 'required|integer|min:0',
            'living_rooms' => 'required|integer|min:0',
            'guest_rooms' => 'required|integer|min:0',
            'kitchens' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'balconies' => 'required|integer|min:0',
            'master_rooms' => 'required|integer|min:0',
            'square_meters' => 'nullable|numeric|min:0',
            'rent_price' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'furniture' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
        ]);

        $validated['is_available'] = $request->boolean('is_available');
        $validated['furniture'] = $this->normalizeDelimitedInput($request->input('furniture'));

        // Handle floor_plan upload
        if ($request->hasFile('floor_plan')) {
            $validated['floor_plan'] = $request->file('floor_plan')->store('apartments/floor-plans', 'private');
        } else {
            unset($validated['floor_plan']);
        }

        // Handle ownership_document upload
        if ($request->hasFile('ownership_document')) {
            $validated['ownership_document'] = $request->file('ownership_document')->store('apartments/ownership-documents', 'private');
        } else {
            unset($validated['ownership_document']);
        }

        // Handle important_files uploads
        if ($request->hasFile('important_files')) {
            $filePaths = [];
            foreach ($request->file('important_files') as $file) {
                $filePaths[] = $file->store('apartments/important-files', 'private');
            }
            $validated['important_files'] = $filePaths;
        } else {
            unset($validated['important_files']);
        }

        return $validated;
    }

    /**
     * Normalize comma or newline separated input into an array.
     */
    private function normalizeDelimitedInput(?string $value): ?array
    {
        if (blank($value)) {
            return null;
        }

        return collect(preg_split('/[,\n]+/', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Persist uploaded apartment images with metadata.
     */
    private function storeImages(Request $request, Apartment $apartment): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $existingCount = (int) $apartment->images()->count();
        $nextOrder = (int) ($apartment->images()->max('order') ?? 0);

        foreach ($request->file('images') as $index => $imageFile) {
            $path = $imageFile->store("apartments/{$apartment->id}", 'private');

            $apartment->images()->create([
                'path' => $path,
                'title' => pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME),
                'caption' => null,
                'is_primary' => $existingCount === 0 && $index === 0,
                'type' => $imageFile->extension(),
                'original_name' => $imageFile->getClientOriginalName(),
                'size' => $imageFile->getSize(),
                'mime_type' => $imageFile->getMimeType(),
                'order' => ++$nextOrder,
            ]);
        }
    }
}
