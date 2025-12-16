@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Apartment Details #{{ $apartment->display_name }}</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('apartments.edit', $apartment) }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('apartments.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                            <dl class="space-y-3">
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Name:</dt>
                                    <dd>{{ $apartment->name }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Apartment Number:</dt>
                                    <dd>{{ $apartment->apartment_number }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Location Text:</dt>
                                    <dd>{{ $apartment->location_text }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Location Link:</dt>
                                    <dd><a href="{{ $apartment->location_link }}" target="_blank"
                                            class="text-blue-600 dark:text-blue-400 underline">View Map</a></dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Short Label:</dt>
                                    <dd>{{ $apartment->location ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Floor Number:</dt>
                                    <dd>{{ $apartment->floor_number ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Floor Plan:</dt>
                                    <dd>{{ $apartment->floor_plan ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Ownership Document:</dt>
                                    <dd>{{ $apartment->ownership_document ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Important Files:</dt>
                                    <dd>
                                        @if ($apartment->important_files)
                                            <ul class="list-disc list-inside space-y-1 text-sm">
                                                @foreach ($apartment->important_files as $file)
                                                    <li>{{ $file }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Status:</dt>
                                    <dd>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $apartment->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $apartment->is_available ? 'Available' : 'Occupied' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Layout & Financials</h3>
                            <dl class="space-y-3">
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Bedrooms:</dt>
                                    <dd>{{ $apartment->bedrooms }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Living Rooms:</dt>
                                    <dd>{{ $apartment->living_rooms }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Guest Rooms:</dt>
                                    <dd>{{ $apartment->guest_rooms }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Kitchens:</dt>
                                    <dd>{{ $apartment->kitchens }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Bathrooms:</dt>
                                    <dd>{{ $apartment->bathrooms }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Balconies:</dt>
                                    <dd>{{ $apartment->balconies }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Master Rooms:</dt>
                                    <dd>{{ $apartment->master_rooms }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Square Meters:</dt>
                                    <dd>{{ $apartment->square_meters ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex">
                                    <dt class="font-medium w-1/3">Rent Price:</dt>
                                    <dd class="text-green-600 dark:text-green-400 font-bold">
                                        JOD {{ number_format($apartment->rent_price, 2) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Description</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $apartment->description ?? 'No description available.' }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Furniture & Notes</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Furniture</p>
                                    @if ($apartment->furniture)
                                        <ul class="list-disc list-inside text-gray-600 dark:text-gray-300">
                                            @foreach ($apartment->furniture as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-gray-500 dark:text-gray-400">No furniture listed.</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</p>
                                    <p class="text-gray-600 dark:text-gray-300">{{ $apartment->notes ?? 'No notes yet.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @php
                            $hasUtilities =
                                $apartment->waterServices->isNotEmpty() || $apartment->electricServices->isNotEmpty();
                        @endphp

                        @if ($hasUtilities)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Utility Services</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($apartment->waterServices as $service)
                                        @php
                                            $lastReading = $service->waterReadings->first();
                                        @endphp
                                        <div
                                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900/40">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Water Service</p>
                                                    <p class="text-lg font-semibold">{{ $service->registration_number }}
                                                    </p>
                                                </div>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <dl class="mt-4 space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Meter #</dt>
                                                    <dd class="font-medium">{{ $service->meter_number }}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Last Reading</dt>
                                                    <dd>{{ $lastReading?->reading_value ? number_format($lastReading->reading_value, 2) : 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Last Cost</dt>
                                                        <dd>
                                                            {{ $lastReading?->cost ? 'JOD ' . number_format($lastReading->cost, 2) : 'N/A' }}
                                                        </dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Reading Date</dt>
                                                    <dd>{{ optional($lastReading?->reading_date)->format('M d, Y') ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                            </dl>
                                            <div class="mt-4 flex flex-wrap gap-2 text-sm">
                                                <a href="{{ route('water-services.show', $service) }}"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100">
                                                    View Service
                                                </a>
                                                <a href="{{ route('water-readings.create', ['water_service_id' => $service->id]) }}"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                                                    Add Reading
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach

                                    @foreach ($apartment->electricServices as $service)
                                        @php
                                            $lastReading = $service->electricReadings->first();
                                        @endphp
                                        <div
                                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900/40">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Electric Service</p>
                                                    <p class="text-lg font-semibold">{{ $service->registration_number }}
                                                    </p>
                                                </div>
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <dl class="mt-4 space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Meter #</dt>
                                                    <dd class="font-medium">{{ $service->meter_number }}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Last Reading</dt>
                                                    <dd>{{ $lastReading?->reading_value ? number_format($lastReading->reading_value, 2) : 'N/A' }}
                                                    </dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Last Cost</dt>
                                                        <dd>
                                                            {{ $lastReading?->cost ? 'JOD ' . number_format($lastReading->cost, 2) : 'N/A' }}
                                                        </dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-500 dark:text-gray-400">Reading Date</dt>
                                                    <dd>{{ optional($lastReading?->reading_date)->format('M d, Y') ?? 'N/A' }}
                                                    </dd>
                                                </div>
                                            </dl>
                                            <div class="mt-4 flex flex-wrap gap-2 text-sm">
                                                <a href="{{ route('electric-services.show', $service) }}"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100">
                                                    View Service
                                                </a>
                                                <a href="{{ route('electric-readings.create', ['electric_service_id' => $service->id]) }}"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                                                    Add Reading
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($apartment->images->count() > 0)
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-semibold mb-4">Images</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($apartment->images as $image)
                                        <div class="relative">
                                            <img src="{{ $image->url }}" alt="Apartment Image"
                                                class="w-full h-48 object-cover rounded shadow">
                                            @if ($image->is_primary)
                                                <span
                                                    class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full">Primary</span>
                                            @endif
                                            @if ($image->title || $image->caption)
                                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                    <p>{{ $image->title ?? 'Photo' }}</p>
                                                    @if ($image->caption)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $image->caption }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4">Leases ({{ $apartment->leases->count() }})</h3>
                            @if ($apartment->leases->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                    Tenant</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                    Start Date</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                    End Date</th>
                                                <th
                                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($apartment->leases as $lease)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm">{{ $lease->tenant_name }}</td>
                                                    <td class="px-4 py-2 text-sm">{{ $lease->start_date }}</td>
                                                    <td class="px-4 py-2 text-sm">{{ $lease->end_date }}</td>
                                                    @php
                                                        $leaseStatusClass = match ($lease->payment_status) {
                                                            'paid' => 'bg-green-100 text-green-800',
                                                            'unpaid' => 'bg-red-100 text-red-800',
                                                            default => 'bg-yellow-100 text-yellow-800',
                                                        };
                                                    @endphp
                                                    <td class="px-4 py-2 text-sm">
                                                        <span
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaseStatusClass }}">
                                                            {{ ucfirst($lease->payment_status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No leases for this apartment.</p>
                            @endif
                        </div>

                        @if ($apartment->leases->count() > 0)
                            <div class="md:col-span-2 space-y-6">
                                <h3 class="text-lg font-semibold">Tenant Activity & Billing</h3>
                                @foreach ($apartment->leases as $lease)
                                    @php
                                        $leaseEnd = $lease->end_date ?? now();
                                        $waterUsage = $apartment->waterServices
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
                                        $electricUsage = $apartment->electricServices
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
                                        $leaseStatusLabel =
                                            $lease->end_date && $lease->end_date->isPast() ? 'Completed' : 'Active';
                                        $leaseStatusClasses =
                                            $leaseStatusLabel === 'Active'
                                                ? 'bg-blue-100 text-blue-800'
                                                : 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <div
                                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 bg-white dark:bg-gray-800/70 shadow">
                                        <div class="flex flex-wrap justify-between gap-4">
                                            <div>
                                                <p class="text-xl font-semibold">{{ $lease->tenant_name }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $lease->tenant_email }} • {{ $lease->tenant_phone }}
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaseStatusClasses }}">
                                                    {{ $leaseStatusLabel }}
                                                </span>
                                                <a href="{{ route('leases.show', $lease) }}"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50">
                                                    View Lease
                                                </a>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm">
                                            <div>
                                                <p class="text-gray-500 dark:text-gray-400">Lease Period</p>
                                                <p class="font-medium">{{ $lease->start_date->format('M d, Y') }} -
                                                    {{ $lease->end_date ? $lease->end_date->format('M d, Y') : 'Present' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 dark:text-gray-400">Rent Amount</p>
                                                <p class="font-medium">JOD {{ number_format($lease->rent_amount, 2) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 dark:text-gray-400">Deposit</p>
                                                <p class="font-medium">
                                                    {{ $lease->deposit_amount ? 'JOD ' . number_format($lease->deposit_amount, 2) : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <h4 class="text-md font-semibold mb-2">Payments</h4>
                                            @if ($lease->leasePayments->count() > 0)
                                                <div class="overflow-x-auto">
                                                    <table
                                                        class="w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                                            <tr>
                                                                <th
                                                                    class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                    Month</th>
                                                                <th
                                                                    class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                    Due Date</th>
                                                                <th
                                                                    class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                    Amount Due</th>
                                                                <th
                                                                    class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                    Amount Paid</th>
                                                                <th
                                                                    class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                    Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                            @foreach ($lease->leasePayments as $payment)
                                                                @php
                                                                    $statusClasses = match ($payment->status) {
                                                                        'paid' => 'bg-green-100 text-green-800',
                                                                        'overdue' => 'bg-red-100 text-red-800',
                                                                        default => 'bg-yellow-100 text-yellow-800',
                                                                    };
                                                                @endphp
                                                                <tr>
                                                                    <td class="px-4 py-2">{{ $payment->month }}</td>
                                                                    <td class="px-4 py-2">
                                                                        {{ optional($payment->due_date)->format('M d, Y') }}
                                                                    </td>
                                                                    <td class="px-4 py-2">
                                                                        JOD {{ number_format($payment->amount_due, 2) }}</td>
                                                                    <td class="px-4 py-2">
                                                                        {{ $payment->amount_paid ? 'JOD ' . number_format($payment->amount_paid, 2) : '—' }}
                                                                    </td>
                                                                    <td class="px-4 py-2">
                                                                        <span
                                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                                                            {{ ucfirst($payment->status) }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No recorded payments
                                                    for this lease.</p>
                                            @endif
                                        </div>

                                        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                            <div>
                                                <h4 class="text-md font-semibold mb-2">Water Readings During Lease</h4>
                                                @if ($waterUsage->isNotEmpty())
                                                    <div class="overflow-x-auto">
                                                        <table
                                                            class="w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                                            <thead class="bg-gray-50 dark:bg-gray-900">
                                                                <tr>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Date</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Meter #</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Reading</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Cost</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Paid</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                                @foreach ($waterUsage as $entry)
                                                                    <tr>
                                                                        <td class="px-4 py-2">
                                                                            {{ $entry['reading']->reading_date->format('M d, Y') }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                            {{ $entry['service']->meter_number }}</td>
                                                                        <td class="px-4 py-2">
                                                                            {{ number_format($entry['reading']->reading_value, 2) }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                                JOD {{ number_format($entry['reading']->cost, 2) }}
                                                                            </td>
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
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">No water readings
                                                        recorded during this lease.</p>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-md font-semibold mb-2">Electric Readings During Lease</h4>
                                                @if ($electricUsage->isNotEmpty())
                                                    <div class="overflow-x-auto">
                                                        <table
                                                            class="w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                                            <thead class="bg-gray-50 dark:bg-gray-900">
                                                                <tr>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Date</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Meter #</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Reading</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Cost</th>
                                                                    <th
                                                                        class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">
                                                                        Paid</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                                                @foreach ($electricUsage as $entry)
                                                                    <tr>
                                                                        <td class="px-4 py-2">
                                                                            {{ $entry['reading']->reading_date->format('M d, Y') }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                            {{ $entry['service']->meter_number }}</td>
                                                                        <td class="px-4 py-2">
                                                                            {{ number_format($entry['reading']->reading_value, 2) }}
                                                                        </td>
                                                                        <td class="px-4 py-2">
                                                                                JOD {{ number_format($entry['reading']->cost, 2) }}
                                                                            </td>
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
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">No electric
                                                        readings recorded during this lease.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
