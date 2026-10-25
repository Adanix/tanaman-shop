@extends('layouts.app')

@section('title', 'Keranjang Belanja — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Beranda</a>
            <span>/</span>
            <span class="text-forest font-semibold">Keranjang Belanja</span>
        </nav>

        <div class="mb-8">
            <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-forest tracking-tight">Keranjang Belanja Anda</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5">
                Daftar tanaman yang Anda pilih tersimpan aman di browser ini dan tidak akan hilang saat halaman dimuat ulang.
            </p>
        </div>

        <!-- 1. Empty Cart State -->
        <div data-cart-empty class="bg-paper border border-hairline rounded-3xl p-10 sm:p-16 text-center max-w-xl mx-auto shadow-sm">
            <div class="w-20 h-20 rounded-full bg-glass flex items-center justify-center text-forest-3 mx-auto mb-5">
                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M6 7h12l-1 13H7L6 7Z"/>
                    <path d="M9 7a3 3 0 0 1 6 0"/>
                </svg>
            </div>
            <h2 class="font-display font-bold text-xl sm:text-2xl text-forest">Keranjang Anda Masih Kosong</h2>
            <p class="text-xs sm:text-sm text-forest-3 mt-2 leading-relaxed">
                Belum ada tanaman yang dipilih. Temukan tanaman hias impian dari greenhouse kami yang siap diadopsi.
            </p>
            <div class="mt-8 flex justify-center">
                <a href="{{ route('shop') }}" class="btn btn-primary text-xs sm:text-sm px-6">
                    Jelajahi Katalog Tanaman
                </a>
            </div>
        </div>

        <!-- 2. Filled Cart State (2-Column Responsive) -->
        <div data-cart-filled hidden class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-12 items-start">
            
            <!-- Left Area: Items List & Digital Gift Options (col-span-8) -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                <!-- Free Shipping Indicator Banner -->
                <div class="bg-paper border border-hairline rounded-2xl p-4 sm:p-5 shadow-sm space-y-2">
                    <div class="flex items-center justify-between text-xs font-semibold text-forest">
                        <span data-threshold-text>Hitung estimasi gratis ongkir...</span>
                        <span class="font-mono text-ochre-deep font-bold">Target Rp 250.000</span>
                    </div>
                    <div class="w-full h-2.5 bg-glass rounded-full overflow-hidden">
                        <div data-threshold-bar class="h-full bg-ochre transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Line Items Container (Populated by app.js) -->
                <div data-cart-lines="page" class="space-y-4"></div>

                <!-- PRD Feature: Digital Gift Unboxing Section -->
                <div class="bg-paper border border-hairline rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="gift-toggle" class="mt-1 w-4 h-4 rounded border-hairline text-ochre focus:ring-ochre cursor-pointer"
                               onchange="document.getElementById('gift-form-fields').classList.toggle('hidden', !this.checked)" />
                        <div>
                            <label for="gift-toggle" class="font-display font-bold text-sm text-forest cursor-pointer flex items-center gap-1.5">
                                <span>🎁 Kirim Pesanan Ini Sebagai Hadiah (Digital Unboxing)</span>
                                <span class="badge badge-stock text-[10px]">Fitur Spesial</span>
                            </label>
                            <p class="text-xs text-forest-3 mt-1 leading-relaxed">
                                Pesanan hadiah tidak akan masuk ke kebun Anda. Penerima akan memperoleh kartu ucapan fisik ber-QR code dan tautan personal (<code class="bg-glass text-forest px-1 py-0.5 rounded text-[11px]">/gift/{token}</code>) untuk proses unboxing digital interaktif serta adopsi otomatis ke akunnya.
                            </p>
                        </div>
                    </div>

                    <!-- Hidden Fields for Gift Details -->
                    <div id="gift-form-fields" class="hidden pl-7 pt-3 space-y-3 border-t border-hairline-soft">
                        <div>
                            <label class="block text-xs font-semibold text-forest mb-1">Nama Penerima Hadiah</label>
                            <input type="text" placeholder="Contoh: Sarah Anindita" class="w-full h-10 px-3.5 text-xs bg-glass border border-hairline rounded-xl focus:outline-none focus:border-ochre text-forest" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-forest mb-1">Pesan Ucapan Personal</label>
                            <textarea rows="3" placeholder="Tuliskan ucapan selamat ulang tahun, wisuda, atau pindahan rumah..." class="w-full p-3 text-xs bg-glass border border-hairline rounded-xl focus:outline-none focus:border-ochre text-forest"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Back Navigation -->
                <div class="pt-2">
                    <a href="{{ route('shop') }}" class="text-xs font-semibold text-forest hover:text-ochre inline-flex items-center gap-1">
                        &larr; Lanjut Tambah Tanaman Lain
                    </a>
                </div>
            </div>

            <!-- Right Area: Order Summary (col-span-5) -->
            <aside class="lg:col-span-5 xl:col-span-4 bg-paper border border-hairline rounded-3xl p-6 sm:p-7 space-y-5 lg:sticky lg:top-24 shadow-sm">
                <h2 class="font-display font-bold text-lg text-forest pb-3 border-b border-hairline">Ringkasan Pesanan</h2>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-forest-2">
                        <span>Subtotal Belanja</span>
                        <span class="font-mono font-bold text-forest text-sm" data-cart-subtotal>Rp 0</span>
                    </div>
                    <div class="flex justify-between text-forest-2">
                        <span>Biaya Pengiriman (Jawa-Bali)</span>
                        <span class="font-mono font-bold text-stock text-sm" data-cart-delivery>Gratis</span>
                    </div>
                    <div class="flex justify-between text-forest-2">
                        <span>Potongan Kode Promo</span>
                        <span class="font-mono font-bold text-forest-4">&minus; Rp 0</span>
                    </div>
                    <div class="pt-3 border-t border-hairline flex justify-between items-baseline">
                        <span class="font-display font-bold text-base text-forest">Total Tagihan</span>
                        <span class="font-mono font-extrabold text-xl text-ochre-deep" data-cart-total>Rp 0</span>
                    </div>
                </div>

                <!-- Promo Code Form -->
                <form class="flex gap-2 pt-2" data-demo-form="Kode promo PLANTIFY2026 berhasil diterapkan!">
                    <input type="text" placeholder="Kode promo..." class="flex-1 h-10 px-3 text-xs bg-glass border border-hairline rounded-full focus:outline-none focus:border-ochre uppercase font-mono" />
                    <button type="submit" class="btn btn-ghost text-xs h-10 px-4">Terapkan</button>
                </form>

                <!-- Checkout Button -->
                <button type="button" class="btn btn-primary text-sm w-full py-3.5 shadow-md flex items-center justify-center gap-2"
                        data-toast="Sesi Stripe Checkout (hosted) siap dibuat dengan masa kedaluwarsa 30 menit (PCI-DSS compliant)">
                    <svg class="w-4 h-4 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Bayar via Stripe Checkout
                </button>

                <!-- Security & Notice -->
                <div class="space-y-2 pt-2 text-[11px] text-forest-3 text-center">
                    <p class="flex items-center justify-center gap-1.5 font-mono">
                        <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Stripe Checkout PCI-DSS &bull; Auto-Release 30 Menit</span>
                    </p>
                    <p class="text-forest-4">
                        Stok tanaman ditahan secara atomik selama proses pembayaran agar tidak terjadi *overselling*.
                    </p>
                </div>
            </aside>

        </div>
    </div>

@endsection
