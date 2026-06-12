@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Receive Stock from Supplier') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-6">Record New Stock Receipt</h3>

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

                    @if($errors->any())
                        <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100 mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('supervisor.stock.receive') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Select Product -->
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Select Product</label>
                                <select name="product_id" id="product_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Choose Product --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->item_id }}">{{ $product->name }} (SKU: {{ $product->item_number ?? 'N/A' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Supplier Name -->
                            <div>
                                <label for="supplier_name" class="block text-sm font-medium text-gray-700">Supplier Name</label>
                                <select name="supplier_name" id="supplier_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select or Type Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->company_name }}">{{ $supplier->company_name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">If not in list, you can create a Supplier in the Supplier portal.</p>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity Received</label>
                                <input type="number" step="0.01" name="quantity" id="quantity" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. 150">
                            </div>

                            <!-- Unit Cost -->
                            <div>
                                <label for="unit_cost" class="block text-sm font-medium text-gray-700">Unit Cost (₦)</label>
                                <input type="number" step="0.01" name="unit_cost" id="unit_cost" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="e.g. 450.00">
                            </div>

                            <!-- Payment Status -->
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <select name="payment_status" id="payment_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="Paid">Paid</option>
                                    <option value="Credit">Credit (Owed)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 border-t border-gray-200 pt-5">
                            <a href="{{ route('supervisor.dashboard') }}" class="mr-3 px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Record Stock Receipt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
