# Dokumen Implementasi UI & Desain Sistem — Plantify Official

**Proyek:** Plantify Official (Plant Marketplace & Management Website)  
**Versi:** 1.0 (Tailwind CSS v4 & Laravel 13 Blade Architecture)  
**Status:** Terverifikasi & Aktif di Dedicated Production Domain (`https://plantify.ramadadawah.my.id`)  
**Basis Template:** Fernwick Plant Shop Ecommerce (Disesuaikan penuh dengan PRD v1.3)

---

## 1. Ringkasan Eksekutif & Arah Desain

Template UI dari berkas `fernwick-plant-shop-ecommerce-html-template.zip` telah berhasil diadaptasi dan diintegrasikan secara menyeluruh ke dalam arsitektur **Laravel 13** dan **Tailwind CSS v4** pada proyek `plantify`.

### Filosofi Desain: "Greenhouse Glasshouse Ground & Shop-First"
- **Color Identity:** Warna hijau diperlakukan sebagai *ink* (teks utama `#14251a`), bukan sekadar aksen. Latar belakang menggunakan warna tanah rumah kaca dingin (*pale glasshouse ground* `#edf1ea`) dengan kartu putih bersih (*crisp paper* `#fbfcfa`), dipertegas aksen kuningan (*brass ochre* `#c9861f`) untuk tombol call-to-action (CTA).
- **Shop-First Layout:** Berbeda dari website konvensional yang menyajikan hero marketing raksasa yang memaksa pengguna menggulir ke bawah, Plantify langsung membuka etalase dengan **Category Rail horizontal** dan **koleksi siap kirim**.
- **Dual-Access Live:** Dapat diakses secara publik melalui domain [https://plantify.ramadadawah.my.id](https://plantify.ramadadawah.my.id) dan internal via Tailscale `http://100.108.222.30:8805`.

---

## 2. Arsitektur Desain Sistem & Konfigurasi Tailwind CSS v4

Tailwind CSS v4 menggunakan konfigurasi **CSS-first** melalui blok `@theme` di dalam berkas `resources/css/app.css`:

```css
@import 'tailwindcss';

@theme {
    /* Palet Warna Primer & Sekunder */
    --color-glass: #edf1ea;          /* Latar belakang halaman */
    --color-glass-dark: #e1e9dc;     /* Shading kontainer sekunder */
    --color-paper: #fbfcfa;          /* Kartu produk & panel formulir */
    --color-forest: #14251a;         /* Teks utama (Ink) */
    --color-forest-dark: #0b150f;    /* Kontras footer & drawer */
    --color-forest-2: #283e30;       /* Teks sekunder */
    --color-forest-3: #526759;       /* Keterangan & sub-label */
    --color-forest-4: #829588;       /* Garis halus & placeholder */
    --color-hairline: #cfdac9;       /* Border pemisah standar */
    --color-hairline-soft: #dfe7da;  /* Border pemisah lembut */

    /* Aksen & Status Fungsional Toko */
    --color-ochre: #c9861f;          /* Aksen tombol utama (CTA) */
    --color-ochre-deep: #a66c12;     /* Hover & teks penekanan */
    --color-ochre-wash: rgba(201, 134, 31, 0.12);
    --color-sale: #b4462b;           /* Status diskon / promo */
    --color-sale-wash: rgba(180, 70, 43, 0.10);
    --color-stock: #3d6b4a;          /* Status stok tersedia / pet-safe */
    --color-stock-wash: rgba(61, 107, 74, 0.12);
    --color-low: #a8720d;            /* Peringatan stok menipis */
    --color-low-wash: rgba(168, 114, 13, 0.12);

    /* Tipografi */
    --font-display: 'Epilogue', ui-sans-serif, system-ui, sans-serif;
    --font-sans: 'Be Vietnam Pro', ui-sans-serif, system-ui, sans-serif;
    --font-mono: 'Sometype Mono', ui-monospace, 'SF Mono', monospace;
}
```

### Standar Utilitas & Komponen
- `.btn`: Tombol interaktif dengan target sentuh mobile $\ge 44\text{px}$ (`min-height: 44px; padding: 0.625rem 1.25rem; border-radius: 9999px;`).
- `.badge`: Tag penanda status fungsional (`.badge-sale`, `.badge-stock`, `.badge-low`, `.badge-new`).
- `.chip`: Tombol filter cepat dengan status aktif `aria-pressed="true"`.
- `.drawer` & `.scrim`: Panel keranjang belanja geser kanan (*slide-out drawer*) dengan latar belakang blur.
- `.toasts`: Notifikasi mengambang dengan transisi otomatis 2.8 detik.

---

## 3. Peta Halaman & Struktur Blade Templates

Seluruh tampilan telah dipecah ke dalam struktur modular yang rapi di `resources/views/`:

```
resources/views/
├── layouts/
│   └── app.blade.php           # Master layout: Announcement, Header, Cart Drawer, Trust, Footer
└── pages/
    ├── home.blade.php          # Beranda: Category Rail, Featured, Janji Nursery, USP PRD, Jurnal
    ├── shop.blade.php          # Katalog: Filter sidebar, chips, sorting, 10 produk, empty state
    ├── product.blade.php       # Detail Produk: Galeri multi-foto, varian pot, spesifikasi dl, accordion
    ├── cart.blade.php          # Keranjang: Ambang bebas ongkir, gift unboxing, order summary
    ├── garden.blade.php        # My Digital Garden: Jadwal siram, weather-aware reminder, CO2 tracker
    ├── care.blade.php          # Panduan & AI Doctor: Diagnosa daun Ciora AI Vision, artikel edukasi
    └── about.blade.php         # Tentang Kami: Kisah nursery, komitmen peat-free, formulir kontak
```

### Rincian Penyesuaian Sesuai PRD v1.3

| Halaman | Fitur PRD yang Diintegrasikan | Penyesuaian Desain |
| :--- | :--- | :--- |
| **Header & Nav** | Live Basket Counter, Search, Navigation Links | Tautan ke My Garden (indikator hijau berkedip), AI Doctor, Katalog |
| **Cart Drawer** | Persistensi keranjang belanja (`localStorage`), steppers, free shipping bar | Tampil tanpa memuat ulang halaman (*instant slide-in*), tombol checkout terhubung |
| **Home (`/`)** | Fitur Inovasi PRD (AI Doctor, Garden, Weather, Gift) | Bagian khusus "Teknologi Eksklusif Plantify" 4 kolom |
| **Katalog (`/shop`)** | Paginasi & Filter (Indoor, Low-light, Pet-Safe, Sukulen) | Filter sidebar responsif, chips filter cepat, sorting harga/nama, live counter |
| **Detail (`/product`)**| Care Spec Table (Cahaya, Air, Suhu, Toksisitas, Serapan CO₂) | Tabel `<dl>` terstruktur, pemilih ukuran pot (12cm, 17cm, 24cm) dengan harga dinamis |
| **Keranjang (`/cart`)**| Opsi "Gift a Plant with Digital Unboxing" (PRD 6.4) | Checkbox interaktif dengan field nama penerima & ucapan personal untuk tautan `/gift/{token}` |
| **Garden (`/garden`)** | My Digital Garden & Weather-Aware Reminder (PRD 6.1 & 6.2) | Peringatan cuaca hujan (OpenWeatherMap sync) otomatis menunda siram tanaman outdoor |
| **Care (`/care`)** | AI Plant Doctor (PRD 5.2 — Ciora AI Vision) | Kartu simulasi unggah foto daun bergejala dengan diagnosis terstruktur (JSON output) |
| **About (`/about`)** | Eco-Impact & $\text{CO}_2$ Tracker (PRD 6.3) | Penjelasan metodologi serapan karbon dan filosofi budidaya mandiri tanpa perantara |

---

## 4. Matriks Pengujian Responsif Multi-Device

Sesuai SOP ketat di `AGENTS.md` (Lapis 2), UI telah diuji dan dioptimalkan untuk seluruh kategori perangkat:

```
+-------------------------------------------------------------------------------------------------------+
| Ukuran Layar              | Breakpoint  | Perilaku Tata Letak & Navigasi                              |
+-------------------------------------------------------------------------------------------------------+
| Mobile Kecil (320px–375px)| (default)   | • Grid 1 kolom (stacked)                                    |
| (iPhone SE, Galaxy A)     |             | • Category rail swipeable horizontal (snap proximity)       |
|                           |             | • Menu burger aktif, drawer navigasi penuh                  |
|                           |             | • Target sentuh tombol minimal 44px (touch-friendly)        |
|                           |             | • overflow-x: clip mutlak (zero horizontal scrolling)       |
+-------------------------------------------------------------------------------------------------------+
| Mobile Standar (390px–425px)| (default) | • Grid 1 kolom kartu lebar penuh                            |
| (iPhone 14/15, Pixel)     |             | • Quick add overlay selalu tampil pada layar sentuh         |
|                           |             | • Form input selebar layar penuh (w-full)                   |
+-------------------------------------------------------------------------------------------------------+
| Tablet (768px – 1024px)   | sm:, md:    | • Grid katalog 2 s/d 3 kolom                                |
| (iPad Mini, Surface)      |             | • Navigasi berpindah ke top bar                             |
|                           |             | • Sidebar filter mulai aktif di samping                     |
|                           |             | • Detail produk galeri berdampingan                         |
+-------------------------------------------------------------------------------------------------------+
| Desktop (1280px+)         | lg:, xl:    | • Grid katalog 3 s/d 4 kolom                                |
| (Monitor 1080p, 1440p)    |             | • Search bar permanen di header                             |
|                           |             | • Kontainer dibatasi max-w-7xl (1280px) mx-auto             |
|                           |             | • Konten tidak melar tak beraturan di layar ultra-wide      |
+-------------------------------------------------------------------------------------------------------+
```

---

## 5. Logika Interaktivitas Frontend (`resources/js/app.js`)

Semua interaksi berjalan cepat menggunakan Vanilla JavaScript modern tanpa dependensi berat (kompatibel penuh dengan Livewire 3):
1. **Keranjang Belanja Mandiri (`localStorage`):**
   - Disimpan di `plantify-cart`.
   - Perhitungan total berbasis angka bulat Rupiah (Rp).
   - Ambang batas gratis ongkir: **Rp 250.000** dengan bilah progres animasi.
2. **Galeri Thumbnail:** Mengganti foto utama dengan transisi halus tanpa me-reload DOM.
3. **Pemilih Ukuran Pot:** Memperbarui harga secara instan (`data-variant-price`).
4. **Penyaring Katalog:** Menggabungkan filter chips dan checkbox dengan logika AND.
5. **Pengurutan Cerdas:** Mengurutkan ulang node DOM produk berdasarkan harga terendah, tertinggi, atau nama.
6. **Sistem Notifikasi Toast:** Menginformasikan aksi tambah keranjang, simpan wishlist, atau penyalinan tautan.

---

## 6. Verifikasi & Standar Penerimaan

- [x] Kompilasi Vite & Tailwind CSS v4 berhasil tanpa peringatan sintaks: `/opt/adanix/bin/adanix build plantify`.
- [x] Seluruh 7 route publik merespons status **HTTP 200 OK**:
  - `GET /` &rarr; 200 OK
  - `GET /shop` &rarr; 200 OK
  - `GET /product` &rarr; 200 OK
  - `GET /cart` &rarr; 200 OK
  - `GET /garden` &rarr; 200 OK
  - `GET /care` &rarr; 200 OK
  - `GET /about` &rarr; 200 OK
- [x] Zero-Error pada `storage/logs/laravel.log`.
- [x] Reverse proxy Nginx (`plantify.ramadadawah.my.id`) aktif merutekan permintaan ke kontainer `plantify-app-1` (port 8805).
