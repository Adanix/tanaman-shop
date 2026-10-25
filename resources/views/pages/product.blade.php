@extends('layouts.app')

@section('title', 'Monstera Deliciosa — Detail Tanaman Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Beranda</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-ochre">Katalog</a>
            <span>/</span>
            <span class="text-forest font-semibold">Monstera Deliciosa</span>
        </nav>

        <!-- Product Hero: 2-Column Responsive (Gallery + Details) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-14 items-start">
            
            <!-- Left: Interactive Gallery -->
            <div class="space-y-4 lg:sticky lg:top-24">
                <!-- Main Image -->
                <div class="w-full aspect-[4/5] rounded-3xl overflow-hidden bg-glass border border-hairline shadow-sm relative">
                    <img data-gallery-main src="https://images.unsplash.com/photo-1545241047-6083a3684587?w=1000&q=75&auto=format&fit=crop" alt="Monstera Deliciosa di pot keramik" class="w-full h-full object-cover transition-all duration-300" />
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <span class="badge badge-sale">Promo Spesial</span>
                        <span class="badge badge-stock">100% Organik</span>
                    </div>
                </div>

                <!-- Thumbnails Grid -->
                <div class="grid grid-cols-4 gap-3">
                    <button type="button" class="aspect-square rounded-xl overflow-hidden border-2 border-ochre bg-glass p-0.5 cursor-pointer focus:outline-none"
                            data-gallery-thumb="https://images.unsplash.com/photo-1545241047-6083a3684587?w=1000&q=75&auto=format&fit=crop" data-alt="Monstera Deliciosa tampak penuh" aria-current="true">
                        <img src="https://images.unsplash.com/photo-1545241047-6083a3684587?w=240&q=70&auto=format&fit=crop" alt="Thumb 1" class="w-full h-full object-cover rounded-lg" />
                    </button>
                    <button type="button" class="aspect-square rounded-xl overflow-hidden border border-hairline hover:border-ochre bg-glass p-0.5 cursor-pointer focus:outline-none transition-colors"
                            data-gallery-thumb="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=1000&q=75&auto=format&fit=crop" data-alt="Detail daun monstera fenestrasi">
                        <img src="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=240&q=70&auto=format&fit=crop" alt="Thumb 2" class="w-full h-full object-cover rounded-lg" />
                    </button>
                    <button type="button" class="aspect-square rounded-xl overflow-hidden border border-hairline hover:border-ochre bg-glass p-0.5 cursor-pointer focus:outline-none transition-colors"
                            data-gallery-thumb="https://images.unsplash.com/photo-1521334884684-d80222895322?w=1000&q=75&auto=format&fit=crop" data-alt="Monstera di sudut ruang kerja">
                        <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?w=240&q=70&auto=format&fit=crop" alt="Thumb 3" class="w-full h-full object-cover rounded-lg" />
                    </button>
                    <button type="button" class="aspect-square rounded-xl overflow-hidden border border-hairline hover:border-ochre bg-glass p-0.5 cursor-pointer focus:outline-none transition-colors"
                            data-gallery-thumb="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=1000&q=75&auto=format&fit=crop" data-alt="Greenhouse pembibitan Monstera">
                        <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=240&q=70&auto=format&fit=crop" alt="Thumb 4" class="w-full h-full object-cover rounded-lg" />
                    </button>
                </div>
            </div>

            <!-- Right: Product Information & Buying Options -->
            <div class="space-y-6">
                <!-- Stock Status & Badges -->
                <div class="flex items-center gap-3">
                    <span class="badge badge-sale">Hemat 19%</span>
                    <span class="badge badge-stock flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-stock animate-pulse"></span>
                        Stok Tersedia &bull; Pengiriman Cepat
                    </span>
                </div>

                <!-- Title & Rating -->
                <div>
                    <h1 class="font-display font-extrabold text-3xl sm:text-4xl text-forest tracking-tight">Monstera Deliciosa</h1>
                    <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                        <p class="text-sm text-forest-4 italic font-serif">Monstera deliciosa (Swiss Cheese Plant)</p>
                        <span class="text-hairline-soft">&bull;</span>
                        <div class="flex items-center gap-1 text-xs text-ochre-deep font-semibold">
                            <span>★ 4.9</span>
                            <span class="text-forest-4 font-normal">(128 Ulasan Terverifikasi)</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing Display -->
                <div class="flex items-baseline gap-3 pt-1 border-t border-hairline">
                    <span class="font-mono font-bold text-3xl text-sale" data-variant-price>Rp 420.000</span>
                    <span class="font-mono text-base text-forest-4 line-through">Rp 520.000</span>
                    <span class="text-xs font-semibold text-stock bg-stock-wash px-2 py-0.5 rounded-full">Hemat Rp 100.000</span>
                </div>

                <!-- Short Leaning Description -->
                <p class="text-xs sm:text-sm text-forest-2 leading-relaxed">
                    Tanaman favorit sejuta pecinta tanaman hias. Tanaman Monstera kami telah berusia 2 tahun di nursery, daunnya telah membelah sempurna (fenestrasi alami), dan telah terlatih kokoh tanpa membutuhkan tiang lumut tambahan.
                </p>

                <!-- Pot Size Variants Selector -->
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-display font-bold text-forest uppercase tracking-wider">Pilih Ukuran Pot</span>
                        <span class="text-forest-4 font-mono">Tinggi 45 - 65 cm</span>
                    </div>
                    <div class="variants grid grid-cols-3 gap-2.5" role="group" aria-label="Pilihan Ukuran Pot">
                        <button type="button" class="variant p-3 rounded-2xl border border-hairline bg-paper text-left transition-all hover:border-ochre cursor-pointer aria-pressed:border-forest aria-pressed:bg-forest aria-pressed:text-white"
                                data-variant="Pot 12cm" data-price="280000" aria-pressed="false">
                            <span class="block text-xs font-bold">Pot 12 cm</span>
                            <span class="block text-[11px] font-mono mt-0.5 opacity-80">Rp 280.000</span>
                        </button>
                        <button type="button" class="variant p-3 rounded-2xl border-2 border-forest bg-forest text-white text-left transition-all cursor-pointer aria-pressed:border-forest aria-pressed:bg-forest aria-pressed:text-white"
                                data-variant="Pot 17cm" data-price="420000" aria-pressed="true">
                            <span class="block text-xs font-bold">Pot 17 cm (Favorit)</span>
                            <span class="block text-[11px] font-mono mt-0.5 opacity-80">Rp 420.000</span>
                        </button>
                        <button type="button" class="variant p-3 rounded-2xl border border-hairline bg-paper text-left transition-all hover:border-ochre cursor-pointer aria-pressed:border-forest aria-pressed:bg-forest aria-pressed:text-white"
                                data-variant="Pot 24cm" data-price="740000" aria-pressed="false">
                            <span class="block text-xs font-bold">Pot 24 cm Jumbo</span>
                            <span class="block text-[11px] font-mono mt-0.5 opacity-80">Rp 740.000</span>
                        </button>
                    </div>
                </div>

                <!-- Quantity & Add to Cart Action -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                    <!-- Stepper -->
                    <div class="flex items-center justify-between border border-hairline rounded-full bg-paper px-3 py-1.5 h-12 flex-shrink-0" data-qty>
                        <button type="button" class="w-8 h-8 rounded-full hover:bg-glass flex items-center justify-center text-forest text-lg font-bold" aria-label="Kurangi">&minus;</button>
                        <input type="number" min="1" value="1" data-qty-input class="w-12 text-center font-mono font-bold text-sm bg-transparent focus:outline-none" aria-label="Jumlah" />
                        <button type="button" class="w-8 h-8 rounded-full hover:bg-glass flex items-center justify-center text-forest text-lg font-bold" aria-label="Tambah">&plus;</button>
                    </div>

                    <!-- Add Button -->
                    <button type="button" class="btn btn-primary text-sm h-12 flex-1 shadow-md"
                            data-add="monstera-deliciosa" data-name="Monstera Deliciosa" data-price="420000"
                            data-image="https://images.unsplash.com/photo-1545241047-6083a3684587?w=200&q=72&auto=format&fit=crop" data-variant="Pot 17cm">
                        Tambah ke Keranjang
                    </button>

                    <!-- Gift CTA -->
                    <a href="{{ route('cart') }}" class="btn btn-ochre text-sm h-12 px-5 flex-shrink-0" data-toast="Tanaman ini otomatis siap di-unboxing secara digital saat Anda mencentang opsi Hadiah di Keranjang!">
                        🎁 Beli Sebagai Hadiah
                    </a>
                </div>

                <!-- Value Guarantee Notice -->
                <p class="text-xs text-forest-3 flex items-center gap-2 pt-1 font-mono">
                    <svg class="w-4 h-4 text-stock flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>Gratis Pengiriman Jawa-Bali &bull; Garansi Hidup 30 Hari Tanpa Debat</span>
                </p>

                <!-- Care Specification Table (dl format) -->
                <div class="bg-paper border border-hairline rounded-2xl p-5 space-y-4">
                    <h3 class="font-display font-bold text-sm text-forest uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-ochre" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                        Spesifikasi Parameter Perawatan (Care Specs)
                    </h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft">
                            <dt class="text-forest-3 font-medium">Kebutuhan Cahaya</dt>
                            <dd class="text-forest font-semibold text-right">Terang Tidak Langsung (Indirect)</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft">
                            <dt class="text-forest-3 font-medium">Interval Penyiraman</dt>
                            <dd class="text-forest font-semibold text-right">1x Seminggu (7 Hari)</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft">
                            <dt class="text-forest-3 font-medium">Suhu & Kelembapan</dt>
                            <dd class="text-forest font-semibold text-right">18°C &ndash; 28°C / 60%</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft">
                            <dt class="text-forest-3 font-medium">Keamanan Hewan</dt>
                            <dd class="text-sale font-semibold text-right">Beracun Jika Dikunyah</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft">
                            <dt class="text-forest-3 font-medium">Lokasi Ideal</dt>
                            <dd class="text-forest font-semibold text-right">Indoor (Ruang Tamu / Meja)</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft">
                            <dt class="text-forest-3 font-medium">Estimasi Serapan CO₂</dt>
                            <dd class="text-stock font-semibold text-right">4.8 kg / tahun</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-hairline-soft sm:col-span-2">
                            <dt class="text-forest-3 font-medium">Tingkat Kesulitan</dt>
                            <dd class="text-forest font-semibold text-right">Mudah &bull; Sangat Cocok untuk Pemula</dd>
                        </div>
                    </dl>
                </div>

                <!-- Interactive Accordions -->
                <div class="space-y-3">
                    <!-- Accordion 1: Apa yang Anda Terima -->
                    <div class="border border-hairline rounded-2xl bg-paper overflow-hidden">
                        <button type="button" class="acc__btn w-full p-4 text-left font-display font-semibold text-sm text-forest flex items-center justify-between cursor-pointer" aria-expanded="true" aria-controls="panel-1">
                            <span>Isi Paket Tanaman yang Dikirim</span>
                            <svg class="acc__icon w-4 h-4 text-forest-3 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div id="panel-1" class="px-4 pb-4 text-xs text-forest-2 leading-relaxed border-t border-hairline-soft pt-3">
                            Satu tanaman Monstera Deliciosa usia 2 tahun dalam nursery pot ramah lingkungan (17cm) yang telah berakar kuat, media tanam kaya nutrisi bebas gambut (peat-free), tatakan pot tahan air, dan kartu panduan perawatan fisik lengkap.
                        </div>
                    </div>

                    <!-- Accordion 2: Pengiriman & Garansi -->
                    <div class="border border-hairline rounded-2xl bg-paper overflow-hidden">
                        <button type="button" class="acc__btn w-full p-4 text-left font-display font-semibold text-sm text-forest flex items-center justify-between cursor-pointer" aria-expanded="false" aria-controls="panel-2">
                            <span>SOP Pengemasan Khusus & Garansi 30 Hari</span>
                            <svg class="acc__icon w-4 h-4 text-forest-3 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div id="panel-2" hidden class="px-4 pb-4 text-xs text-forest-2 leading-relaxed border-t border-hairline-soft pt-3">
                            Dikemas menggunakan karton penahan goncangan khusus tanaman agar daun tidak patah dan media tidak tumpah. Dikirim setiap hari Selasa hingga Jumat agar tidak mengendap di gudang ekspedisi saat akhir pekan. Garansi 30 hari: tanaman mati dalam 30 hari langsung kami ganti baru!
                        </div>
                    </div>

                    <!-- Accordion 3: Konsultasi AI Doctor -->
                    <div class="border border-hairline rounded-2xl bg-paper overflow-hidden">
                        <button type="button" class="acc__btn w-full p-4 text-left font-display font-semibold text-sm text-forest flex items-center justify-between cursor-pointer" aria-expanded="false" aria-controls="panel-3">
                            <span>Konsultasi AI Plant Doctor (Ciora AI)</span>
                            <svg class="acc__icon w-4 h-4 text-forest-3 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div id="panel-3" hidden class="px-4 pb-4 text-xs text-forest-2 leading-relaxed border-t border-hairline-soft pt-3">
                            Punya pertanyaan tentang ujung daun monstera Anda yang kecokelatan? Anda dapat menanyakan langsung di chatbot asisten pintar kami atau mengunggah foto daun di fitur AI Plant Doctor untuk diagnosa instan berbasis model Ciora AI gratis.
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Related Products Section -->
        <section class="mt-20 pt-12 border-t border-hairline">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Pasangan Ideal</span>
                    <h2 class="font-display font-extrabold text-2xl text-forest mt-0.5 tracking-tight">Cocok Disandingkan Bersama</h2>
                </div>
                <a href="{{ route('shop') }}" class="btn btn-ghost text-xs">Jelajahi Semua</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Related 1 -->
                <article class="bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                    <a href="{{ route('product.show', ['slug' => 'rattlesnake-calathea']) }}" class="block aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                        <img src="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=700&q=72&auto=format&fit=crop" alt="Calathea" class="w-full h-full object-cover hover:scale-105 transition-transform" loading="lazy" />
                    </a>
                    <h3 class="font-display font-semibold text-sm text-forest mt-3 truncate">Rattlesnake Calathea</h3>
                    <div class="flex justify-between items-baseline mt-2 font-mono">
                        <span class="font-bold text-forest text-sm">Rp 340.000</span>
                        <span class="text-[11px] text-stock">Pet Safe</span>
                    </div>
                </article>

                <!-- Related 2 -->
                <article class="bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                    <a href="{{ route('product.show', ['slug' => 'ginseng-ficus-bonsai']) }}" class="block aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                        <img src="https://images.unsplash.com/photo-1512428813834-c702c7702b78?w=700&q=72&auto=format&fit=crop" alt="Bonsai" class="w-full h-full object-cover hover:scale-105 transition-transform" loading="lazy" />
                    </a>
                    <h3 class="font-display font-semibold text-sm text-forest mt-3 truncate">Ginseng Ficus Bonsai</h3>
                    <div class="flex justify-between items-baseline mt-2 font-mono">
                        <span class="font-bold text-forest text-sm">Rp 580.000</span>
                        <span class="text-[11px] text-low">Sisa 3 unit</span>
                    </div>
                </article>

                <!-- Related 3 -->
                <article class="bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                    <a href="{{ route('product.show', ['slug' => 'zebra-haworthia']) }}" class="block aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                        <img src="https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=700&q=72&auto=format&fit=crop" alt="Haworthia" class="w-full h-full object-cover hover:scale-105 transition-transform" loading="lazy" />
                    </a>
                    <h3 class="font-display font-semibold text-sm text-forest mt-3 truncate">Zebra Haworthia</h3>
                    <div class="flex justify-between items-baseline mt-2 font-mono">
                        <span class="font-bold text-forest text-sm">Rp 180.000</span>
                        <span class="text-[11px] text-stock">Pet Safe</span>
                    </div>
                </article>

                <!-- Related 4 -->
                <article class="bg-paper border border-hairline rounded-2xl p-3 hover:border-ochre transition-all shadow-sm" data-product>
                    <a href="{{ route('product.show', ['slug' => 'moon-cactus']) }}" class="block aspect-[4/5] rounded-xl overflow-hidden bg-glass">
                        <img src="https://images.unsplash.com/photo-1509937528035-ad76254b0356?w=700&q=72&auto=format&fit=crop" alt="Moon Cactus" class="w-full h-full object-cover hover:scale-105 transition-transform" loading="lazy" />
                    </a>
                    <h3 class="font-display font-semibold text-sm text-forest mt-3 truncate">Moon Cactus</h3>
                    <div class="flex justify-between items-baseline mt-2 font-mono">
                        <span class="font-bold text-forest text-sm">Rp 140.000</span>
                        <span class="text-[11px] text-ochre-deep">Baru Masuk</span>
                    </div>
                </article>
            </div>
        </section>
    </div>

@endsection
