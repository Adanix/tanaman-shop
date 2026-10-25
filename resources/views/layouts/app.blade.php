<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Plantify Official — Marketplace & Manajemen Perawatan Tanaman')</title>
    <meta name="description" content="Plantify Official adalah marketplace tanaman hias modern dengan fitur manajemen kebun digital (My Digital Garden) dan AI Plant Doctor." />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Epilogue:wght@500;600;700;800&family=Sometype+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-glass text-forest antialiased font-sans selection:bg-ochre selection:text-white">

    <!-- 1. Announcement Bar -->
    <div class="bg-forest text-glass text-center py-2 px-4 text-xs font-mono tracking-wider uppercase border-b border-forest-2">
        <p class="max-w-7xl mx-auto flex items-center justify-center gap-2 flex-wrap">
            <span>Gratis Pengiriman Wilayah Jawa-Bali untuk Pesanan di Atas Rp 250.000</span>
            <span class="text-hairline-soft hidden sm:inline">•</span>
            <a href="{{ route('shop') }}" class="text-ochre hover:underline font-semibold tracking-normal normal-case">Belanja Sekarang &rarr;</a>
        </p>
    </div>

    <!-- 2. Sticky Glasshouse Header -->
    <header class="sticky top-0 z-50 bg-glass/90 backdrop-blur-md border-b border-hairline transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-4">
            
            <!-- Logo / Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 group focus:outline-none" aria-label="Plantify Official — Beranda">
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
            <nav class="hidden md:flex items-center gap-6 lg:gap-8" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('home') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Beranda
                </a>
                <a href="{{ route('shop') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('shop*') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Katalog Tanaman
                </a>
                <a href="{{ route('garden') }}" class="text-sm font-medium transition-colors hover:text-ochre flex items-center gap-1.5 {{ request()->routeIs('garden') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    <span class="w-2 h-2 rounded-full bg-stock animate-pulse"></span>
                    My Garden
                </a>
                <a href="{{ route('care') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('care') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Panduan Rawat & AI
                </a>
                <a href="{{ route('about') }}" class="text-sm font-medium transition-colors hover:text-ochre {{ request()->routeIs('about') ? 'text-forest font-bold border-b-2 border-ochre pb-1' : 'text-forest-2' }}">
                    Tentang Kami
                </a>
            </nav>

            <!-- Header Tools / Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Search Box (Desktop) -->
                <div class="relative hidden lg:block w-52 xl:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-forest-3">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    </span>
                    <input type="search" placeholder="Cari tanaman..." class="w-full h-10 pl-9 pr-4 text-xs bg-paper border border-hairline rounded-full focus:outline-none focus:border-ochre focus:ring-1 focus:ring-ochre transition-all text-forest placeholder:text-forest-4" />
                </div>

                <!-- Account Button -->
                <button type="button" class="btn-icon min-h-[44px]" data-toast="Fitur Akun Terintegrasi dengan Laravel Breeze & Livewire 3" aria-label="Akun Saya">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="3.4"/>
                        <path d="M5 20a7 7 0 0 1 14 0"/>
                    </svg>
                </button>

                <!-- Basket / Cart Drawer Button -->
                <button type="button" class="btn-icon relative min-h-[44px]" data-action="open-drawer" aria-label="Buka Keranjang Belanja">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 7h12l-1 13H7L6 7Z"/>
                        <path d="M9 7a3 3 0 0 1 6 0"/>
                    </svg>
                    <span data-cart-count hidden class="absolute top-1.5 right-1.5 min-w-[20px] h-5 px-1.5 rounded-full bg-ochre text-white font-mono text-[10px] font-bold flex items-center justify-center border-2 border-glass">
                        0
                    </span>
                </button>

                <!-- Mobile Burger Button -->
                <button type="button" class="burger md:hidden btn-icon min-h-[44px]" aria-expanded="false" aria-label="Buka Menu Navigasi">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer Dropdown -->
        <div class="mobile-nav hidden md:hidden bg-glass border-b border-hairline px-6 py-5 shadow-lg" data-open="false">
            <nav class="flex flex-col gap-3 font-medium">
                <a href="{{ route('home') }}" class="py-2.5 px-3 rounded-xl hover:bg-paper flex items-center justify-between text-forest {{ request()->routeIs('home') ? 'bg-paper font-bold text-ochre-deep' : '' }}">
                    <span>Beranda</span>
                    <span class="text-forest-4">&rarr;</span>
                </a>
                <a href="{{ route('shop') }}" class="py-2.5 px-3 rounded-xl hover:bg-paper flex items-center justify-between text-forest {{ request()->routeIs('shop*') ? 'bg-paper font-bold text-ochre-deep' : '' }}">
                    <span>Katalog Tanaman</span>
                    <span class="text-forest-4">&rarr;</span>
                </a>
                <a href="{{ route('garden') }}" class="py-2.5 px-3 rounded-xl hover:bg-paper flex items-center justify-between text-forest {{ request()->routeIs('garden') ? 'bg-paper font-bold text-ochre-deep' : '' }}">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-stock animate-pulse"></span>
                        <span>My Digital Garden</span>
                    </div>
                    <span class="text-forest-4">&rarr;</span>
                </a>
                <a href="{{ route('care') }}" class="py-2.5 px-3 rounded-xl hover:bg-paper flex items-center justify-between text-forest {{ request()->routeIs('care') ? 'bg-paper font-bold text-ochre-deep' : '' }}">
                    <span>Panduan Rawat & AI Doctor</span>
                    <span class="text-forest-4">&rarr;</span>
                </a>
                <a href="{{ route('about') }}" class="py-2.5 px-3 rounded-xl hover:bg-paper flex items-center justify-between text-forest {{ request()->routeIs('about') ? 'bg-paper font-bold text-ochre-deep' : '' }}">
                    <span>Tentang Nursery Plantify</span>
                    <span class="text-forest-4">&rarr;</span>
                </a>
            </nav>
            <div class="mt-4 pt-4 border-t border-hairline">
                <input type="search" placeholder="Cari tanaman hias..." class="w-full h-11 px-4 text-sm bg-paper border border-hairline rounded-full focus:outline-none focus:border-ochre text-forest" />
            </div>
        </div>
    </header>

    <!-- 3. Main Content Slot -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 4. Trust Strip -->
    <section class="border-t border-hairline bg-paper/60 py-12 px-4 sm:px-6 lg:px-8 mt-16">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-stock-wash text-stock flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3l7 3v6c0 4.4-3 8-7 9-4-1-7-4.6-7-9V6l7-3Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-forest text-base">Garansi Hidup 30 Hari</h4>
                    <p class="text-xs text-forest-3 mt-1 leading-relaxed">Jika tanaman mati dalam 30 hari pertama, kami ganti gratis tanpa syarat rumit.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-ochre-wash text-ochre flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="14" height="11" rx="2"/>
                        <path d="M16 10h3l3 3v5h-6"/>
                        <circle cx="7" cy="19" r="2"/>
                        <circle cx="18" cy="19" r="2"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-forest text-base">Pengemasan Khusus Aman</h4>
                    <p class="text-xs text-forest-3 mt-1 leading-relaxed">Kardus daur ulang ramah lingkungan, tanaman terlindungi dari guncangan kurir.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-stock-wash text-stock flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21V9"/>
                        <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z"/>
                        <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-forest text-base">Pembibitan Mandiri</h4>
                    <p class="text-xs text-forest-3 mt-1 leading-relaxed">Dibudidayakan di nursery lokal, bebas gambut (peat-free), dan sudah berakar kuat.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-ochre-wash text-ochre flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-display font-semibold text-forest text-base">AI Doctor & Konsultasi</h4>
                    <p class="text-xs text-forest-3 mt-1 leading-relaxed">Didukung Ciora AI Vision untuk diagnosa daun sakit 24/7 dan asisten perawatan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Footer -->
    <footer class="bg-forest text-glass border-t border-forest-2 pt-16 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
                <!-- Brand Info & Newsletter -->
                <div class="lg:col-span-2 space-y-4">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-xl bg-glass text-forest flex items-center justify-center font-bold">
                            <svg class="w-5 h-5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 21V9"/>
                                <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z"/>
                            </svg>
                        </span>
                        <span class="font-display font-extrabold text-2xl tracking-tight text-white">
                            Plantify<span class="text-ochre">.</span>
                        </span>
                    </a>
                    <p class="text-xs sm:text-sm text-hairline leading-relaxed max-w-sm">
                        Marketplace tanaman hias terpercaya dengan sistem pendampingan perawatan digital "My Digital Garden" dan AI Plant Doctor. Membantu setiap orang menjadi *plant parent* yang sukses.
                    </p>
                    <form class="flex gap-2 max-w-md pt-2" data-demo-form="Terima kasih! Anda telah terdaftar di buletin mingguan Plantify.">
                        <input type="email" placeholder="Alamat email Anda..." required class="flex-1 h-11 px-4 text-xs bg-forest-2 border border-forest-3 rounded-full text-white placeholder:text-forest-4 focus:outline-none focus:border-ochre" />
                        <button type="submit" class="btn btn-ochre text-xs h-11 px-5 flex-shrink-0">Daftar</button>
                    </form>
                </div>

                <!-- Navigation Columns -->
                <div>
                    <h5 class="font-display font-bold text-sm text-white mb-4 uppercase tracking-wider">Katalog Belanja</h5>
                    <ul class="space-y-2.5 text-xs text-hairline">
                        <li><a href="{{ route('shop') }}" class="hover:text-ochre transition-colors">Semua Tanaman</a></li>
                        <li><a href="{{ route('shop') }}?category=indoor" class="hover:text-ochre transition-colors">Tanaman Indoor</a></li>
                        <li><a href="{{ route('shop') }}?category=sukulen" class="hover:text-ochre transition-colors">Kaktus & Sukulen</a></li>
                        <li><a href="{{ route('shop') }}?filter=pet-safe" class="hover:text-ochre transition-colors">Pet-Friendly (Aman Hewan)</a></li>
                        <li><a href="{{ route('shop') }}?filter=easy-care" class="hover:text-ochre transition-colors">Mudah Dirawat (Pemula)</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-display font-bold text-sm text-white mb-4 uppercase tracking-wider">Fitur Unggulan</h5>
                    <ul class="space-y-2.5 text-xs text-hairline">
                        <li><a href="{{ route('garden') }}" class="hover:text-ochre transition-colors">My Digital Garden</a></li>
                        <li><a href="{{ route('care') }}" class="hover:text-ochre transition-colors">AI Plant Doctor</a></li>
                        <li><a href="{{ route('cart') }}" class="hover:text-ochre transition-colors">Gift a Plant (Unboxing)</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-ochre transition-colors">Eco-Impact CO₂ Tracker</a></li>
                        <li><a href="{{ route('care') }}" class="hover:text-ochre transition-colors">Pengingat Cuaca Pintar</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-display font-bold text-sm text-white mb-4 uppercase tracking-wider">Bantuan & Legal</h5>
                    <ul class="space-y-2.5 text-xs text-hairline">
                        <li><a href="{{ route('about') }}" class="hover:text-ochre transition-colors">Tentang Pembibitan</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-ochre transition-colors">Kebijakan Garansi 30 Hari</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-ochre transition-colors">Ketentuan Layanan</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-ochre transition-colors">Kontak Kami</a></li>
                        <li><a href="#" data-toast="PCI-DSS compliant via Stripe Checkout" class="hover:text-ochre transition-colors">Keamanan Pembayaran</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-forest-2 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-mono text-hairline">
                <p>&copy; {{ date('Y') }} Plantify Official. All rights reserved.</p>
                <p class="flex items-center gap-3">
                    <span>Tech Stack: Laravel 13 &bull; Tailwind CSS 4 &bull; Ciora AI</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- 6. Cart Drawer (Slide-Out Aside) -->
    <div class="scrim" id="drawerScrim" data-open="false" aria-hidden="true"></div>
    <aside class="drawer" data-open="false" role="dialog" aria-modal="true" aria-label="Keranjang Belanja">
        <!-- Drawer Header -->
        <div class="p-5 border-b border-hairline flex items-center justify-between bg-glass/60">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 7h12l-1 13H7L6 7Z"/>
                    <path d="M9 7a3 3 0 0 1 6 0"/>
                </svg>
                <h3 class="font-display font-bold text-base text-forest">Keranjang Belanja</h3>
            </div>
            <button type="button" class="btn-icon min-h-[44px]" data-action="close-drawer" aria-label="Tutup Keranjang">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Free Delivery Progress Bar -->
        <div class="px-5 py-3 bg-glass-dark border-b border-hairline">
            <div class="flex items-center justify-between text-xs font-medium text-forest mb-1.5">
                <span data-threshold-text>Tambah belanjaan untuk gratis ongkir</span>
            </div>
            <div class="w-full h-2 bg-hairline rounded-full overflow-hidden">
                <div data-threshold-bar class="h-full bg-ochre transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>

        <!-- Drawer Body -->
        <div class="flex-1 overflow-y-auto p-5">
            <!-- Empty Cart State -->
            <div data-cart-empty class="py-12 text-center flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-glass flex items-center justify-center text-forest-3 mb-4">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 7h12l-1 13H7L6 7Z"/>
                        <path d="M9 7a3 3 0 0 1 6 0"/>
                    </svg>
                </div>
                <h4 class="font-display font-semibold text-forest text-base">Keranjang Masih Kosong</h4>
                <p class="text-xs text-forest-3 mt-1 max-w-xs">Jelajahi ragam tanaman nursery kami dan mulai kebun impianmu hari ini.</p>
                <a href="{{ route('shop') }}" class="btn btn-primary text-xs mt-6" data-action="close-drawer">
                    Mulai Belanja Tanaman
                </a>
            </div>

            <!-- Line Items Container -->
            <div data-cart-lines="drawer" class="space-y-4"></div>
        </div>

        <!-- Drawer Footer (When Items Exist) -->
        <div class="p-5 border-t border-hairline bg-paper" data-cart-filled hidden>
            <div class="space-y-2 text-xs mb-4">
                <div class="flex justify-between text-forest-2">
                    <span>Subtotal</span>
                    <span class="font-mono font-bold text-forest" data-cart-subtotal>Rp 0</span>
                </div>
                <div class="flex justify-between text-forest-2">
                    <span>Estimasi Ongkir</span>
                    <span class="font-mono font-bold text-stock" data-cart-delivery>Gratis</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-forest pt-2 border-t border-hairline">
                    <span>Total Pembayaran</span>
                    <span class="font-mono text-base text-ochre-deep" data-cart-total>Rp 0</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('cart') }}" class="btn btn-ghost text-xs w-full py-2.5">
                    Lihat Keranjang
                </a>
                <a href="{{ route('cart') }}" class="btn btn-primary text-xs w-full py-2.5">
                    Checkout Sekarang
                </a>
            </div>
            <p class="text-[11px] text-center text-forest-4 mt-2 font-mono">
                🔒 Pembayaran Aman & Garansi 30 Hari
            </p>
        </div>
    </aside>

    <!-- 7. Toast Container -->
    <div class="toasts" aria-live="polite"></div>

</body>
</html>
