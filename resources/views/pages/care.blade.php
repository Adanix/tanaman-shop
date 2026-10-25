@extends('layouts.app')

@section('title', 'Panduan Rawat & AI Plant Doctor — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Beranda</a>
            <span>/</span>
            <span class="text-forest font-semibold">Panduan Rawat & AI Doctor</span>
        </nav>

        <!-- Page Header -->
        <div class="mb-10">
            <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Perawatan Cerdas Berbasis AI</span>
            <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-forest mt-1 tracking-tight">AI Plant Doctor & Panduan Edukasi</h1>
            <p class="text-xs sm:text-sm text-forest-3 mt-1.5 max-w-2xl">
                Diagnosa masalah daun secara instan dengan Ciora AI Vision gratis dan pelajari teknik perawatan ilmiah agar tanaman Anda tumbuh subur sepanjang tahun.
            </p>
        </div>

        <!-- 1. AI Plant Doctor Interactive Showcase (PRD Bagian 5.2) -->
        <section class="bg-forest text-glass rounded-3xl p-6 sm:p-10 mb-14 border border-forest-2 relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left Details (col-span-6) -->
                <div class="lg:col-span-6 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest-2 border border-forest-3 text-ochre text-xs font-mono font-semibold">
                        <span>🤖 Didukung Ciora AI Vision (Model Gratis)</span>
                    </div>
                    <h2 class="font-display font-extrabold text-2xl sm:text-3xl text-white tracking-tight">
                        Punya Tanaman Sakit? Foto & Diagnosa Sekarang
                    </h2>
                    <p class="text-xs sm:text-sm text-hairline leading-relaxed">
                        Cukup unggah foto daun yang menguning, bercak cokelat, atau layu. Model vision Ciora AI menganalisis gejala dalam hitungan detik dan menyusun instruksi penanganan langkah demi langkah.
                    </p>
                    
                    <div class="space-y-2 pt-2 text-xs text-hairline-soft font-mono">
                        <div class="flex items-center gap-2">
                            <span class="text-stock font-bold">✓</span>
                            <span>Kuota Harian: 5 diagnosa / hari per pengguna terverifikasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-stock font-bold">✓</span>
                            <span>Privasi Terjaga: Foto disimpan di disk privat & ditinjau dengan signed URL</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-stock font-bold">✓</span>
                            <span>Rekomendasi Produk: Disinkronkan dengan stok aktif katalog Plantify</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-forest-4 italic pt-1">
                        *Disclaimer: Hasil AI bersifat indikatif dan edukatif, bukan pengganti diagnosis sertifikasi laboratorium pertanian.
                    </p>
                </div>

                <!-- Right: Interactive Upload / Result Card (col-span-6) -->
                <div class="lg:col-span-6 bg-paper text-forest rounded-2xl p-6 border border-hairline shadow-xl">
                    <h3 class="font-display font-bold text-base text-forest mb-4 flex items-center justify-between">
                        <span>Simulasi Diagnosa Daun</span>
                        <span class="badge badge-stock text-[10px]">Status: Siap Analisis</span>
                    </h3>

                    <!-- Upload Zone Simulation -->
                    <div class="border-2 border-dashed border-hairline rounded-xl p-6 text-center hover:border-ochre transition-colors bg-glass/40 cursor-pointer"
                         onclick="document.getElementById('demo-diagnosis-result').classList.toggle('hidden')">
                        <svg class="w-10 h-10 text-forest-3 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p class="text-xs font-semibold text-forest">Klik untuk Unggah Foto Daun Tanaman</p>
                        <p class="text-[11px] text-forest-4 mt-0.5">Format JPG, PNG, atau WebP (Maksimal 4 MB)</p>
                    </div>

                    <!-- Simulated Result (Hidden until clicked) -->
                    <div id="demo-diagnosis-result" class="mt-4 pt-4 border-t border-hairline space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-forest">Hasil Analisis Ciora AI Vision</span>
                            <span class="badge badge-sale text-[10px]">Tingkat: Ringan (Early Stage)</span>
                        </div>
                        <div class="bg-glass rounded-xl p-3.5 space-y-2 text-xs">
                            <p class="font-bold text-ochre-deep">Diagnosa: Gejala Awal Overwatering (Edema Daun)</p>
                            <p class="text-forest-2 leading-relaxed">
                                Sel daun kelebihan air akibat frekuensi penyiraman terlalu rapat dan sirkulasi udara kurang. Tidak ditemukan indikasi infeksi jamur atau bakteri patogen.
                            </p>
                            <div class="pt-2 border-t border-hairline-soft">
                                <p class="font-semibold text-forest mb-1">Langkah Penyelamatan Cepat:</p>
                                <ol class="list-decimal list-inside space-y-1 text-forest-3">
                                    <li>Hentikan penyiraman selama 7-10 hari hingga media mengering.</li>
                                    <li>Pindahkan ke lokasi dengan sirkulasi angin lebih baik.</li>
                                    <li>Gunakan pot dengan lubang drainase terbuka.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Care Guides Articles Library -->
        <section aria-labelledby="guides-library-h">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <h2 id="guides-library-h" class="font-display font-extrabold text-xl sm:text-2xl text-forest tracking-tight">Koleksi Panduan Lengkap</h2>
                    <p class="text-xs sm:text-sm text-forest-3 mt-1">Teknik praktis dari nursery untuk merawat berbagai spesies tanaman.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Guide 1 -->
                <article class="bg-paper border border-hairline rounded-2xl overflow-hidden hover:border-ochre transition-all group flex flex-col shadow-sm">
                    <img src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=700&q=72&auto=format&fit=crop" alt="Mengganti Pot" class="w-full aspect-[16/10] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-[11px] font-mono text-forest-4 uppercase">
                                <span class="text-ochre-deep font-semibold">Repotting</span>
                                <span>&bull;</span>
                                <span>6 Menit Baca</span>
                            </div>
                            <h3 class="font-display font-bold text-forest text-base sm:text-lg mt-2 group-hover:text-ochre-deep transition-colors">
                                Kapan Waktu Tepat Mengganti Pot Tanaman?
                            </h3>
                            <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                                Akar yang mulai keluar dari lubang bawah pot hanyalah tanda awal. Tiga tanda lainnya: tanah cepat kering, tanaman berhenti bertunas, dan daun bagian bawah mulai rontok berkala.
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-hairline-soft flex items-center justify-between text-xs">
                            <span class="text-forest-4 font-mono">Oleh Tim Hortikultura</span>
                            <span class="font-semibold text-forest group-hover:text-ochre-deep">&rarr;</span>
                        </div>
                    </div>
                </article>

                <!-- Guide 2 -->
                <article class="bg-paper border border-hairline rounded-2xl overflow-hidden hover:border-ochre transition-all group flex flex-col shadow-sm">
                    <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?w=700&q=72&auto=format&fit=crop" alt="Membaca Cahaya" class="w-full aspect-[16/10] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-[11px] font-mono text-forest-4 uppercase">
                                <span class="text-ochre-deep font-semibold">Pencahayaan</span>
                                <span>&bull;</span>
                                <span>4 Menit Baca</span>
                            </div>
                            <h3 class="font-display font-bold text-forest text-base sm:text-lg mt-2 group-hover:text-ochre-deep transition-colors">
                                Membaca Arah Cahaya Alami di Setiap Sudut Ruang
                            </h3>
                            <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                                Jendela menghadap timur memberikan sinar pagi yang lembut, sementara jendela barat menghadirkan terik panas yang bisa membakar daun tipis. Pelajari zonasi jarak 1-2 meter dari jendela.
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-hairline-soft flex items-center justify-between text-xs">
                            <span class="text-forest-4 font-mono">Oleh Tim Hortikultura</span>
                            <span class="font-semibold text-forest group-hover:text-ochre-deep">&rarr;</span>
                        </div>
                    </div>
                </article>

                <!-- Guide 3 -->
                <article class="bg-paper border border-hairline rounded-2xl overflow-hidden hover:border-ochre transition-all group flex flex-col shadow-sm">
                    <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=700&q=72&auto=format&fit=crop" alt="Penyiraman" class="w-full aspect-[16/10] object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-[11px] font-mono text-forest-4 uppercase">
                                <span class="text-ochre-deep font-semibold">Penyiraman</span>
                                <span>&bull;</span>
                                <span>5 Menit Baca</span>
                            </div>
                            <h3 class="font-display font-bold text-forest text-base sm:text-lg mt-2 group-hover:text-ochre-deep transition-colors">
                                Hampir Semua Tanaman Mati Karena Terlalu Disayang
                            </h3>
                            <p class="text-xs text-forest-3 mt-2 leading-relaxed">
                                Jangan pernah menyiram tanaman berdasarkan hari kalender kaku. Masukkan jari 2-3 cm ke dalam media tanam: jika masih dingin dan lembap, tunda penyiraman beberapa hari lagi.
                            </p>
                        </div>
                        <div class="pt-4 mt-4 border-t border-hairline-soft flex items-center justify-between text-xs">
                            <span class="text-forest-4 font-mono">Oleh Tim Hortikultura</span>
                            <span class="font-semibold text-forest group-hover:text-ochre-deep">&rarr;</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>

@endsection
