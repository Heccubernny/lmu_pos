@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Accountant Financial & Reconciliation Portal') }}
        </h2>
    @endsection

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-8">
        
        <!-- Filter Card -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Reconciliation & Financial Filters</h3>
            <form method="GET" action="{{ route('accountant.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Period -->
                <div>
                    <label for="period" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Period</label>
                    <select name="period" id="period" onchange="toggleCustomDates(this.value)"
                        class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-xs py-2.5 transition shadow-sm">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_30_days" {{ $period === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                    </select>
                </div>

                <!-- Store / Hall -->
                <div>
                    <label for="store_id" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Scope to Hostel Hall</label>
                    <select name="store_id" id="store_id"
                        class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-xs py-2.5 transition shadow-sm">
                        <option value="">-- All Hostel Halls --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Supervisor -->
                <div>
                    <label for="supervisor_id" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Scope to Supervisor private stock</label>
                    <select name="supervisor_id" id="supervisor_id"
                        class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-xs py-2.5 transition shadow-sm">
                        <option value="">-- All Private Stocks --</option>
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->person_id }}" {{ $supervisorId == $supervisor->person_id ? 'selected' : '' }}>{{ $supervisor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Custom Start Date -->
                <div class="custom-date-field" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                    <label for="start_date" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-xs py-2 transition shadow-sm">
                </div>

                <!-- Custom End Date -->
                <div class="custom-date-field" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                    <label for="end_date" class="block text-xs font-bold text-slate-500 uppercase mb-1.5">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-xs py-2 transition shadow-sm">
                </div>

                <!-- Filter Button -->
                <div class="lg:col-span-5 flex justify-end mt-2">
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-100 transition transform active:scale-95 text-xs">
                        Apply Scoped Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Metric Panels -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Gross Sales -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Gross Sales (Scoped)</h4>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">₦{{ number_format($grossSales, 2) }}</p>
                </div>
                <div class="bg-indigo-100 text-indigo-600 p-3.5 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Units Sold -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Units Sold (Scoped)</h4>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ number_format($unitsSold) }}</p>
                </div>
                <div class="bg-sky-100 text-sky-600 p-3.5 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>

            <!-- POS Matched Count -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">POS Matched Count</h4>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ number_format($posMatchedCount) }}</p>
                </div>
                <div class="bg-emerald-100 text-emerald-600 p-3.5 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Inventory Reconciliation Table -->
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Real-Time Stock Account Reconciliation</h3>
                <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Stock tracking showing: Opening + Received - Sold - Damaged = Closing Stock</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Product</th>
                            <th class="py-3.5 px-6 text-center">Opening Stock</th>
                            @if(!$storeId)
                                <th class="py-3.5 px-6 text-center text-emerald-600">Stock Received</th>
                            @endif
                            @if(!$supervisorId)
                                <th class="py-3.5 px-6 text-center text-sky-600">Stock Allocated</th>
                            @endif
                            <th class="py-3.5 px-6 text-center text-rose-600">Stock Damaged</th>
                            <th class="py-3.5 px-6 text-center text-indigo-600">Stock Sold</th>
                            <th class="py-3.5 px-6 text-center font-extrabold">Closing Stock</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 divide-y divide-slate-100 font-medium text-sm">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    {{ $row['product_name'] }}
                                </td>
                                <td class="py-4 px-6 text-center text-slate-500">
                                    {{ $row['opening_stock'] }}
                                </td>
                                @if(!$storeId)
                                    <td class="py-4 px-6 text-center text-emerald-700 bg-emerald-50/20 font-bold">
                                        +{{ $row['received_stock'] }}
                                    </td>
                                @endif
                                @if(!$supervisorId)
                                    <td class="py-4 px-6 text-center text-sky-700 bg-sky-50/20 font-bold">
                                        {{ $storeId ? '+' : '-' }}{{ $row['allocated_stock'] }}
                                    </td>
                                @endif
                                <td class="py-4 px-6 text-center text-rose-700 bg-rose-50/20 font-bold">
                                    -{{ $row['damaged_stock'] }}
                                </td>
                                <td class="py-4 px-6 text-center text-indigo-700 bg-indigo-50/20 font-bold">
                                    -{{ $row['sold_stock'] }}
                                </td>
                                <td class="py-4 px-6 text-center font-extrabold text-slate-900 bg-slate-50/40">
                                    {{ $row['closing_stock'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-500">
                                    No stock flow activity logged in this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleCustomDates(value) {
            const fields = document.querySelectorAll('.custom-date-field');
            fields.forEach(el => {
                el.style.display = value === 'custom' ? 'block' : 'none';
            });
        }
    </script>
@endsection
