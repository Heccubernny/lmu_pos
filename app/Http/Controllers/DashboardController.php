<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate today's sales using distinct transaction totals
        $todayales = DB::table('pos_sales_items')
            ->where('status', 'completed')
            ->whereDate('date', Carbon::today())
            ->distinct()
            ->select('recipt_number', 'total_amount')
            ->get()
            ->sum('total_amount');

        // Calculate weekly sales using distinct transaction totals
        $weeklySales = DB::table('pos_sales_items')
            ->where('status', 'completed')
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->distinct()
            ->select('recipt_number', 'total_amount')
            ->get()
            ->sum('total_amount');

        // Count total unique orders
        $totalOrders = DB::table('pos_sales_items')
            ->where('status', 'completed')
            ->distinct('recipt_number')
            ->count('recipt_number');

        // Total products in catalog
        $totalProducts = \App\Models\Product::count();

        // Low stock products threshold (<= 5)
        $lowStockProducts = \App\Models\Product::where('quantity', '<=', 5)->count();

        // Retrieve recent 5 distinct transactions
        $maxIdsQuery = DB::table('pos_sales_items')
            ->selectRaw('MAX(id) as max_id')
            ->groupBy('recipt_number');

        $recentSales = \App\Models\Sale::whereIn('id', $maxIdsQuery)
            ->latest('date')
            ->take(5)
            ->get();

        return view('dashboard', compact('todayales', 'weeklySales', 'totalOrders', 'totalProducts', 'lowStockProducts', 'recentSales'));
    }
}
