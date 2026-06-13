@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Daily Sales Report') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 rounded-xl p-6">
                <!-- Filters Panel -->
                <div class="mb-8 bg-slate-50 p-5 rounded-xl border border-slate-200">
                    <form action="{{ route('admin.reports.daily_sales') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Store Filter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Store</label>
                                <select name="store_id" class="block w-full py-2 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                    <option value="">All Stores</option>
                                    @foreach($stores as $st)
                                        <option value="{{ $st->id }}" {{ isset($storeId) && $storeId == $st->id ? 'selected' : '' }}>{{ $st->name ?? ($st->host ?? 'Store '.$st->id) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Product Category</label>
                                <select name="category" class="block w-full py-2 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}" {{ isset($category) && $category == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Cashier/Staff Filter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cashier / Staff</label>
                                <select name="staff_id" class="block w-full py-2 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                    <option value="">All Cashiers</option>
                                    @foreach($cashiers as $cashier)
                                        <option value="{{ $cashier->staff_id }}" {{ isset($staffId) && $staffId == $cashier->staff_id ? 'selected' : '' }}>{{ $cashier->name }} ({{ $cashier->staff_id }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Payment Mode Filter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Payment Mode</label>
                                <select name="mode_payment" class="block w-full py-2 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                    <option value="">All Payments</option>
                                    <option value="Cash" {{ isset($modePayment) && $modePayment == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Card" {{ isset($modePayment) && $modePayment == 'Card' ? 'selected' : '' }}>Card</option>
                                    <option value="Transfer" {{ isset($modePayment) && $modePayment == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                                <select name="status" class="block w-full py-2 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                    <option value="all" {{ isset($status) && $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                                    <option value="completed" {{ !isset($status) || $status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="voided" {{ isset($status) && $status == 'voided' ? 'selected' : '' }}>Voided</option>
                                </select>
                            </div>

                            <!-- Period Preset Filter -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Period Preset</label>
                                <select name="period" id="period-select" class="block w-full py-2 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                    <option value="today" {{ !isset($period) || $period == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="yesterday" {{ isset($period) && $period == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                    <option value="this_week" {{ isset($period) && $period == 'this_week' ? 'selected' : '' }}>This Week</option>
                                    <option value="this_month" {{ isset($period) && $period == 'this_month' ? 'selected' : '' }}>This Month</option>
                                    <option value="last_30_days" {{ isset($period) && $period == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                    <option value="custom" {{ isset($period) && $period == 'custom' ? 'selected' : '' }}>Custom Dates</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Fields -->
                        <div id="custom-date-fields" class="hidden flex-col sm:flex-row gap-4 pt-3 border-t border-slate-200/50">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">From</span>
                                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="py-1.5 px-2.5 text-xs border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">To</span>
                                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="py-1.5 px-2.5 text-xs border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            </div>
                        </div>

                        <!-- Time Range Fields -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-3 border-t border-slate-200/50">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Start Time</span>
                                <input type="time" name="start_time" value="{{ $startTime ?? '' }}" class="py-1.5 px-2.5 text-xs border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">End Time</span>
                                <input type="time" name="end_time" value="{{ $endTime ?? '' }}" class="py-1.5 px-2.5 text-xs border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end pt-3 border-t border-slate-200/50">
                            <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition shadow-sm text-xs font-bold flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Apply Filters
                            </button>
                            @if(!empty($storeId) || !empty($staffId) || !empty($category) || !empty($modePayment) || (isset($status) && $status !== 'completed') || (isset($period) && $period !== 'today') || !empty($startDate) || !empty($endDate) || !empty($startTime) || !empty($endTime))
                                <a href="{{ route('admin.reports.daily_sales') }}" class="px-5 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition text-xs font-bold flex items-center">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const periodSelect = document.getElementById('period-select');
                        const customDateFields = document.getElementById('custom-date-fields');

                        if (periodSelect && customDateFields) {
                            const toggleDateFields = () => {
                                if (periodSelect.value === 'custom') {
                                    customDateFields.classList.remove('hidden');
                                    customDateFields.classList.add('flex');
                                } else {
                                    customDateFields.classList.remove('flex');
                                    customDateFields.classList.add('hidden');
                                }
                            };
                            periodSelect.addEventListener('change', toggleDateFields);
                            toggleDateFields(); // Run initial check
                        }
                    });
                </script>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-6">
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mb-1">Total Sales Amount</p>
                        <h3 class="text-3xl font-bold text-slate-800">₦{{ number_format($totalSales, 2) }}</h3>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-6">
                        <p class="text-sm text-slate-500 font-medium uppercase tracking-wider mb-1">Total Transactions</p>
                        <h3 class="text-3xl font-bold text-slate-800">{{ $sales->count() }}</h3>
                    </div>
                </div>

                <!-- Charts Grid -->
                @if($sales->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 mt-6">
                        <!-- Sales by Payment Mode -->
                        <div class="bg-slate-50 p-5 border border-slate-200 rounded-2xl">
                            <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 inline-block"></span>
                                Sales Volume by Payment Mode
                            </h4>
                            <div class="relative h-64 flex items-center justify-center">
                                <canvas id="paymentModeChart"></canvas>
                            </div>
                        </div>

                        <!-- Sales by Store Branch -->
                        <div class="bg-slate-50 p-5 border border-slate-200 rounded-2xl">
                            <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 inline-block"></span>
                                Revenue by Store Branch (₦)
                            </h4>
                            <div class="relative h-64 flex items-center justify-center">
                                <canvas id="storeSalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Store</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cashier</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Item ID</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Mode</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sales as $sale)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        <span class="inline-flex items-center rounded bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 border border-slate-200">
                                            {{ $sale->store->name ?? ($sale->store->host ?? 'Main Store') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $sale->cashier_name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $sale->item_id }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $sale->quantity_purchased }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">₦{{ number_format($sale->item_unit_price, 2) }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">₦{{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $sale->mode_payment }}</td>
                                    @php
                                        $customerDisplay = 'Walk-in';
                                        if (is_object($sale->customer)) {
                                            $customerDisplay = $sale->customer->name ?? ($sale->customer->customer ?? 'Walk-in');
                                        } elseif (is_string($sale->customer) && trim($sale->customer) !== '') {
                                            $customerDisplay = $sale->customer;
                                        }
                                    @endphp
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $customerDisplay }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-sm text-slate-500 text-center">No sales records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawSales = @json($sales);
        
        if (rawSales && rawSales.length > 0) {
            // Group by Payment Mode
            const modeGroups = {};
            // Group by Store Name
            const storeGroups = {};

            rawSales.forEach(sale => {
                const mode = sale.mode_payment || 'Unknown';
                const amount = parseFloat(sale.total_amount) || 0;
                
                // Fetch store name
                let storeName = 'Main Store';
                if (sale.store && sale.store.name) {
                    storeName = sale.store.name;
                } else if (sale.store_id) {
                    storeName = 'Store #' + sale.store_id;
                }

                modeGroups[mode] = (modeGroups[mode] || 0) + amount;
                storeGroups[storeName] = (storeGroups[storeName] || 0) + amount;
            });

            // 1. Payment Mode Share Chart (Pie)
            const ctxMode = document.getElementById('paymentModeChart').getContext('2d');
            new Chart(ctxMode, {
                type: 'pie',
                data: {
                    labels: Object.keys(modeGroups),
                    datasets: [{
                        data: Object.values(modeGroups),
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',  // Emerald for Cash
                            'rgba(99, 102, 241, 0.8)',  // Indigo for Card
                            'rgba(245, 158, 11, 0.8)',  // Amber for Transfer
                            'rgba(156, 163, 175, 0.8)'  // Grey for Other
                        ],
                        borderColor: [
                            'rgb(16, 185, 129)',
                            'rgb(99, 102, 241)',
                            'rgb(245, 158, 11)',
                            'rgb(156, 163, 175)'
                        ],
                        borderWidth: 1.5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                font: { size: 11, weight: '500' },
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ₦' + new Intl.NumberFormat().format(context.raw);
                                }
                            }
                        }
                    }
                }
            });

            // 2. Revenue by Store Chart (Bar)
            const ctxStore = document.getElementById('storeSalesChart').getContext('2d');
            new Chart(ctxStore, {
                type: 'bar',
                data: {
                    labels: Object.keys(storeGroups),
                    datasets: [{
                        label: 'Revenue',
                        data: Object.values(storeGroups),
                        backgroundColor: 'rgba(99, 102, 241, 0.15)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₦' + new Intl.NumberFormat().format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    return '₦' + new Intl.NumberFormat().format(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
