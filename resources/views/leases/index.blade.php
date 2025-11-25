@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Leases</h2>
                        <a href="{{ route('leases.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Lease
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Apartment</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tenant</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Phone</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Start Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        End Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($leases as $lease)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $lease->id }}</td>
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
