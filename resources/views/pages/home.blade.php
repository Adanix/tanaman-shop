@extends('layouts.app')

@section('title', 'Plantify Official — Marketplace Tanaman Hias & Perawatan Cerdas')

@section('content')

    <!-- 1. Category Rail (Shop-First: langsung ke pilihan kategori) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-2" aria-label="Belanja Berdasarkan Kategori">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-display font-bold text-lg sm:text-xl text-forest tracking-tight">Eksplorasi Kategori</h2>
            <a href="{{ route('shop') }}" class="text-xs font-semibold text-ochre-deep hover:underline">Semua Kategori &rarr;</a>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar snap-x snap-mandatory">
            <!-- Item 1 -->
            <a href="{{ route('shop') }}?category=sukulen" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=340&q=72&auto=format&fit=crop" alt="Kategori Sukulen" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Sukulen</span>
                    <span class="font-mono text-[11px] text-forest-4">24 jenis</span>
                </div>
            </a>

            <!-- Item 2 -->
            <a href="{{ route('shop') }}?category=indoor" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1545241047-6083a3684587?w=340&q=72&auto=format&fit=crop" alt="Kategori Statement Indoor" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Statement</span>
                    <span class="font-mono text-[11px] text-forest-4">18 jenis</span>
                </div>
            </a>

            <!-- Item 3 -->
            <a href="{{ route('shop') }}?filter=light-low" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=340&q=72&auto=format&fit=crop" alt="Kategori Rendah Cahaya" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Low Light</span>
                    <span class="font-mono text-[11px] text-forest-4">31 jenis</span>
                </div>
            </a>

            <!-- Item 4 -->
            <a href="{{ route('shop') }}?category=bonsai" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1512428813834-c702c7702b78?w=340&q=72&auto=format&fit=crop" alt="Kategori Bonsai" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Bonsai</span>
                    <span class="font-mono text-[11px] text-forest-4">9 jenis</span>
                </div>
            </a>

            <!-- Item 5 -->
            <a href="{{ route('shop') }}?filter=pet-safe" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1466781783364-36c955e42a7f?w=340&q=72&auto=format&fit=crop" alt="Kategori Pet-Friendly" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Pet-Safe</span>
                    <span class="font-mono text-[11px] text-forest-4">12 jenis</span>
                </div>
            </a>

            <!-- Item 6 -->
            <a href="{{ route('shop') }}?category=gift" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1459156212016-c812468e2115?w=340&q=72&auto=format&fit=crop" alt="Kategori Paket Hadiah" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Gift Sets</span>
                    <span class="font-mono text-[11px] text-forest-4">7 paket</span>
                </div>
            </a>

            <!-- Item 7 -->
            <a href="{{ route('shop') }}?category=outdoor" class="flex-none w-36 sm:w-44 snap-start p-3 bg-paper border border-hairline rounded-2xl hover:border-ochre hover:-translate-y-1 transition-all group">
                <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=340&q=72&auto=format&fit=crop" alt="Kategori Outdoor" class="w-full aspect-square object-cover rounded-xl bg-glass group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                <div class="flex items-baseline justify-between mt-2.5">
                    <span class="font-semibold text-xs sm:text-sm text-forest group-hover:text-ochre-deep">Outdoor</span>
                    <span class="font-mono text-[11px] text-forest-4">15 jenis</span>
                </div>
            </a>
        </div>
    </section>

    <!-- 2. Koleksi Siap Kirim Minggu Ini (Featured Products) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" aria-labelledby="featured-h">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Koleksi Terhangat</span>
                <h2 id="featured-h" class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-0.5 tracking-tight">Baru Siap dari Green House</h2>
                <p class="text-xs sm:text-sm text-forest-3 mt-1">Seluruh tanaman berikut telah berakar sehat, diaklimatisasi, dan siap dikirim dengan aman.</p>
            </div>
            <a href="{{ route('shop') }}" class="btn btn-ghost text-xs self-start sm:self-auto">Lihat Semua Tanaman</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Product Card 1: Monstera Deliciosa -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'monstera-deliciosa']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1545241047-6083a3684587?w=700&q=72&auto=format&fit=crop" alt="Monstera Deliciosa" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge badge-sale">Promo</span>
                    </div>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <!-- Quick Add Button Overlay -->
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="monstera-deliciosa" data-name="Monstera Deliciosa" data-price="420000" data-image="https://images.unsplash.com/photo-1545241047-6083a3684587?w=200&q=72&auto=format&fit=crop" data-variant="Pot 17cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'monstera-deliciosa']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Monstera Deliciosa</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Monstera deliciosa</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2"/></svg>
                                Terang Sejuk
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                7 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="flex items-baseline gap-2 font-mono">
                            <span class="font-bold text-sale text-sm sm:text-base">Rp 420.000</span>
                            <span class="text-xs text-forest-4 line-through">Rp 520.000</span>
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>

            <!-- Product Card 2: Rattlesnake Calathea -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'rattlesnake-calathea']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=700&q=72&auto=format&fit=crop" alt="Rattlesnake Calathea" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge badge-stock">Pet Safe</span>
                    </div>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="calathea" data-name="Rattlesnake Calathea" data-price="340000" data-image="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=200&q=72&auto=format&fit=crop" data-variant="Pot 14cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'rattlesnake-calathea']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Rattlesnake Calathea</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Calathea lancifolia</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>
                                Low Light
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                3-4 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="font-mono font-bold text-forest text-sm sm:text-base">
                            Rp 340.000
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>

            <!-- Product Card 3: Ginseng Ficus Bonsai -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'ginseng-ficus-bonsai']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1512428813834-c702c7702b78?w=700&q=72&auto=format&fit=crop" alt="Ginseng Ficus Bonsai" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge badge-low">Sisa 3 unit</span>
                    </div>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="bonsai" data-name="Ginseng Ficus Bonsai" data-price="580000" data-image="https://images.unsplash.com/photo-1512428813834-c702c7702b78?w=200&q=72&auto=format&fit=crop" data-variant="Pot Keramik 18cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'ginseng-ficus-bonsai']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Ginseng Ficus Bonsai</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Ficus microcarpa</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>
                                Terang Langsung
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                5 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="font-mono font-bold text-forest text-sm sm:text-base">
                            Rp 580.000
                        </div>
                        <span class="text-[10px] font-mono text-low bg-low-wash px-1.5 py-0.5 rounded">Terbatas</span>
                    </div>
                </div>
            </article>

            <!-- Product Card 4: Zebra Haworthia -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'zebra-haworthia']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=700&q=72&auto=format&fit=crop" alt="Zebra Haworthia" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge badge-stock">Pet Safe</span>
                    </div>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="haworthia" data-name="Zebra Haworthia" data-price="180000" data-image="https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=200&q=72&auto=format&fit=crop" data-variant="Pot 10cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'zebra-haworthia']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Zebra Haworthia</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Haworthiopsis attenuata</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2"/></svg>
                                Terang Cukup
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                14 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="font-mono font-bold text-forest text-sm sm:text-base">
                            Rp 180.000
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- 3. Janji Nursery Plantify (Editorial Green Band) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-forest text-glass rounded-3xl p-6 sm:p-10 lg:p-14 overflow-hidden border border-forest-2 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div class="space-y-4">
                    <span class="text-xs font-mono font-semibold text-ochre uppercase tracking-wider">Janji Pembibitan Plantify</span>
                    <h2 class="font-display font-extrabold text-2xl sm:text-4xl text-white tracking-tight leading-tight">
                        Kami Rawat Dari Benih, dan Terbuka Jika Perawatannya Rumit.
                    </h2>
                    <p class="text-xs sm:text-sm text-hairline leading-relaxed max-w-xl">
                        Setiap tanaman di katalog Plantify dibudidayakan langsung di greenhouse pembibitan mandiri kami — tanpa perantara, tanpa impor ilegal. Setiap halaman produk merinci kebutuhan cahaya, frekuensi siram, serta tingkat toleransi jika Anda lupa menyiramnya.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('about') }}" class="btn btn-ochre text-xs sm:text-sm">
                            Kunjungi Green House Kami
                        </a>
                        <a href="{{ route('care') }}" class="btn btn-ghost text-xs sm:text-sm !text-glass border-glass/30 hover:bg-glass/10">
                            Pelajari Panduan Rawat
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=900&q=72&auto=format&fit=crop" alt="Greenhouse Pembibitan Tanaman Plantify" class="w-full aspect-[4/3] object-cover rounded-2xl shadow-xl border border-forest-2" loading="lazy" />
                    <div class="absolute -bottom-4 -left-4 bg-paper/95 backdrop-blur-md text-forest p-4 rounded-xl border border-hairline shadow-lg hidden sm:flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-stock-wash text-stock flex items-center justify-center font-bold text-lg">🌿</span>
                        <div>
                            <p class="font-display font-bold text-sm">100% Peat-Free & Organik</p>
                            <p class="text-[11px] text-forest-3">Aman bagi anak, kucing, & bumi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Fitur Inovasi PRD: AI Doctor, Digital Garden & Gift Unboxing -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" aria-labelledby="innovations-h">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Teknologi Eksklusif Plantify</span>
            <h2 id="innovations-h" class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-1 tracking-tight">Lebih Dari Sekadar Beli Tanaman</h2>
            <p class="text-xs sm:text-sm text-forest-3 mt-2">Ekosistem lengkap yang mendampingi perjalanan merawat tanaman Anda dari hari pertama.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Box 1: AI Plant Doctor -->
            <div class="bg-paper border border-hairline rounded-2xl p-6 hover:border-ochre transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-ochre-wash text-ochre-deep flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                            <circle cx="12" cy="13" r="3"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">AI Plant Doctor</h3>
                    <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                        Cukup foto daun tanaman yang menguning atau berjamur. Ciora AI Vision mendeteksi penyakit dan menyarankan langkah penyelamatan seketika.
                    </p>
                </div>
                <a href="{{ route('care') }}" class="text-xs font-semibold text-ochre-deep mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Coba Diagnosa AI &rarr;
                </a>
            </div>

            <!-- Box 2: My Digital Garden -->
            <div class="bg-paper border border-hairline rounded-2xl p-6 hover:border-ochre transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-stock-wash text-stock flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21V9"/>
                            <path d="M12 13c0-3.3 2.7-6 6-6 0 3.3-2.7 6-6 6Z"/>
                            <path d="M12 16c0-2.8-2.2-5-5-5 0 2.8 2.2 5 5 5Z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">My Digital Garden</h3>
                    <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                        Saat pesanan Anda berstatus sampai, tanaman otomatis masuk ke kebun digital Anda dengan jadwal siram dan pupuk personal.
                    </p>
                </div>
                <a href="{{ route('garden') }}" class="text-xs font-semibold text-ochre-deep mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Buka Kebun Saya &rarr;
                </a>
            </div>

            <!-- Box 3: Weather-Aware Reminder -->
            <div class="bg-paper border border-hairline rounded-2xl p-6 hover:border-ochre transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 16.2A4.5 4.5 0 0 0 17.5 8h-1.8A7 7 0 1 0 4 14.9"/>
                            <path d="M16 14v6m-4-4v6m-4-2v6"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">Weather-Aware Alerts</h3>
                    <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                        Sinkronisasi cuaca kota Anda (OpenWeatherMap). Jika sedang musim hujan, jadwal siram tanaman luar ruangan otomatis ditunda demi mencegah busuk akar.
                    </p>
                </div>
                <a href="{{ route('garden') }}" class="text-xs font-semibold text-ochre-deep mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Lihat Prakiraan Siram &rarr;
                </a>
            </div>

            <!-- Box 4: Gift Unboxing -->
            <div class="bg-paper border border-hairline rounded-2xl p-6 hover:border-ochre transition-all flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="8" width="18" height="14" rx="2"/>
                            <path d="M12 8v14"/>
                            <path d="M3 12h18"/>
                            <path d="M12 8c-2-3-6-3-6 0v0h6Z"/>
                            <path d="M12 8c2-3 6-3 6 0v0h-6Z"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-base text-forest">Digital Gift Unboxing</h3>
                    <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                        Kirim tanaman sebagai hadiah istimewa. Penerima akan menerima tautan unboxing digital interaktif dan langsung mengadopsi tanaman ke kebunnya.
                    </p>
                </div>
                <a href="{{ route('cart') }}" class="text-xs font-semibold text-ochre-deep mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Pelajari Fitur Hadiah &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- 5. Tanaman Tahan Banting (Hard to Kill / Pemula) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" aria-labelledby="easy-h">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-mono font-semibold text-stock uppercase tracking-wider">Ramah Pemula</span>
                <h2 id="easy-h" class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-0.5 tracking-tight">Koleksi Tahan Banting</h2>
                <p class="text-xs sm:text-sm text-forest-3 mt-1">Pilihan tanaman pemaaf yang tetap hidup subur walau Anda terlupa menyiram hingga beberapa minggu.</p>
            </div>
            <a href="{{ route('shop') }}?filter=easy-care" class="btn btn-ghost text-xs self-start sm:self-auto">Lihat Semua Tipe Pemula</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Item 1: Moon Cactus -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'moon-cactus']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1509937528035-ad76254b0356?w=700&q=72&auto=format&fit=crop" alt="Moon Cactus" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge badge-new">Baru Masuk</span>
                    </div>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="moon-cactus" data-name="Moon Cactus" data-price="140000" data-image="https://images.unsplash.com/photo-1509937528035-ad76254b0356?w=200&q=72&auto=format&fit=crop" data-variant="Pot 10cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'moon-cactus']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Moon Cactus</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Gymnocalycium mihanovichii</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>
                                Terang Hangat
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                30 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="font-mono font-bold text-forest text-sm sm:text-base">
                            Rp 140.000
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>

            <!-- Item 2: Echeveria in Concrete -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'echeveria-concrete']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1483794344563-d27a8d18014e?w=700&q=72&auto=format&fit=crop" alt="Echeveria in Concrete" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="echeveria" data-name="Echeveria in Concrete" data-price="220000" data-image="https://images.unsplash.com/photo-1483794344563-d27a8d18014e?w=200&q=72&auto=format&fit=crop" data-variant="Pot Beton 12cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'echeveria-concrete']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Echeveria in Concrete</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Echeveria elegans</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>
                                Terang Penuh
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                14 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="font-mono font-bold text-forest text-sm sm:text-base">
                            Rp 220.000
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>

            <!-- Item 3: Lucky Bamboo -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'lucky-bamboo']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1567331711402-509c12c41959?w=700&q=72&auto=format&fit=crop" alt="Lucky Bamboo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                        <span class="badge badge-sale">Hemat 20%</span>
                    </div>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="bamboo" data-name="Lucky Bamboo" data-price="160000" data-image="https://images.unsplash.com/photo-1567331711402-509c12c41959?w=200&q=72&auto=format&fit=crop" data-variant="Vas Kaca 12cm">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'lucky-bamboo']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Lucky Bamboo</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Dracaena sanderiana</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>
                                Low Light
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                Media Air
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="flex items-baseline gap-2 font-mono">
                            <span class="font-bold text-sale text-sm sm:text-base">Rp 160.000</span>
                            <span class="text-xs text-forest-4 line-through">Rp 200.000</span>
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>

            <!-- Item 4: Succulent Trio -->
            <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                    <a href="{{ route('product.show', ['slug' => 'succulent-trio']) }}" class="block w-full h-full">
                        <img src="https://images.unsplash.com/photo-1459156212016-c812468e2115?w=700&q=72&auto=format&fit=crop" alt="Succulent Trio" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    </a>
                    <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                    </button>
                    <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="succulent-trio" data-name="Succulent Trio" data-price="260000" data-image="https://images.unsplash.com/photo-1459156212016-c812468e2115?w=200&q=72&auto=format&fit=crop" data-variant="Set 3 Pot">
                            + Keranjang
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-between mt-3">
                    <div>
                        <a href="{{ route('product.show', ['slug' => 'succulent-trio']) }}">
                            <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Succulent Trio Set</h3>
                        </a>
                        <p class="text-xs text-forest-4 italic font-serif mt-0.5">Haworthia & Echeveria</p>
                        
                        <div class="flex items-center gap-3 mt-2.5 text-xs text-forest-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>
                                Terang Cukup
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>
                                14 Hari
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                        <div class="font-mono font-bold text-forest text-sm sm:text-base">
                            Rp 260.000
                        </div>
                        <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- 6. Jurnal Perawatan Tanaman (Care Guides) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" aria-labelledby="journal-h">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Jurnal & Edukasi</span>
                <h2 id="journal-h" class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-0.5 tracking-tight">Panduan Hidup Sehat Tanaman</h2>
                <p class="text-xs sm:text-sm text-forest-3 mt-1">Artikel ringkas dari para ahli botani pembibitan Plantify.</p>
            </div>
            <a href="{{ route('care') }}" class="btn btn-ghost text-xs self-start sm:self-auto">Baca Semua Artikel</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <article class="bg-paper border border-hairline rounded-2xl overflow-hidden hover:border-ochre transition-all group flex flex-col">
                <img src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=700&q=72&auto=format&fit=crop" alt="Mengganti Pot Tanaman" class="w-full aspect-[3/2] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-mono text-forest-4 uppercase">
                            <span class="text-ochre-deep font-semibold">Repotting</span>
                            <span>•</span>
                            <span>6 Menit Baca</span>
                        </div>
                        <h3 class="font-display font-bold text-forest text-base sm:text-lg mt-2 group-hover:text-ochre-deep transition-colors">
                            Kapan Waktu Tepat Mengganti Pot Tanaman?
                        </h3>
                        <p class="text-xs text-forest-3 mt-2 line-clamp-2">
                            Akar yang menyembul di lubang drainase adalah tanda jelas. Namun ada tiga tanda tersembunyi lainnya yang jauh lebih genting.
                        </p>
                    </div>
                    <a href="{{ route('care') }}" class="text-xs font-semibold text-forest group-hover:text-ochre-deep mt-4 inline-flex items-center gap-1">
                        Baca Selengkapnya &rarr;
                    </a>
                </div>
            </article>

            <article class="bg-paper border border-hairline rounded-2xl overflow-hidden hover:border-ochre transition-all group flex flex-col">
                <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?w=700&q=72&auto=format&fit=crop" alt="Membaca Cahaya Ruangan" class="w-full aspect-[3/2] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-mono text-forest-4 uppercase">
                            <span class="text-ochre-deep font-semibold">Pencahayaan</span>
                            <span>•</span>
                            <span>4 Menit Baca</span>
                        </div>
                        <h3 class="font-display font-bold text-forest text-base sm:text-lg mt-2 group-hover:text-ochre-deep transition-colors">
                            Cara Membaca Arah Cahaya Alami di Rumah
                        </h3>
                        <p class="text-xs text-forest-3 mt-2 line-clamp-2">
                            Istilah "Bright Indirect" sering kali membingungkan. Berikut cara akurat mengukurnya hanya dengan kamera smartphone Anda.
                        </p>
                    </div>
                    <a href="{{ route('care') }}" class="text-xs font-semibold text-forest group-hover:text-ochre-deep mt-4 inline-flex items-center gap-1">
                        Baca Selengkapnya &rarr;
                    </a>
                </div>
            </article>

            <article class="bg-paper border border-hairline rounded-2xl overflow-hidden hover:border-ochre transition-all group flex flex-col">
                <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=700&q=72&auto=format&fit=crop" alt="Penyiraman Tanaman" class="w-full aspect-[3/2] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-mono text-forest-4 uppercase">
                            <span class="text-ochre-deep font-semibold">Penyiraman</span>
                            <span>•</span>
                            <span>5 Menit Baca</span>
                        </div>
                        <h3 class="font-display font-bold text-forest text-base sm:text-lg mt-2 group-hover:text-ochre-deep transition-colors">
                            Hampir Semua Tanaman Mati Karena Terlalu Disayang
                        </h3>
                        <p class="text-xs text-forest-3 mt-2 line-clamp-2">
                            Penyiraman berlebihan (*overwatering*) membunuh lebih banyak tanaman dibanding kekeringan. Simak teknik uji dua ruas jari.
                        </p>
                    </div>
                    <a href="{{ route('care') }}" class="text-xs font-semibold text-forest group-hover:text-ochre-deep mt-4 inline-flex items-center gap-1">
                        Baca Selengkapnya &rarr;
                    </a>
                </div>
            </article>
        </div>
    </section>

@endsection
