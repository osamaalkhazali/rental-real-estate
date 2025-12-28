@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Leases (عقود الإيجار)</h2>
                        <a href="{{ route('leases.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Lease (إضافة عقد جديد)
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('leases.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Search (بحث)</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Tenant name, phone, email..."
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
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Start From (بداية من)</label>
                                <input type="date" name="start_from" value="{{ request('start_from') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Start To (بداية إلى)</label>
                                <input type="date" name="start_to" value="{{ request('start_to') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">End From (نهاية من)</label>
                                <input type="date" name="end_from" value="{{ request('end_from') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">End To (نهاية إلى)</label>
                                <input type="date" name="end_to" value="{{ request('end_to') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Filter
                                </button>
                                <a href="{{ route('leases.index') }}"
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
                                        <a href="{{ route('leases.index', array_merge(request()->query(), ['sort' => 'apartment', 'direction' => request('sort') === 'apartment' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Apartment (الشقة)
                                            @if(request('sort') === 'apartment')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('leases.index', array_merge(request()->query(), ['sort' => 'tenant_name', 'direction' => request('sort') === 'tenant_name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Tenant (المستأجر)
                                            @if(request('sort') === 'tenant_name')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('leases.index', array_merge(request()->query(), ['sort' => 'tenant_phone', 'direction' => request('sort') === 'tenant_phone' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Phone (الهاتف)
                                            @if(request('sort') === 'tenant_phone')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('leases.index', array_merge(request()->query(), ['sort' => 'start_date', 'direction' => request('sort') === 'start_date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Start Date (بداية العقد)
                                            @if(request('sort') === 'start_date')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('leases.index', array_merge(request()->query(), ['sort' => 'end_date', 'direction' => request('sort') === 'end_date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            End Date (نهاية العقد)
                                            @if(request('sort') === 'end_date')
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
                                @forelse($leases as $index => $lease)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $leases->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $lease->apartment->display_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $lease->tenant_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $lease->tenant_phone }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ optional($lease->start_date)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $lease->end_date ? $lease->end_date->format('Y-m-d') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('leases.show', $lease) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">View</a>
                                            <a href="{{ route('leases.edit', $lease) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                                            <form action="{{ route('leases.destroy', $lease) }}" method="POST"
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
                                        <td colspan="7"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No leases found. <a href="{{ route('leases.create') }}"
                                                class="text-blue-600 hover:text-blue-900">Add one now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $leases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
