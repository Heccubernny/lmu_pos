@extends('layouts.app')

@section('header', 'Edit Store')

@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
            <a href="{{ route('admin.stores.index') }}" class="hover:text-indigo-600 transition-colors font-medium">Store Management</a>
            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-slate-700 font-semibold">Edit: {{ $store->name }}</span>
        </nav>

        {{-- Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="px-7 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Edit Store</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Store ID #{{ $store->id }} &mdash; Last updated {{ $store->updated_at?->diffForHumans() ?? 'never' }}</p>
                    </div>
                </div>
                {{-- Quick Status Badge --}}
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $store->authorized ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $store->authorized ? 'bg-emerald-500' : 'bg-rose-400' }} inline-block"></span>
                    {{ $store->authorized ? 'Authorized' : 'Unauthorized' }}
                </span>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.stores.update', $store) }}" class="px-7 py-6 space-y-6">
                @csrf
                @method('PUT')

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-sm">
                        <p class="font-semibold mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Store Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Store Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $store->name) }}"
                           required
                           placeholder="e.g. Main Store — Block A"
                           class="w-full px-4 py-2.5 text-sm border rounded-xl bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:bg-white transition-all @error('name') border-rose-400 bg-rose-50 @else border-slate-200 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Host / IP --}}
                <div>
                    <label for="host" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Host / IP Address
                        <span class="text-slate-400 font-normal text-xs ml-1">(optional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                        </div>
                        <input type="text"
                               id="host"
                               name="host"
                               value="{{ old('host', $store->host) }}"
                               placeholder="e.g. 192.168.1.50 or store-a.local"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl bg-slate-50 text-slate-800 font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:bg-white transition-all @error('host') border-rose-400 bg-rose-50 @else border-slate-200 @enderror">
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">Used to connect to the remote store database.</p>
                    @error('host')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Assigned Supervisor --}}
                <div>
                    <label for="supervisor_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Assigned Supervisor
                        <span class="text-slate-400 font-normal text-xs ml-1">(optional)</span>
                    </label>
                    <select id="supervisor_id"
                            name="supervisor_id"
                            class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 focus:bg-white transition-all">
                        <option value="">— No Supervisor Assigned —</option>
                        @foreach($supervisors as $supervisor)
                            <option value="{{ $supervisor->person_id }}"
                                {{ old('supervisor_id', $store->supervisor_id) == $supervisor->person_id ? 'selected' : '' }}>
                                {{ $supervisor->name }} ({{ $supervisor->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('supervisor_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Authorization Toggle --}}
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Authorize for Sales</p>
                        <p class="text-xs text-slate-500 mt-0.5">When authorized, cashiers can process sales from this store.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="hidden" name="authorized" value="0">
                        <input type="checkbox" id="authorized" name="authorized" value="1"
                               {{ old('authorized', $store->authorized) ? 'checked' : '' }}
                               class="sr-only peer"
                               onchange="this.previousElementSibling.value = this.checked ? 1 : 0">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-400 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                {{-- Danger Zone: Delete --}}
                <div class="rounded-xl border border-rose-200 bg-rose-50/50 p-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-rose-700">Delete Store</p>
                        <p class="text-xs text-rose-500 mt-0.5">Permanently remove this store. This action cannot be undone.</p>
                    </div>
                    <form action="{{ route('admin.stores.destroy', $store) }}" method="POST"
                          class="swal-delete-form"
                          data-title="Delete {{ $store->name }}?"
                          data-text="This will permanently delete this store and all related data. This cannot be undone.">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                class="swal-delete-btn inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-rose-600 bg-white hover:bg-rose-50 border border-rose-300 rounded-xl transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Store
                        </button>
                    </form>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <a href="{{ route('admin.stores.index') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all hover:-translate-y-px hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush
