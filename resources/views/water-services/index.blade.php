@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Water Services (خدمات المياه)</h2>
                        <a href="{{ route('water-services.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Service (إضافة خدمة)
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('water-services.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Search (بحث)</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Registration #, meter #..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Apartment (الشقة)</label>
                                <select name="apartment_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    @foreach ($apartments as $apartment)
                                        <option value="{{ $apartment->id }}" {{ request('apartment_id') == $apartment->id ? 'selected' : '' }}>
                                            {{ $apartment->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Status (الحالة)</label>
                                <select name="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Filter
                                </button>
                                <a href="{{ route('water-services.index') }}"
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
                                        <a href="{{ route('water-services.index', array_merge(request()->query(), ['sort' => 'apartment', 'direction' => request('sort') === 'apartment' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Apartment (الشقة)
                                            @if(request('sort') === 'apartment')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('water-services.index', array_merge(request()->query(), ['sort' => 'registration_number', 'direction' => request('sort') === 'registration_number' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Registration # (رقم التسجيل)
                                            @if(request('sort') === 'registration_number')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('water-services.index', array_merge(request()->query(), ['sort' => 'meter_number', 'direction' => request('sort') === 'meter_number' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Meter # (رقم العداد)
                                            @if(request('sort') === 'meter_number')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('water-services.index', array_merge(request()->query(), ['sort' => 'is_active', 'direction' => request('sort') === 'is_active' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Status (الحالة)
                                            @if(request('sort') === 'is_active')
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
                                @forelse($waterServices as $index => $service)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $waterServices->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $service->apartment->display_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $service->registration_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $service->meter_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $service->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('water-services.show', $service) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">View</a>
                                            <a href="{{ route('water-services.edit', $service) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                                            <form action="{{ route('water-services.destroy', $service) }}" method="POST"
                                                class="inline">
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
                                        <td colspan="6"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No water services found. <a href="{{ route('water-services.create') }}"
                                                class="text-blue-600 hover:text-blue-900">Add one now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $waterServices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
