<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function visitors()
    {
        $today = Carbon::today();

        // Calculate the first day of the current year
        $startOfYear = Carbon::create($today->year, 1, 1);

        // Query to get unique visitors by month and country for the current year
        $visitorsPerMonth = Visitor::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            'country',
            DB::raw('count(DISTINCT ip_address) as unique_visitors')
        )
            ->where('date', '>=', $startOfYear)
            ->groupBy('month', 'country')
            ->orderBy('month')
            ->get();

        // Query to get unique visitors by date and country for the past week
        $oneWeekAgo = $today->copy()->subWeek();
        $visitorsLastWeek = Visitor::select(
            DB::raw('DATE(date) as date'),
            'country',
            DB::raw('count(DISTINCT ip_address) as unique_visitors')
        )
            ->where('date', '>=', $oneWeekAgo)
            ->groupBy('date', 'country')
            ->orderBy('date')
            ->get();

        // Query to get unique visitors by date and country for the past month
        $oneMonthAgo = $today->copy()->subMonth();
        $visitorsLastMonth = Visitor::select(
            DB::raw('DATE(date) as date'),
            'country',
            DB::raw('count(DISTINCT ip_address) as unique_visitors')
        )
            ->where('date', '>=', $oneMonthAgo)
            ->groupBy('date', 'country')
            ->orderBy('date')
            ->get();

        // Pass the data to the view
        return view('admin.analysis.visitors', compact('visitorsPerMonth', 'visitorsLastWeek', 'visitorsLastMonth'));
    }

    public function visits()
    {
        $today = Carbon::today();

        // Query to get total visits by date and country for today
        $visitsToday = Visitor::select(
            DB::raw('DATE(date) as date'),
            'country',
            DB::raw('count(*) as total_visits') // Count all visits for today
        )
            ->whereDate('date', $today)
            ->groupBy('date', 'country')
            ->orderBy('date')
            ->get();

        // Calculate the first day of the current year
        $startOfYear = Carbon::create($today->year, 1, 1);

        // Query to get total visits by month and country for the current year
        $visitsPerMonth = Visitor::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            'country',
            DB::raw('count(*) as total_visits') // Count all visits
        )
            ->where('date', '>=', $startOfYear)
            ->groupBy('month', 'country')
            ->orderBy('month')
            ->get();

        // Query to get total visits by date and country for the past week
        $oneWeekAgo = $today->copy()->subWeek();
        $visitsLastWeek = Visitor::select(
            DB::raw('DATE(date) as date'),
            'country',
            DB::raw('count(*) as total_visits') // Count all visits
        )
            ->where('date', '>=', $oneWeekAgo)
            ->groupBy('date', 'country')
            ->orderBy('date')
            ->get();

        // Query to get total visits by date and country for the past month
        $oneMonthAgo = $today->copy()->subMonth();
        $visitsLastMonth = Visitor::select(
            DB::raw('DATE(date) as date'),
            'country',
            DB::raw('count(*) as total_visits') // Count all visits
        )
            ->where('date', '>=', $oneMonthAgo)
            ->groupBy('date', 'country')
            ->orderBy('date')
            ->get();

        // Pass the data to the view
        return view('admin.analysis.visits', compact('visitsToday', 'visitsPerMonth', 'visitsLastWeek', 'visitsLastMonth'));
    }

    public function storehouse()
    {
        $products = Product::Active()->get();
        return view('admin.analysis.products',compact('products'));
    }
}
