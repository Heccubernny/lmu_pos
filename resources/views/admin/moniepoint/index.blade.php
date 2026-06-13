@extends('layouts.app')

@section('content')
    @section('header')
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 tracking-tight">
                    {{ __('Moniepoint POS Transactions') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Monitor, verify, and audit Moniepoint card payments and bank transfers in real-time.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="inline-flex items-center bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg border border-emerald-100 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-ping"></span>
                    Live Webhook Active
                </div>
            </div>
        </div>
    @endsection

    <div class="py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- Filters Card -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <form method="GET" action="{{ route('admin.moniepoint.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all"
                            placeholder="Search Ref, Customer, Terminal...">
                    </div>

                    <!-- Store Filter -->
                    <div>
                        <select name="store_id"
                            class="block w-full py-2 px-3 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all">
                            <option value="">All Stores/Halls</option>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}" {{ request('store_id') == $st->id ? 'selected' : '' }}>
                                    {{ $st->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Method Filter -->
                    <div>
                        <select name="payment_method"
                            class="block w-full py-2 px-3 border border-slate-200 bg-slate-50 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 transition-all">
                            <option value="">All Methods (Card/Transfer)</option>
                            <option value="Card" {{ request('payment_method') == 'Card' ? 'selected' : '' }}>Card Payments</option>
                            <option value="Transfer" {{ request('payment_method') == 'Transfer' ? 'selected' : '' }}>Bank Transfers</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex space-x-2">
                        <button type="submit"
                            class="flex-1 py-2 px-4 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-sm">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'store_id', 'payment_method']))
                            <a href="{{ route('admin.moniepoint.index') }}"
                                class="py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-bold text-center transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Transactions List -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Store / Cashier</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Method & Details</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Amount</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Checkout Link</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-mono text-sm font-bold text-slate-800">{{ $tx->reference }}</div>
                                        @if($tx->terminal_id)
                                            <div class="text-2xs text-slate-400 mt-0.5">Terminal: {{ $tx->terminal_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $tx->created_at->format('M d, Y') }}
                                        <div class="text-2xs text-slate-400 mt-0.5">{{ $tx->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="font-semibold text-slate-700">{{ $tx->store->name ?? 'Main Warehouse' }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">Cashier: {{ $tx->cashier->name ?? 'System' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($tx->payment_method === 'Transfer')
                                            <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-2xs font-bold border border-blue-100 uppercase">Transfer</span>
                                            @if($tx->bank_name || $tx->account_number)
                                                <div class="text-xs text-slate-600 mt-1 font-medium">{{ $tx->bank_name ?? 'MFB' }} *{{ substr($tx->account_number, -4) }}</div>
                                            @endif
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-2xs font-bold border border-indigo-100 uppercase">Card</span>
                                            @if($tx->card_brand || $tx->card_last_4)
                                                <div class="text-xs text-slate-600 mt-1 font-medium">{{ $tx->card_brand ?? 'Visa' }} ({{ $tx->card_last_4 ?? '****' }})</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-extrabold text-slate-800">
                                        ₦{{ number_format($tx->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @if($tx->status === 'successful')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Successful
                                            </span>
                                        @elseif($tx->status === 'failed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                                Failed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @php
                                            $linkedSale = \App\Models\Sale::where('recipt_number', $tx->recipt_number)->first();
                                        @endphp

                                        @if($linkedSale)
                                            <a href="{{ route('admin.sales.show', $linkedSale) }}"
                                                class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-900 border border-slate-200 rounded-lg px-2.5 py-1 hover:bg-slate-50 transition">
                                                View Receipt
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 italic font-medium">Unlinked / Abandoned</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <span class="font-medium">No Moniepoint transactions found.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
