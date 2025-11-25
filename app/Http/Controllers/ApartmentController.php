<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::with('leases')->latest()->paginate(10);
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
            'floor_plan' => 'nullable|string|max:255',
            'ownership_document' => 'nullable|string|max:255',
            'important_files' => 'nullable|string',
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
        $validated['important_files'] = $this->normalizeDelimitedInput($request->input('important_files'));

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
            $path = $imageFile->store("apartments/{$apartment->id}", 'public');

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
