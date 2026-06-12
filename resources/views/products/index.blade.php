@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Products (Inventory)') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h3 class="text-base font-semibold text-slate-800 mb-4 sm:mb-0">Manage Inventory</h3>
                        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Product
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 mb-6 rounded-lg text-sm flex items-center">
                            <svg class="h-5 w-5 mr-3 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Item #</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Category</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cost P.</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Unit P.</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Qty</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative py-3.5 pl-6 pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($products as $product)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900">{{ $product->item_number ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">{{ $product->name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $product->category ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">₦{{ number_format($product->cost_price, 2) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">₦{{ number_format($product->unit_price, 2) }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @if($product->quantity <= 5)
                                                <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700 border border-rose-100">{{ $product->quantity }}</span>
                                            @else
                                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">{{ $product->quantity }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200">{{ ucfirst($product->status ?? 'active') }}</span>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 transition-colors">Edit</a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline swal-delete-form" data-title="Delete Product" data-text="Are you sure you want to delete {{ $product->name }}? Stock will be permanently removed.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="text-rose-600 hover:text-rose-900 transition-colors swal-delete-btn">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 whitespace-nowrap text-sm text-slate-500 text-center">No products found in inventory.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $products->links() }}
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
