@extends('layouts.app')

@section('header', 'Store Management')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Title & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Store Management</h1>
                <p class="text-sm text-slate-500 mt-1">Manage all store locations, hosts, and authorization status.</p>
            </div>
            <a href="{{ route('admin.stores.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-150 hover:-translate-y-px hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                New Store
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div id="flash-swal" data-msg="{{ session('success') }}" data-type="success"></div>
        @endif
        @if(session('error'))
            <div id="flash-swal" data-msg="{{ session('error') }}" data-type="error"></div>
        @endif

        {{-- Stats Cards --}}
        @php
            $totalStores     = $stores->count();
            $authorizedCount = $stores->where('authorized', true)->count();
            $blockedCount    = $stores->where('authorized', false)->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
            {{-- Total --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4 shadow-sm">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Stores</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalStores }}</p>
                </div>
            </div>
            {{-- Authorized --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4 shadow-sm">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Authorized</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $authorizedCount }}</p>
                </div>
            </div>
            {{-- Blocked --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-center gap-4 shadow-sm">
                <div class="h-12 w-12 rounded-xl bg-rose-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Unauthorized</p>
                    <p class="text-2xl font-bold text-rose-500">{{ $blockedCount }}</p>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-700">All Stores</p>
                <span class="text-xs text-slate-400 font-medium">{{ $totalStores }} {{ Str::plural('location', $totalStores) }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Store</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Host / IP</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Authorization</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Supervisor ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider pr-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($stores as $store)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                {{-- Store Name --}}
                                <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-indigo-700 font-bold text-sm">{{ strtoupper(substr($store->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $store->name }}</p>
                                            <p class="text-xs text-slate-400">ID #{{ $store->id }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Host --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($store->host)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-mono text-slate-600 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-md">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                                            </svg>
                                            {{ $store->host }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Not configured</span>
                                    @endif
                                </td>

                                {{-- Authorization --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    <form method="POST" action="{{ route('admin.stores.authorize', $store) }}" class="inline">
                                        @csrf
                                        @if($store->authorized)
                                            <input type="hidden" name="authorized" value="0">
                                            <button type="button" class="swal-toggle-btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors"
                                                data-confirm-title="Revoke Authorization?"
                                                data-confirm-text="Cashiers will no longer be able to sell from '{{ $store->name }}'. Continue?"
                                                data-confirm-icon="warning"
                                                data-confirm-btn="Revoke">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                                Authorized
                                            </button>
                                        @else
                                            <input type="hidden" name="authorized" value="1">
                                            <button type="button" class="swal-toggle-btn inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 transition-colors"
                                                data-confirm-title="Authorize Store?"
                                                data-confirm-text="This will allow cashiers to sell from '{{ $store->name }}'."
                                                data-confirm-icon="question"
                                                data-confirm-btn="Yes, Authorize">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 inline-block"></span>
                                                Unauthorized
                                            </button>
                                        @endif
                                    </form>
                                </td>

                                {{-- Supervisor ID --}}
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($store->supervisor_id)
                                        <span class="text-xs font-semibold text-slate-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-md">
                                            #{{ $store->supervisor_id }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Unassigned</span>
                                    @endif
                                </td>

                                {{-- Created At --}}
                                <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-500">
                                    {{ $store->created_at?->format('M d, Y') ?? '—' }}
                                </td>

                                {{-- Actions --}}
                                <td class="whitespace-nowrap py-4 pl-3 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.stores.edit', $store) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.stores.destroy', $store) }}" method="POST"
                                              class="inline swal-delete-form"
                                              data-title="Delete Store"
                                              data-text="Are you sure you want to delete '{{ $store->name }}'? This action cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="swal-delete-btn inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-100 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-600">No stores found</p>
                                        <p class="text-xs text-slate-400">Create your first store to get started.</p>
                                        <a href="{{ route('admin.stores.create') }}" class="mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-700 underline underline-offset-2">
                                            + Create a store
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
// Flash session messages via SweetAlert2
const flashEl = document.getElementById('flash-swal');
if (flashEl) {
    const type = flashEl.dataset.type || 'success';
    const msg  = flashEl.dataset.msg;
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: msg,
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
    });
}

// Delete store confirmation
document.querySelectorAll('.swal-delete-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = btn.closest('.swal-delete-form');
        Swal.fire({
            title: form.dataset.title,
            text: form.dataset.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});

// Authorization toggle confirmation
document.querySelectorAll('.swal-toggle-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = btn.closest('form');
        Swal.fire({
            title: btn.dataset.confirmTitle,
            text: btn.dataset.confirmText,
            icon: btn.dataset.confirmIcon,
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#64748b',
            confirmButtonText: btn.dataset.confirmBtn,
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
