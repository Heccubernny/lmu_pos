@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Returns (Sales Point)') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h3 class="text-base font-semibold text-slate-800 mb-4 sm:mb-0">Recent Returns</h3>
                        <a href="{{ route('admin.returns.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Record Return
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Item Name</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Staff Name</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($returns as $return)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-900">
                                            {{ \Carbon\Carbon::parse($return->date)->format('d M, Y h:i A') }}
                                            <span class="text-xs text-slate-400 block mt-0.5">({{ \Carbon\Carbon::parse($return->date)->diffForHumans() }})</span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-slate-700">{{ $return->itemname }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $return->quantity }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $return->staff_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 whitespace-nowrap text-sm text-slate-500 text-center">No returns found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $returns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
