<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register First Administrator - T-Conn POS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-color: #e2e8f0;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="bg-pattern antialiased text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <div class="flex items-center gap-2">
                <div class="bg-indigo-600 rounded-lg p-2 shadow-lg shadow-indigo-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-3xl font-bold tracking-tight text-slate-900">T-Conn <span class="text-indigo-600 font-medium">POS</span></span>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-violet-500"></div>
            
            <div class="p-8">
                <h2 class="text-xl font-semibold text-slate-800 mb-2">First-Launch Setup</h2>
                <p class="text-slate-500 text-sm mb-6">Create the primary protected IT Administrator account to configure the system.</p>

                @if(session('error'))
                    <div class="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register-admin.submit') }}" class="space-y-4">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="David Okunola">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="admin@admin.com">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone number</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="08012345678">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="••••••••">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="••••••••">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 mt-6 rounded-lg shadow-md text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Register & Setup System
                    </button>
                </form>
            </div>
            
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Upon submission, database will be configured and initialized.</p>
            </div>
        </div>
    </div>

</body>
</html>
