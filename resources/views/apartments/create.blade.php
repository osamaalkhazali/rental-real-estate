@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Add New Apartment (إضافة شقة جديدة)</h2>
                        <a href="{{ route('apartments.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List (العودة للقائمة)
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

                    <form action="{{ route('apartments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-10">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">General
                                    Information (المعلومات العامة)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium mb-2">Name (الاسم) *</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="apartment_number" class="block text-sm font-medium mb-2">Apartment
                                            Number (رقم الشقة) *</label>
                                        <input type="text" name="apartment_number" id="apartment_number"
                                            value="{{ old('apartment_number') }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="location_text" class="block text-sm font-medium mb-2">Location Text
                                            (وصف الموقع) *</label>
                                        <input type="text" name="location_text" id="location_text"
                                            value="{{ old('location_text') }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="location_link" class="block text-sm font-medium mb-2">Location Link
                                            (رابط الموقع) *</label>
                                        <input type="url" name="location_link" id="location_link"
                                            value="{{ old('location_link') }}" required
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="location" class="block text-sm font-medium mb-2">Short Location
                                            Label (وصف قصير للموقع)</label>
                                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="floor_number" class="block text-sm font-medium mb-2">Floor
                                            Number (رقم الطابق)</label>
                                        <input type="number" name="floor_number" id="floor_number"
                                            value="{{ old('floor_number') }}"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="floor_plan" class="block text-sm font-medium mb-2">Floor Plan
                                            (مخطط الطابق)</label>
                                        <input type="file" name="floor_plan" id="floor_plan"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>

                                    <div>
                                        <label for="ownership_document" class="block text-sm font-medium mb-2">Ownership
                                            Document (سند الملكية)</label>
                                        <input type="file" name="ownership_document" id="ownership_document"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="important_files" class="block text-sm font-medium mb-2">Important
                                            Files (الملفات المهمة)</label>
                                        <input type="file" name="important_files[]" id="important_files" multiple
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">You can select multiple files (PDFs, images, documents)</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Layout &
                                    Amenities (المخطط والمرافق)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="bedrooms" class="block text-sm font-medium mb-2">Bedrooms (غرف النوم) *</label>
                                        <input type="number" name="bedrooms" id="bedrooms"
                                            value="{{ old('bedrooms', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="living_rooms" class="block text-sm font-medium mb-2">Living Rooms
                                            (غرف المعيشة) *</label>
                                        <input type="number" name="living_rooms" id="living_rooms"
                                            value="{{ old('living_rooms', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="guest_rooms" class="block text-sm font-medium mb-2">Guest Rooms
                                            (غرف الضيوف) *</label>
                                        <input type="number" name="guest_rooms" id="guest_rooms"
                                            value="{{ old('guest_rooms', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="kitchens" class="block text-sm font-medium mb-2">Kitchens (المطابخ) *</label>
                                        <input type="number" name="kitchens" id="kitchens"
                                            value="{{ old('kitchens', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="bathrooms" class="block text-sm font-medium mb-2">Bathrooms
                                            (الحمامات) *</label>
                                        <input type="number" name="bathrooms" id="bathrooms"
                                            value="{{ old('bathrooms', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="balconies" class="block text-sm font-medium mb-2">Balconies (الشرفات) *</label>
                                        <input type="number" name="balconies" id="balconies"
                                            value="{{ old('balconies', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="master_rooms" class="block text-sm font-medium mb-2">Master Rooms
                                            (الغرف الماستر) *</label>
                                        <input type="number" name="master_rooms" id="master_rooms"
                                            value="{{ old('master_rooms', 0) }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="square_meters" class="block text-sm font-medium mb-2">Square
                                            Meters (المساحة بالمتر المربع)</label>
                                        <input type="number" step="0.01" name="square_meters" id="square_meters"
                                            value="{{ old('square_meters') }}" min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="rent_price" class="block text-sm font-medium mb-2">Rent Price
                                            (الإيجار) *</label>
                                        <input type="number" step="0.01" name="rent_price" id="rent_price"
                                            value="{{ old('rent_price') }}" required min="0"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="is_available" class="block text-sm font-medium mb-2">Status (الحالة)</label>
                                        <select name="is_available" id="is_available"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="1" {{ old('is_available', 1) == 1 ? 'selected' : '' }}>
                                                Available
                                            </option>
                                            <option value="0" {{ old('is_available') == 0 ? 'selected' : '' }}>
                                                Occupied
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="description" class="block text-sm font-medium mb-2">Description (الوصف)</label>
                                    <textarea name="description" id="description" rows="3"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Media & Notes (الوسائط والملاحظات)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="furniture" class="block text-sm font-medium mb-2">Furniture (الأثاث)
                                            (مفصولة بفاصلة)</label>
                                        <textarea name="furniture" id="furniture" rows="2"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Sofa, Dining Table, Washer">{{ old('furniture') }}</textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="notes" class="block text-sm font-medium mb-2">Internal
                                            Notes (ملاحظات داخلية)</label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="images" class="block text-sm font-medium mb-2">Apartment
                                            Images (صور الشقة)</label>
                                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can select multiple
                                            images (max 5MB each) / يمكن اختيار عدة صور (بحد أقصى 5MB لكل صورة)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('apartments.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel (إلغاء)
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Apartment (حفظ الشقة)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
