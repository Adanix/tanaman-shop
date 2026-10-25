@extends('layouts.app')

@section('title', 'Katalog Tanaman Hias — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-4" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Beranda</a>
            <span>/</span>
            <span class="text-forest font-semibold">Katalog Tanaman</span>
        </nav>

        <!-- Section Heading -->
        <div class="mb-8">
            <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-forest tracking-tight">Koleksi Tanaman Nursery</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5 max-w-2xl">
                Seluruh tanaman dibudidayakan mandiri di Lea Valley Green House. Diaklimatisasi dengan kuat, berakar kokoh, dan siap diadopsi.
            </p>
        </div>

        <!-- Toolbar (Quick Filter Chips, Count, Sorting) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-6 border-b border-hairline">
            <!-- Quick Chips -->
            <div class="flex items-center gap-2 flex-wrap" role="group" aria-label="Filter Cepat">
                <button type="button" class="chip" data-filter="easy-care" aria-pressed="false">
                    Mudah Dirawat
                </button>
                <button type="button" class="chip" data-filter="pet-safe" aria-pressed="false">
                    Aman Hewan (Pet-Safe)
                </button>
                <button type="button" class="chip" data-filter="light-low" aria-pressed="false">
                    Rendah Cahaya (Low Light)
                </button>
                <button type="button" class="chip" data-filter="sale" aria-pressed="false">
                    Sedang Promo (Sale)
                </button>
            </div>

            <!-- Count & Sort -->
            <div class="flex items-center justify-between sm:justify-end gap-3 self-stretch sm:self-auto">
                <span class="text-xs font-medium text-forest-3 whitespace-nowrap">
                    Menampilkan <b class="font-mono text-forest" data-result-count>10</b> tanaman
                </span>
                <select class="h-9 px-3 text-xs bg-paper border border-hairline rounded-full text-forest focus:outline-none focus:border-ochre font-medium cursor-pointer" data-sort aria-label="Urutkan Produk">
                    <option value="featured">Pilihan Unggulan</option>
                    <option value="price-asc">Harga: Terendah &rarr; Tertinggi</option>
                    <option value="price-desc">Harga: Tertinggi &rarr; Terendah</option>
                    <option value="name">Nama: A &rarr; Z</option>
                </select>
            </div>
        </div>

        <!-- 2-Column Layout: Sidebar Filters + Products Grid -->
        <div class="flex flex-col md:flex-row gap-8 items-start">
            
            <!-- Sidebar Filters (Desktop Sticky + Mobile Collapse) -->
            <aside class="w-full md:w-60 xl:w-64 flex-shrink-0 bg-paper/60 md:bg-transparent p-4 md:p-0 rounded-2xl md:rounded-none border md:border-none border-hairline md:sticky md:top-24 space-y-6">
                <!-- Group 1: Pencahayaan -->
                <div class="space-y-3 pb-5 border-b border-hairline">
                    <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider">Kebutuhan Cahaya</h3>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="light-bright" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Terang Langsung / Cukup</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">7</span>
                        </label>
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="light-low" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Rendah Cahaya (Low Light)</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">3</span>
                        </label>
                    </div>
                </div>

                <!-- Group 2: Ukuran Pot / Tanaman -->
                <div class="space-y-3 pb-5 border-b border-hairline">
                    <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider">Ukuran Tanaman</h3>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="size-small" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Kecil (Pot 10 - 12 cm)</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">5</span>
                        </label>
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="size-medium" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Sedang (Pot 14 - 17 cm)</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">3</span>
                        </label>
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="size-large" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Besar / Statement (20cm+)</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">2</span>
                        </label>
                    </div>
                </div>

                <!-- Group 3: Karakteristik -->
                <div class="space-y-3 pb-5 border-b border-hairline">
                    <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider">Karakteristik Khusus</h3>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="pet-safe" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Aman untuk Anjing & Kucing</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">4</span>
                        </label>
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="easy-care" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Ramah Pemula (Tahan Banting)</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">6</span>
                        </label>
                        <label class="flex items-center justify-between gap-2 text-forest-2 cursor-pointer hover:text-forest">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" data-filter-check="sale" class="rounded border-hairline text-ochre focus:ring-ochre w-4 h-4 cursor-pointer" />
                                <span>Sedang Potongan Harga</span>
                            </span>
                            <span class="font-mono text-[11px] text-forest-4">3</span>
                        </label>
                    </div>
                </div>

                <!-- Clear Button -->
                <button type="button" class="btn btn-ghost text-xs w-full py-2.5" data-clear-filters>
                    Reset Semua Filter
                </button>
            </aside>

            <!-- Main Catalog Grid -->
            <div class="flex-1 w-full">
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-product-grid>
                    
                    <!-- 1. Monstera Deliciosa -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright easy-care size-large sale" data-price="420000" data-name="Monstera Deliciosa" data-order="0" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Terang Sejuk</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>7 Hari</span>
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

                    <!-- 2. Rattlesnake Calathea -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-low pet-safe size-medium" data-price="340000" data-name="Rattlesnake Calathea" data-order="1" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>Low Light</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>3-4 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 340.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                    <!-- 3. Ginseng Ficus Bonsai -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright size-medium" data-price="580000" data-name="Ginseng Ficus Bonsai" data-order="2" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Terang Langsung</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>5 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 580.000</div>
                                <span class="text-[10px] font-mono text-low bg-low-wash px-1.5 py-0.5 rounded">Terbatas</span>
                            </div>
                        </div>
                    </article>

                    <!-- 4. Zebra Haworthia -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright easy-care pet-safe size-small" data-price="180000" data-name="Zebra Haworthia" data-order="3" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Terang Cukup</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>14 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 180.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                    <!-- 5. Moon Cactus -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright easy-care size-small" data-price="140000" data-name="Moon Cactus" data-order="4" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Terang Hangat</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>30 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 140.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                    <!-- 6. Echeveria in Concrete -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright easy-care pet-safe size-small" data-price="220000" data-name="Echeveria in Concrete" data-order="5" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Terang Penuh</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>14 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 220.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                    <!-- 7. Lucky Bamboo -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-low easy-care size-small sale" data-price="160000" data-name="Lucky Bamboo" data-order="6" data-product>
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>Low Light</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>Media Air</span>
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

                    <!-- 8. Succulent Trio Set -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright easy-care pet-safe size-small" data-price="260000" data-name="Succulent Trio Set" data-order="7" data-product>
                        <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                            <a href="{{ route('product.show', ['slug' => 'succulent-trio']) }}" class="block w-full h-full">
                                <img src="https://images.unsplash.com/photo-1459156212016-c812468e2115?w=700&q=72&auto=format&fit=crop" alt="Succulent Trio" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                            </a>
                            <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                            </button>
                            <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="succulent-trio" data-name="Succulent Trio Set" data-price="260000" data-image="https://images.unsplash.com/photo-1459156212016-c812468e2115?w=200&q=72&auto=format&fit=crop" data-variant="Set 3 Pot">
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
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Terang Cukup</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>14 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 260.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                    <!-- 9. Boxwood Topiary -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright size-large" data-price="750000" data-name="Boxwood Topiary" data-order="8" data-product>
                        <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                            <a href="{{ route('product.show', ['slug' => 'boxwood-topiary']) }}" class="block w-full h-full">
                                <img src="https://images.unsplash.com/photo-1520412099551-62b6bafeb5bb?w=700&q=72&auto=format&fit=crop" alt="Boxwood Topiary" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                            </a>
                            <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                            </button>
                            <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="topiary" data-name="Boxwood Topiary" data-price="750000" data-image="https://images.unsplash.com/photo-1520412099551-62b6bafeb5bb?w=200&q=72&auto=format&fit=crop" data-variant="Pot 24cm">
                                    + Keranjang
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-between mt-3">
                            <div>
                                <a href="{{ route('product.show', ['slug' => 'boxwood-topiary']) }}">
                                    <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Boxwood Topiary</h3>
                                </a>
                                <p class="text-xs text-forest-4 italic font-serif mt-0.5">Buxus sempervirens</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2"/></svg>Outdoor</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>2 Hari</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 750.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                    <!-- 10. Eucalyptus Stems -->
                    <article class="relative flex flex-col group bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm"
                             data-tags="light-bright size-medium" data-price="195000" data-name="Eucalyptus Stems" data-order="9" data-product>
                        <div class="relative w-full aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                            <a href="{{ route('product.show', ['slug' => 'eucalyptus-stems']) }}" class="block w-full h-full">
                                <img src="https://images.unsplash.com/photo-1466781783364-36c955e42a7f?w=700&q=72&auto=format&fit=crop" alt="Eucalyptus Stems" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                            </a>
                            <button type="button" class="product__wish absolute top-2.5 right-2.5 z-10 w-9 h-9 rounded-full bg-paper/90 backdrop-blur-sm flex items-center justify-center text-forest-2 hover:text-sale transition-colors shadow-sm" aria-pressed="false" aria-label="Simpan ke Wishlist">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7-4.5-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.5 12 20 12 20Z"/></svg>
                            </button>
                            <div class="absolute inset-x-2.5 bottom-2.5 z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" class="btn btn-primary text-xs w-full py-2.5 shadow-md" data-add="eucalyptus" data-name="Eucalyptus Stems" data-price="195000" data-image="https://images.unsplash.com/photo-1466781783364-36c955e42a7f?w=200&q=72&auto=format&fit=crop" data-variant="Buket Segar">
                                    + Keranjang
                                </button>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-between mt-3">
                            <div>
                                <a href="{{ route('product.show', ['slug' => 'eucalyptus-stems']) }}">
                                    <h3 class="font-display font-semibold text-forest text-base group-hover:text-ochre-deep transition-colors">Eucalyptus Fresh Stems</h3>
                                </a>
                                <p class="text-xs text-forest-4 italic font-serif mt-0.5">Eucalyptus cinerea</p>
                                <div class="flex items-center gap-3 mt-2 text-xs text-forest-3">
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/></svg>Aromatik</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-stock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3s6 6.2 6 10a6 6 0 0 1-12 0c0-3.8 6-10 6-10Z"/></svg>Tahan 3 Pekan</span>
                                </div>
                            </div>
                            <div class="flex items-baseline justify-between mt-3 pt-2.5 border-t border-hairline-soft">
                                <div class="font-mono font-bold text-forest text-sm sm:text-base">Rp 195.000</div>
                                <span class="text-[10px] font-mono text-stock bg-stock-wash px-1.5 py-0.5 rounded">Tersedia</span>
                            </div>
                        </div>
                    </article>

                </div>

                <!-- Empty State (Fallback if filters return zero results) -->
                <div data-no-results hidden class="py-16 text-center bg-paper rounded-2xl border border-hairline p-8 mt-6">
                    <div class="w-16 h-16 rounded-full bg-glass flex items-center justify-center text-forest-3 mx-auto mb-4">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-forest">Tidak Ada Tanaman yang Sesuai</h3>
                    <p class="text-xs text-forest-3 mt-1.5 max-w-sm mx-auto">
                        Coba kurangi kombinasi filter Anda atau pilih opsi filter lain untuk menemukan tanaman yang cocok.
                    </p>
                    <button type="button" class="btn btn-primary text-xs mt-6" data-clear-filters>
                        Hapus Semua Filter
                    </button>
                </div>
            </div>

        </div>
    </div>

@endsection
