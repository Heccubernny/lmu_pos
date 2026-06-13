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
        <!-- Premium Google Stitch Inspired Landing Page -->
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            .stitch-grid {
                background-color: #0b0f19;
                background-image: 
                    radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.12) 0%, transparent 50%),
                    radial-gradient(circle at 80% 70%, rgba(236, 72, 153, 0.09) 0%, transparent 50%),
                    radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 0);
                background-size: 100% 100%, 100% 100%, 32px 32px;
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-card {
                background: rgba(17, 24, 39, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
            .glass-card-hover {
                transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .glass-card-hover:hover {
                transform: translateY(-5px);
                border-color: rgba(99, 102, 241, 0.3);
                box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), 0 0 50px -10px rgba(99, 102, 241, 0.15);
            }
            .gradient-text {
                background: linear-gradient(135deg, #818cf8 0%, #c084fc 50%, #f472b6 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .float-slow {
                animation: float-slow 8s ease-in-out infinite;
            }
            .float-mid {
                animation: float-mid 6s ease-in-out infinite;
            }
            .pulse-subtle {
                animation: pulse-subtle 4s ease-in-out infinite;
            }
            @keyframes float-slow {
                0%, 100% { transform: translateY(0px) rotate(0.5deg); }
                50% { transform: translateY(-12px) rotate(-0.5deg); }
            }
            @keyframes float-mid {
                0%, 100% { transform: translateY(0px) rotate(-0.5deg); }
                50% { transform: translateY(-16px) rotate(0.5deg); }
            }
            @keyframes pulse-subtle {
                0%, 100% { opacity: 0.85; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.02); }
            }
        </style>

        <div class="min-h-screen w-full stitch-grid flex flex-col justify-between text-slate-100 overflow-hidden relative">
            <!-- Background Graphic Lines -->
            <div class="absolute inset-0 pointer-events-none opacity-20 z-0">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <!-- Elegant intersecting wave lines representing data routing pathways -->
                    <path d="M-100,200 Q300,50 700,400 T1500,200" fill="none" stroke="url(#indigo-grad)" stroke-width="2" />
                    <path d="M-50,600 Q400,800 900,450 T1800,700" fill="none" stroke="url(#rose-grad)" stroke-width="1.5" />
                    <defs>
                        <linearGradient id="indigo-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#6366f1" />
                            <stop offset="100%" stop-color="#4f46e5" stop-opacity="0" />
                        </linearGradient>
                        <linearGradient id="rose-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#ec4899" />
                            <stop offset="100%" stop-color="#db2777" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <!-- Top Navigation -->
            <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between z-10 relative">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600/25 border border-indigo-500/30 rounded-xl p-2 shadow-lg backdrop-blur">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-white">T-Conn <span class="text-indigo-400 font-medium">POS</span></span>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition bg-white/5 border border-white/10 hover:bg-white/10 rounded-full px-5 py-2">
                        Fast Sign In
                    </a>
                </div>
            </header>

            <!-- Main Content: Split Layout -->
            <main class="w-full max-w-7xl mx-auto px-6 py-12 flex-1 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center z-10 relative">
                
                <!-- Left Side: Hero Text -->
                <div class="lg:col-span-6 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 rounded-full px-4 py-1.5 text-xs font-semibold tracking-wide uppercase shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                        Next-Generation Campus Retail
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-none text-white">
                        Streamline Your <br class="hidden md:inline">
                        <span class="gradient-text">Campus Commerce</span>
                    </h1>

                    <p class="text-slate-400 text-base md:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed font-light">
                        A unified point-of-sale system built for hostel halls, buttery stores, and campus vendors. Fully integrated with automated stock tracking, auditor verification, and instant Moniepoint terminal payments.
                    </p>

                    <!-- Interactive Portal Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        <a href="{{ route('login') }}" class="group relative px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-500/20 transition-all flex items-center justify-center gap-3">
                            <span>Operator Desk Login</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.login') }}" class="px-8 py-4 bg-white/5 border border-white/10 hover:bg-white/10 text-white hover:text-slate-100 rounded-2xl font-bold shadow-md transition-all flex items-center justify-center gap-3">
                            <span>Management Console</span>
                        </a>
                    </div>
                </div>

                <!-- Right Side: Interactive Canvas with Mock Cards -->
                <div class="lg:col-span-6 relative h-[450px] w-full flex items-center justify-center">
                    
                    <!-- Dotted circle background element -->
                    <div class="absolute w-80 h-80 rounded-full border border-dashed border-white/5 flex items-center justify-center z-0 pulse-subtle">
                        <div class="w-60 h-60 rounded-full border border-dashed border-indigo-500/10"></div>
                    </div>

                    <!-- Floating Card 1: Sales Summary Tracker -->
                    <div class="absolute top-8 left-4 md:left-12 w-64 glass-card p-5 rounded-2xl shadow-2xl z-20 float-slow glass-card-hover">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-semibold text-indigo-400 uppercase tracking-wide">Sales Analytics</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <h4 class="text-xl font-bold text-white">₦240,500.00</h4>
                        <p class="text-xs text-slate-400 mt-1">Today's Transactions</p>
                        <!-- Mock sparkline using SVG -->
                        <div class="mt-4 h-12 w-full">
                            <svg class="w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
                                <path d="M0,25 Q15,5 30,20 T60,8 T90,22 T100,5" fill="none" stroke="#818cf8" stroke-width="2.5" stroke-linecap="round" />
                                <path d="M0,25 Q15,5 30,20 T60,8 T90,22 T100,5 L100,30 L0,30 Z" fill="url(#sparkline-grad)" opacity="0.15" />
                                <defs>
                                    <linearGradient id="sparkline-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="#818cf8" />
                                        <stop offset="100%" stop-color="#818cf8" stop-opacity="0" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>

                    <!-- Floating Card 2: Store / Hall Allocation -->
                    <div class="absolute bottom-8 right-4 md:right-12 w-60 glass-card p-5 rounded-2xl shadow-2xl z-20 float-mid glass-card-hover">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="bg-rose-500/20 border border-rose-500/30 rounded-xl p-2">
                                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-300">Male Hostel 1</h4>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-wide mt-1">Authorized</span>
                            </div>
                        </div>
                        <div class="space-y-2 mt-4">
                            <div class="flex justify-between text-xs text-slate-400">
                                <span>Buttery Stock</span>
                                <span class="text-white font-medium">92%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Card 3: POS Terminal Connection -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-56 glass-card p-4 rounded-xl shadow-xl z-10 text-center space-y-3 glass-card-hover">
                        <div class="bg-indigo-600/30 border border-indigo-500/40 rounded-full p-2.5 w-12 h-12 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-white uppercase tracking-wider">Moniepoint Link</h5>
                            <p class="text-[11px] text-emerald-400 font-semibold mt-1">MP-TERM-492 Connected</p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Bottom Footer -->
            <footer class="w-full max-w-7xl mx-auto px-6 py-6 border-t border-white/5 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 z-10 relative">
                <div>&copy; 2026 T-Conn POS System. All rights reserved.</div>
                <div class="flex gap-6 mt-3 md:mt-0">
                    <a href="#" class="hover:text-slate-300 transition">Terms of Service</a>
                    <a href="#" class="hover:text-slate-300 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-300 transition">Support Center</a>
                </div>
            </footer>
        </div>
    @endauth
@endsection
