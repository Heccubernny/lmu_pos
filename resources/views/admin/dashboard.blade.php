@extends('layouts.app')

@section('header', 'Administration')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <h3 class="text-lg font-semibold mb-3">Stores</h3>
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="text-left text-sm text-slate-600">
                        <th class="p-2">ID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Authorized</th>
                        <th class="p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stores as $store)
                        <tr class="border-t">
                            <td class="p-2 text-sm">{{ $store->id }}</td>
                            <td class="p-2 text-sm">{{ $store->name ?? $store->host }}</td>
                            <td class="p-2 text-sm">{{ $store->status ?? 'active' }}</td>
                            <td class="p-2 text-sm">{{ isset($store->authorized) ? ($store->authorized ? 'Yes' : 'No') : 'N/A' }}</td>
                            <td class="p-2 text-sm flex gap-2">
                                <!-- Toggle status (active/inactive) -->
                                <form method="POST" action="{{ url('/admin/store/' . $store->id . '/toggle-status') }}">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ ($store->status ?? 'active') === 'active' ? 'inactive' : 'active' }}">
                                    <button class="px-2 py-1 rounded text-sm bg-slate-100 hover:bg-slate-200">Toggle Status</button>
                                </form>

                                <!-- Toggle authorized (if column exists) -->
                                @if(\Illuminate\Support\Facades\Schema::hasColumn('stores', 'authorized'))
                                    <form method="POST" action="{{ url('/admin/store/' . $store->id . '/authorize') }}">
                                        @csrf
                                        <input type="hidden" name="authorized" value="{{ $store->authorized ? 0 : 1 }}">
                                        <button class="px-2 py-1 rounded text-sm bg-indigo-600 text-white hover:bg-indigo-700">{{ $store->authorized ? 'Deauthorize' : 'Authorize' }}</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Authorize N/A (run migrations)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>

        <div class="bg-white p-4 rounded shadow-sm">
            <h2 class="text-lg font-semibold mb-3">Connect to remote store</h2>
            <form method="POST" action="/admin/connect" class="flex items-center gap-3">
                @csrf
                <select name="store_id" required class="border rounded px-3 py-2">
                    <option value="">Select Store</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name ?? $store->host }}</option>
                    @endforeach
                </select>
                <button class="px-3 py-2 bg-indigo-600 text-white rounded">Connect</button>
            </form>

            @if(isset($users))
                <h3 class="mt-4 font-medium">Users from {{ $selectedStore->host }}</h3>
                <div class="mt-2">
                    <table class="w-full text-sm table-auto">
                        <thead>
                            <tr class="text-left text-slate-600">
                                <th class="p-2">ID</th>
                                <th class="p-2">Name</th>
                                <th class="p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-t">
                                    <form method="POST" action="/admin/update-user" class="flex items-center gap-3 p-2">
                                        @csrf
                                        <td class="p-2">{{ $user->id }}</td>
                                        <td class="p-2">
                                            <input type="text" name="name" value="{{ $user->name }}" class="border rounded px-2 py-1">
                                        </td>
                                        <td class="p-2">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="store_id" value="{{ $selectedStore->id }}">
                                            <button class="px-2 py-1 bg-indigo-600 text-white rounded">Update</button>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection