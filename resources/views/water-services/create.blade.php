@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Add New Water Service</h2>
                        <a href="{{ route('water-services.index') }}"
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

                    <form action="{{ route('water-services.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="apartment_id" class="block text-sm font-medium mb-2">Apartment *</label>
                                <select name="apartment_id" id="apartment_id" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Apartment</option>
                                    @foreach ($apartments as $apartment)
                                        <option value="{{ $apartment->id }}"
                                            {{ old('apartment_id') == $apartment->id ? 'selected' : '' }}>
                                            {{ $apartment->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="registration_number" class="block text-sm font-medium mb-2">Registration
                                    Number *</label>
                                <input type="text" name="registration_number" id="registration_number" required
                                    value="{{ old('registration_number') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="meter_number" class="block text-sm font-medium mb-2">Meter Number *</label>
                                <input type="text" name="meter_number" id="meter_number" required
                                    value="{{ old('meter_number') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="is_active" class="text-sm font-medium">Service is active</label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('water-services.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Save Water Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
