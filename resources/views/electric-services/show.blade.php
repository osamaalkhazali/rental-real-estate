@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Electric Service</p>
                            <h2 class="text-2xl font-semibold">
                                {{ $electricService->apartment->display_name ?? 'Apartment removed' }}
                            </h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('electric-services.index') }}"
                                class="inline-flex items-center rounded-md border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                                Back to list
                            </a>
                            <a href="{{ route('electric-readings.create', ['electric_service_id' => $electricService->id, 'return_to_service' => 1]) }}"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Add reading
                            </a>
                            <a href="{{ route('electric-services.edit', $electricService) }}"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                Edit service
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Registration number</p>
                            <p class="text-lg font-semibold">{{ $electricService->registration_number }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Meter number</p>
                            <p class="text-lg font-semibold">{{ $electricService->meter_number }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                            <p>
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $electricService->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $electricService->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Last updated</p>
                            <p class="text-lg font-semibold">
                                {{ optional($electricService->updated_at)->format('M d, Y') ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold">Recent readings</h3>
                            <a href="{{ route('electric-readings.create', ['electric_service_id' => $electricService->id, 'return_to_service' => 1]) }}"
                                class="text-sm font-medium text-blue-600 hover:text-blue-800">Log reading</a>
                        </div>

                        @if ($electricService->electricReadings->isEmpty())
                            <div
                                class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 p-6 text-center text-gray-500 dark:text-gray-400">
                                No readings recorded yet.
                                <a href="{{ route('electric-readings.create', ['electric_service_id' => $electricService->id]) }}"
                                    class="text-blue-600 hover:text-blue-800">Add the first reading</a>.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                                Date</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                                Reading (kWh)</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                                Cost</th>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                                Paid?</th>
                                            <th class="px-6 py-3"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($electricService->electricReadings as $reading)
                                            <tr>
                                                <td class="px-6 py-4 text-sm">
                                                    {{ optional($reading->reading_date)->format('M d, Y') ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm">
                                                    {{ number_format($reading->reading_value, 2) }}</td>
                                                <td class="px-6 py-4 text-sm">JOD {{ number_format($reading->cost, 2) }}</td>
                                                <td class="px-6 py-4 text-sm">
                                                    <span
                                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $reading->is_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $reading->is_paid ? 'Paid' : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-right">
                                                    <a href="{{ route('electric-readings.edit', $reading) }}"
                                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
