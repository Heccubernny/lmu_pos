@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Recent Sales') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900 border-b border-slate-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Sales History</h3>
                        @if(auth()->user()->isITAdmin() || auth()->user()->isHead())
                            <a href="{{ route('admin.sales.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-semibold flex items-center shrink-0">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Sale (POS)
                            </a>
                        @endif
                    </div>

                    <!-- Search and Filters Form -->
                    <div class="mb-8 bg-slate-50 p-5 rounded-xl border border-slate-200">
                        <form action="{{ auth()->user()->isAuditor() ? route('auditor.sales.index') : (auth()->user()->isAccountant() ? route('accountant.sales.index') : route('admin.sales.index')) }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Search bar -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Search</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Scan receipt barcode or search customer/staff..." class="block w-full pl-9 pr-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 bg-white transition-all font-medium text-slate-800">
                                    </div>
                                </div>

                                <!-- Store Filter -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Store</label>
                                    <select name="store_id" class="block w-full py-2.5 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                        <option value="">All Stores</option>
                                        @foreach($stores as $st)
                                            <option value="{{ $st->id }}" {{ isset($storeId) && $storeId == $st->id ? 'selected' : '' }}>{{ $st->name ?? ($st->host ?? 'Store '.$st->id) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category Filter -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Product Category</label>
                                    <select name="category" class="block w-full py-2.5 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->name }}" {{ isset($category) && $category == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Cashier/Staff Filter -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cashier / Staff</label>
                                    <select name="staff_id" class="block w-full py-2.5 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                        <option value="">All Cashiers</option>
                                        @foreach($cashiers as $cashier)
                                            <option value="{{ $cashier->staff_id }}" {{ isset($staffId) && $staffId == $cashier->staff_id ? 'selected' : '' }}>{{ $cashier->name }} ({{ $cashier->staff_id }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Payment Mode Filter -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Payment Mode</label>
                                    <select name="mode_payment" class="block w-full py-2.5 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                        <option value="">All Payments</option>
                                        <option value="Cash" {{ isset($modePayment) && $modePayment == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Card" {{ isset($modePayment) && $modePayment == 'Card' ? 'selected' : '' }}>Card</option>
                                        <option value="Transfer" {{ isset($modePayment) && $modePayment == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                                    <select name="status" class="block w-full py-2.5 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                        <option value="all" {{ isset($status) && $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                                        <option value="completed" {{ !isset($status) || $status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="voided" {{ isset($status) && $status == 'voided' ? 'selected' : '' }}>Voided</option>
                                    </select>
                                </div>

                                <!-- Period Preset Filter -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Period Preset</label>
                                    <select name="period" id="period-select" class="block w-full py-2.5 px-3 text-sm border border-slate-300 bg-white rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all font-medium text-slate-700">
                                        <option value="all" {{ !isset($period) || $period == 'all' ? 'selected' : '' }}>All Time</option>
                                        <option value="today" {{ isset($period) && $period == 'today' ? 'selected' : '' }}>Today</option>
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
                                @if(!empty($search) || !empty($storeId) || !empty($staffId) || !empty($category) || !empty($modePayment) || (isset($status) && $status !== 'all') || (isset($period) && $period !== 'all' && !empty($period)) || !empty($startDate) || !empty($endDate) || !empty($startTime) || !empty($endTime))
                                    <a href="{{ auth()->user()->isAuditor() ? route('auditor.sales.index') : (auth()->user()->isAccountant() ? route('accountant.sales.index') : route('admin.sales.index')) }}" class="px-5 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition text-xs font-bold flex items-center">
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

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 mb-6 rounded-lg text-sm flex items-center">
                            <svg class="h-5 w-5 mr-3 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Receipt No</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Store</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cashier</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Amount</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Payment Mode</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($sales as $sale)
                                    <tr class="hover:bg-slate-50 transition-colors {{ $sale->status == 'voided' ? 'bg-rose-50/50' : '' }}">
                                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900">{{ $sale->receipt_number }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 border border-slate-200">
                                                {{ $sale->store->name ?? ($sale->store->host ?? 'Main Store') }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $sale->cashier_name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-700">₦{{ number_format($sale->total_amount, 2) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $sale->mode_payment }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @if($sale->status == 'completed')
                                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">Completed</span>
                                            @elseif($sale->status == 'voided')
                                                <span class="inline-flex items-center rounded-md bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 border border-rose-100">Voided</span>
                                            @else
                                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200">{{ ucfirst($sale->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                            <a href="{{ auth()->user()->isAuditor() ? route('auditor.sales.show', $sale) : (auth()->user()->isAccountant() ? route('accountant.sales.show', $sale) : route('admin.sales.show', $sale)) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 transition-colors">View Receipt</a>
                                            @if($sale->status != 'voided' && (auth()->user()->isITAdmin() || auth()->user()->isHead()))
                                                <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" class="inline swal-void-form" data-title="Void This Sale?" data-text="This will revert inventory for receipt {{ $sale->receipt_number }}. This cannot be undone.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="text-rose-600 hover:text-rose-900 transition-colors swal-void-btn">Void</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 whitespace-nowrap text-sm text-slate-500 text-center">No sales found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $sales->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.swal-void-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = btn.closest('.swal-void-form');
        Swal.fire({
            title: form.dataset.title || 'Void this sale?',
            text: form.dataset.text || 'This will revert inventory and cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Void Sale',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
