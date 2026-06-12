@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Today's Sales -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-start transition-all hover:shadow-md">
                    <div class="p-2.5 rounded-lg bg-indigo-50 text-indigo-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Today's Sales</p>
                        <p class="text-2xl font-bold text-slate-800">₦{{ number_format($todayales, 2) }}</p>
                    </div>
                </div>

                <!-- Weekly Sales -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-start transition-all hover:shadow-md">
                    <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Weekly Sales</p>
                        <p class="text-2xl font-bold text-slate-800">₦{{ number_format($weeklySales, 2) }}</p>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-start transition-all hover:shadow-md">
                    <div class="p-2.5 rounded-lg bg-violet-50 text-violet-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Orders</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalOrders) }}</p>
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-start transition-all hover:shadow-md">
                    <div class="p-2.5 rounded-lg bg-rose-50 text-rose-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Low Stock Items</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($lowStockProducts) }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Sales Table -->
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-base font-semibold text-slate-800">Recent Transactions</h3>
                    <a href="{{ route('admin.sales.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Receipt No</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($recentSales as $sale)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><a href="{{ route('admin.sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-900">{{ $sale->receipt_number }}</a></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $sale->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">₦{{ number_format($sale->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($sale->status == 'completed')
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100">Completed</span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-rose-50 text-rose-700 border border-rose-100">Voided</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">No recent sales.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
@endsection
