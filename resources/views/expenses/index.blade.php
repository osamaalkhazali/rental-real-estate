@extends('layouts.sidebar')

@section('title', 'Expenses')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Expenses</h2>
                        <a href="{{ route('expenses.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Expense</a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('expenses.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-8 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Search (بحث)</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Title, vendor..."
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
                                <label class="block text-sm font-medium mb-1">Lease (العقد)</label>
                                <select name="lease_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    @foreach ($leases as $lease)
                                        <option value="{{ $lease->id }}" {{ request('lease_id') == $lease->id ? 'selected' : '' }}>
                                            {{ $lease->tenant_name }} - {{ $lease->apartment->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Type (النوع)</label>
                                <select name="type"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
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
                                <label class="block text-sm font-medium mb-1">Min Amount</label>
                                <input type="number" name="amount_min" value="{{ request('amount_min') }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Amount</label>
                                <input type="number" name="amount_max" value="{{ request('amount_max') }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Filter
                                </button>
                                <a href="{{ route('expenses.index') }}"
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
                                        <a href="{{ route('expenses.index', array_merge(request()->query(), ['sort' => 'apartment', 'direction' => request('sort') === 'apartment' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Apartment (الشقة)
                                            @if(request('sort') === 'apartment')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('expenses.index', array_merge(request()->query(), ['sort' => 'type', 'direction' => request('sort') === 'type' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Type (النوع)
                                            @if(request('sort') === 'type')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('expenses.index', array_merge(request()->query(), ['sort' => 'title', 'direction' => request('sort') === 'title' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Title (العنوان)
                                            @if(request('sort') === 'title')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('expenses.index', array_merge(request()->query(), ['sort' => 'expense_date', 'direction' => request('sort') === 'expense_date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Date (التاريخ)
                                            @if(request('sort') === 'expense_date')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('expenses.index', array_merge(request()->query(), ['sort' => 'amount', 'direction' => request('sort') === 'amount' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Amount (المبلغ)
                                            @if(request('sort') === 'amount')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('expenses.index', array_merge(request()->query(), ['sort' => 'lease', 'direction' => request('sort') === 'lease' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Lease (العقد)
                                            @if(request('sort') === 'lease')
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
                                @forelse ($expenses as $index => $expense)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expenses->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $expense->apartment->display_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($expense->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $expense->expense_date?->format('Y-m-d') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            JOD {{ number_format($expense->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $expense->lease?->tenant_name ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('expenses.show', $expense) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">View</a>
                                            <a href="{{ route('expenses.edit', $expense) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400"
                                                    onclick="return confirm('Delete this expense?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No expenses recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
