@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Add New Customer') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900">
                    <form action="{{ route('admin.customers.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Customer Name</label>
                                <input type="text" name="name" id="name" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors" required>
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                                <input type="text" name="address" id="address" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                                <input type="email" name="email" id="email" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-colors">
                            </div>

                            <div class="flex items-center justify-end pt-4 border-t border-slate-100">
                                <a href="{{ route('admin.customers.index') }}" class="text-sm text-slate-500 hover:text-slate-700 mr-4 transition-colors">Cancel</a>
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md text-sm font-semibold">
                                    Save Customer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
