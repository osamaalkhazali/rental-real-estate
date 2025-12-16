@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Payment Details - {{ $leasePayment->month }}</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('lease-payments.edit', $leasePayment) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('lease-payments.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Payment Information (معلومات الدفعة)</h3>
                            <dl class="space-y-2">
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Lease (العقد):</dt>
                                    <dd>{{ $leasePayment->lease->tenant_name }} -
                                        {{ $leasePayment->lease->apartment->display_name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Month (الشهر):</dt>
                                    <dd>{{ $leasePayment->month }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Due Date (تاريخ الاستحقاق):</dt>
                                    <dd>{{ $leasePayment->due_date->format('M d, Y') }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Amount Due (المبلغ المستحق):</dt>
                                    <dd class="text-red-600 dark:text-red-400 font-bold">
                                        JOD {{ number_format($leasePayment->amount_due, 2) }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Amount Paid (المبلغ المدفوع):</dt>
                                    <dd class="text-green-600 dark:text-green-400 font-bold">
                                        {{ $leasePayment->amount_paid ? 'JOD ' . number_format($leasePayment->amount_paid, 2) : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Payment Date (تاريخ الدفع):</dt>
                                    <dd>{{ $leasePayment->payment_date ? $leasePayment->payment_date->format('M d, Y') : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Status (الحالة):</dt>
                                    <dd>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if ($leasePayment->status === 'paid') bg-green-100 text-green-800
                                        @elseif($leasePayment->status === 'partial') bg-yellow-100 text-yellow-800
                                        @elseif($leasePayment->status === 'late') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($leasePayment->status) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Additional Details (تفاصيل إضافية)</h3>
                            <dl class="space-y-2">
                                @if ($leasePayment->amount_paid && $leasePayment->amount_due)
                                    <div class="flex">
                                        <dt class="font-medium w-1/3">Balance (الرصيد):</dt>
                                        <dd
                                            class="font-bold {{ $leasePayment->amount_paid >= $leasePayment->amount_due ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            JOD {{ number_format($leasePayment->amount_due - $leasePayment->amount_paid, 2) }}
                                        </dd>
                                    </div>
                                @endif
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Receipt (الإيصال):</dt>
                                    <dd>
                                        @if ($leasePayment->receipt_file)
                                            <a href="{{ route('private.file', $leasePayment->receipt_file) }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                                View Receipt
                                            </a>
                                        @else
                                            No receipt uploaded
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        @if ($leasePayment->notes)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Notes (ملاحظات)</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ $leasePayment->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
