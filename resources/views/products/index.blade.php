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
                        @if(!isset($role) || strtolower($role ?? '') !== 'supervisor')
                        <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Product
                        </a>
                        @endif
                    </div>

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 mb-6 rounded-lg text-sm flex items-center">
                            <svg class="h-5 w-5 mr-3 text-emerald-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-100 text-rose-700 p-4 mb-6 rounded-lg text-sm flex items-center">
                            <svg class="h-5 w-5 mr-3 text-rose-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 002 0V9a1 1 0 00-2 0zm1-4a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                            </svg>
                            {{ session('error') }}
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
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Warehouse Qty</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative py-3.5 pl-6 pr-6 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
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
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium space-x-2">
                                            {{-- Assign to Store button --}}
                                            @php $canAssign = !isset($userRole) || strtolower(auth()->user()->role ?? '') !== 'supervisor'; @endphp
                                            @if($canAssign)
                                                <button
                                                    type="button"
                                                    onclick="openAssignModal({{ $product->item_id }}, '{{ addslashes($product->name) }}', {{ $product->quantity }}, {{ $product->unit_price }})"
                                                    class="inline-flex items-center text-xs font-semibold px-2.5 py-1.5 rounded-md
                                                        {{ $product->quantity > 0
                                                            ? 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 cursor-pointer'
                                                            : 'bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed' }}"
                                                    {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                    </svg>
                                                    Assign
                                                </button>

                                                <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors text-xs">Edit</a>
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline swal-delete-form" data-title="Delete Product" data-text="Are you sure you want to delete {{ $product->name }}? Stock will be permanently removed.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="text-rose-600 hover:text-rose-900 transition-colors swal-delete-btn text-xs">Delete</button>
                                                </form>
                                            @endif
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

    {{-- ========================================================
         ASSIGN TO STORE MODAL
         ======================================================== --}}
    <div id="assign-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" id="assign-modal-backdrop" onclick="closeAssignModal()"></div>

            {{-- Panel --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-100 rounded-lg p-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-title">Assign Product to Store</h3>
                            <p class="text-xs text-slate-500">Moves stock from global warehouse to a store</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAssignModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Product Info Card --}}
                <div class="mx-6 mt-5 bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start space-x-4">
                    <div class="bg-indigo-600 rounded-lg p-2.5 flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate" id="modal-product-name">—</p>
                        <div class="flex items-center space-x-3 mt-1.5">
                            <span class="text-xs text-slate-500">Warehouse stock:</span>
                            <span id="modal-product-qty" class="inline-flex items-center rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-700">0</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Unit price: <span class="font-semibold text-slate-700" id="modal-product-price">₦0.00</span></p>
                    </div>
                </div>

                {{-- Form --}}
                <form id="assign-form" method="POST" action="" class="px-6 py-5 space-y-5">
                    @csrf

                    <div>
                        <label for="assign-store-id" class="block text-sm font-semibold text-slate-700 mb-1.5">Target Store</label>
                        <select id="assign-store-id" name="store_id" required
                            class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all">
                            <option value="">— Select a store —</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assign-quantity" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Quantity to Assign
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                id="assign-quantity"
                                name="quantity"
                                min="0.01"
                                step="0.01"
                                required
                                placeholder="e.g. 10"
                                class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all pr-20"
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-slate-400 text-xs font-medium">Max: <span id="assign-max-qty">0</span></span>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">Cannot exceed the available warehouse stock. This will be deducted from the global inventory.</p>
                    </div>

                    {{-- Alert area --}}
                    <div id="assign-alert" class="hidden text-xs text-rose-600 bg-rose-50 border border-rose-100 rounded-lg p-3"></div>

                    {{-- Footer Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-2 border-t border-slate-100">
                        <button type="button" onclick="closeAssignModal()"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="assign-submit-btn"
                            class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span>Assign to Store</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// ─── SweetAlert delete confirmation ─────────────────────────────────────────
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

// ─── Assign to Store Modal ───────────────────────────────────────────────────
let currentMaxQty = 0;

function openAssignModal(productId, productName, productQty, unitPrice) {
    if (productQty <= 0) return;

    currentMaxQty = productQty;

    // Populate info card
    document.getElementById('modal-product-name').textContent  = productName;
    document.getElementById('modal-product-qty').textContent   = productQty;
    document.getElementById('assign-max-qty').textContent      = productQty;
    document.getElementById('modal-product-price').textContent = '₦' + parseFloat(unitPrice).toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    // Build the action URL
    document.getElementById('assign-form').action = '/admin/dashboard/products/' + productId + '/assign';

    // Reset fields
    document.getElementById('assign-store-id').value  = '';
    document.getElementById('assign-quantity').value  = '';
    document.getElementById('assign-quantity').max    = productQty;
    document.getElementById('assign-alert').classList.add('hidden');
    document.getElementById('assign-alert').textContent = '';

    // Show modal
    document.getElementById('assign-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAssignModal() {
    document.getElementById('assign-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Inline quantity guard
document.getElementById('assign-quantity').addEventListener('input', function () {
    const alert = document.getElementById('assign-alert');
    const val   = parseFloat(this.value);
    if (val > currentMaxQty) {
        alert.textContent = `Quantity (${val}) exceeds available warehouse stock (${currentMaxQty}). Please enter a smaller amount.`;
        alert.classList.remove('hidden');
        document.getElementById('assign-submit-btn').disabled = true;
        document.getElementById('assign-submit-btn').classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        alert.classList.add('hidden');
        document.getElementById('assign-submit-btn').disabled = false;
        document.getElementById('assign-submit-btn').classList.remove('opacity-50', 'cursor-not-allowed');
    }
});

// SweetAlert confirm on form submit
document.getElementById('assign-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form     = this;
    const store    = document.getElementById('assign-store-id');
    const qty      = document.getElementById('assign-quantity').value;
    const name     = document.getElementById('modal-product-name').textContent;
    const storeText = store.options[store.selectedIndex]?.text ?? 'selected store';

    if (!store.value || !qty || parseFloat(qty) <= 0) return;

    Swal.fire({
        title: 'Confirm Assignment',
        html: `Assign <b>${qty}</b> unit(s) of <b>${name}</b> to <b>${storeText}</b>?<br><small class="text-slate-500 mt-1 block">This will deduct from global warehouse stock.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Assign',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAssignModal();
});
</script>
@endpush
