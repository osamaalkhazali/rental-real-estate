@extends('layouts.sidebar')

@section('title', 'Expense Details')

@section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $expense->title }}</h2>
                            <p class="text-sm text-gray-500">Recorded on {{ $expense->expense_date->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('expenses.edit', $expense) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                onsubmit="return confirm('Delete this expense?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">Delete</button>
                            </form>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="rounded-md bg-green-100 text-green-800 px-4 py-2 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-gray-500">Apartment</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $expense->apartment->display_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Lease</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $expense->lease?->tenant_name ?? 'Not tied to a lease' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Type</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ ucfirst($expense->type) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Amount</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                ${{ number_format($expense->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Vendor</p>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $expense->vendor_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Receipt</p>
                            @if ($expense->receipt_file)
                                <a href="{{ asset('storage/' . $expense->receipt_file) }}" target="_blank"
                                    class="text-blue-600 dark:text-blue-400 underline">View Receipt</a>
                            @else
                                <p class="font-semibold text-gray-900 dark:text-gray-100">—</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Description</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $expense->description ?? 'No description provided.' }}</p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Attachments</h3>
                        @if (!empty($expense->attachments))
                            <ul class="list-disc list-inside text-blue-600 dark:text-blue-400">
                                @foreach ($expense->attachments as $attachment)
                                    <li><a href="{{ $attachment }}" target="_blank"
                                            class="underline">{{ $attachment }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-700 dark:text-gray-300">No attachments uploaded.</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Notes</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $expense->notes ?? 'No notes recorded.' }}</p>
                    </div>

                    <div>
                        <a href="{{ route('expenses.index') }}" class="text-blue-600 dark:text-blue-400">Back to list</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
