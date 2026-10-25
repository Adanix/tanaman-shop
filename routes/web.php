<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Plantify Official Marketplace & Management
|--------------------------------------------------------------------------
|
| Routing untuk halaman etalase marketplace, detail produk, keranjang,
| fitur kebun virtual (My Digital Garden), panduan rawat & AI, serta profil nursery.
|
*/

// Beranda (Shop-First Category Rail, Featured Plants, USP Innovations)
Route::get('/', function () {
    return view('pages.home');
})->name('home');

// Katalog Tanaman (Filter Cahaya, Ruangan, Ukuran, Kesulitan, Live Count)
Route::get('/shop', function () {
    return view('pages.shop');
})->name('shop');

// Detail Tanaman (Galeri Interaktif, Varian Pot, Parameter Perawatan, Accordion)
Route::get('/product/{slug?}', function ($slug = 'monstera-deliciosa') {
    return view('pages.product', compact('slug'));
})->name('product.show');

// Keranjang Belanja (Free Delivery Threshold, Fitur Kirim Hadiah Unboxing, Stripe Checkout)
Route::get('/cart', function () {
    return view('pages.cart');
})->name('cart');

// My Digital Garden (Kebun Virtual, Jadwal Siram, Weather-Aware Reminder, CO2 Tracker)
Route::get('/garden', function () {
    return view('pages.garden');
})->name('garden');

// Panduan Rawat & AI Plant Doctor (Diagnosa Daun via Ciora AI Vision & Edukasi)
Route::get('/care', function () {
    return view('pages.care');
})->name('care');

// Tentang Kami (Kisah Pembibitan, Metodologi CO2, Jam Buka Nursery, Formulir Kontak)
Route::get('/about', function () {
    return view('pages.about');
})->name('about');
