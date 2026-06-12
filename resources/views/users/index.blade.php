@extends('layouts.app')

@section('content')
    @section('header')
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Staff Management') }}
        </h2>
    @endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-200 sm:rounded-xl">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-base font-semibold text-slate-800">Manage Employees & Users</h3>
                        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm text-sm font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add Staff
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 mb-6 rounded-lg text-sm flex items-center">
                            <svg class="h-5 w-5 mr-3 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-100 text-rose-700 p-4 mb-6 rounded-lg text-sm flex items-center">
                            <svg class="h-5 w-5 mr-3 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Email (Login)</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">System Role</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Assigned Store(s)</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Joined Date</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right font-semibold text-slate-500">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900 flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center mr-3 text-xs ring-2 ring-white">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            {{ $user->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $user->email }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                                            @if($user->role == 'administrator')
                                                <span class="inline-flex items-center rounded-md bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-700 border border-violet-100">{{ ucfirst($user->role) }}</span>
                                            @elseif($user->role == 'supervisor')
                                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100">{{ ucfirst($user->role) }}</span>
                                            @else
                                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200">{{ ucfirst($user->role) }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 font-medium">
                                            @if(strtolower($user->role) === 'sales representative' || strtolower($user->role) === 'operator')
                                                {{ $user->store ? $user->store->name : 'N/A' }}
                                            @else
                                                @if($user->stores->count() > 0)
                                                    <span class="inline-flex items-center rounded-md bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 border border-indigo-100">
                                                        {{ $user->stores->pluck('name')->join(', ') }}
                                                    </span>
                                                @else
                                                    <span class="text-slate-400">All / Global</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-4 transition-colors">Edit</a>
                                            @if(auth()->id() != $user->id)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline swal-delete-form" data-title="Delete Staff Member" data-text="Are you sure you want to delete {{ $user->name }}? This action cannot be undone.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900 transition-colors swal-delete-btn">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 whitespace-nowrap text-sm text-slate-500 text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.swal-delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = btn.closest('.swal-delete-form');
        Swal.fire({
            title: form.dataset.title || 'Are you sure?',
            text: form.dataset.text || 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
