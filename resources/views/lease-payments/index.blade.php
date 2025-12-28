@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Lease Payments</h2>
                        <a href="{{ route('lease-payments.create') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Payment
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <form method="GET" action="{{ route('lease-payments.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-9 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Search (بحث)</label>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Tenant, apartment, month..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
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
                                <label class="block text-sm font-medium mb-1">Status (الحالة)</label>
                                <select name="status"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                                    <option value="">All</option>
                                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Month (الشهر)</label>
                                <input type="text" name="month" value="{{ request('month') }}" placeholder="e.g. 2024-11"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
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
                                <label class="block text-sm font-medium mb-1">Payment From</label>
                                <input type="date" name="payment_from" value="{{ request('payment_from') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Payment To</label>
                                <input type="date" name="payment_to" value="{{ request('payment_to') }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Min Due</label>
                                <input type="number" name="amount_due_min" value="{{ request('amount_due_min') }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Due</label>
                                <input type="number" name="amount_due_max" value="{{ request('amount_due_max') }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Min Paid</label>
                                <input type="number" name="amount_paid_min" value="{{ request('amount_paid_min') }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Max Paid</label>
                                <input type="number" name="amount_paid_max" value="{{ request('amount_paid_max') }}" step="0.01"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm text-sm">
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    Filter
                                </button>
                                <a href="{{ route('lease-payments.index') }}"
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
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'lease', 'direction' => request('sort') === 'lease' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Lease
                                            @if(request('sort') === 'lease')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'month', 'direction' => request('sort') === 'month' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Month
                                            @if(request('sort') === 'month')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'due_date', 'direction' => request('sort') === 'due_date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Due Date
                                            @if(request('sort') === 'due_date')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'payment_date', 'direction' => request('sort') === 'payment_date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Payment Date
                                            @if(request('sort') === 'payment_date')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'amount_due', 'direction' => request('sort') === 'amount_due' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Amount Due (المبلغ المستحق)
                                            @if(request('sort') === 'amount_due')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'amount_paid', 'direction' => request('sort') === 'amount_paid' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Amount Paid (المبلغ المدفوع)
                                            @if(request('sort') === 'amount_paid')
                                                <span>{{ request('direction') === 'asc' ? '↑' : '↓' }}</span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <a href="{{ route('lease-payments.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                                            class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-white">
                                            Status (الحالة)
                                            @if(request('sort') === 'status')
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
                                @forelse($leasePayments as $index => $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $leasePayments->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $payment->lease->tenant_name }} -
                                            {{ $payment->lease->apartment->display_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $payment->month }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $payment->due_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $payment->payment_date?->format('M d, Y') ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            JOD {{ number_format($payment->amount_due, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $payment->amount_paid ? 'JOD ' . number_format($payment->amount_paid, 2) : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($payment->status === 'paid') bg-green-100 text-green-800
                                            @elseif($payment->status === 'partial') bg-yellow-100 text-yellow-800
                                            @elseif($payment->status === 'late') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('lease-payments.show', $payment) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 mr-3">View</a>
                                            <a href="{{ route('lease-payments.edit', $payment) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 mr-3">Edit</a>
                                            <form action="{{ route('lease-payments.destroy', $payment) }}" method="POST"
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
                                        <td colspan="9"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No lease payments found. <a href="{{ route('lease-payments.create') }}"
                                                class="text-blue-600 hover:text-blue-900">Add one now</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $leasePayments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
