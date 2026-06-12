@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('New Requisition') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900">
                    <form action="{{ route('admin.requisitions.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Item Name</label>
                                <select name="name" id="name" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                                    <option value="">Select Item</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->name }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                                <select name="category" id="category" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
                                <input type="number" step="0.01" name="quantity" id="quantity" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                            </div>

                            <div>
                                <label for="collectedby" class="block text-sm font-medium text-slate-700 mb-1">Collected By</label>
                                <select name="collectedby" id="collectedby" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                                    <option value="">Select Staff</option>
                                    @foreach($staff as $s)
                                        <option value="{{ $s->name }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium text-slate-700 mb-1">Department</label>
                                <select name="department" id="department" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Acounts">Acounts</option>
                                    <option value="HR">HR</option>
                                    <option value="Security">Security</option>
                                    <option value="Logistics">Logistics</option>
                                    <option value="Production">Production</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                                <a href="{{ route('admin.requisitions.index') }}" class="text-sm text-slate-500 hover:text-slate-700 mr-4 transition-colors">Cancel</a>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md text-sm font-semibold">
                                    Create Requisition
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
