@extends('layouts.app')

@section('title', 'Tentang Pembibitan — Plantify Official')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-16">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-forest-3 font-mono mb-6" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-ochre">Beranda</a>
            <span>/</span>
            <span class="text-forest font-semibold">Tentang Kami</span>
        </nav>

        <!-- Nursery Story Header -->
        <div class="max-w-3xl mb-12">
            <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Kisah Kami</span>
            <h1 class="font-display font-extrabold text-3xl sm:text-5xl text-forest mt-1.5 tracking-tight leading-tight">
                Kami Menumbuhkan Tanaman, Bukan Sekadar Menjualnya.
            </h1>
            <p class="text-sm sm:text-base text-forest-2 mt-4 leading-relaxed">
                Plantify lahir dari keyakinan sederhana: setiap orang berhak menikmati keasrian tanaman di rumahnya tanpa rasa bersalah melihat tanaman mati sia-sia. Kami menggabungkan bibit unggul pembibitan mandiri dengan panduan digital cermat dan kecerdasan buatan.
            </p>
        </div>

        <!-- Facts & Numbers Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 mb-16">
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-forest">100%</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">Bebas Gambut (Peat-Free)</p>
            </div>
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-forest">30 Hari</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">Garansi Hidup Ganti Baru</p>
            </div>
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-ochre-deep">14.8 Ton</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">CO₂ Terserap / Tahun</p>
            </div>
            <div class="bg-paper border border-hairline rounded-2xl p-6 text-center">
                <span class="font-display font-extrabold text-3xl sm:text-4xl text-forest">&gt; 5.000</span>
                <p class="text-xs text-forest-3 mt-1.5 font-medium">Plant Parent Terbantu</p>
            </div>
        </div>

        <!-- Editorial Story + Image -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center mb-16">
            <div class="space-y-4">
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-forest tracking-tight">
                    Mengapa Harus Dari Pembibitan Mandiri?
                </h2>
                <p class="text-xs sm:text-sm text-forest-2 leading-relaxed">
                    Banyak toko tanaman hias hanya berperan sebagai *dropshipper* atau pengimpor yang memajang tanaman stres dari perjalanan kontainer berhari-hari. Akibatnya, tanaman sering mati mendadak dalam kurun seminggu setelah tiba di rumah Anda.
                </p>
                <p class="text-xs sm:text-sm text-forest-2 leading-relaxed">
                    Di Plantify, setiap anakan tanaman dirawat di greenhouse dengan ventilasi terkontrol, nutrisi organik, dan media tanam kokoh. Sebelum masuk katalog pengiriman, tanaman telah melewati fase aklimatisasi minimal 60 hari untuk memastikan sistem perakarannya siap menghadapi kondisi ruangan baru Anda.
                </p>
            </div>

            <div class="relative">
                <img src="https://images.unsplash.com/photo-1470058869958-2a77ade41c02?w=900&q=72&auto=format&fit=crop" alt="Greenhouse Plantify" class="w-full aspect-[4/3] object-cover rounded-3xl shadow-lg border border-hairline" loading="lazy" />
            </div>
        </div>

        <!-- Eco-Impact & Methodology Section (PRD Bagian 6.3) -->
        <div class="bg-paper border border-hairline rounded-3xl p-6 sm:p-10 mb-16 shadow-sm">
            <div class="max-w-2xl">
                <span class="text-xs font-mono font-semibold text-stock uppercase tracking-wider">Komitmen Keberlanjutan</span>
                <h3 class="font-display font-bold text-xl sm:text-2xl text-forest mt-1 tracking-tight">
                    Metodologi Eco-Impact & Pelacak Serapan CO₂
                </h3>
                <p class="text-xs sm:text-sm text-forest-3 mt-2 leading-relaxed">
                    Setiap tanaman di Plantify memiliki atribut indeks serapan karbon (<code class="bg-glass text-forest px-1 py-0.5 rounded text-[11px]">co2_absorption_rate</code>) yang dihitung berdasarkan luas permukaan daun, biomassa, dan laju transpirasi. Melalui fitur "My Digital Garden", Anda dapat memantau kontribusi nyata kebun mini Anda dalam menyerap emisi karbon perkotaan setiap harinya.
                </p>
            </div>
        </div>

        <!-- Nursery Visit & Contact Form -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div class="space-y-4">
                <span class="text-xs font-mono font-semibold text-ochre-deep uppercase tracking-wider">Kunjungi Nursery</span>
                <h3 class="font-display font-bold text-2xl text-forest tracking-tight">Pintu Green House Kami Selalu Terbuka</h3>
                <p class="text-xs sm:text-sm text-forest-3 leading-relaxed">
                    Ingin memilih tanaman secara langsung atau berkonsultasi mengenai penataan tanaman di kantor dan rumah? Silakan berkunjung ke nursery kami.
                </p>
                <div class="space-y-2 text-xs text-forest-2 pt-2">
                    <p><strong>Alamat:</strong> Jl. Agrowisata Hijau No. 24, Kawasan Nursery Selatan</p>
                    <p><strong>Jam Operasional:</strong> Selasa &ndash; Minggu (08.30 &ndash; 17.00 WIB)</p>
                    <p><strong>Email Kontak:</strong> nursery@plantify.ramadadawah.my.id</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-paper border border-hairline rounded-3xl p-6 sm:p-8 shadow-sm">
                <h4 class="font-display font-bold text-lg text-forest mb-4">Kirim Pesan atau Pertanyaan</h4>
                <form class="space-y-4" data-demo-form="Terima kasih! Pesan Anda telah kami terima, tim kami akan membalasnya dalam 1x24 jam.">
                    <div>
                        <label class="block text-xs font-semibold text-forest mb-1">Nama Lengkap</label>
                        <input type="text" required placeholder="Nama Anda..." class="w-full h-11 px-4 text-xs bg-glass border border-hairline rounded-xl focus:outline-none focus:border-ochre text-forest" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-forest mb-1">Email</label>
                        <input type="email" required placeholder="nama@email.com..." class="w-full h-11 px-4 text-xs bg-glass border border-hairline rounded-xl focus:outline-none focus:border-ochre text-forest" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-forest mb-1">Pesan atau Konsultasi</label>
                        <textarea rows="4" required placeholder="Ceritakan pertanyaan perawatan atau kebutuhan tanaman Anda..." class="w-full p-3 text-xs bg-glass border border-hairline rounded-xl focus:outline-none focus:border-ochre text-forest"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary text-xs w-full py-3">
                        Kirim Pesan ke Tim Nursery
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
