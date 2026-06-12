<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - T-Conn POS</title>

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

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-violet-500"></div>
            
            <div class="p-8">
                <h2 class="text-xl font-semibold text-slate-800 mb-2">Welcome back</h2>
                <p class="text-slate-500 text-sm mb-6">Enter your credentials to access your account.</p>

                @if(session('success'))
                    <div class="mb-4 bg-emerald-50 text-emerald-600 p-3 rounded-lg text-sm border border-emerald-100">
                        {{ session('success') }}
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

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="staff@tconn.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1 flex justify-between">
                            <span>Password</span>
                        </label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 text-slate-900 transition-all outline-none"
                            placeholder="••••••••">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 transition-colors">
                        <label for="remember" class="ml-2 block text-sm text-slate-600">
                            Remember me for 30 days
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 rounded-lg shadow-md text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Sign in to account
                    </button>
                </form>
            </div>
            
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">Secure staff portal for T-Conn POS system.</p>
                <!-- <p class="text-xs mt-2"><a href="{{ route('admin.login') }}" class="text-indigo-600 hover:underline">Admin login</a></p> -->
            </div>
        </div>
    </div>

</body>
</html>
