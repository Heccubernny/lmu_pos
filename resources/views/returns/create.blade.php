@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Record Return') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900">
                    <form action="{{ route('admin.returns.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="itemname" class="block text-sm font-medium text-slate-700 mb-1">Item Name</label>
                                <select name="itemname" id="itemname" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                                    <option value="">Select Item</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->name }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" min="1" required>
                            </div>

                            <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                                <a href="{{ route('admin.returns.index') }}" class="text-sm text-slate-500 hover:text-slate-700 mr-4 transition-colors">Cancel</a>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md text-sm font-semibold">
                                    Process Return
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
