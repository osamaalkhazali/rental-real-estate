@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @php
                        $leaseEnd = $lease->end_date ?? now();
                        $waterUsage = $lease->apartment->waterServices
                            ->flatMap(function ($service) use ($lease, $leaseEnd) {
                                return $service->waterReadings
                                    ->filter(
                                        fn($reading) => $reading->reading_date->between(
                                            $lease->start_date,
                                            $leaseEnd,
                                            true,
                                        ),
                                    )
                                    ->map(
                                        fn($reading) => [
                                            'service' => $service,
                                            'reading' => $reading,
                                        ],
                                    );
                            })
                            ->sortByDesc(fn($entry) => $entry['reading']->reading_date)
                            ->values();
                        $electricUsage = $lease->apartment->electricServices
                            ->flatMap(function ($service) use ($lease, $leaseEnd) {
                                return $service->electricReadings
                                    ->filter(
                                        fn($reading) => $reading->reading_date->between(
                                            $lease->start_date,
                                            $leaseEnd,
                                            true,
                                        ),
                                    )
                                    ->map(
                                        fn($reading) => [
                                            'service' => $service,
                                            'reading' => $reading,
                                        ],
                                    );
                            })
                            ->sortByDesc(fn($entry) => $entry['reading']->reading_date)
                            ->values();
                    @endphp

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Lease Details - {{ $lease->tenant_name }} (تفاصيل العقد)</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('leases.edit', $lease) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit (تعديل)
                            </a>
                            <a href="{{ route('leases.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List (العودة للقائمة)
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Lease Information (بيانات العقد)</h3>
                            <dl class="space-y-2">
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Apartment (الشقة):</dt>
                                    <dd>{{ $lease->apartment->display_name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Start Date (تاريخ البدء):</dt>
                                    <dd>{{ optional($lease->start_date)->format('Y-m-d') }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">End Date (تاريخ الانتهاء):</dt>
                                    <dd>{{ $lease->end_date ? $lease->end_date->format('Y-m-d') : '—' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Deposit Amount (مبلغ التأمين):</dt>
                                    <dd class="text-green-600 dark:text-green-400 font-bold">
                                        {{ $lease->deposit_amount ? 'JOD ' . number_format($lease->deposit_amount, 2) : 'N/A' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Tenant Information (بيانات المستأجر)</h3>
                            <dl class="space-y-2">
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Name (الاسم):</dt>
                                    <dd>{{ $lease->tenant_name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Email (البريد):</dt>
                                    <dd>{{ $lease->tenant_email ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Phone (الهاتف):</dt>
                                    <dd>{{ $lease->tenant_phone }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">National ID (الرقم الوطني):</dt>
                                    <dd>{{ $lease->tenant_national_id ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4">Documents (المستندات)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @if ($lease->tenant_image)
                                    <div>
                                        <p class="text-sm font-medium mb-2">Tenant Photo (صورة المستأجر)</p>
                                        <img src="{{ route('private.file.view', $lease->tenant_image) }}" alt="Tenant Photo"
                                            class="w-full h-48 object-cover rounded shadow">
                                    </div>
                                @endif

                                @if ($lease->national_id_image)
                                    <div>
                                        <p class="text-sm font-medium mb-2">National ID (الهوية)</p>
                                        <img src="{{ route('private.file.view', $lease->national_id_image) }}" alt="National ID"
                                            class="w-full h-48 object-cover rounded shadow">
                                    </div>
                                @endif

                                @if ($lease->lease_document)
                                    <div>
                                        <p class="text-sm font-medium mb-2">Lease Document (ملف العقد)</p>
                                        <a href="{{ route('private.file', $lease->lease_document) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                                            View Document (عرض الملف)
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4">Utility Readings During Lease (قراءات المرافق خلال العقد)</h3>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-md font-semibold mb-2">Water (مياه)</h4>
                                    @if ($waterUsage->isNotEmpty())
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-900">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Date (التاريخ)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Meter # (رقم العداد)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Reading (القراءة)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Cost (التكلفة)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Paid (الدفع)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach ($waterUsage as $entry)
                                                        <tr>
                                                            <td class="px-4 py-2">
                                                                {{ $entry['reading']->reading_date->format('Y-m-d') }}</td>
                                                            <td class="px-4 py-2">{{ $entry['service']->meter_number }}
                                                            </td>
                                                            <td class="px-4 py-2">
                                                                {{ number_format($entry['reading']->reading_value, 2) }}
                                                            </td>
                                                            <td class="px-4 py-2">
                                                                JOD {{ number_format($entry['reading']->cost, 2) }}</td>
                                                            <td class="px-4 py-2">
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $entry['reading']->is_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ $entry['reading']->is_paid ? 'Paid' : 'Pending' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No water readings recorded
                                            during this lease. (لا توجد قراءات مياه خلال العقد)</p>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-md font-semibold mb-2">Electric (كهرباء)</h4>
                                    @if ($electricUsage->isNotEmpty())
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-900">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Date (التاريخ)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Meter # (رقم العداد)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Reading (القراءة)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Cost (التكلفة)</th>
                                                        <th
                                                            class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                            Paid (الدفع)</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    @foreach ($electricUsage as $entry)
                                                        <tr>
                                                            <td class="px-4 py-2">
                                                                {{ $entry['reading']->reading_date->format('Y-m-d') }}</td>
                                                            <td class="px-4 py-2">{{ $entry['service']->meter_number }}
                                                            </td>
                                                            <td class="px-4 py-2">
                                                                {{ number_format($entry['reading']->reading_value, 2) }}
                                                            </td>
                                                            <td class="px-4 py-2">
                                                                JOD {{ number_format($entry['reading']->cost, 2) }}</td>
                                                            <td class="px-4 py-2">
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $entry['reading']->is_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ $entry['reading']->is_paid ? 'Paid' : 'Pending' }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No electric readings recorded
                                            during this lease. (لا توجد قراءات كهرباء خلال العقد)</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($lease->notes)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Notes (ملاحظات)</h3>
                                <p class="text-gray-600 dark:text-gray-400">{{ $lease->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
