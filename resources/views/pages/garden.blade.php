@extends('layouts.app')

@section('title', 'My Digital Garden — Kebun Digital & Jadwal Perawatan Plantify')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Beranda</a>
            <span>/</span>
            <span class="text-forest font-semibold">My Digital Garden</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-stock animate-pulse"></span>
                    <span class="text-xs font-mono font-semibold text-stock uppercase tracking-wider">Kebun Virtual Aktif</span>
                </div>
                <h1 class="font-display font-extrabold text-2xl sm:text-4xl text-forest mt-1 tracking-tight">My Digital Garden</h1>
                <p class="text-xs sm:text-sm text-forest-3 mt-1.5 max-w-xl">
                    Setiap tanaman yang Anda beli otomatis diadopsi ke dashboard ini dengan jadwal siram, pemupukan, dan pengingat cuaca cerdas.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('shop') }}" class="btn btn-primary text-xs">
                    + Tambah Tanaman Baru
                </a>
            </div>
        </div>

        <!-- 1. Stats Overview Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <!-- Card 1: Total Plants -->
            <div class="bg-paper border border-hairline rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-forest-3">Koleksi Tanaman Anda</p>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-1">4 <span class="text-sm font-sans font-normal text-forest-4">tanaman</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-stock-wash text-stock flex items-center justify-center font-bold text-xl">
                    🌿
                </div>
            </div>

            <!-- Card 2: Watering Today -->
            <div class="bg-paper border border-hairline rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-forest-3">Jadwal Siram Hari Ini</p>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-ochre-deep mt-1">1 <span class="text-sm font-sans font-normal text-forest-4">perlu disiram</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-ochre-wash text-ochre flex items-center justify-center font-bold text-xl">
                    💧
                </div>
            </div>

            <!-- Card 3: Eco-Impact CO2 -->
            <div class="bg-paper border border-hairline rounded-2xl p-5 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-forest-3">Total Serapan CO₂</p>
                    <p class="font-display font-extrabold text-2xl sm:text-3xl text-forest mt-1">14.6 <span class="text-sm font-sans font-normal text-forest-4">kg / tahun</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl">
                    🍃
                </div>
            </div>
        </div>

        <!-- 2. Weather-Aware Reminder Banner (PRD USP Bagian 6.2) -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 sm:p-6 mb-10 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 text-lg">
                    🌧️
                </div>
                <div>
                    <h3 class="font-display font-bold text-sm sm:text-base text-blue-950">
                        Sinkronisasi Cuaca Aktif (OpenWeatherMap &bull; Jakarta Selatan)
                    </h3>
                    <p class="text-xs text-blue-800 mt-1 max-w-2xl leading-relaxed">
                        Prakiraan hujan lebat dan kelembapan tinggi (&gt;80%) terdeteksi hari ini. Jadwal penyiraman otomatis <b>ditunda 1 hari</b> khusus untuk tanaman dengan label <span class="bg-blue-200/60 px-1.5 py-0.5 rounded text-[11px] font-semibold">Outdoor</span> demi mencegah kebusukan akar akibat media terlalu basah.
                    </p>
                </div>
            </div>
            <button type="button" class="btn btn-ghost text-xs border-blue-300 text-blue-900 bg-white hover:bg-blue-50 flex-shrink-0" data-toast="Status cuaca diperbarui dari cache OpenWeatherMap">
                Cek Data Cuaca
            </button>
        </div>

        <!-- 3. Active Garden Plants Grid -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-display font-bold text-xl text-forest tracking-tight">Daftar Tanaman di Kebun Anda</h2>
                <span class="text-xs text-forest-4 font-mono">Diurutkan berdasarkan jadwal siram terdekat</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Plant 1: Monstera Deliciosa -->
                <div class="bg-paper border border-hairline rounded-2xl p-5 flex flex-col sm:flex-row gap-5 shadow-sm hover:border-ochre transition-all">
                    <img src="https://images.unsplash.com/photo-1545241047-6083a3684587?w=400&q=72&auto=format&fit=crop" alt="Monstera" class="w-full sm:w-36 aspect-square object-cover rounded-xl bg-glass flex-shrink-0" />
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="badge badge-stock text-[10px]">Indoor &bull; Ruang Tamu</span>
                                <span class="text-[11px] font-mono text-forest-4">Adopsi: 12 Okt 2026</span>
                            </div>
                            <h3 class="font-display font-bold text-base text-forest mt-1.5">"Monty Si Monstera"</h3>
                            <p class="text-xs text-forest-4 italic font-serif">Monstera deliciosa &bull; Pot 17cm</p>
                            
                            <!-- Watering Progress -->
                            <div class="mt-4 space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold">
                                    <span class="text-ochre-deep flex items-center gap-1">
                                        💧 Siram Hari Ini!
                                    </span>
                                    <span class="font-mono text-forest-3">Terakhir: 7 hari lalu</span>
                                </div>
                                <div class="w-full h-2 bg-hairline rounded-full overflow-hidden">
                                    <div class="h-full bg-ochre-deep rounded-full" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4 mt-3 border-t border-hairline-soft">
                            <button type="button" class="btn btn-primary text-xs flex-1 py-2" data-toast="Jadwal siram Monty diperbarui ke 7 hari ke depan!">
                                ✓ Sudah Disiram
                            </button>
                            <a href="{{ route('care') }}" class="btn btn-ghost text-xs px-3 py-2" title="Diagnosa AI">
                                🔍 AI Check
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Plant 2: Rattlesnake Calathea -->
                <div class="bg-paper border border-hairline rounded-2xl p-5 flex flex-col sm:flex-row gap-5 shadow-sm hover:border-ochre transition-all">
                    <img src="https://images.unsplash.com/photo-1602923668104-8f9e03e77e62?w=400&q=72&auto=format&fit=crop" alt="Calathea" class="w-full sm:w-36 aspect-square object-cover rounded-xl bg-glass flex-shrink-0" />
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="badge badge-stock text-[10px]">Indoor &bull; Meja Kerja</span>
                                <span class="text-[11px] font-mono text-forest-4">Adopsi: 18 Okt 2026</span>
                            </div>
                            <h3 class="font-display font-bold text-base text-forest mt-1.5">"Cleo Calathea"</h3>
                            <p class="text-xs text-forest-4 italic font-serif">Calathea lancifolia &bull; Pot 14cm</p>
                            
                            <div class="mt-4 space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold">
                                    <span class="text-stock flex items-center gap-1">
                                        💧 2 Hari Lagi
                                    </span>
                                    <span class="font-mono text-forest-3">Terakhir: 2 hari lalu</span>
                                </div>
                                <div class="w-full h-2 bg-hairline rounded-full overflow-hidden">
                                    <div class="h-full bg-stock rounded-full" style="width: 50%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4 mt-3 border-t border-hairline-soft">
                            <button type="button" class="btn btn-ghost text-xs flex-1 py-2" data-toast="Status disiram dicatat!">
                                ✓ Tandai Siram
                            </button>
                            <a href="{{ route('care') }}" class="btn btn-ghost text-xs px-3 py-2" title="Diagnosa AI">
                                🔍 AI Check
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Plant 3: Boxwood Topiary (Outdoor with Weather Delay) -->
                <div class="bg-paper border border-hairline rounded-2xl p-5 flex flex-col sm:flex-row gap-5 shadow-sm hover:border-ochre transition-all">
                    <img src="https://images.unsplash.com/photo-1520412099551-62b6bafeb5bb?w=400&q=72&auto=format&fit=crop" alt="Topiary" class="w-full sm:w-36 aspect-square object-cover rounded-xl bg-glass flex-shrink-0" />
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase">Outdoor &bull; Balkon</span>
                                <span class="text-[11px] font-mono text-blue-600 font-semibold">🌧️ Ditunda Hujan</span>
                            </div>
                            <h3 class="font-display font-bold text-base text-forest mt-1.5">"Bobi Boxwood"</h3>
                            <p class="text-xs text-forest-4 italic font-serif">Buxus sempervirens &bull; Pot 24cm</p>
                            
                            <div class="mt-4 space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold">
                                    <span class="text-blue-700 flex items-center gap-1">
                                        🌧️ Ditunda 1 Hari (Hujan)
                                    </span>
                                    <span class="font-mono text-forest-3">Kelembapan 85%</span>
                                </div>
                                <div class="w-full h-2 bg-hairline rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: 30%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4 mt-3 border-t border-hairline-soft">
                            <button type="button" class="btn btn-ghost text-xs flex-1 py-2" data-toast="Status penyiraman manual dicatat!">
                                ✓ Siram Manual
                            </button>
                            <a href="{{ route('care') }}" class="btn btn-ghost text-xs px-3 py-2" title="Diagnosa AI">
                                🔍 AI Check
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Plant 4: Moon Cactus -->
                <div class="bg-paper border border-hairline rounded-2xl p-5 flex flex-col sm:flex-row gap-5 shadow-sm hover:border-ochre transition-all">
                    <img src="https://images.unsplash.com/photo-1509937528035-ad76254b0356?w=400&q=72&auto=format&fit=crop" alt="Cactus" class="w-full sm:w-36 aspect-square object-cover rounded-xl bg-glass flex-shrink-0" />
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="badge badge-stock text-[10px]">Indoor &bull; Jendela</span>
                                <span class="text-[11px] font-mono text-forest-4">Adopsi: 20 Okt 2026</span>
                            </div>
                            <h3 class="font-display font-bold text-base text-forest mt-1.5">"Ruby Si Kaktus"</h3>
                            <p class="text-xs text-forest-4 italic font-serif">Gymnocalycium &bull; Pot 10cm</p>
                            
                            <div class="mt-4 space-y-1.5">
                                <div class="flex justify-between text-xs font-semibold">
                                    <span class="text-stock flex items-center gap-1">
                                        💧 18 Hari Lagi
                                    </span>
                                    <span class="font-mono text-forest-3">Interval 30 hari</span>
                                </div>
                                <div class="w-full h-2 bg-hairline rounded-full overflow-hidden">
                                    <div class="h-full bg-stock rounded-full" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4 mt-3 border-t border-hairline-soft">
                            <button type="button" class="btn btn-ghost text-xs flex-1 py-2" data-toast="Kaktus masih dalam kondisi prima tanpa perlu disiram!">
                                ✓ Cek Media
                            </button>
                            <a href="{{ route('care') }}" class="btn btn-ghost text-xs px-3 py-2" title="Diagnosa AI">
                                🔍 AI Check
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
