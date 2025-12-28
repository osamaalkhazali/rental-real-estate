@extends('layouts.sidebar')

@section('title', 'Investment Performance')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                        <div>
                            <h2 class="text-2xl font-semibold">Portfolio Investment Performance (أداء الاستثمار للمحفظة)</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor acquisition spend, rental
                                returns, and owner-funded costs across every apartment. (راقب تكاليف الشراء، عوائد الإيجار، ومصاريف المالك لكل شقة)</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 text-blue-700 px-3 py-1 text-xs font-semibold">
                                Updated {{ now()->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    
                    @php
                        $currency = fn(float $value) => 'JOD ' . number_format($value, 2);
                        $averageRoi = $totals['average_roi'];
                        $topApartment = $apartments->first(fn($apartment) => $apartment->roi !== null);
                        $netProfitClass =
                            $totals['net_profit'] >= 0
                                ? 'text-green-600 dark:text-green-400'
                                : 'text-red-600 dark:text-red-400';
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                        <div
                            class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Total Investment (إجمالي الاستثمار)</p>
                            <p class="mt-2 text-2xl font-semibold">{{ $currency($totals['purchase_price']) }}</p>
                        </div>
                        <div
                            class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Total Rental Income (إجمالي دخل الإيجار)</p>
                            <p class="mt-2 text-2xl font-semibold">{{ $currency($totals['income']) }}</p>
                        </div>
                        <div
                            class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Total Owner Expenses (إجمالي مصروفات المالك)</p>
                            <p class="mt-2 text-2xl font-semibold">{{ $currency($totals['expenses']) }}</p>
                        </div>
                        <div
                            class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Net Profit (صافي الربح)</p>
                            <p class="mt-2 text-2xl font-semibold {{ $netProfitClass }}">
                                {{ $currency($totals['net_profit']) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">General Expenses (المصروفات العامة)</p>
                            <p class="mt-2 text-xl font-semibold">{{ $currency($totals['general_expenses']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maintenance, fees, and other owner
                                charges.</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Owner Utility Expenses (مصاريف خدمات المالك)</p>
                            <p class="mt-2 text-xl font-semibold">{{ $currency($totals['owner_utility_expenses']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Utilities not billed to tenants.</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                            <p class="text-xs uppercase text-gray-500 dark:text-gray-400">Portfolio Average ROI (متوسط العائد على الاستثمار للمحفظة)</p>
                            <p class="mt-2 text-xl font-semibold">
                                {{ $averageRoi !== null ? number_format($averageRoi, 2) . '%' : 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Across apartments with purchase price
                                on record.</p>
                        </div>
                    </div>

                    @if ($topApartment)
                        <div
                            class="mb-8 rounded-lg bg-blue-50 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-700 p-5">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase text-blue-700 dark:text-blue-300">Top Performing Apartment (أعلى شقة أداءً)
                                    </p>
                                    <h3 class="mt-1 text-xl font-semibold text-blue-900 dark:text-blue-100">
                                        {{ $topApartment->display_name }}</h3>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">ROI (العائد):
                                        {{ number_format($topApartment->roi, 2) }}% &middot; Net Profit:
                                        {{ $currency($topApartment->total_income - $topApartment->total_expenses) }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs uppercase text-blue-700 dark:text-blue-300">Purchase Price (سعر الشراء)</p>
                                        <p class="mt-1 text-lg font-semibold text-blue-900 dark:text-blue-100">
                                            {{ $topApartment->purchase_price ? $currency((float) $topApartment->purchase_price) : 'N/A' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase text-blue-700 dark:text-blue-300">Rental Income (دخل الإيجار)</p>
                                        <p class="mt-1 text-lg font-semibold text-blue-900 dark:text-blue-100">
                                            {{ $currency($topApartment->total_income) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase text-blue-700 dark:text-blue-300">Owner Expenses (مصاريف المالك)</p>
                                        <p class="mt-1 text-lg font-semibold text-blue-900 dark:text-blue-100">
                                            {{ $currency($topApartment->total_expenses) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase text-blue-700 dark:text-blue-300">Leases (العقود)</p>
                                        <p class="mt-1 text-lg font-semibold text-blue-900 dark:text-blue-100">
                                            {{ $topApartment->leases->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-10">
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">ROI by Apartment (العائد لكل شقة)</h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Percentage (النسبة المئوية)</span>
                            </div>
                            <div class="relative h-72">
                                <canvas id="roiChart" class="absolute inset-0 w-full h-full"></canvas>
                            </div>
                        </div>
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">Income vs Expenses (الإيرادات مقابل المصروفات)</h3>
                                <span class="text-xs text-gray-500 dark:text-gray-400">JOD (دينار أردني)</span>
                            </div>
                            <div class="relative h-72">
                                <canvas id="financialChart" class="absolute inset-0 w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID (المعرف)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Apartment (الشقة)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Purchase Price (سعر الشراء)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Rent Income (دخل الإيجار)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        General Expenses (المصروفات العامة)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Owner Utility Expenses (مصاريف خدمات المالك)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total Expenses (إجمالي المصروفات)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Net Profit (صافي الربح)</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ROI (العائد على الاستثمار)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @forelse ($apartments as $apartment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $apartment->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $apartment->display_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $apartment->purchase_price !== null ? $currency((float) $apartment->purchase_price) : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $currency($apartment->total_income) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $currency($apartment->total_general_expenses) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $currency($apartment->total_owner_utility_expenses) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $currency($apartment->total_expenses) }}
                                        </td>
                                        @php
                                            $apartmentNetProfit = $apartment->total_income - $apartment->total_expenses;
                                            $profitClass =
                                                $apartmentNetProfit >= 0
                                                    ? 'text-green-600 dark:text-green-400'
                                                    : 'text-red-600 dark:text-red-400';
                                        @endphp
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold {{ $profitClass }}">
                                            {{ $currency($apartmentNetProfit) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $apartment->roi !== null ? number_format($apartment->roi, 2) . '%' : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                                            No apartments available for ROI reporting. (لا توجد شقق متاحة لتقرير العائد)
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chartData = @json($chartData);

            const roiCtx = document.getElementById('roiChart');
            const financialCtx = document.getElementById('financialChart');

            if (roiCtx) {
                const existing = Chart.getChart(roiCtx);
                if (existing) existing.destroy();

                new Chart(roiCtx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'ROI (%) / العائد على الاستثمار',
                            data: chartData.roi,
                            backgroundColor: 'rgba(59, 130, 246, 0.7)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1,
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => value + '%',
                                },
                            },
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => `${context.parsed.y.toFixed(2)}% / العائد`,
                                },
                            },
                        },
                    },
                });
            }

            if (financialCtx) {
                const existing = Chart.getChart(financialCtx);
                if (existing) existing.destroy();

                new Chart(financialCtx, {
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                                type: 'bar',
                                label: 'Income (الإيرادات)',
                                data: chartData.income,
                                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                                borderColor: 'rgba(34, 197, 94, 1)',
                                borderWidth: 1,
                            },
                            {
                                type: 'bar',
                                label: 'Expenses (المصروفات)',
                                data: chartData.expenses,
                                backgroundColor: 'rgba(239, 68, 68, 0.7)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 1,
                            },
                            {
                                type: 'line',
                                label: 'Net Profit (صافي الربح)',
                                data: chartData.net_profit,
                                borderColor: 'rgba(99, 102, 241, 1)',
                                backgroundColor: 'rgba(99, 102, 241, 0.25)',
                                borderWidth: 2,
                                yAxisID: 'y1',
                                tension: 0.3,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => 'JOD ' + Number(value).toLocaleString(),
                                },
                            },
                            y1: {
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    callback: (value) => 'JOD ' + Number(value).toLocaleString(),
                                },
                            },
                        },
                    },
                });
            }
        });
    </script>
@endpush
