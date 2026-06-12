@extends('layouts.app')

@section('content')
    @if(isset($noStoreAssigned) && $noStoreAssigned)
        <div class="h-full flex flex-col items-center justify-center bg-slate-50 p-6" style="height: calc(100vh - 8rem);">
            <div class="max-w-md w-full bg-white border border-slate-200 rounded-2xl p-8 shadow-lg text-center space-y-6">
                <div class="mx-auto bg-rose-100 text-rose-600 p-4 rounded-full w-16 h-16 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800">No Store/Hall Assigned</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Your sales representative account is not currently assigned to any hostel hall or buttery store. 
                    Please contact your store supervisor or system administrator to assign your account.
                </p>
                <div class="pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 transition">
                            Logout & Sign In Again
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- We hide the default header and padding for a full-screen POS feel -->
        <div x-data="posSystem()" class="h-full flex flex-col md:flex-row bg-slate-50 p-3 md:p-6 gap-6"
            style="height: calc(100vh - 4rem);">

        <!-- Left Side: Product List -->
        <div class="flex-1 flex flex-col bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Search & Filters -->
            <div class="p-5 border-b border-slate-100 bg-white">
                <div class="relative max-w-xl mx-auto">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input x-model="searchQuery" type="text"
                        class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl leading-5 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 sm:text-sm transition-all"
                        placeholder="Search products by name or barcode...">
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto p-5 bg-slate-50/50">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)"
                            class="cursor-pointer bg-white border border-slate-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow-md transition-all active:scale-[0.98] flex flex-col justify-between h-36">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-800 line-clamp-2 leading-snug"
                                    x-text="product.name"></h4>
                                <div
                                    class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                    <span x-text="'Stock: ' + product.quantity"></span>
                                </div>
                            </div>
                            <div class="text-right mt-2">
                                <span class="text-lg font-bold text-indigo-600"
                                    x-text="'₦' + parseFloat(product.unit_price).toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredProducts.length === 0"
                        class="col-span-full py-12 text-center text-slate-500 flex flex-col items-center justify-center space-y-3">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>No products found matching your search.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Cart & Checkout -->
        <div
            class="w-full md:w-[400px] flex flex-col bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex-shrink-0">
            <!-- Cart Header -->
            <div class="px-5 py-4 border-b border-slate-100 bg-white flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 flex items-center tracking-tight">
                    <div class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    Current Order
                </h3>
                <button @click="clearCart()"
                    class="text-sm font-semibold text-rose-500 hover:text-rose-700 transition-colors">Clear</button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-4 bg-slate-50/30">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div
                        class="flex justify-between items-center p-3 mb-3 bg-white rounded-xl border border-slate-100 shadow-sm hover:border-indigo-100 transition-colors group">
                        <div class="flex-1 min-w-0 pr-3 relative">
                            <h4 class="text-sm font-semibold text-slate-800 truncate" x-text="item.name"></h4>
                            <p class="text-xs font-medium text-slate-500 mt-0.5"
                                x-text="'₦' + parseFloat(item.price).toLocaleString('en-US', {minimumFractionDigits: 2})">
                            </p>
                        </div>
                        <div class="flex items-center flex-shrink-0 bg-slate-50 rounded-lg p-1 border border-slate-100">
                            <button @click="decrement(index)"
                                class="w-7 h-7 flex items-center justify-center rounded-md bg-white text-slate-600 hover:bg-slate-200 transition-colors shadow-sm">-</button>
                            <input type="number" x-model.number="item.quantity"
                                class="w-10 h-7 mx-0.5 text-center text-sm font-medium border-0 bg-transparent text-slate-800 focus:ring-0 appearance-none p-0"
                                min="1">
                            <button @click="increment(index)"
                                class="w-7 h-7 flex items-center justify-center rounded-md bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors shadow-sm">+</button>
                        </div>
                        <button @click="removeFromCart(index)"
                            class="ml-3 p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </template>
                <div x-show="cart.length === 0"
                    class="h-full flex flex-col items-center justify-center text-slate-400 p-8 text-center space-y-4">
                    <div class="bg-slate-100 p-4 rounded-full">
                        <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium">Scan or select products to add to cart.</p>
                </div>
            </div>

            <!-- Checkout Section -->
            <div class="p-5 bg-slate-50 border-t border-slate-200">
                <form action="{{ auth()->user()->isSalesRep() ? route('cashier.sales.store') : route('admin.sales.store') }}" method="POST" id="checkout-form" @submit.prevent="checkout($event)">
                    @csrf
                    <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                    <input type="hidden" name="merchant_reference" :value="cashlessData.merchant_reference">
                    <input type="hidden" name="terminal_id" :value="cashlessData.terminal_id">
                    <input type="hidden" name="payment_reference" :value="cashlessData.payment_reference">
                    <input type="hidden" name="processing_status" :value="cashlessData.processing_status">
                    <input type="hidden" name="raw_response" :value="cashlessData.raw_response">

                    <div class="flex justify-between items-center text-sm font-medium text-slate-500 mb-3">
                        <span>Subtotal</span>
                        <span class="text-slate-700"
                            x-text="'₦' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                    </div>

                    <div class="flex justify-between items-center text-sm font-medium text-slate-500 mb-4">
                        <span class="flex items-center">
                            Discount %
                            <input type="number" name="discount_percent" x-model.number="discount"
                                class="ml-3 w-16 h-8 text-center text-sm bg-white border border-slate-200 rounded-md focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 py-0"
                                min="0" max="100">
                        </span>
                        <span class="text-rose-500"
                            x-text="discount > 0 ? '-₦' + discountAmount.toLocaleString('en-US', {minimumFractionDigits: 2}) : '₦0.00'"></span>
                    </div>

                    <div
                        class="flex justify-between items-center text-xl font-bold text-slate-900 pt-4 border-t border-slate-200 mb-6">
                        <span>Total Payable</span>
                        <span class="text-indigo-600"
                            x-text="'₦' + total.toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <select name="customer_id"
                                class="block w-full text-sm font-medium bg-white text-slate-700 border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 py-2.5 shadow-sm">
                                <option value="">Walk-in Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select name="mode_payment"
                                class="block w-full text-sm font-medium bg-white text-slate-700 border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-50 focus:border-indigo-500 py-2.5 shadow-sm">
                                <option value="Cash">Cash</option>
                                <option value="Card">Card</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        @if(session('error'))
                            <div
                                class="bg-rose-50 text-rose-600 p-2 rounded-md text-xs font-semibold mb-3 border border-rose-100 text-center">
                                {{ session('error') }}
                            </div>
                        @endif
                        <button type="submit" :disabled="cart.length === 0"
                            class="w-full flex justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white transition-all transform active:scale-95"
                            :class="cart.length === 0 ? 'bg-slate-300 shadow-none cursor-not-allowed text-slate-500' : 'bg-indigo-600 hover:bg-indigo-700 hover:shadow-indigo-300 focus:outline-none focus:ring-4 focus:ring-indigo-100'">
                            Complete Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mock Moniepoint Payment Modal -->
        <div x-show="showPaymentModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-cloak style="display: none;">
            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-md w-full overflow-hidden transform transition-all duration-300">
                <!-- Header -->
                <div class="bg-indigo-600 px-6 py-4 flex items-center justify-between text-white">
                    <h3 class="font-bold text-lg flex items-center">
                        <svg class="w-6 h-6 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        Moniepoint POS Terminal
                    </h3>
                    <button type="button" @click="cancelPayment()" class="text-white/80 hover:text-white font-semibold">Cancel</button>
                </div>
                <!-- Body -->
                <div class="p-6 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">transaction status</p>
                    <h4 class="text-lg font-bold text-slate-800 mt-2" x-text="terminalStatus">Initializing...</h4>

                    <!-- Spinner or Status Icon -->
                    <div class="flex justify-center my-6">
                        <div x-show="paymentSimulating" class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-600 border-t-transparent"></div>
                        <div x-show="!paymentSimulating && paymentSuccess" class="bg-emerald-100 p-3 rounded-full text-emerald-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div x-show="!paymentSimulating && paymentFailed" class="bg-rose-100 p-3 rounded-full text-rose-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </div>

                    <!-- Details Table -->
                    <div class="bg-slate-50 rounded-xl p-4 text-left text-sm space-y-2 mb-6 border border-slate-100">
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Terminal ID:</span>
                            <span class="text-slate-800 font-semibold">MP-TERM-492</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Merchant Ref:</span>
                            <span class="text-slate-800 font-mono text-xs" x-text="merchantRef">REF-XXXX</span>
                        </div>
                        <div class="flex justify-between border-t border-slate-200 pt-2 font-bold text-base text-slate-900">
                            <span>Amount Due:</span>
                            <span class="text-indigo-600" x-text="'₦' + total.toLocaleString('en-US', {minimumFractionDigits: 2})"></span>
                        </div>
                    </div>

                    <!-- Simulation Controls -->
                    <div class="flex flex-col gap-3" x-show="paymentSimulating">
                        <button type="button" @click="simulateSuccess()" class="w-full py-3 bg-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition">
                            Simulate Card Swipe (Approved)
                        </button>
                        <button type="button" @click="simulateFailure()" class="w-full py-3 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl font-bold hover:bg-rose-100 transition">
                            Simulate Transaction Decline
                        </button>
                    </div>

                    <!-- Decline State Actions -->
                    <div class="flex gap-3" x-show="paymentFailed">
                        <button type="button" @click="retryPayment()" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                            Retry Swipe
                        </button>
                        <button type="button" @click="cancelPayment()" class="flex-1 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold hover:bg-slate-200 transition">
                            Close POS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
        <script>
            function initPosSystem() {
                if (window.Alpine) {
                    window.Alpine.data('posSystem', () => ({
                        products: @json($products),
                        searchQuery: '',
                        cart: [],
                        discount: 0,

                        // Payment modal properties
                        showPaymentModal: false,
                        paymentSimulating: true,
                        paymentSuccess: false,
                        paymentFailed: false,
                        terminalStatus: 'Ready',
                        merchantRef: '',
                        cashlessData: {
                            merchant_reference: '',
                            terminal_id: '',
                            payment_reference: '',
                            processing_status: '',
                            raw_response: ''
                        },

                        get filteredProducts() {
                            if (this.searchQuery === '') {
                                return this.products;
                            }
                            const query = this.searchQuery.toLowerCase();
                            return this.products.filter(p =>
                                p.name.toLowerCase().includes(query) ||
                                (p.item_number && p.item_number.toLowerCase().includes(query))
                            );
                        },

                        addToCart(product) {
                            const existingItem = this.cart.find(item => item.id === product.id);
                            if (existingItem) {
                                if (existingItem.quantity < product.quantity) {
                                    existingItem.quantity++;
                                } else {
                                    Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Cannot add more than available stock.', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                                }
                            } else {
                                this.cart.push({
                                    id: product.id,
                                    name: product.name,
                                    price: product.unit_price,
                                    quantity: 1,
                                    maxQuantity: product.quantity
                                });
                            }
                        },

                        increment(index) {
                            if (this.cart[index].quantity < this.cart[index].maxQuantity) {
                                this.cart[index].quantity++;
                            } else {
                                Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'Cannot add more than available stock.', showConfirmButton: false, timer: 2500, timerProgressBar: true });
                            }
                        },

                        decrement(index) {
                            if (this.cart[index].quantity > 1) {
                                this.cart[index].quantity--;
                            } else {
                                this.removeFromCart(index);
                            }
                        },

                        removeFromCart(index) {
                            this.cart.splice(index, 1);
                        },

                        clearCart() {
                            Swal.fire({
                                title: 'Clear the Cart?',
                                text: 'All items will be removed from the cart. Are you sure?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#e11d48',
                                cancelButtonColor: '#64748b',
                                confirmButtonText: 'Yes, Clear Cart',
                                cancelButtonText: 'Cancel',
                                reverseButtons: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.cart = [];
                                    this.discount = 0;
                                }
                            });
                        },

                        get subtotal() {
                            return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                        },

                        get discountAmount() {
                            return this.subtotal * (this.discount / 100);
                        },

                        get total() {
                            return this.subtotal - this.discountAmount;
                        },

                        checkout(event) {
                            const form = document.getElementById('checkout-form');
                            const paymentMethod = form.querySelector('select[name="mode_payment"]').value;

                            if (paymentMethod === 'Card' || paymentMethod === 'Transfer') {
                                // Trigger Mock Moniepoint payment flow
                                this.merchantRef = 'MREF-' + Date.now() + Math.floor(Math.random() * 1000);
                                this.showPaymentModal = true;
                                this.paymentSimulating = true;
                                this.paymentSuccess = false;
                                this.paymentFailed = false;
                                this.terminalStatus = 'Waiting for card swipe / payment authorization on terminal...';
                            } else {
                                // Direct cash submission
                                form.submit();
                            }
                        },

                        simulateSuccess() {
                            const paymentRef = 'MP-TXN-' + Math.floor(Math.random() * 1000000000);
                            this.cashlessData = {
                                merchant_reference: this.merchantRef,
                                terminal_id: 'MP-TERM-492',
                                payment_reference: paymentRef,
                                processing_status: 'SUCCESS',
                                raw_response: JSON.stringify({
                                    status: 'approved',
                                    card_brand: 'Visa',
                                    terminal: 'MP-TERM-492',
                                    merchant_ref: this.merchantRef
                                })
                            };

                            this.paymentSimulating = false;
                            this.paymentSuccess = true;
                            this.terminalStatus = 'Approved! Finalizing checkout...';

                            setTimeout(() => {
                                document.getElementById('checkout-form').submit();
                            }, 1000);
                        },

                        simulateFailure() {
                            this.paymentSimulating = false;
                            this.paymentFailed = true;
                            this.terminalStatus = 'Transaction Declined (Insufficient Funds).';
                        },

                        retryPayment() {
                            this.paymentSimulating = true;
                            this.paymentFailed = false;
                            this.terminalStatus = 'Waiting for card swipe / payment authorization on terminal...';
                        },

                        cancelPayment() {
                            this.showPaymentModal = false;
                        }
                    }));
                }
            }

            if (window.Alpine) {
                initPosSystem();
            } else {
                document.addEventListener('alpine:init', initPosSystem);
            }
        </script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Ensure Alpine is loaded -->
    @endpush
@endsection