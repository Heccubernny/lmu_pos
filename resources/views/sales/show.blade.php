@extends('layouts.app')

@section('content')
    @section('header')
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Sale Receipt') }} #{{ $sale->receipt_number }}
            </h2>
            <button onclick="window.print()"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm font-semibold flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Print Receipt
            </button>
        </div>
    @endsection

    <div class="py-6 md:py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            <!-- Left Column: Receipt Slip Preview -->
            <div class="w-full lg:w-[500px] flex-shrink-0 mx-auto">
                <div class="bg-white overflow-hidden shadow-md border border-slate-200 rounded-2xl" id="receipt-area">
                    <div class="p-6 md:p-8 text-slate-800">
                        <!-- Receipt Header -->
                        <div class="text-center mb-8 border-b border-slate-100 pb-6">
                            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">T-Conn POS</h1>
                            <p class="text-sm text-slate-500 mt-1">Lagos, Nigeria</p>
                            <p class="text-sm text-slate-500">Email: info@t-conn.com</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-b border-slate-100 pb-6 mb-6">
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Billed To</p>
                                <p class="text-base text-slate-800 font-bold mt-1">
                                    {{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                                @if($sale->customer && $sale->customer->phone != 'N/A')
                                    <p class="text-sm text-slate-500 mt-0.5">{{ $sale->customer->phone }}</p>
                                @endif
                                <p class="text-sm text-slate-500">Cashier: <span
                                        class="font-medium text-slate-700">{{ $sale->cashier_name }}</span></p>
                                <p class="text-sm text-slate-500">Store: <span
                                        class="font-medium text-slate-700">{{ $sale->store->name ?? 'Main Store' }}</span>
                                </p>
                                @if($sale->status == 'voided')
                                    <p
                                        class="text-rose-500 font-extrabold mt-2 text-sm uppercase tracking-widest border-2 border-rose-500 inline-block px-2 rounded">
                                        VOIDED</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Receipt Info</p>
                                <p class="text-sm text-slate-500 mt-1">No: <span class="font-bold text-slate-800">{{ $sale->receipt_number }}</span></p>
                                <p class="text-sm text-slate-500">UID: <span class="font-mono text-xs text-slate-600">{{ $posReceipt->receipt_uid ?? 'N/A' }}</span></p>
                                <p class="text-sm text-slate-500">Barcode ID: <span class="font-mono text-xs text-slate-600">{{ $posReceipt->barcode_identifier ?? 'N/A' }}</span></p>
                                <p class="text-sm text-slate-500">Txn ID: <span class="font-bold text-slate-800">{{ $posReceipt->id ?? $sale->id }}</span></p>
                                <p class="text-sm text-slate-500">Date: <span class="font-medium text-slate-700">{{ $sale->created_at->format('d M, Y h:i A') }}</span></p>
                                <p class="text-sm text-slate-500">Payment: <span class="font-medium text-slate-700">{{ $sale->mode_payment }}</span></p>
                                @if(!empty($posReceipt->terminal_id))
                                    <p class="text-sm text-slate-500">Terminal ID: <span class="font-medium text-slate-700">{{ $posReceipt->terminal_id }}</span></p>
                                @endif
                                @if(!empty($posReceipt->moniepoint_ref))
                                    <p class="text-sm text-slate-500">Moniepoint Ref: <span class="font-medium text-slate-700">{{ $posReceipt->moniepoint_ref }}</span></p>
                                @endif
                            </div>
                        </div>

                        <!-- Items -->
                        <table class="w-full text-left table-auto mb-8">
                            <thead>
                                <tr
                                    class="border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="py-2.5 w-12 text-center">S/N</th>
                                    <th class="py-2.5">Item</th>
                                    <th class="py-2.5 text-center">Qty</th>
                                    <th class="py-2.5 text-right">Price</th>
                                    <th class="py-2.5 text-right font-semibold">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 divide-y divide-slate-100">
                                @foreach($sale->items as $item)
                                    <tr>
                                        <td class="py-3 text-center text-sm font-medium text-slate-500">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="py-3">
                                            <p class="text-sm font-semibold text-slate-800">
                                                {{ $item->product->name ?? 'Unknown Item' }}</p>
                                        </td>
                                        <td class="py-3 text-center text-sm font-medium text-slate-600">
                                            {{ $item->quantity_purchased }}</td>
                                        <td class="py-3 text-right text-sm text-slate-500">
                                            ₦{{ number_format($item->item_unit_price, 2) }}</td>
                                        <td class="py-3 text-right text-sm font-bold text-slate-800">
                                            ₦{{ number_format($item->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Totals -->
                        <div class="w-full flex justify-end">
                            <div class="w-full sm:w-2/3">
                                @php
                                    $subtotal = $sale->total_amount;
                                    $discountAmount = 0;
                                    if ($sale->discount_percent > 0) {
                                        $subtotal = collect($sale->items)->sum('total_amount');
                                        $discountAmount = $subtotal * ($sale->discount_percent / 100);
                                    }
                                @endphp

                                @if($sale->discount_percent > 0)
                                    <div class="flex justify-between py-1.5 text-sm text-slate-500">
                                        <span>Subtotal:</span>
                                        <span>₦{{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between py-1.5 text-sm text-slate-500">
                                        <span>Discount ({{ $sale->discount_percent }}%):</span>
                                        <span class="text-rose-500">-₦{{ number_format($discountAmount, 2) }}</span>
                                    </div>
                                @endif
                                <div
                                    class="flex justify-between py-3 border-t-2 border-slate-950 text-base font-bold text-slate-900">
                                    <span>Grand Total:</span>
                                    <span class="text-indigo-600">₦{{ number_format($sale->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between py-1.5 text-sm text-slate-500">
                                    <span>Amount Paid:</span>
                                    <span
                                        class="font-semibold text-slate-800">₦{{ number_format($sale->amount_paid, 2) }}</span>
                                </div>
                                <!-- Calculate change if amount paid > total amount -->
                                @if($sale->amount_paid > $sale->total_amount)
                                    <div class="flex justify-between py-1.5 text-sm text-slate-500">
                                        <span>Change:</span>
                                        <span
                                            class="font-semibold text-emerald-600">₦{{ number_format($sale->amount_paid - $sale->total_amount, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Receipt Footer & Security -->
                        <div class="mt-12 border-t border-slate-200 pt-6 text-center text-sm text-slate-600 space-y-4">
                            <div class="py-2 border-y border-dashed border-slate-200 max-w-xs mx-auto">
                                <p class="font-mono text-xs uppercase tracking-wider text-slate-400">security pass</p>
                                <p class="font-extrabold text-slate-700 mt-0.5">sign me/punch me</p>
                            </div>

                            <div>
                                <p class="text-slate-700 font-semibold text-base">Thank you for coming. Please call us for
                                    any enquires.</p>
                                <p class="text-xs text-slate-400 mt-2 font-medium">Powered by <a
                                        href="https://www.e-points.org" target="_blank"
                                        class="underline hover:text-indigo-600">www.e-points.org</a></p>
                            </div>

                            <!-- Barcode Section -->
                            <div class="flex flex-col items-center pt-2">
                                <svg id="barcode" class="max-w-[220px]"></svg>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Control Panel & Navigation (Hidden when printing) -->
            <div class="flex-1 w-full space-y-6 print:hidden">
                <!-- Transaction Status Card -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center space-x-4 mb-5">
                        <div class="bg-emerald-100 text-emerald-600 p-3 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Checkout Complete</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Receipt #{{ $sale->receipt_number }}</p>
                        </div>
                    </div>

                    <!-- Details Summary List -->
                    <div class="border-t border-slate-100 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Customer:</span>
                            <span
                                class="text-slate-800 font-semibold">{{ $sale->customer->name ?? 'Walk-in Customer' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Payment Mode:</span>
                            <span class="text-slate-800 font-semibold">{{ $sale->mode_payment }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Cashier:</span>
                            <span class="text-slate-800 font-semibold">{{ $sale->cashier_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Store:</span>
                            <span class="text-slate-800 font-semibold">{{ $sale->store->name ?? 'Main Store' }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t border-slate-100 pt-3">
                            <span class="text-slate-600 font-bold">Total Paid:</span>
                            <span
                                class="text-indigo-600 font-extrabold text-base">₦{{ number_format($sale->amount_paid, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Panel -->
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Quick Actions</h4>

                    <button onclick="window.print()"
                        class="w-full flex items-center justify-center py-3.5 px-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Print Receipt
                    </button>

                    <div class="grid grid-cols-2 gap-3">
                        @if(auth()->user()->isSalesRep() || auth()->user()->isITAdmin() || auth()->user()->isHead())
                            <a href="{{ auth()->user()->isSalesRep() ? route('cashier.sales.create') : route('admin.sales.create') }}"
                                class="flex items-center justify-center py-3.5 px-4 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-100 text-center transition text-sm">
                                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                New Sale
                            </a>
                        @endif
                        @if(auth()->check() && auth()->user()->isSalesRep())
                            <a href="{{ route('cashier.sales.history') }}"
                                class="flex items-center justify-center py-3.5 px-4 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 text-center transition text-sm">
                                <svg class="w-4.5 h-4.5 mr-1.5 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                History
                            </a>
                        @else
                            <a href="{{ auth()->user()->isAuditor() ? route('auditor.sales.index') : (auth()->user()->isAccountant() ? route('accountant.sales.index') : route('admin.sales.index')) }}"
                                class="flex items-center justify-center py-3.5 px-4 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-50 text-center transition text-sm">
                                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                All Sales
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <style>
                @media print {
                    /* Hide headers, sidebars, other page wrappers entirely from layout */
                    aside,
                    header,
                    .print\:hidden {
                        display: none !important;
                    }

                    /* Override height and overflow limits on html, body and outer wrappers */
                    html, body, .h-screen, .flex-1, .flex-col, .overflow-hidden {
                        height: auto !important;
                        min-height: 0 !important;
                        overflow: visible !important;
                        background: #white !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    main {
                        padding: 0 !important;
                        margin: 0 !important;
                        background: transparent !important;
                        overflow: visible !important;
                    }

                    /* Make receipt container a full-width flat element for printer margins */
                    #receipt-area {
                        position: absolute !important;
                        left: 0 !important;
                        top: 0 !important;
                        width: 100% !important;
                        max-width: 100% !important;
                        box-shadow: none !important;
                        border: none !important;
                        border-radius: 0 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        page-break-inside: avoid !important;
                    }

                    #receipt-area * {
                        visibility: visible !important;
                    }

                    /* Make vertical spacing more compact for print */
                    #receipt-area .p-6,
                    #receipt-area .p-8 {
                        padding: 10px !important;
                    }

                    #receipt-area .mb-8 {
                        margin-bottom: 10px !important;
                    }

                    #receipt-area .mb-6 {
                        margin-bottom: 6px !important;
                    }

                    #receipt-area .pb-6 {
                        padding-bottom: 6px !important;
                    }

                    #receipt-area .mt-12 {
                        margin-top: 12px !important;
                    }

                    #receipt-area table th,
                    #receipt-area table td {
                        padding-top: 4px !important;
                        padding-bottom: 4px !important;
                    }

                    /* Adjust margin of printed document page */
                    @page {
                        margin: 0.25in !important;
                    }
                }
            </style>

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if (typeof JsBarcode !== 'undefined') {
                            JsBarcode("#barcode", "{{ $posReceipt->barcode_identifier ?? $sale->receipt_number }}", {
                                format: "CODE128",
                                width: 1.8,
                                height: 45,
                                displayValue: true,
                                fontSize: 11,
                                fontOptions: "bold",
                                font: "monospace",
                                margin: 0,
                                lineColor: "#1e293b"
                            });
                        }

                        // Auto-print if redirecting from successful sale completion
                        @if(session('success'))
                            setTimeout(function() {
                                window.print();
                            }, 500);
                        @endif
                    });
                </script>
            @endpush
@endsection