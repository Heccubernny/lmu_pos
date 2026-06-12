@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($user) ? __('Edit Staff Member') : __('Add Staff Member') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address // Login ID</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">System Role</label>
                                <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($roles as $r)
                                        <option value="{{ $r }}" {{ (strtolower(old('role', $user->role ?? '')) == strtolower($r)) ? 'selected' : '' }}>
                                            {{ $r }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Assigned Store(s) -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Store(s)</label>
                                
                                <!-- Single Store Selection (for Sales Representative) -->
                                <div id="single-store-container">
                                    <select name="store_id" id="store_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Global / Select Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ (old('store_id', $user->store_id ?? '') == $store->id) ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Multiple Store Selection (for other roles) -->
                                <div id="multiple-store-container" class="hidden mt-2 border border-gray-200 rounded-lg p-4 bg-gray-50 max-h-48 overflow-y-auto shadow-inner">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($stores as $store)
                                            <label class="inline-flex items-center p-2 rounded hover:bg-white border border-transparent hover:border-gray-200 transition cursor-pointer">
                                                <input type="checkbox" name="store_ids[]" value="{{ $store->id }}" 
                                                    {{ (in_array($store->id, old('store_ids', isset($user) ? $user->stores->pluck('id')->toArray() : []))) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700 font-medium">{{ $store->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2" id="store-help-text">Select store assignment for this user.</p>
                                @error('store_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                @error('store_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="password" {{ isset($user) ? '' : 'required' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @if(isset($user))
                                    <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password.</p>
                                @endif
                                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" {{ isset($user) ? '' : 'required' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 border-t border-gray-200 pt-5">
                            <a href="{{ route('admin.users.index') }}" class="mr-3 px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Save User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const singleStoreContainer = document.getElementById('single-store-container');
            const multipleStoreContainer = document.getElementById('multiple-store-container');
            const storeHelpText = document.getElementById('store-help-text');
            
            function toggleStoreSelection() {
                const role = roleSelect.value.toLowerCase();
                // Sales Representative (or operator) is assigned to a single store
                if (role === 'sales representative' || role === 'operator') {
                    singleStoreContainer.classList.remove('hidden');
                    multipleStoreContainer.classList.add('hidden');
                    storeHelpText.textContent = 'Sales Representatives can only be assigned to a single store.';
                    document.getElementById('store_id').disabled = false;
                    multipleStoreContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.disabled = true);
                } else {
                    singleStoreContainer.classList.add('hidden');
                    multipleStoreContainer.classList.remove('hidden');
                    storeHelpText.textContent = 'This role can be assigned to multiple stores (check all that apply).';
                    document.getElementById('store_id').disabled = true;
                    multipleStoreContainer.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.disabled = false);
                }
            }

            if (roleSelect) {
                roleSelect.addEventListener('change', toggleStoreSelection);
                toggleStoreSelection(); // Run on load
            }
        });
    </script>
    @endpush
@endsection
