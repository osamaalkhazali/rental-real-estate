<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Lease;
use App\Models\LeasePayment;
use App\Models\WaterReading;
use App\Models\ElectricReading;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalApartments = Apartment::count();
        $availableApartments = Apartment::where('is_available', true)->count();
        $activeLeases = Lease::where('end_date', '>=', now())->count();

        // Revenue calculations
        $monthlyRevenue = Lease::where('end_date', '>=', now())
            ->sum('rent_amount');

        $paidPayments = LeasePayment::where('status', 'paid')
            ->whereMonth('payment_date', now()->month)
            ->sum('amount_paid');

        $pendingPayments = LeasePayment::whereIn('status', ['unpaid', 'partial', 'late'])->count();
        $totalPendingAmount = LeasePayment::whereIn('status', ['unpaid', 'partial', 'late'])
            ->sum('amount_due');

        // Recent leases (last 5)
        $recentLeases = Lease::with('apartment')
            ->latest()
            ->take(5)
            ->get();

        // Utility readings
        $waterReadings = WaterReading::count();
        $waterPendingReadings = WaterReading::where('is_paid', false)->count();
        $electricReadings = ElectricReading::count();
        $electricPendingReadings = ElectricReading::where('is_paid', false)->count();

        // Payment status distribution
        $paymentStatusData = [
            'paid' => LeasePayment::where('status', 'paid')->count(),
            'unpaid' => LeasePayment::where('status', 'unpaid')->count(),
            'partial' => LeasePayment::where('status', 'partial')->count(),
            'late' => LeasePayment::where('status', 'late')->count(),
        ];

        // Monthly revenue chart (last 6 months)
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = LeasePayment::where('status', 'paid')
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount_paid');

            $monthlyRevenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Occupancy rate
        $occupancyRate = $totalApartments > 0
            ? round((($totalApartments - $availableApartments) / $totalApartments) * 100, 1)
            : 0;

        // Utility costs (last 6 months)
        $utilityCostsData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);

            $waterCost = WaterReading::whereYear('reading_date', $month->year)
                ->whereMonth('reading_date', $month->month)
                ->sum('cost');

            $electricCost = ElectricReading::whereYear('reading_date', $month->year)
                ->whereMonth('reading_date', $month->month)
                ->sum('cost');

            $utilityCostsData[] = [
                'month' => $month->format('M Y'),
                'water' => $waterCost,
                'electric' => $electricCost,
            ];
        }

        return view('dashboard', compact(
            'totalApartments',
            'availableApartments',
            'activeLeases',
            'monthlyRevenue',
            'paidPayments',
            'pendingPayments',
            'totalPendingAmount',
            'recentLeases',
            'waterReadings',
            'waterPendingReadings',
            'electricReadings',
            'electricPendingReadings',
            'paymentStatusData',
            'monthlyRevenueData',
            'occupancyRate',
            'utilityCostsData'
        ));
    }
}
