@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Edit Lease Payment</h2>
                        <a href="{{ route('lease-payments.index') }}"
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

                    <form action="{{ route('lease-payments.update', $leasePayment) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lease_id" class="block text-sm font-medium mb-2">Lease *</label>
                                <select name="lease_id" id="lease_id" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Lease</option>
                                    @foreach ($leases as $lease)
                                        <option value="{{ $lease->id }}"
                                            {{ old('lease_id', $leasePayment->lease_id) == $lease->id ? 'selected' : '' }}>
                                            {{ $lease->tenant_name }} - {{ $lease->apartment->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="month" class="block text-sm font-medium mb-2">Month *</label>
                                <input type="text" name="month" id="month"
                                    value="{{ old('month', $leasePayment->month) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium mb-2">Due Date *</label>
                                <input type="date" name="due_date" id="due_date"
                                    value="{{ old('due_date', $leasePayment->due_date->format('Y-m-d')) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="amount_due" class="block text-sm font-medium mb-2">Amount Due (JOD) *</label>
                                <input type="number" step="0.01" name="amount_due" id="amount_due"
                                    value="{{ old('amount_due', $leasePayment->amount_due) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="amount_paid" class="block text-sm font-medium mb-2">Amount Paid (JOD)</label>
                                <input type="number" step="0.01" name="amount_paid" id="amount_paid"
                                    value="{{ old('amount_paid', $leasePayment->amount_paid) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="payment_date" class="block text-sm font-medium mb-2">Payment Date</label>
                                <input type="date" name="payment_date" id="payment_date"
                                    value="{{ old('payment_date', $leasePayment->payment_date?->format('Y-m-d')) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium mb-2">Status *</label>
                                <select name="status" id="status" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="unpaid"
                                        {{ old('status', $leasePayment->status) == 'unpaid' ? 'selected' : '' }}>Unpaid
                                    </option>
                                    <option value="paid"
                                        {{ old('status', $leasePayment->status) == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="partial"
                                        {{ old('status', $leasePayment->status) == 'partial' ? 'selected' : '' }}>Partial
                                    </option>
                                    <option value="late"
                                        {{ old('status', $leasePayment->status) == 'late' ? 'selected' : '' }}>Late
                                    </option>
                                </select>
                            </div>

                            @if ($leasePayment->receipt_file)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2">Current Receipt File</label>
                                    <a href="{{ asset('storage/' . $leasePayment->receipt_file) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                        View Current Receipt
                                    </a>
                                </div>
                            @endif

                            <div class="md:col-span-2">
                                <label for="receipt_file" class="block text-sm font-medium mb-2">Receipt File
                                    {{ $leasePayment->receipt_file ? '(Replace)' : '' }}</label>
                                <input type="file" name="receipt_file" id="receipt_file" accept="image/*,.pdf"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium mb-2">Notes</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $leasePayment->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('lease-payments.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
