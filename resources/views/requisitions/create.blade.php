@extends('layouts.app')

@section('content')
    @section('header')
        <div class="flex items-center space-x-4">
            <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.requisitions.index') : route('admin.requisitions.index') }}" 
                class="text-slate-500 hover:text-slate-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-slate-800 tracking-tight">
                {{ __('New Requisition') }}
            </h2>
        </div>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Submit Stock Transfer Request</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Select a warehouse item and target store to request stock allocation.</p>
                </div>

                <div class="p-6">
                    <form action="{{ auth()->user()->isSupervisor() ? route('supervisor.requisitions.store') : route('admin.requisitions.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <!-- Product Select -->
                            <div>
                                <label for="product_id" class="block text-sm font-bold text-slate-700 mb-1">Select Product</label>
                                <select name="product_id" id="product_id" 
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 shadow-sm transition" required>
                                    <option value="">Choose item from warehouse...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->item_id }}" data-category="{{ $product->category->name ?? $product->category ?? 'General' }}">
                                            {{ $product->name }} (Warehouse Stock: {{ number_format($product->quantity, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Auto Category -->
                            <div>
                                <label for="category" class="block text-sm font-bold text-slate-700 mb-1">Product Category</label>
                                <input type="text" id="category" 
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed shadow-sm focus:ring-0 focus:border-slate-200 transition" 
                                    readonly placeholder="Auto-populated on product selection...">
                            </div>

                            <!-- Store Select -->
                            <div>
                                <label for="store_id" class="block text-sm font-bold text-slate-700 mb-1">Requesting Store / Hall</label>
                                <select name="store_id" id="store_id" 
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 shadow-sm transition" required>
                                    @if(count($stores) > 1)
                                        <option value="">Select destination store...</option>
                                    @endif
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ count($stores) === 1 ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-bold text-slate-700 mb-1">Quantity Requested</label>
                                <input type="number" step="1" name="quantity" id="quantity" min="1" 
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 shadow-sm transition" 
                                    placeholder="Enter quantity to transfer..." required>
                            </div>

                            <!-- Collected By -->
                            <div>
                                <label for="collectedby" class="block text-sm font-bold text-slate-700 mb-1">Collected By</label>
                                <select name="collectedby" id="collectedby" 
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 shadow-sm transition" required>
                                    <option value="">Select receiving staff member...</option>
                                    @foreach($staff as $s)
                                        <option value="{{ $s->name }}" {{ auth()->user()->person_id === $s->person_id ? 'selected' : '' }}>
                                            {{ $s->name }} ({{ $s->position ?? 'Staff' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-sm font-bold text-slate-700 mb-1">Department</label>
                                <select name="department" id="department" 
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 shadow-sm transition" required>
                                    <option value="">-- Select Department --</option>
                                    @forelse($departments as $dept)
                                        <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @empty
                                        <option value="" disabled>No departments found. Please ask Admin to add departments first.</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end pt-5 border-t border-slate-100 space-x-3">
                                <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.requisitions.index') : route('admin.requisitions.index') }}" 
                                    class="text-sm font-bold text-slate-500 hover:text-slate-700 transition">Cancel</a>
                                <button type="submit" 
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 transition text-sm">
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const categoryInput = document.getElementById('category');

        function updateCategory() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const category = selectedOption ? selectedOption.dataset.category : '';
            categoryInput.value = category || '';
        }

        productSelect.addEventListener('change', updateCategory);
        // Trigger initial category load if a product is preselected
        updateCategory();
    });
</script>
@endpush
