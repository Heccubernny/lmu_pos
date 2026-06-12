@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suppliers') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Manage Suppliers</h3>
                        @if(auth()->user()->isITAdmin() || auth()->user()->isHead())
                            <a href="{{ route('admin.suppliers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Add Supplier</a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Debit (Paid)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credit (Due)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Branch</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($suppliers as $supplier)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600">
                                            <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.suppliers.show', $supplier) : route('admin.suppliers.show', $supplier) }}" class="hover:underline">{{ $supplier->company_name }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-emerald-600 font-semibold">₦{{ number_format($supplier->debit ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-rose-500 font-semibold">₦{{ number_format($supplier->credit ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ ($supplier->balance ?? 0) > 0 ? 'text-rose-600' : 'text-slate-600' }}">₦{{ number_format($supplier->balance ?? 0, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $supplier->phone }}<br>
                                            <span class="text-xs text-gray-400">{{ $supplier->email }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $supplier->branch }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $supplier->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.suppliers.show', $supplier) : route('admin.suppliers.show', $supplier) }}" class="text-teal-600 hover:text-teal-900 mr-3">History</a>
                                            @if(strtolower(auth()->user()->role ?? '') !== 'supervisor')
                                                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline swal-delete-form" data-title="Delete Supplier" data-text="Are you sure you want to delete {{ $supplier->company_name }}? This action cannot be undone.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="text-red-600 hover:text-red-900 swal-delete-btn">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No suppliers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.swal-delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = btn.closest('.swal-delete-form');
        Swal.fire({
            title: form.dataset.title || 'Are you sure?',
            text: form.dataset.text || 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
