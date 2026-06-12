@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Allocate Stock to Hostel Halls') }}
        </h2>
    @endsection

    <div class="py-12" x-data="allocationForm()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-6">Create New Stock Allocation</h3>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('supervisor.stock.allocate') }}" method="POST" @submit="validateForm($event)">
                        @csrf

                        <!-- Select Hall -->
                        <div class="mb-6 max-w-md">
                            <label for="store_id" class="block text-sm font-medium text-gray-700">Target Hostel/Hall (Store)</label>
                            <select name="store_id" id="store_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Select Target Hall --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Allocation Rows -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Store Stock</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocation Quantity</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select :name="'allocations[' + index + '][product_id]'" 
                                                    x-model="row.product_id" 
                                                    @change="updateAvailableQty(index)"
                                                    required 
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                    <option value="">-- Choose Product --</option>
                                                    <template x-for="p in products" :key="p.product_id">
                                                        <option :value="p.product_id" x-text="p.product.name" :disabled="isAlreadySelected(p.product_id, index)"></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <span class="font-semibold" x-text="row.available_qty">0</span> units
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" 
                                                    step="0.01" 
                                                    :name="'allocations[' + index + '][quantity]'" 
                                                    x-model.number="row.quantity" 
                                                    required 
                                                    :max="row.available_qty"
                                                    class="block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    placeholder="0.00">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" @click="removeRow(index)" class="text-rose-600 hover:text-rose-900 font-semibold">Remove</button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="rows.length === 0">
                                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No products selected. Click "Add Product Row" below.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Control Buttons -->
                        <div class="flex justify-between items-center mb-8">
                            <button type="button" @click="addRow()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                + Add Product Row
                            </button>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end border-t border-gray-200 pt-5">
                            <a href="{{ route('supervisor.dashboard') }}" class="mr-3 px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                            <button type="submit" :disabled="rows.length === 0" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                Allocate Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function allocationForm() {
                return {
                    products: @json($stocks),
                    rows: [
                        { product_id: '', quantity: 1, available_qty: 0 }
                    ],

                    addRow() {
                        this.rows.push({ product_id: '', quantity: 1, available_qty: 0 });
                    },

                    removeRow(index) {
                        this.rows.splice(index, 1);
                    },

                    updateAvailableQty(index) {
                        const selectedProduct = this.products.find(p => p.product_id == this.rows[index].product_id);
                        if (selectedProduct) {
                            this.rows[index].available_qty = selectedProduct.quantity;
                        } else {
                            this.rows[index].available_qty = 0;
                        }
                    },

                    isAlreadySelected(productId, currentIndex) {
                        return this.rows.some((row, index) => row.product_id == productId && index !== currentIndex);
                    },

                    validateForm(event) {
                        // Validate all rows have product and correct quantities
                        let errorMsg = null;
                        for (let row of this.rows) {
                            if (!row.product_id) {
                                errorMsg = 'Please select a product for all rows.';
                                break;
                            }
                            if (row.quantity <= 0) {
                                errorMsg = 'Quantity must be greater than 0 for all rows.';
                                break;
                            }
                            if (row.quantity > row.available_qty) {
                                errorMsg = 'Allocation quantity cannot exceed available stock.';
                                break;
                            }
                        }
                        if (errorMsg) {
                            event.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: errorMsg,
                                confirmButtonColor: '#6366f1',
                            });
                        }
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
@endsection
