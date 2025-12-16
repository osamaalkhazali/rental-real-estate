<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\View\View;

class ApartmentRoiController extends Controller
{
    public function index(): View
    {
        $apartments = Apartment::with([
            'leases',
            'leasePayments',
            'expenses',
            'waterServices.waterReadings',
            'electricServices.electricReadings',
        ])->get()->sortByDesc(fn (Apartment $apartment) => $apartment->roi ?? -INF)->values();

        $totals = [
            'purchase_price' => (float) $apartments->sum(fn (Apartment $apartment) => (float) ($apartment->purchase_price ?? 0)),
            'income' => (float) $apartments->sum('total_income'),
            'general_expenses' => (float) $apartments->sum('total_general_expenses'),
            'owner_utility_expenses' => (float) $apartments->sum('total_owner_utility_expenses'),
        ];

        $totals['expenses'] = $totals['general_expenses'] + $totals['owner_utility_expenses'];
        $totals['net_profit'] = $totals['income'] - $totals['expenses'];

        $roiValues = $apartments->pluck('roi')->filter(fn ($roi) => $roi !== null);
        $totals['average_roi'] = $roiValues->isNotEmpty() ? $roiValues->avg() : null;

        $chartData = [
            'labels' => $apartments->pluck('display_name')->values(),
            'roi' => $apartments->map(fn (Apartment $apartment) => $apartment->roi ?? 0)->values(),
            'income' => $apartments->map(fn (Apartment $apartment) => round($apartment->total_income, 2))->values(),
            'expenses' => $apartments->map(fn (Apartment $apartment) => round($apartment->total_expenses, 2))->values(),
            'net_profit' => $apartments->map(fn (Apartment $apartment) => round($apartment->total_income - $apartment->total_expenses, 2))->values(),
            'purchase_price' => $apartments->map(fn (Apartment $apartment) => round((float) ($apartment->purchase_price ?? 0), 2))->values(),
        ];

        return view('reports.apartments-roi', compact('apartments', 'totals', 'chartData'));
    }
}
