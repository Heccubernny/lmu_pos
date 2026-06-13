<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            body { 
                font-family: 'Inter', sans-serif; 
                background-color: #f8fafc; /* Tailwind slate-50 */
            }
            .saas-shadow {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 overflow-hidden">
        <div class="h-screen flex bg-slate-50">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 lg:px-8 z-10 saas-shadow">
                    <!-- Mobile Hamburger -->
                    <button class="md:hidden text-slate-500 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Page Heading (if set) -->
                    <div class="font-semibold text-xl text-slate-800 tracking-tight">
                        @yield('header')
                    </div>

                    <!-- Right side top header (Notifications & User) -->
                    <div class="flex items-center space-x-6">
                       @auth
                           @php
                               $userStores = auth()->user()->stores;
                               $activeStoreId = session('authorized_store')['store_id'] ?? auth()->user()->store_id;
                           @endphp
                           @if($userStores->count() > 1)
                               <div class="relative inline-block text-left mr-2">
                                   <form action="{{ url('/authorize-store') }}" method="POST" id="header-store-switch-form" class="m-0">
                                       @csrf
                                       <select name="store_id" onchange="this.form.submit()" class="text-xs font-semibold bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2">
                                           @foreach($userStores as $userStore)
                                               <option value="{{ $userStore->id }}" {{ $userStore->id == $activeStoreId ? 'selected' : '' }}>
                                                   {{ $userStore->name }}
                                               </option>
                                           @endforeach
                                       </select>
                                   </form>
                               </div>
                           @elseif($userStores->count() == 1 || $activeStoreId)
                               @php
                                   $singleStore = $userStores->first() ?? \App\Models\Store::find($activeStoreId);
                               @endphp
                               @if($singleStore)
                                   <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 border border-slate-200 mr-2">
                                       <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                       </svg>
                                       {{ $singleStore->name }}
                                   </span>
                               @endif
                           @endif
                       @endauth

                       <button class="relative text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                           <span class="sr-only">Notifications</span>
                           <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-400"></span>
                       </button>

                       <!-- User Menu -->
                       <div class="relative flex items-center gap-3">
                            <div class="hidden md:block text-right">
                                <p class="text-sm font-medium text-slate-700 leading-none">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                <p class="text-xs text-slate-500 mt-1 capitalize">{{ auth()->user()->role ?? 'Administrator' }}</p>
                            </div>
                            <div class="h-9 w-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            </div>
                            <!-- Simple Logout Button for now -->
                            <form method="POST" action="{{ route('logout') }}" class="ml-2" id="logout-form">
                                @csrf
                                <button type="submit" id="logout-button" class="text-xs text-slate-500 hover:text-indigo-600 font-medium tracking-wide flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </form>
                       </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-slate-50">
                    @yield('content')
                </main>
            </div>
        </div>
        <!-- Global flash/toast area -->
        <div aria-live="polite" class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 z-50 print:hidden">
            <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
                @if(session('success'))
                    <div id="flash-success" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Success</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ session('success') }}</p>
                                </div>
                                <div class="ml-4 flex-shrink-0 self-start">
                                    <button type="button" onclick="document.getElementById('flash-success')?.remove()" class="inline-flex text-gray-400 hover:text-gray-600">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div id="flash-error" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
                        <div class="p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Error</p>
                                    <p class="mt-1 text-sm text-gray-500">{{ session('error') }}</p>
                                </div>
                                <div class="ml-4 flex-shrink-0 self-start">
                                    <button type="button" onclick="document.getElementById('flash-error')?.remove()" class="inline-flex text-gray-400 hover:text-gray-600">
                                        <span class="sr-only">Close</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @stack('scripts')

        <script>
            // Auto-dismiss flash messages after 4 seconds
            setTimeout(() => {
                document.getElementById('flash-success')?.remove();
                document.getElementById('flash-error')?.remove();
            }, 4000);
        </script>
        <script>
            // When logout is clicked, save the user's role to session via a small POST,
            // then submit the logout form so the server can redirect to the appropriate login page.
            (function(){
                const btn = document.getElementById('logout-button');
                const form = document.getElementById('logout-form');
                if (!btn || !form) return;

                btn.addEventListener('click', function (ev) {
                    ev.preventDefault();
                    Swal.fire({
                        title: 'Confirm Logout',
                        text: 'Are you sure you want to log out?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, Logout',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const role = '{{ strtolower(auth()->user()->role ?? '') }}';
                            fetch('{{ url('/_store-role') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ role })
                            }).finally(() => {
                                form.submit();
                            });
                        }
                    });
                });
            })();
        </script>
    </body>
</html>
