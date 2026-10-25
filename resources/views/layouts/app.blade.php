<!DOCTYPE html>
<html lang="en-US" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Plantify Official — Botanical Marketplace & Digital Care')</title>
    <meta name="description" content="@yield('meta_description', 'Plantify Official is an artisanal botanical nursery and living marketplace featuring Digital Garden care tracking and AI Plant Doctor.')" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Epilogue:wght@500;600;700;800&family=Sometype+Mono:wght@400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col min-h-screen bg-glass text-forest antialiased font-sans selection:bg-ochre selection:text-white">

    <!-- 1. Announcement Bar -->
    <div class="bg-forest text-glass text-center py-2 px-4 text-xs font-mono tracking-wider uppercase border-b border-forest-2">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-ochre" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Complimentary climate-safe delivery on qualified orders
            </span>
            <span class="text-hairline-soft hidden sm:inline">&bull;</span>
            <a href="{{ route('shop') }}" class="text-ochre hover:underline font-semibold tracking-normal normal-case">
                Explore Living Catalog &rarr;
            </a>
        </div>
    </div>

    <!-- 2. Sticky Glasshouse Header -->
    <header class="sticky top-0 z-50 bg-glass/95 backdrop-blur-md border-b border-hairline transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-4">
            
            <!-- Logo / Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 group focus:outline-none" aria-label="Plantify Official — Home">
                <span class="w-10 h-10 rounded-xl bg-forest flex items-center justify-center text-ochre shadow-sm group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21V9" />
                        <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z" />
                        <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z" />
                    </svg>
                </span>
                <span class="font-display font-extrabold text-xl sm:text-2xl tracking-tight text-forest group-hover:text-forest-2">
                    Plantify<span class="text-ochre">.</span>
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8" aria-label="Main Navigation">
                <a href="{{ route('home') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('home') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Home
                </a>
                <a href="{{ route('shop') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('shop*') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Shop Plants
                </a>
                <a href="{{ route('garden.index') }}" class="text-sm font-medium transition-colors hover:text-ochre flex items-center gap-1.5 {{ request()->routeIs('garden*') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    <span class="w-2 h-2 rounded-full bg-stock animate-pulse"></span>
                    My Digital Garden
                </a>
                <a href="{{ route('care.index') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('care*') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    AI Doctor &amp; Care
                </a>
                <a href="{{ route('order.track.search') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('order.track*') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Track Order
                </a>
                <a href="{{ route('about') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('about') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    About Nursery
                </a>
            </nav>

            <!-- User Actions & Cart -->
            <div class="flex items-center gap-2 sm:gap-4">
                
                <!-- User Account Dropdown / Auth Links -->
                @auth
                    <div class="relative group" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-full bg-paper border border-hairline hover:border-ochre text-forest transition-colors">
                            <svg class="w-4 h-4 text-forest-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="7" r="4" />
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            </svg>
                            <span class="max-w-[100px] truncate hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 text-forest-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-paper rounded-xl shadow-xl border border-hairline py-1 z-50 text-xs">
                            <div class="px-4 py-2 border-b border-hairline text-forest-4">
                                Signed in as <strong class="text-forest block truncate">{{ Auth::user()->email }}</strong>
                            </div>
                            <a href="{{ route('garden.index') }}" class="block px-4 py-2 text-forest-2 hover:bg-glass hover:text-forest">
                                My Digital Garden
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-ochre font-semibold hover:bg-glass">
                                    Admin Console
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-hairline mt-1">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sale hover:bg-sale-wash transition-colors">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-semibold px-3 py-1.5 rounded-full text-forest-2 hover:text-forest transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex text-xs font-semibold px-3.5 py-1.5 rounded-full bg-forest text-glass hover:bg-forest-2 transition-colors">
                        Register
                    </a>
                @endauth

                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="relative btn-icon bg-paper border border-hairline hover:border-ochre" aria-label="Shopping Cart">
                    <svg class="w-5 h-5 text-forest" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    @if(($globalCartCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 bg-ochre text-white text-[10px] font-mono font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-sm">
                            {{ $globalCartCount }}
                        </span>
                    @endif
                </a>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden" x-data="{ mobileOpen: false }">
                    <button type="button" @click="mobileOpen = !mobileOpen" class="btn-icon bg-paper border border-hairline" aria-label="Toggle Navigation">
                        <svg class="w-5 h-5 text-forest" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <line x1="3" y1="18" x2="21" y2="18" />
                        </svg>
                    </button>

                    <!-- Mobile Navigation Drawer -->
                    <div x-show="mobileOpen" x-cloak class="fixed inset-0 z-50 bg-forest/50 backdrop-blur-sm" @click="mobileOpen = false"></div>
                    <div x-show="mobileOpen" x-cloak class="fixed top-0 right-0 bottom-0 w-72 bg-paper z-50 p-6 shadow-2xl flex flex-col justify-between overflow-y-auto">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-4 border-b border-hairline">
                                <span class="font-display font-bold text-lg text-forest">Menu</span>
                                <button type="button" @click="mobileOpen = false" class="p-1 text-forest-3 hover:text-forest">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                            </div>

                            <nav class="flex flex-col space-y-3 text-sm font-medium">
                                <a href="{{ route('home') }}" class="py-2 text-forest-2 hover:text-ochre">Home</a>
                                <a href="{{ route('shop') }}" class="py-2 text-forest-2 hover:text-ochre">Shop Plants</a>
                                <a href="{{ route('garden.index') }}" class="py-2 text-forest-2 hover:text-ochre flex items-center justify-between">
                                    <span>My Digital Garden</span>
                                    <span class="w-2 h-2 rounded-full bg-stock"></span>
                                </a>
                                <a href="{{ route('care.index') }}" class="py-2 text-forest-2 hover:text-ochre">AI Doctor &amp; Care</a>
                                <a href="{{ route('order.track.search') }}" class="py-2 text-forest-2 hover:text-ochre">Track Order</a>
                                <a href="{{ route('about') }}" class="py-2 text-forest-2 hover:text-ochre">About Nursery</a>
                            </nav>
                        </div>

                        <div class="pt-6 border-t border-hairline space-y-2">
                            @auth
                                <div class="text-xs text-forest-4 mb-2 truncate">Signed in: <strong>{{ Auth::user()->name }}</strong></div>
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-full text-xs">Admin Console</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost w-full text-xs text-sale">Sign Out</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-ghost w-full text-xs">Sign In</a>
                                <a href="{{ route('register') }}" class="btn btn-primary w-full text-xs">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Global Alert Banners -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if (session('status'))
            <div class="p-4 rounded-xl bg-stock-wash border border-stock/30 text-stock text-sm font-medium flex items-center justify-between mb-4 shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-xl bg-sale-wash border border-sale/30 text-sale text-sm font-medium flex items-center justify-between mb-4 shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-sale-wash border border-sale/30 text-sale text-sm font-medium mb-4 shadow-sm" role="alert">
                <div class="flex items-center gap-2 mb-2 font-semibold">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Please resolve the following issues:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Trust Strip with Botanical SVG Badges -->
    <section class="border-t border-b border-hairline bg-paper py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-forest/5 flex items-center justify-center flex-shrink-0 text-forest">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-sm text-forest">14-Day Growth Guarantee</h4>
                        <p class="text-xs text-forest-3 mt-1 leading-relaxed">Guaranteed healthy arrival or free replacement. Nursery certified.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-forest/5 flex items-center justify-center flex-shrink-0 text-forest">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-sm text-forest">Climate-Safe Packaging</h4>
                        <p class="text-xs text-forest-3 mt-1 leading-relaxed">Biodegradable insulation and root hydration wrap for secure shipping.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-forest/5 flex items-center justify-center flex-shrink-0 text-forest">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-sm text-forest">AI Plant Doctor Companion</h4>
                        <p class="text-xs text-forest-3 mt-1 leading-relaxed">Integrated vision diagnoses &amp; smart weather-aware watering reminders.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-forest/5 flex items-center justify-center flex-shrink-0 text-forest">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-display font-semibold text-sm text-forest">Carbon Footprint Offset</h4>
                        <p class="text-xs text-forest-3 mt-1 leading-relaxed">Track estimated monthly CO2 absorption per specimen in your garden.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Botanical Footer -->
    <footer class="bg-forest text-glass/80 py-12 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-ochre flex items-center justify-center text-forest font-bold">
                            <svg class="w-5 h-5 text-forest" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 21V9" />
                                <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z" />
                                <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z" />
                            </svg>
                        </span>
                        <span class="font-display font-extrabold text-xl text-glass">
                            Plantify<span class="text-ochre">.</span>
                        </span>
                    </div>
                    <p class="text-xs text-glass/70 leading-relaxed">
                        Curated houseplants, premium botanical ceramics, and an intelligent digital care sanctuary designed for plant lovers.
                    </p>
                    <p class="text-xs font-mono text-ochre">
                        Currency: USD ($) &bull; Standard English
                    </p>
                </div>

                <div class="space-y-3">
                    <h5 class="text-xs font-mono uppercase tracking-wider text-glass font-semibold">Storefront</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('shop') }}" class="hover:text-ochre transition-colors">Complete Botanical Catalog</a></li>
                        <li><a href="{{ route('shop', ['difficulty' => 'beginner']) }}" class="hover:text-ochre transition-colors">Low-Maintenance Plants</a></li>
                        <li><a href="{{ route('shop', ['pet_safe' => 1]) }}" class="hover:text-ochre transition-colors">Pet-Friendly Botanicals</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-ochre transition-colors">Shopping Cart</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h5 class="text-xs font-mono uppercase tracking-wider text-glass font-semibold">Customer &amp; Care</h5>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('garden.index') }}" class="hover:text-ochre transition-colors">My Digital Garden</a></li>
                        <li><a href="{{ route('care.index') }}" class="hover:text-ochre transition-colors">AI Plant Doctor Diagnosis</a></li>
                        <li><a href="{{ route('order.track.search') }}" class="hover:text-ochre transition-colors">Track Order Status</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-ochre transition-colors">Nursery Hours &amp; Location</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h5 class="text-xs font-mono uppercase tracking-wider text-glass font-semibold">Physical Nursery Desk</h5>
                    <p class="text-xs text-glass/70 leading-relaxed">
                        Jl. Agrowisata Hijau No. 24, Botanical District, Jakarta Selatan
                    </p>
                    <p class="text-xs text-glass/70 leading-relaxed">
                        Store Pickup: Tuesday &ndash; Sunday, 08:30 &ndash; 17:00
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('order.track.search') }}" class="btn btn-ochre text-xs !py-1.5 !px-3">
                            Track Your Order
                        </a>
                    </div>
                </div>

            </div>

            <div class="mt-12 pt-6 border-t border-forest-2 flex flex-col sm:flex-row items-center justify-between text-xs text-glass/60 gap-4">
                <p>&copy; {{ date('Y') }} Plantify Official Nursery Inc. All rights reserved.</p>
                <p class="font-mono text-[11px]">Clean Architecture &bull; Laravel 13 &bull; Zero-Emoji Compliant</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
