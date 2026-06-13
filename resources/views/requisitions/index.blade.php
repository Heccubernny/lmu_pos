@extends('layouts.app')

@section('content')
    @section('header')
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 tracking-tight">
                    {{ __('Store Requisitions') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">Manage and track inventory requests from stores and halls to the central warehouse.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.requisitions.create') : route('admin.requisitions.create') }}" 
                    class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 text-sm font-bold flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Requisition
                </a>
            </div>
        </div>
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Requisitions Registry</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-left">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Item Details</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Requesting Store</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Qty Requested</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Warehouse Stock Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Staff / Dept</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse ($requisitions as $req)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-800 text-sm">{{ $req->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $req->category }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-700">
                                        {{ $req->store->name ?? $req->branch ?? 'Main' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-slate-800">
                                        {{ number_format($req->quantity, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($req->product)
                                            @if($req->product->quantity >= $req->quantity)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    Available ({{ number_format($req->product->quantity, 0) }} in warehouse)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                                    Shorthanded ({{ number_format($req->product->quantity, 0) }} in warehouse)
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-slate-400 italic">No warehouse record</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="font-medium text-slate-700">{{ $req->collectedby }}</div>
                                        <div class="text-2xs text-slate-400 mt-0.5">Dept: {{ $req->department }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @if($req->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Approved
                                            </span>
                                        @elseif($req->status === 'declined')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                                Declined
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200 animate-pulse">
                                                Pending Review
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                        @if($req->status === 'pending')
                                            @if(auth()->user()->isITAdmin() || auth()->user()->isHead())
                                                @php
                                                    $canApprove = $req->product && $req->product->quantity >= $req->quantity;
                                                @endphp
                                                <form action="{{ route('admin.requisitions.approve', $req) }}" method="POST" class="inline swal-confirm-form" 
                                                    data-title="Approve Requisition" 
                                                    data-text="Are you sure you want to approve this requisition and transfer {{ number_format($req->quantity, 0) }} units of {{ $req->name }} to {{ $req->store->name ?? 'store branch' }}?">
                                                    @csrf
                                                    <button type="submit" 
                                                        class="text-indigo-600 hover:text-indigo-900 transition-colors mr-3 {{ !$canApprove ? 'opacity-40 cursor-not-allowed' : '' }}"
                                                        {{ !$canApprove ? 'disabled' : '' }}>
                                                        Accept
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.requisitions.decline', $req) }}" method="POST" class="inline swal-confirm-form" 
                                                    data-title="Decline Requisition" 
                                                    data-text="Are you sure you want to decline this requisition request?">
                                                    @csrf
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900 transition-colors">Decline</button>
                                                </form>
                                            @else
                                                <span class="text-slate-400 text-xs italic font-normal">Awaiting Admin Action</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic font-normal text-xs">Reviewed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <span class="font-medium">No store requisitions recorded.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($requisitions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $requisitions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.swal-confirm-form').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: form.dataset.title || 'Are you sure?',
            text: form.dataset.text || 'Do you want to proceed with this action?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Proceed',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
