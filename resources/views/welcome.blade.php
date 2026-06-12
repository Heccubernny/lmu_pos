@extends('layouts.pos')

@section('content')
    @auth
        <div class="w-full">
            
            @php
                // Try to determine an authorized store from the database first.
                // If the 'authorized' column isn't available (older deployments),
                // fall back to the session-based selection for compatibility.
                $authStore = null;
                if (\Illuminate\Support\Facades\Schema::hasColumn('stores', 'authorized')) {
                    // Build query for an active, authorized store.
                    $query = \App\Models\Store::where('authorized', true);
                    
                    // Only filter by status if the column exists.
                    if (\Illuminate\Support\Facades\Schema::hasColumn('stores', 'status')) {
                        $query->where(function($q) { 
                            $q->where('status', '!=', 'inactive')->orWhereNull('status'); 
                        });
                    }
                    
                    $authStore = $query->first();
                } else {
                    // Fallback: if we previously stored an authorized_store in session,
                    // try to resolve that to a Store record.
                    $s = session('authorized_store');
                    if ($s && isset($s['store_id'])) {
                        $authStore = \App\Models\Store::find($s['store_id']);
                    }
                }
            @endphp

            @if(!$authStore)
                <div class="bg-white p-6 rounded-lg text-center text-gray-600">Please ask an administrator to authorize a store or contact support.</div>
            @else
                {{-- Prepare variables and render the POS create page for cashier users --}}
                @php
                    $products = \App\Models\Product::where('status', '!=', 'inactive')
                        ->where('quantity', '>', 0)
                        ->select('item_id as id', 'name', 'unit_price', 'quantity', 'item_number')
                        ->get();

                    $customers = \App\Models\Customer::all(['person_id as id', 'name']);
                @endphp

                @include('sales.create', compact('products', 'customers'))
            @endif
        </div>
            @else
        <div class="text-center py-12">
            <h2 class="text-2xl font-semibold mb-4">Welcome</h2>
            <p class="mb-6">Please <a href="{{ route('login') }}" class="text-indigo-600 underline">log in</a> with an Operator account to start selling.</p>
        </div>
    @endauth
@endsection
