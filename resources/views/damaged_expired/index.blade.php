@extends('layouts.app')

@section('content')
    @section('header')
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Damaged & Expired Items Logs') }}
            </h2>
            @if(auth()->user()->isSupervisor())
                <a href="{{ route('supervisor.damaged-expired.create') }}"
                    class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-100 text-sm font-semibold flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Report Damaged/Expired
                </a>
            @endif
        </div>
    @endsection

    <div class="py-6 max-w-6xl mx-auto px-4">
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Stock Write-Off Reports History</h3>
                <p class="text-xs font-semibold text-slate-400 mt-1 uppercase tracking-wider">Tracks supervisor reports and
                    auditor status updates</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-6">Product</th>
                            <th class="py-3 px-6">Type</th>
                            <th class="py-3 px-6">Source</th>
                            <th class="py-3 px-6">Quantity</th>
                            <th class="py-3 px-6">Reported By</th>
                            <th class="py-3 px-6">Date</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6">Audited By</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 divide-y divide-slate-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <span
                                        class="font-semibold text-slate-800">{{ $item->product->name ?? 'Unknown Item' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase {{ $item->type === 'expired' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ $item->type }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                                    {{ $item->store_id ? $item->store->name : 'Supervisor Private Stock' }}
                                </td>
                                <td class="py-4 px-6 text-sm font-bold text-slate-800">
                                    {{ $item->quantity }}
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-500">
                                    {{ $item->user->name ?? 'System' }}
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                                    {{ $item->created_at->format('d M, Y h:i A') }}
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase {{ $item->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($item->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                                    {{ $item->approver ? $item->approver->name : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-500 font-medium">
                                    No write-off reports have been submitted.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items->hasPages())
                <div class="p-6 border-t border-slate-100 bg-slate-50">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection