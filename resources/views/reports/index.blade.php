@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Analytics & Reports') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Daily Sales Card -->
                <div class="bg-white overflow-hidden shadow-sm border border-slate-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Daily Sales</h3>
                    </div>
                    <p class="text-slate-500 text-sm mb-6">View a detailed breakdown of sales for a specific date.</p>
                    <a href="{{ route('admin.reports.daily_sales') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        View Report
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Category Analysis Card -->
                <div class="bg-white overflow-hidden shadow-sm border border-slate-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Category Analysis</h3>
                    </div>
                    <p class="text-slate-500 text-sm mb-6">Compare performance across different product categories.</p>
                    <a href="{{ route('admin.reports.category_sales') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        View Report
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Item Performance Card -->
                <div class="bg-white overflow-hidden shadow-sm border border-slate-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Item Analysis</h3>
                    </div>
                    <p class="text-slate-500 text-sm mb-6">Analyze individual product performance and trends.</p>
                    <a href="{{ route('admin.reports.item_analysis') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        View Report
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
