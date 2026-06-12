@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Supplier History: {{ $supplier->company_name }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                    <span class="text-sm text-gray-500 font-medium uppercase tracking-wider">Debit (Total Paid)</span>
                    <span class="text-2xl font-bold text-emerald-600 mt-2">₦{{ number_format($debit, 2) }}</span>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                    <span class="text-sm text-gray-500 font-medium uppercase tracking-wider">Credit (Total Owed)</span>
                    <span class="text-2xl font-bold text-rose-500 mt-2">₦{{ number_format($credit, 2) }}</span>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                    <span class="text-sm text-gray-500 font-medium uppercase tracking-wider">Current Balance Due</span>
                    <span class="text-2xl font-bold {{ $balance > 0 ? 'text-rose-600' : 'text-slate-700' }} mt-2">₦{{ number_format($balance, 2) }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters Form -->
                    <form method="GET" action="{{ auth()->user()->isSupervisor() ? route('supervisor.suppliers.show', $supplier) : route('admin.suppliers.show', $supplier) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 items-end">
                        <div>
                            <label for="payment_status" class="block text-xs font-semibold uppercase text-gray-500 mb-1">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="w-full border rounded-lg px-3 py-2 text-sm bg-slate-50 border-slate-200">
                                <option value="">All Statuses</option>
                                <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Credit" {{ request('payment_status') == 'Credit' ? 'selected' : '' }}>Credit</option>
                            </select>
                        </div>
                        <div>
                            <label for="start_date" class="block text-xs font-semibold uppercase text-gray-500 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-slate-50 border-slate-200">
                        </div>
                        <div>
                            <label for="end_date" class="block text-xs font-semibold uppercase text-gray-500 mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-slate-50 border-slate-200">
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-colors">
                                Filter
                            </button>
                             <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.suppliers.show', $supplier) : route('admin.suppliers.show', $supplier) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2 px-4 rounded-lg text-sm transition-colors text-center">
                                Reset
                            </a>
                        </div>
                    </form>

                    <!-- History Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($receipts as $receipt)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $receipt->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $receipt->product->name ?? 'Deleted Product' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $receipt->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ₦{{ number_format($receipt->unit_cost, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            ₦{{ number_format($receipt->total_cost, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $receipt->payment_status === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $receipt->payment_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $receipt->supervisor->name ?? 'System' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No supply history records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $receipts->appends(request()->query())->links() }}
                    </div>

                    <div class="mt-6 flex justify-start">
                        <a href="{{ auth()->user()->isSupervisor() ? route('supervisor.suppliers.index') : route('admin.suppliers.index') }}" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Back to Suppliers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
