<aside class="w-64 flex-shrink-0 flex flex-col bg-slate-900 shadow-xl transition-all duration-300 z-20 h-full">
    <!-- Branding -->
    <div class="h-16 flex items-center justify-center border-b border-white/10 px-4">
        <a href="{{ auth()->check() && auth()->user()->isSalesRep() ? route('cashier.sales.create') : (auth()->user()->isSupervisor() ? route('supervisor.stock.allocate.form') : (auth()->user()->isAuditor() ? route('auditor.dashboard') : (auth()->user()->isAccountant() ? route('accountant.dashboard') : route('admin.dashboard')))) }}" class="flex items-center gap-2">
            <div class="bg-indigo-500 rounded p-1.5 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <span class="text-white text-xl font-bold tracking-tight">T-Conn <span class="text-indigo-400 font-medium">POS</span></span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        @php
            $user = auth()->user();
        @endphp

        <!-- Cashier / Sales Representative Menu -->
        @if($user && $user->isSalesRep())
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Cashier Menu</p>

            <a href="{{ route('cashier.sales.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('cashier.sales.create') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('cashier.sales.create') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Point of Sale</span>
            </a>

            <a href="{{ route('cashier.sales.history') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('cashier.sales.history') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('cashier.sales.history') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>My Sales & Analytics</span>
            </a>
        @endif

        <!-- Supervisor Scoped Menu -->
        @if($user && $user->isSupervisor())
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Supervisor Menu</p>

            <a href="{{ route('supervisor.stock.receive.form') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('supervisor.stock.receive.form') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('supervisor.stock.receive.form') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Receive Supplier Stock</span>
            </a>

            <a href="{{ route('supervisor.stock.allocate.form') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('supervisor.stock.allocate.form') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('supervisor.stock.allocate.form') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <span>Allocate Hostel Stock</span>
            </a>

            <a href="{{ route('supervisor.requisitions.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('supervisor.requisitions.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('supervisor.requisitions.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Store Requisitions</span>
            </a>

            <a href="{{ route('supervisor.damaged-expired.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('supervisor.damaged-expired.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('supervisor.damaged-expired.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Write-Off Reports</span>
            </a>

            <a href="{{ route('supervisor.suppliers.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('supervisor.suppliers.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('supervisor.suppliers.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>My Scoped Suppliers</span>
            </a>

            <a href="{{ route('supervisor.reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('supervisor.reports.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('supervisor.reports.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <span>Analytics & Reports</span>
            </a>
        @endif

        <!-- Auditor Scoped Menu -->
        @if($user && $user->isAuditor())
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Auditor Menu</p>

            <a href="{{ route('auditor.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('auditor.dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('auditor.dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Verification Portal</span>
            </a>

            <a href="{{ route('auditor.damaged-expired.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('auditor.damaged-expired.index') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('auditor.damaged-expired.index') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Write-Off Requests</span>
            </a>

            <a href="{{ route('auditor.sales.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('auditor.sales.index') || request()->routeIs('auditor.sales.show') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('auditor.sales.index') || request()->routeIs('auditor.sales.show') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Recent Transactions</span>
            </a>

            <a href="{{ route('auditor.reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('auditor.reports.index') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('auditor.reports.index') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <span>System Analytics</span>
            </a>
        @endif

        <!-- Accountant Scoped Menu -->
        @if($user && $user->isAccountant())
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Accountant Menu</p>

            <a href="{{ route('accountant.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('accountant.dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('accountant.dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <span>Financial Portal</span>
            </a>

            <a href="{{ route('accountant.sales.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('accountant.sales.index') || request()->routeIs('accountant.sales.show') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('accountant.sales.index') || request()->routeIs('accountant.sales.show') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Transaction History</span>
            </a>

            <a href="{{ route('accountant.damaged-expired.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('accountant.damaged-expired.index') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('accountant.damaged-expired.index') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Write-Off Logs</span>
            </a>

            <a href="{{ route('accountant.reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('accountant.reports.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('accountant.reports.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <span>Financial Reports</span>
            </a>
        @endif

        <!-- Administrator & Head Menu -->
        @if($user && ($user->isITAdmin() || $user->isHead()))
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Overview</p>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <div class="h-4"></div>
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sales</p>

            <a href="{{ route('admin.sales.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.sales.create') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.sales.create') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Point of Sale</span>
            </a>

            <a href="{{ route('admin.sales.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.sales.index') || request()->routeIs('admin.sales.show') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.sales.index') || request()->routeIs('admin.sales.show') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Recent Sales</span>
            </a>

            <a href="{{ route('admin.moniepoint.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.moniepoint.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.moniepoint.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Moniepoint Logs</span>
            </a>

            <div class="h-4"></div>
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Catalog</p>

            <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.products.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span>Inventory</span>
            </a>

            <div class="h-4"></div>
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Operations</p>

            <a href="{{ route('admin.requisitions.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.requisitions.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.requisitions.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Requisitions</span>
            </a>

            <a href="{{ route('admin.returns.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.returns.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.returns.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                </svg>
                <span>Returns</span>
            </a>

            <a href="{{ route('admin.bad-damages.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.bad-damages.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.bad-damages.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                 </svg>
                 <span>Bad & Damages</span>
             </a>

            <a href="{{ route('admin.damaged-expired.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.damaged-expired.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.damaged-expired.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Damaged & Expired</span>
            </a>

            <div class="h-4"></div>
            <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Reports</p>

            <a href="{{ route('admin.reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                <span>Analytics & Reports</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.suppliers.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.suppliers.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Categories</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.customers.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span>Customers</span>
            </a>

            @if($user->isITAdmin())
                <div class="h-4"></div>
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Audit & Finance</p>

                <a href="{{ route('auditor.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('auditor.dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <svg class="w-5 h-5 {{ request()->routeIs('auditor.dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Auditor Dashboard</span>
                </a>

                <a href="{{ route('accountant.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('accountant.dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <svg class="w-5 h-5 {{ request()->routeIs('accountant.dashboard') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>Accountant Portal</span>
                </a>
            @endif

            <div class="h-4"></div>
            <div class="pt-4 border-t border-white/5">
                <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Admin</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Staff Management</span>
                </a>
                <a href="{{ route('admin.stores.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.stores.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.stores.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>Store Management</span>
                </a>
                <a href="{{ route('admin.departments.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.departments.*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20 shadow-inner' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.departments.*') ? 'text-indigo-400' : 'text-slate-400 group-hover:text-slate-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Department Management</span>
                </a>
            </div>
        @endif
    </nav>
</aside>
