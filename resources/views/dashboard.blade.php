@extends('layouts.sidebar')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Apartments</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $totalApartments }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ $availableApartments }} available</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Leases</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $activeLeases }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">{{ $occupancyRate }}% occupancy</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Revenue</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        JOD {{ number_format($monthlyRevenue, 2) }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">JOD {{ number_format($paidPayments, 2) }}
                        collected</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Payments</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $pendingPayments }}</p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">JOD {{ number_format($totalPendingAmount, 2) }} due
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Revenue Trend (Last 6 Months)</h3>
            <div style="height: 250px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Payment Status Distribution</h3>
            <div style="height: 250px;">
                <canvas id="paymentStatusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Utility Costs (Last 6 Months)</h3>
        <div style="height: 300px;">
            <canvas id="utilityCostsChart"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Recent Leases</h2>
            </div>
            <div class="p-6">
                @if ($recentLeases->count() > 0)
                    <div class="space-y-4">
                        @foreach ($recentLeases as $lease)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-700 dark:text-gray-200">{{ $lease->tenant_name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Apartment:
                                        {{ $lease->apartment->display_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($lease->start_date)->format('M d, Y') }} -
                                        {{ \Carbon\Carbon::parse($lease->end_date)->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-700 dark:text-gray-200">
                                        JOD {{ number_format($lease->rent_amount, 2) }}</p>
                                    @php
                                        $statusClass =
                                            $lease->payment_status === 'paid'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                                : ($lease->payment_status === 'unpaid'
                                                    ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200');
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">{{ ucfirst($lease->payment_status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('leases.index') }}"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View all leases →</a>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>No leases found</p>
                        <a href="{{ route('leases.create') }}"
                            class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Create
                            New Lease</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Utility Readings</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Water Readings</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Pending: {{ $waterPendingReadings }}
                                </p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $waterReadings }}
                            Total</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">Electric Readings</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Pending:
                                    {{ $electricPendingReadings }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-yellow-600 dark:text-yellow-400">{{ $electricReadings }}
                            Total</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('water-readings.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Water Readings →</a>
                            <a href="{{ route('electric-readings.index') }}"
                                class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline">View Electric Readings
                                →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-8">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <a href="{{ route('apartments.create') }}"
                class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full mb-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Add Apartment</span>
            </a>
            <a href="{{ route('leases.create') }}"
                class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full mb-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">New Lease</span>
            </a>
            <a href="{{ route('water-readings.create') }}"
                class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full mb-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Add Reading</span>
            </a>
            <a href="{{ route('lease-payments.index') }}"
                class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition">
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full mb-3">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">View Payments</span>
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($monthlyRevenueData, 'month')) !!},
                datasets: [{
                    label: 'Revenue (JOD)',
                    data: {!! json_encode(array_column($monthlyRevenueData, 'revenue')) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'JOD ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        new Chart(paymentStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Unpaid', 'Partial', 'Late'],
                datasets: [{
                    data: [{{ $paymentStatusData['paid'] }}, {{ $paymentStatusData['unpaid'] }},
                        {{ $paymentStatusData['partial'] }}, {{ $paymentStatusData['late'] }}
                    ],
                    backgroundColor: ['rgb(34, 197, 94)', 'rgb(239, 68, 68)', 'rgb(250, 204, 21)',
                        'rgb(249, 115, 22)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        const utilityCostsCtx = document.getElementById('utilityCostsChart').getContext('2d');
        new Chart(utilityCostsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($utilityCostsData, 'month')) !!},
                datasets: [{
                    label: 'Water (JOD)',
                        data: {!! json_encode(array_column($utilityCostsData, 'water')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Electric (JOD)',
                        data: {!! json_encode(array_column($utilityCostsData, 'electric')) !!},
                        backgroundColor: 'rgba(250, 204, 21, 0.7)',
                        borderColor: 'rgb(250, 204, 21)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'JOD ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
