@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Auditor Verification Portal') }}
        </h2>
    @endsection

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-8">
        <!-- Message Flashes -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Columns: Audit Work queues -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Pending Void Requests -->
                <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-white">
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Pending Void Sales Requests</h3>
                        <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Requires auditor review before restoring stock</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($pendingVoids as $receiptNumber => $saleItems)
                            @php
                                $firstItem = $saleItems->first();
                                $totalAmount = $saleItems->first()->total_amount;
                            @endphp
                            <div class="p-6 hover:bg-slate-50/50 transition-colors flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-slate-800">{{ $receiptNumber }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                            {{ $firstItem->store ? $firstItem->store->name : 'Main Counter' }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Cashier: <span class="text-slate-700 font-semibold">{{ $firstItem->cashier_name }}</span> |
                                        Customer: <span class="text-slate-700 font-semibold">{{ $firstItem->customer }}</span>
                                    </p>
                                    <div class="text-xs text-slate-400 font-medium">
                                        Sold Items:
                                        @foreach($saleItems as $item)
                                            <span class="bg-slate-50 border border-slate-100 px-1.5 py-0.5 rounded text-slate-600 font-semibold">
                                                {{ $item->product->name ?? 'Item' }} (x{{ $item->quantity_purchased }})
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 self-end md:self-center">
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Grand Total</p>
                                        <p class="text-base font-extrabold text-indigo-600">₦{{ number_format($totalAmount, 2) }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <!-- Approve Action -->
                                        <form action="{{ route('auditor.void.approve', $receiptNumber) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                Approve Void
                                            </button>
                                        </form>
                                        <!-- Reject Action -->
                                        <form action="{{ route('auditor.void.reject', $receiptNumber) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition border border-slate-200">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-500 font-medium">
                                No sales void requests are pending.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pending Damaged/Expired Requests -->
                <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-white">
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Pending Stock Write-Off Requests</h3>
                        <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Reports of damaged or expired items awaiting approval</p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($pendingWriteOffs as $writeOff)
                            <div class="p-6 hover:bg-slate-50/50 transition-colors flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-bold text-slate-800">{{ $writeOff->product->name ?? 'Unknown Item' }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold uppercase {{ $writeOff->type === 'expired' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                            {{ $writeOff->type }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-slate-500 font-medium">
                                        Location: <span class="text-slate-700 font-semibold">{{ $writeOff->store_id ? $writeOff->store->name : 'Supervisor Private Stock' }}</span>
                                    </p>
                                    <p class="text-xs text-slate-400 font-medium">
                                        Reported by Supervisor: <span class="text-slate-700 font-semibold">{{ $writeOff->user->name ?? 'System' }}</span>
                                        on {{ $writeOff->created_at->format('d M, Y h:i A') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-4 self-end md:self-center">
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Quantity</p>
                                        <p class="text-lg font-bold text-slate-800">{{ $writeOff->quantity }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <!-- Approve Action -->
                                        <form action="{{ route('auditor.writeoff.approve', $writeOff->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                Approve Write-Off
                                            </button>
                                        </form>
                                        <!-- Reject Action -->
                                        <form action="{{ route('auditor.writeoff.reject', $writeOff->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition border border-slate-200">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-slate-500 font-medium">
                                No write-off requests are pending.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right 1 Column: System Audit Trail -->
            <div class="space-y-8">
                <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-white">
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">Recent Activity Log</h3>
                        <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Live System Audit Trail</p>
                    </div>

                    <div class="p-6 overflow-y-auto max-h-[600px] space-y-4">
                        @forelse($logs as $log)
                            <div class="border-b border-slate-100 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ in_array($log->action, ['login', 'sales_completion']) ? 'bg-emerald-50 text-emerald-700' : (in_array($log->action, ['blocked_login', 'duplicate_session_attempt']) ? 'bg-rose-50 text-rose-700' : 'bg-indigo-50 text-indigo-700') }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-700 font-semibold mt-1">{{ $log->description }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">By: {{ $log->user ? $log->user->name : 'Guest User' }} (IP: {{ $log->ip_address }})</p>
                            </div>
                        @empty
                            <div class="text-center text-slate-400 py-6 text-sm">
                                No activity logs recorded.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
