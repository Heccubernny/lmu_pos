<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function dailySales(Request $request)
    {
        $stores = Store::all();
        $categories = Category::all();
        $cashiers = User::all();

        $storeId = $request->input('store_id');
        $staffId = $request->input('staff_id');
        $category = $request->input('category');
        $modePayment = $request->input('mode_payment');
        $status = $request->input('status', 'completed');
        $period = $request->input('period', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        if ($period !== 'custom') {
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay()->toDateString();
                    $endDate = now()->endOfDay()->toDateString();
                    break;
                case 'yesterday':
                    $startDate = now()->subDay()->startOfDay()->toDateString();
                    $endDate = now()->subDay()->endOfDay()->toDateString();
                    break;
                case 'this_week':
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    break;
                case 'last_30_days':
                    $startDate = now()->subDays(30)->toDateString();
                    $endDate = now()->toDateString();
                    break;
                default:
                    $startDate = now()->toDateString();
                    $endDate = now()->toDateString();
                    break;
            }
        } else {
            $startDate = $startDate ?: now()->toDateString();
            $endDate = $endDate ?: now()->toDateString();
        }

        $query = Sale::query();
        
        $request->merge([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period,
            'status' => $status
        ]);

        $query = $this->applyFilters($query, $request);

        $sales = $query
            ->select('id', 'store_id', 'item_id', 'item_unit_price', 'quantity_purchased', 'total_amount', 'mode_payment', 'customer', 'staff_id', 'date', 'status')
            ->latest('date')
            ->get();

        $totalSales = $sales->sum('total_amount');

        return view('reports.daily_sales', compact(
            'sales',
            'totalSales',
            'stores',
            'categories',
            'cashiers',
            'storeId',
            'staffId',
            'category',
            'modePayment',
            'status',
            'period',
            'startDate',
            'endDate',
            'startTime',
            'endTime'
        ));
    }

    public function categorySales(Request $request)
    {
        $stores = Store::all();
        $categories = Category::all();
        $cashiers = User::all();

        $storeId = $request->input('store_id');
        $staffId = $request->input('staff_id');
        $category = $request->input('category');
        $modePayment = $request->input('mode_payment');
        $status = $request->input('status', 'completed');
        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        if ($period !== 'custom') {
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay()->toDateString();
                    $endDate = now()->endOfDay()->toDateString();
                    break;
                case 'yesterday':
                    $startDate = now()->subDay()->startOfDay()->toDateString();
                    $endDate = now()->subDay()->endOfDay()->toDateString();
                    break;
                case 'this_week':
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    break;
                case 'last_30_days':
                    $startDate = now()->subDays(30)->toDateString();
                    $endDate = now()->toDateString();
                    break;
                default:
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->toDateString();
                    break;
            }
        } else {
            $startDate = $startDate ?: now()->startOfMonth()->toDateString();
            $endDate = $endDate ?: now()->toDateString();
        }

        $query = Sale::query();

        $request->merge([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period,
            'status' => $status
        ]);

        $query = $this->applyFilters($query, $request);

        $categorySales = $query
            ->select('category', DB::raw('SUM(total_amount) as total_amount'), DB::raw('SUM(quantity_purchased) as total_qty'))
            ->groupBy('category')
            ->get();

        return view('reports.category_sales', compact(
            'categorySales',
            'stores',
            'categories',
            'cashiers',
            'storeId',
            'staffId',
            'category',
            'modePayment',
            'status',
            'period',
            'startDate',
            'endDate',
            'startTime',
            'endTime'
        ));
    }

    public function itemAnalysis(Request $request)
    {
        $stores = Store::all();
        $categories = Category::all();
        $cashiers = User::all();

        $storeId = $request->input('store_id');
        $staffId = $request->input('staff_id');
        $category = $request->input('category');
        $modePayment = $request->input('mode_payment');
        $status = $request->input('status', 'completed');
        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        if ($period !== 'custom') {
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay()->toDateString();
                    $endDate = now()->endOfDay()->toDateString();
                    break;
                case 'yesterday':
                    $startDate = now()->subDay()->startOfDay()->toDateString();
                    $endDate = now()->subDay()->endOfDay()->toDateString();
                    break;
                case 'this_week':
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    break;
                case 'last_30_days':
                    $startDate = now()->subDays(30)->toDateString();
                    $endDate = now()->toDateString();
                    break;
                default:
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->toDateString();
                    break;
            }
        } else {
            $startDate = $startDate ?: now()->startOfMonth()->toDateString();
            $endDate = $endDate ?: now()->toDateString();
        }

        $query = Sale::query();

        $request->merge([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'period' => $period,
            'status' => $status
        ]);

        $query = $this->applyFilters($query, $request);

        $itemAnalysis = $query
            ->select('item_id', DB::raw('SUM(quantity_purchased) as total_qty'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('item_id')
            ->with('product')
            ->get();

        return view('reports.item_analysis', compact(
            'itemAnalysis',
            'stores',
            'categories',
            'cashiers',
            'storeId',
            'staffId',
            'category',
            'modePayment',
            'status',
            'period',
            'startDate',
            'endDate',
            'startTime',
            'endTime'
        ));
    }

    /**
     * Apply report filters dynamically to a query builder.
     */
    private function applyFilters($query, Request $request, $defaultStatus = 'completed')
    {
        if ($storeId = $request->input('store_id')) {
            $query->where('store_id', $storeId);
        }

        if ($staffId = $request->input('staff_id')) {
            $query->where('staff_id', $staffId);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($modePayment = $request->input('mode_payment')) {
            $query->where('mode_payment', $modePayment);
        }

        $status = $request->input('status');
        if ($status) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        } else {
            if ($defaultStatus) {
                $query->where('status', $defaultStatus);
            }
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        if ($startTime = $request->input('start_time')) {
            $query->whereTime('date', '>=', $startTime);
        }
        if ($endTime = $request->input('end_time')) {
            $query->whereTime('date', '<=', $endTime);
        }

        return $query;
    }
}

