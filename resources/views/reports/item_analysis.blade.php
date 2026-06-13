@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Item Performance Analysis') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 rounded-xl p-6">
                <!-- Filters Panel -->
                <div class="mb-8 bg-slate-50 p-5 rounded-xl border border-slate-200">
                    <form action="{{ route('admin.reports.item_analysis') }}" method="GET" class="space-y-4">
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
                                    <option value="today" {{ isset($period) && $period == 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="yesterday" {{ isset($period) && $period == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                    <option value="this_week" {{ isset($period) && $period == 'this_week' ? 'selected' : '' }}>This Week</option>
                                    <option value="this_month" {{ !isset($period) || $period == 'this_month' ? 'selected' : '' }}>This Month</option>
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
                            @if(!empty($storeId) || !empty($staffId) || !empty($category) || !empty($modePayment) || (isset($status) && $status !== 'completed') || (isset($period) && $period !== 'this_month') || !empty($startDate) || !empty($endDate) || !empty($startTime) || !empty($endTime))
                                <a href="{{ route('admin.reports.item_analysis') }}" class="px-5 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition text-xs font-bold flex items-center">
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

                <!-- Charts Grid -->
                @if($itemAnalysis->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 mt-6">
                        <!-- Top Products by Revenue -->
                        <div class="bg-slate-50 p-5 border border-slate-200 rounded-2xl">
                            <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 inline-block"></span>
                                Top Products by Revenue (₦)
                            </h4>
                            <div class="relative h-64 flex items-center justify-center">
                                <canvas id="productRevenueChart"></canvas>
                            </div>
                        </div>

                        <!-- Top Products by Volume -->
                        <div class="bg-slate-50 p-5 border border-slate-200 rounded-2xl">
                            <h4 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 inline-block"></span>
                                Top Products by Volume (Quantity Sold)
                            </h4>
                            <div class="relative h-64 flex items-center justify-center">
                                <canvas id="productVolumeChart"></canvas>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Item Name</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Qty Sold</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($itemAnalysis as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $item->product->name ?? $item->item_id }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ $item->product->category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">{{ number_format($item->total_qty) }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">₦{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-sm text-slate-500 text-center">No item sales data found for the selected period.</td>
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
        // Parse database results
        const rawItems = @json($itemAnalysis);
        
        if (rawItems && rawItems.length > 0) {
            // Map records
            const items = rawItems.map(item => ({
                name: item.product ? item.product.name : ('Item #' + item.item_id),
                revenue: parseFloat(item.total_amount),
                qty: parseInt(item.total_qty)
            }));

            // Sort by revenue for revenue chart (take top 7)
            const topRevenue = [...items].sort((a, b) => b.revenue - a.revenue).slice(0, 7);
            // Sort by qty for volume chart (take top 7)
            const topVolume = [...items].sort((a, b) => b.qty - a.qty).slice(0, 7);

            // 1. Revenue Horizontal Bar Chart
            const ctxRev = document.getElementById('productRevenueChart').getContext('2d');
            new Chart(ctxRev, {
                type: 'bar',
                data: {
                    labels: topRevenue.map(item => item.name),
                    datasets: [{
                        label: 'Revenue (₦)',
                        data: topRevenue.map(item => item.revenue),
                        backgroundColor: 'rgba(99, 102, 241, 0.75)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
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
                        x: {
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { font: { size: 9 } }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 9, weight: '500' } }
                        }
                    }
                }
            });

            // 2. Volume Vertical Bar Chart
            const ctxVol = document.getElementById('productVolumeChart').getContext('2d');
            new Chart(ctxVol, {
                type: 'bar',
                data: {
                    labels: topVolume.map(item => item.name),
                    datasets: [{
                        label: 'Quantity Sold',
                        data: topVolume.map(item => item.qty),
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { font: { size: 9 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 9, weight: '500' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
