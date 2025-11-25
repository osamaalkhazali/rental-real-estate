@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Add New Electric Reading</h2>
                        <a href="{{ route('electric-readings.index') }}"
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

                    <form action="{{ route('electric-readings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (!empty($returnToService))
                            <input type="hidden" name="return_to_service" value="1">
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="electric_service_id" class="block text-sm font-medium mb-2">Electric Service
                                    *</label>
                                <select name="electric_service_id" id="electric_service_id" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Electric Service</option>
                                    @foreach ($electricServices as $service)
                                        <option value="{{ $service->id }}"
                                            {{ old('electric_service_id', $selectedServiceId) == $service->id ? 'selected' : '' }}>
                                            {{ $service->apartment->display_name }} -
                                            {{ $service->meter_number ?? 'No Meter' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="reading_date" class="block text-sm font-medium mb-2">Reading Date *</label>
                                <input type="date" name="reading_date" id="reading_date"
                                    value="{{ old('reading_date') }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="reading_value" class="block text-sm font-medium mb-2">Reading Value (kWh)
                                    *</label>
                                <input type="number" step="0.01" name="reading_value" id="reading_value" required
                                    value="{{ old('reading_value') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="cost" class="block text-sm font-medium mb-2">Cost *</label>
                                <input type="number" step="0.01" name="cost" id="cost" required
                                    value="{{ old('cost') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_paid" id="is_paid" value="1"
                                    {{ old('is_paid') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="is_paid" class="text-sm font-medium">Mark as paid</label>
                            </div>

                            <div class="md:col-span-2">
                                <label for="image_path" class="block text-sm font-medium mb-2">Meter Reading Photo</label>
                                <input type="file" name="image_path" id="image_path" accept="image/*"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('electric-readings.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Reading
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
