@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Electric Readings</h2>
                        <a href="{{ route('electric-readings.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Reading
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('electric-readings.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-10 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Search (بحث)</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Apartment name..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Service (الخدمة)</label>
                                <select name="electric_service_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    @foreach ($electricServices as $service)
                                        <option value="{{ $service->id }}" {{ request('electric_service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->apartment->display_name }} - {{ $service->meter_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Status (الحالة)</label>
                                <select name="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">From Date (من)</label>
                                <input type="date" name="from_date" value="{{ request('from_date') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">To Date (إلى)</label>
                                <input type="date" name="to_date" value="{{ request('to_date') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Min Reading</label>
                                <input type="number" step="0.01" name="reading_min" value="{{ request('reading_min') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Reading</label>
                                <input type="number" step="0.01" name="reading_max" value="{{ request('reading_max') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Min Cost</label>
                                <input type="number" step="0.01" name="cost_min" value="{{ request('cost_min') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Cost</label>
                                <input type="number" step="0.01" name="cost_max" value="{{ request('cost_max') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Filter
                                </button>
                                <a href="{{ route('electric-readings.index') }}"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No. (رقم)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('electric-readings.index', array_merge(request()->query(), ['sort' => 'apartment', 'direction' => request('sort') === 'apartment' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Service (الخدمة)
                                            @if(request('sort') === 'apartment')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('electric-readings.index', array_merge(request()->query(), ['sort' => 'reading_date', 'direction' => request('sort') === 'reading_date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Reading Date (تاريخ القراءة)
                                            @if(request('sort') === 'reading_date')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('electric-readings.index', array_merge(request()->query(), ['sort' => 'reading_value', 'direction' => request('sort') === 'reading_value' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Reading Value (قيمة القراءة)
                                            @if(request('sort') === 'reading_value')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('electric-readings.index', array_merge(request()->query(), ['sort' => 'cost', 'direction' => request('sort') === 'cost' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Cost (التكلفة)
                                            @if(request('sort') === 'cost')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('electric-readings.index', array_merge(request()->query(), ['sort' => 'is_paid', 'direction' => request('sort') === 'is_paid' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Paid? (مدفوع؟)
                                            @if(request('sort') === 'is_paid')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions (إجراءات)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($electricReadings as $index => $reading)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $electricReadings->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $reading->electricService->apartment->display_name }} - {{ $reading->electricService->meter_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ optional($reading->reading_date)->format('M d, Y') ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ number_format($reading->reading_value, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            JOD {{ number_format($reading->cost, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $reading->is_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $reading->is_paid ? 'Paid' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('electric-readings.edit', $reading) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                                            <form action="{{ route('electric-readings.destroy', $reading) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No electric readings found. <a href="{{ route('electric-readings.create') }}"
                                                class="text-blue-600 hover:text-blue-900">Add one now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $electricReadings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
