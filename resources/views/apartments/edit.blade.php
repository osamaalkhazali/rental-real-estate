@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Edit Apartment #{{ $apartment->display_name }}</h2>
                        <a href="{{ route('apartments.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('apartments.update', $apartment) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-10">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">General
                                    Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium mb-2">Name *</label>
                                        <input type="text" name="name" id="name"
                                            value="{{ old('name', $apartment->name) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="apartment_number" class="block text-sm font-medium mb-2">Apartment
                                            Number *</label>
                                        <input type="text" name="apartment_number" id="apartment_number"
                                            value="{{ old('apartment_number', $apartment->apartment_number) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="location_text" class="block text-sm font-medium mb-2">Location Text
                                            *</label>
                                        <input type="text" name="location_text" id="location_text"
                                            value="{{ old('location_text', $apartment->location_text) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="location_link" class="block text-sm font-medium mb-2">Location Link
                                            *</label>
                                        <input type="url" name="location_link" id="location_link"
                                            value="{{ old('location_link', $apartment->location_link) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="location" class="block text-sm font-medium mb-2">Short Location
                                            Label</label>
                                        <input type="text" name="location" id="location"
                                            value="{{ old('location', $apartment->location) }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="floor_number" class="block text-sm font-medium mb-2">Floor
                                            Number</label>
                                        <input type="number" name="floor_number" id="floor_number"
                                            value="{{ old('floor_number', $apartment->floor_number) }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="floor_plan" class="block text-sm font-medium mb-2">Floor Plan
                                            Path</label>
                                        <input type="text" name="floor_plan" id="floor_plan"
                                            value="{{ old('floor_plan', $apartment->floor_plan) }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="ownership_document" class="block text-sm font-medium mb-2">Ownership
                                            Document Path</label>
                                        <input type="text" name="ownership_document" id="ownership_document"
                                            value="{{ old('ownership_document', $apartment->ownership_document) }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="important_files" class="block text-sm font-medium mb-2">Important
                                            Files (comma separated)</label>
                                        <textarea name="important_files" id="important_files" rows="2"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('important_files', implode(', ', $apartment->important_files ?? [])) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Layout &
                                    Amenities</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="bedrooms" class="block text-sm font-medium mb-2">Bedrooms *</label>
                                        <input type="number" name="bedrooms" id="bedrooms" min="0"
                                            value="{{ old('bedrooms', $apartment->bedrooms) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="living_rooms" class="block text-sm font-medium mb-2">Living Rooms
                                            *</label>
                                        <input type="number" name="living_rooms" id="living_rooms" min="0"
                                            value="{{ old('living_rooms', $apartment->living_rooms) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="guest_rooms" class="block text-sm font-medium mb-2">Guest Rooms
                                            *</label>
                                        <input type="number" name="guest_rooms" id="guest_rooms" min="0"
                                            value="{{ old('guest_rooms', $apartment->guest_rooms) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="kitchens" class="block text-sm font-medium mb-2">Kitchens *</label>
                                        <input type="number" name="kitchens" id="kitchens" min="0"
                                            value="{{ old('kitchens', $apartment->kitchens) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="bathrooms" class="block text-sm font-medium mb-2">Bathrooms
                                            *</label>
                                        <input type="number" name="bathrooms" id="bathrooms" min="0"
                                            value="{{ old('bathrooms', $apartment->bathrooms) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="balconies" class="block text-sm font-medium mb-2">Balconies *</label>
                                        <input type="number" name="balconies" id="balconies" min="0"
                                            value="{{ old('balconies', $apartment->balconies) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="master_rooms" class="block text-sm font-medium mb-2">Master Rooms
                                            *</label>
                                        <input type="number" name="master_rooms" id="master_rooms" min="0"
                                            value="{{ old('master_rooms', $apartment->master_rooms) }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="square_meters" class="block text-sm font-medium mb-2">Square
                                            Meters</label>
                                        <input type="number" step="0.01" name="square_meters" id="square_meters"
                                            min="0" value="{{ old('square_meters', $apartment->square_meters) }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="rent_price" class="block text-sm font-medium mb-2">Rent Price
                                            *</label>
                                        <input type="number" step="0.01" name="rent_price" id="rent_price"
                                            min="0" value="{{ old('rent_price', $apartment->rent_price) }}"
                                            required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="is_available" class="block text-sm font-medium mb-2">Status</label>
                                        <select name="is_available" id="is_available"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="1"
                                                {{ old('is_available', $apartment->is_available) == 1 ? 'selected' : '' }}>
                                                Available</option>
                                            <option value="0"
                                                {{ old('is_available', $apartment->is_available) == 0 ? 'selected' : '' }}>
                                                Occupied</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="description" class="block text-sm font-medium mb-2">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $apartment->description) }}</textarea>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Media & Notes</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="furniture" class="block text-sm font-medium mb-2">Furniture (comma
                                            separated)</label>
                                        <textarea name="furniture" id="furniture" rows="2"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('furniture', implode(', ', $apartment->furniture ?? [])) }}</textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="notes" class="block text-sm font-medium mb-2">Internal
                                            Notes</label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $apartment->notes) }}</textarea>
                                    </div>

                                    @if ($apartment->images->count() > 0)
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium mb-2">Current Images</label>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                @foreach ($apartment->images as $image)
                                                    <div class="relative">
                                                        <img src="{{ $image->url }}" alt="Apartment Image"
                                                            class="w-full h-32 object-cover rounded">
                                                        @if ($image->is_primary)
                                                            <span
                                                                class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">Primary</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="md:col-span-2">
                                        <label for="images" class="block text-sm font-medium mb-2">Add New
                                            Images</label>
                                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can select multiple
                                            images
                                            (max 5MB each)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('apartments.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Apartment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
