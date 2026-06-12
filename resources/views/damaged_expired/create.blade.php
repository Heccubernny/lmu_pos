@extends('layouts.app')

@section('content')
    @section('header')
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Report Damaged or Expired Stock') }}
            </h2>
            <a href="{{ route('supervisor.damaged-expired.index') }}"
                class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition text-sm font-semibold flex items-center border border-slate-200">
                Back to Reports List
            </a>
        </div>
    @endsection

    <div class="py-6 max-w-4xl mx-auto px-4" x-data="damageExpiredForm()">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-200">
            <div class="p-6 md:p-8 text-slate-800">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <span class="bg-rose-100 text-rose-600 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </span>
                    Record Stock Write-Off Request
                </h3>

                @if($errors->any())
                    <div class="bg-rose-50 text-rose-600 p-4 rounded-xl text-sm border border-rose-100 mb-6">
                        <ul class="list-disc list-inside space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('supervisor.damaged-expired.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Source Type Selector -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Select Stock Source</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border-2 rounded-xl p-4 flex items-center cursor-pointer justify-center transition-all"
                                    :class="sourceType === 'private' ? 'border-indigo-600 bg-indigo-50/40 text-indigo-700' : 'border-slate-200 hover:bg-slate-50 text-slate-600'">
                                    <input type="radio" name="source_type" value="private" x-model="sourceType" class="sr-only">
                                    <span class="font-bold text-sm">Supervisor Store Stock</span>
                                </label>
                                <label class="border-2 rounded-xl p-4 flex items-center cursor-pointer justify-center transition-all"
                                    :class="sourceType === 'hall' ? 'border-indigo-600 bg-indigo-50/40 text-indigo-700' : 'border-slate-200 hover:bg-slate-50 text-slate-600'">
                                    <input type="radio" name="source_type" value="hall" x-model="sourceType" class="sr-only">
                                    <span class="font-bold text-sm">Hostel Hall Stock</span>
                                </label>
                            </div>
                        </div>

                        <!-- Select Hall (Hidden if Private Store Stock) -->
                        <div x-show="sourceType === 'hall'" x-transition>
                            <label for="store_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Hostel Hall</label>
                            <select name="store_id" id="store_id" x-model="selectedStore" :required="sourceType === 'hall'"
                                class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-sm py-3 transition shadow-sm">
                                <option value="">-- Choose Assigned Hall --</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Product (Dynamic options loaded by Alpine based on source) -->
                        <div>
                            <label for="product_id" class="block text-sm font-semibold text-slate-700 mb-2">Select Product</label>
                            <select name="product_id" id="product_id" x-model="selectedProduct" required
                                class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-sm py-3 transition shadow-sm">
                                <option value="">-- Choose Product --</option>
                                <template x-for="item in availableProducts" :key="item.product_id">
                                    <option :value="item.product_id" x-text="item.name + ' (Available: ' + item.quantity + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Write-Off Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">Discrepancy Type</label>
                            <select name="type" id="type" required
                                class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-sm py-3 transition shadow-sm">
                                <option value="damaged">Damaged Item</option>
                                <option value="expired">Expired Item</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-slate-700 mb-2">Quantity to Write-Off</label>
                            <input type="number" step="0.01" name="quantity" id="quantity" required :max="maxQuantity"
                                class="block w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-700 text-sm py-3 transition shadow-sm"
                                placeholder="e.g. 5">
                            <p class="text-xs text-slate-400 mt-1" x-show="maxQuantity > 0" x-text="'Maximum allowed: ' + maxQuantity"></p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8 border-t border-slate-100 pt-6">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 transition transform active:scale-95 text-sm">
                            Submit Write-Off Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function damageExpiredForm() {
                return {
                    sourceType: 'private',
                    selectedStore: '',
                    selectedProduct: '',
                    privateStocks: @json($privateStocks),
                    hallStocks: @json($hallStocks),

                    get availableProducts() {
                        this.selectedProduct = ''; // Reset selected product when source changes
                        if (this.sourceType === 'private') {
                            return this.privateStocks.map(s => ({
                                product_id: s.product_id,
                                name: s.product ? s.product.name : 'Unknown Product',
                                quantity: s.quantity
                            }));
                        } else {
                            if (!this.selectedStore) return [];
                            return this.hallStocks
                                .filter(s => s.store_id == this.selectedStore)
                                .map(s => ({
                                    product_id: s.product_id,
                                    name: s.product ? s.product.name : 'Unknown Product',
                                    quantity: s.quantity
                                }));
                        }
                    },

                    get maxQuantity() {
                        if (!this.selectedProduct) return 0;
                        const match = this.availableProducts.find(p => p.product_id == this.selectedProduct);
                        return match ? match.quantity : 0;
                    }
                }
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
@endsection
