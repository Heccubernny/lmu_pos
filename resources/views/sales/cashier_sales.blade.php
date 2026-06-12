@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('My Sales & Analytics') }}
        </h2>
    @endsection

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Periodic Filtering Tabs -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Select Reporting Period</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Filter your sales statistics dynamically</p>
                </div>
                <div class="flex bg-slate-100 p-1 rounded-lg self-stretch sm:self-auto">
                    <a href="{{ route('cashier.sales.history', ['period' => 'daily']) }}" 
                       class="flex-1 sm:flex-none text-center px-4 py-2 text-xs font-bold rounded-md transition-all {{ $period === 'daily' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
                        Daily (Today)
                    </a>
                    <a href="{{ route('cashier.sales.history', ['period' => 'weekly']) }}" 
                       class="flex-1 sm:flex-none text-center px-4 py-2 text-xs font-bold rounded-md transition-all {{ $period === 'weekly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
                        Weekly
                    </a>
                    <a href="{{ route('cashier.sales.history', ['period' => 'monthly']) }}" 
                       class="flex-1 sm:flex-none text-center px-4 py-2 text-xs font-bold rounded-md transition-all {{ $period === 'monthly' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-800' }}">
                        Monthly
                    </a>
                </div>
            </div>

            <!-- Analytics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Total Sales Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sales</p>
                        <h4 class="text-2xl font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                            ₦{{ number_format($totalSales, 2) }}
                        </h4>
                    </div>
                    <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Total Transactions Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transactions</p>
                        <h4 class="text-2xl font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                            {{ $totalTransactions }}
                        </h4>
                    </div>
                    <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Average Order Value Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex items-center justify-between group">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Avg Order Value</p>
                        <h4 class="text-2xl font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                            ₦{{ number_format($avgOrderValue, 2) }}
                        </h4>
                    </div>
                    <div class="bg-indigo-50 text-indigo-600 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Transaction Table -->
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 rounded-2xl">
                <div class="p-6 border-b border-slate-200 bg-white">
                    <h3 class="text-base font-semibold text-slate-800">My Sales History ({{ ucfirst($period) }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Receipt No</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date & Time</th>
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
                                    <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-semibold text-slate-900">{{ $sale->receipt_number }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $sale->created_at->format('d M, Y h:i A') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700 font-medium">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-800">₦{{ number_format($sale->total_amount, 2) }}</td>
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
                                        <a href="{{ route('cashier.sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 transition-colors font-bold">View Receipt</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 whitespace-nowrap text-sm text-slate-500 text-center">No sales recorded for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($sales->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $sales->appends(request()->input())->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
