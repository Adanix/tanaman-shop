#!/usr/bin/env python3
"""
Plantify Official — Enterprise Web App Proposal PDF Generator
Generates an executive, publication-grade proposal PDF document covering:
15 comprehensive chapters with Cover Page, Transmittal Information, Table of Contents,
Comparative Problem-Solution Matrices, Complete Menu/Submenu Inventories,
10-Point Feature Specifications, UI Components, Role Access Matrix,
6 End-to-End Workflows, Database & Integration Schemas, and Extensibility Narratives.
"""

import os
import sys
from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.lib.units import mm
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    """
    Two-pass canvas to dynamically compute and print total page numbers,
    running headers, and professional footers on internal pages,
    while preserving clean dedicated cover on Page 1 and back cover on the last page.
    """
    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super().showPage()
        super().save()

    def draw_page_decorations(self, page_count):
        # Page 1 is Cover Page (already has custom background)
        if self._pageNumber == 1:
            return

        # Last Page is Back Cover (has dark background)
        if self._pageNumber == page_count:
            return

        # Running Header and Footer on Pages 2 through (page_count - 1)
        self.saveState()
        # Top running header
        self.setFont("Helvetica-Bold", 7.5)
        self.setFillColor(colors.HexColor("#1E3A2F")) # Forest Green
        self.drawString(40, 842 - 32, "PLANTIFY OFFICIAL")
        self.setFont("Helvetica", 7.5)
        self.setFillColor(colors.HexColor("#4A5568"))
        self.drawString(135, 842 - 32, "— Proposal Solusi Digital Botanical Nursery & Digital Care Tracking")
        self.drawRightString(595 - 40, 842 - 32, "CONFIDENTIAL • DOKUMEN PROPOSAL")

        # Top separator rule
        self.setStrokeColor(colors.HexColor("#CBD5E1"))
        self.setLineWidth(0.6)
        self.line(40, 842 - 36, 595 - 40, 842 - 36)

        # Bottom separator rule
        self.line(40, 38, 595 - 40, 38)

        # Bottom running footer
        self.setFont("Helvetica", 7.5)
        self.setFillColor(colors.HexColor("#4A5568"))
        self.drawString(40, 26, "Plantify Official • Bantul, Special Region of Yogyakarta • https://demo.ramadadawah.my.id")
        self.drawRightString(595 - 40, 26, f"Halaman {self._pageNumber} dari {page_count}")
        self.restoreState()

def draw_cover_background(canvas, doc):
    """
    Renders botanical luxury executive header block, ochre & sage accents,
    and decorative elements on the cover page.
    """
    canvas.saveState()
    # Top Header Block: Deep Botanical Forest Green (#1E3A2F)
    canvas.setFillColor(colors.HexColor("#1E3A2F"))
    canvas.rect(0, 480, 595, 362, fill=True, stroke=False)

    # Ochre Accent Stripe (#D69E2E)
    canvas.setFillColor(colors.HexColor("#D69E2E"))
    canvas.rect(0, 472, 595, 8, fill=True, stroke=False)

    # Mint Sage Accent Stripe (#2E7D32)
    canvas.setFillColor(colors.HexColor("#2E7D32"))
    canvas.rect(0, 467, 595, 5, fill=True, stroke=False)

    # Subtle geometric grid line accents on top dark header
    canvas.setStrokeColor(colors.HexColor("#2A4D3E"))
    canvas.setLineWidth(0.5)
    for y in range(500, 840, 32):
        canvas.line(40, y, 555, y)

    # Bottom border accent
    canvas.setFillColor(colors.HexColor("#1E3A2F"))
    canvas.rect(0, 0, 595, 14, fill=True, stroke=False)
    canvas.setFillColor(colors.HexColor("#D69E2E"))
    canvas.rect(0, 14, 595, 4, fill=True, stroke=False)
    canvas.restoreState()

def build_pdf(filename):
    doc = SimpleDocTemplate(
        filename,
        pagesize=A4,
        leftMargin=40,
        rightMargin=40,
        topMargin=46,
        bottomMargin=46
    )

    styles = getSampleStyleSheet()

    # Plantify Botanical Color Palette
    C_FOREST = colors.HexColor("#1E3A2F")     # Primary Dark Forest
    C_OCHRE = colors.HexColor("#D69E2E")      # Primary Brand Ochre Gold
    C_MINT = colors.HexColor("#2E7D32")       # Sage Mint Green
    C_CHARCOAL = colors.HexColor("#1F2937")   # Text Charcoal
    C_MUTED = colors.HexColor("#64748B")      # Muted Slate
    C_BORDER = colors.HexColor("#CBD5E1")     # Border Line
    C_BG_LIGHT = colors.HexColor("#F9FAF8")   # Cream Glass White
    C_BG_CARD = colors.HexColor("#F1F5F0")    # Soft Botanical Tint

    # Custom Typography Styles
    styles.add(ParagraphStyle(
        'CoverRefBadge',
        fontName='Helvetica-Bold',
        fontSize=8.5,
        leading=11,
        textColor=colors.HexColor("#FEF08A"), # Soft Yellow
        spaceAfter=8
    ))
    styles.add(ParagraphStyle(
        'CoverMainTitle',
        fontName='Helvetica-Bold',
        fontSize=20,
        leading=24,
        textColor=colors.white,
        spaceAfter=10
    ))
    styles.add(ParagraphStyle(
        'CoverSubtitle',
        fontName='Helvetica',
        fontSize=9.5,
        leading=14,
        textColor=colors.HexColor("#E2E8F0"),
        spaceAfter=12
    ))
    styles.add(ParagraphStyle(
        'DocHeading1',
        fontName='Helvetica-Bold',
        fontSize=13.5,
        leading=16,
        textColor=C_FOREST,
        spaceBefore=11,
        spaceAfter=5,
        keepWithNext=True
    ))
    styles.add(ParagraphStyle(
        'DocHeading2',
        fontName='Helvetica-Bold',
        fontSize=10.5,
        leading=13.5,
        textColor=C_OCHRE,
        spaceBefore=8,
        spaceAfter=3,
        keepWithNext=True
    ))
    styles.add(ParagraphStyle(
        'DocHeading3',
        fontName='Helvetica-Bold',
        fontSize=9,
        leading=12,
        textColor=C_FOREST,
        spaceBefore=6,
        spaceAfter=2,
        keepWithNext=True
    ))
    styles.add(ParagraphStyle(
        'DocBody',
        fontName='Helvetica',
        fontSize=8,
        leading=11.5,
        textColor=C_CHARCOAL,
        spaceAfter=4
    ))
    styles.add(ParagraphStyle(
        'DocBodyBold',
        fontName='Helvetica-Bold',
        fontSize=8,
        leading=11.5,
        textColor=C_FOREST,
        spaceAfter=4
    ))
    styles.add(ParagraphStyle(
        'DocBullet',
        fontName='Helvetica',
        fontSize=8,
        leading=11.2,
        textColor=C_CHARCOAL,
        leftIndent=10,
        firstLineIndent=-7,
        spaceAfter=2.5
    ))
    styles.add(ParagraphStyle(
        'CalloutText',
        fontName='Helvetica',
        fontSize=8,
        leading=11.5,
        textColor=C_FOREST
    ))
    styles.add(ParagraphStyle(
        'TableHead',
        fontName='Helvetica-Bold',
        fontSize=7.5,
        leading=9.5,
        textColor=colors.white,
        alignment=0
    ))
    styles.add(ParagraphStyle(
        'TableCell',
        fontName='Helvetica',
        fontSize=7.2,
        leading=9.5,
        textColor=C_CHARCOAL
    ))
    styles.add(ParagraphStyle(
        'TableCellBold',
        fontName='Helvetica-Bold',
        fontSize=7.2,
        leading=9.5,
        textColor=C_FOREST
    ))
    styles.add(ParagraphStyle(
        'BadgeSuccess',
        fontName='Helvetica-Bold',
        fontSize=6.8,
        leading=8,
        textColor=colors.HexColor("#065F46")
    ))
    styles.add(ParagraphStyle(
        'BadgeWarning',
        fontName='Helvetica-Bold',
        fontSize=6.8,
        leading=8,
        textColor=colors.HexColor("#92400E")
    ))

    story = []

    # =========================================================================
    # BAB 1: COVER PROFESIONAL & LEMBAR PENGESAHAN
    # =========================================================================
    story.append(Spacer(1, 15))
    story.append(Paragraph("DOKUMEN PROPOSAL TEKNIS & SOLUSI DIGITAL", styles['CoverRefBadge']))
    story.append(Paragraph("PLANTIFY OFFICIAL", styles['CoverMainTitle']))
    story.append(Paragraph(
        "Platform Terintegrasi E-Commerce Botanical Nursery, Digital Care Tracking, "
        "Adopsi Tanaman Hias, & Botanical Intelligence Berbasis AI",
        styles['CoverSubtitle']
    ))
    story.append(Spacer(1, 140))

    # Transmittal & Meta Table
    cover_meta = [
        [Paragraph("<b>Nama Produk:</b>", styles['TableCellBold']), Paragraph("Plantify Official — Botanical Living Hub", styles['TableCell'])],
        [Paragraph("<b>Domain Aplikasi:</b>", styles['TableCellBold']), Paragraph("https://demo.ramadadawah.my.id", styles['TableCell'])],
        [Paragraph("<b>Alamat Nursery:</b>", styles['TableCellBold']), Paragraph("Jl. Agrowisata Hijau No. 24, Botanical District, Bantul, Special Region of Yogyakarta", styles['TableCell'])],
        [Paragraph("<b>Klasifikasi:</b>", styles['TableCellBold']), Paragraph("Proposal Solusi Digital & Dokumen Teknis Arsitektur", styles['TableCell'])],
        [Paragraph("<b>Versi Dokumen:</b>", styles['TableCellBold']), Paragraph("Versi 2.2 — Final Production & Interactive Demo Edition", styles['TableCell'])],
        [Paragraph("<b>Tanggal Rilis:</b>", styles['TableCellBold']), Paragraph("26 Oktober 2026", styles['TableCell'])],
        [Paragraph("<b>Target Pengguna:</b>", styles['TableCellBold']), Paragraph("Pecinta Tanaman Hias, Pembeli Botanical Gift, & Manajemen Nursery", styles['TableCell'])],
        [Paragraph("<b>Teknologi Inti:</b>", styles['TableCellBold']), Paragraph("Laravel 13, Tailwind CSS 4, Alpine.js, Ciora AI Vision & Chat Gateway", styles['TableCell'])],
    ]
    t_cover = Table(cover_meta, colWidths=[130, 385])
    t_cover.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), C_BG_CARD),
        ('BOX', (0,0), (-1,-1), 1, C_OCHRE),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_cover)
    story.append(Spacer(1, 15))

    p_summary_box = [
        [Paragraph("<b>RINGKASAN PROPOSAL:</b> Dokumen ini menguraikan arsitektur sistem, seluruh menu, fitur operasional, matriks hak akses, alur kerja transaksi hingga pemeliharaan tanaman pasca pembelian, serta kapabilitas kecerdasan buatan (Ciora AI 100x quota) yang telah aktif beroperasi pada platform Plantify Official.", styles['CalloutText'])]
    ]
    t_summary_box = Table(p_summary_box, colWidths=[515])
    t_summary_box.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#FEF3C7")),
        ('BOX', (0,0), (-1,-1), 1, C_OCHRE),
        ('TOPPADDING', (0,0), (-1,-1), 8),
        ('BOTTOMPADDING', (0,0), (-1,-1), 8),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_summary_box)

    story.append(PageBreak())

    # =========================================================================
    # BAB 2: RINGKASAN EKSEKUTIF (EXECUTIVE SUMMARY)
    # =========================================================================
    story.append(Paragraph("BAB 2 — RINGKASAN EKSEKUTIF", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "<b>Plantify Official</b> adalah solusi platform digital komprehensif yang dirancang khusus untuk mentransformasi operasional nursery tanaman hias artisanal modern ke ranah digital. Tidak seperti e-commerce retail umum yang berhenti saat barang terkirim, Plantify dirancang dengan filosofi <i>Living Catalog & Lifetime Care Retention</i>—mengintegrasikan transaksi belanja tanaman hidup dengan pendampingan perawatan digital berkelanjutan.",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "<b>Masalah Utama yang Diselesaikan:</b> E-commerce konvensional gagal melayani kebutuhan tanaman hidup. Pelanggan awam sering mengalami kegagalan merawat tanaman hingga layu/mati karena tidak memahami kebutuhan spesifik spesies tanaman (frekuensi siram, intensitas sinar matahari, dan kelembapan). Di sisi lain, nursery tradisional kesulitan memverifikasi bukti transfer bank manual secara cepat, mengelola antrean pengambilan toko (Store Pickup) secara akurat, serta memitigasi risiko kerusakan tanaman selama pengiriman.",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "<b>Solusi Terpadu Plantify:</b> Platform ini menghadirkan 3 pilar fungsional saling terhubung: (1) <i>Living Storefront</i> dengan navigasi kurasi filter botani dan pembelian langsung (Buy Now &rarr; Direct Checkout), (2) <i>Botanical Intelligence & Care Hub</i> yang ditenagai oleh Ciora AI Vision (diagnosa penyakit daun) dan Ciora AI Chat (asisten botani real-time) dengan kuota pengujian 100x/hari, serta (3) <i>Nursery Operations & POS Back-Office</i> yang memudahkan verifikasi transfer bank dalam 2 jam, pengelolaan pengambilan pesanan dengan kode 6 digit, dan pemantauan pengiriman aman iklim.",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "<b>Nilai Tambah Bisnis:</b> Melalui fitur <i>Digital Garden Adoption</i>, setiap tanaman yang dibeli atau diterima sebagai kado (Botanical Gift) langsung terdaftar ke kebun digital pelanggan. Sistem secara cerdas menyinkronkan jadwal penyiraman dengan data cuaca lokal terkalibrasi, memastikan retensi pelanggan jangka panjang, meningkatkan angka keberhasilan hidup tanaman hingga mendekati 100%, serta membangun reputasi nursery sebagai penyedia botani premium terpercaya.",
        styles['DocBody']
    ))
    story.append(Spacer(1, 8))

    # =========================================================================
    # BAB 3: IDENTIFIKASI MASALAH DAN SOLUSI
    # =========================================================================
    story.append(Paragraph("BAB 3 — IDENTIFIKASI MASALAH DAN SOLUSI", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Analisis mendalam terhadap alur operasional nursery tanaman hias fisik dan toko daring konvensional menghasilkan identifikasi permasalahan utama dan perumusan solusi konkret berikut:",
        styles['DocBody']
    ))

    problems_table = [
        [Paragraph("Aspek Operasional", styles['TableHead']), Paragraph("Sebelum Menggunakan Sistem (Kondisi Eksisting)", styles['TableHead']), Paragraph("Setelah Menggunakan Plantify (Solusi Terintegrasi)", styles['TableHead'])],
        [
            Paragraph("<b>Proses Kerja E-Commerce</b>", styles['TableCellBold']),
            Paragraph("Alur checkout kaku mengharuskan navigasi keranjang belanja berulang; tidak ada opsi beli cepat untuk pelanggan berniat beli tinggi.", styles['TableCell']),
            Paragraph("Tersedia tombol <b>Buy Now &rarr;</b> langsung ke checkout, form ringkas, pemesanan kado botanical gift dengan pesan khusus dalam 1 alur.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Pengelolaan Pembayaran</b>", styles['TableCellBold']),
            Paragraph("Bukti transfer bank dikirim lewat chat WhatsApp terpisah, rawan terselip, rawan pemalsuan bukti transfer, verifikasi lambat.", styles['TableCell']),
            Paragraph("Upload bukti transfer langsung saat checkout (JPG/PNG/PDF), preview instan, antrean approval khusus admin dengan batas toleransi 2 jam.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Perawatan Tanaman Pasca Beli</b>", styles['TableCellBold']),
            Paragraph("Pelanggan tidak tahu kapan harus menyiram; tanaman sering busuk akar atau layu; minim dukungan dari pihak toko.", styles['TableCell']),
            Paragraph("<b>Digital Garden</b>: Adopsi tanaman otomatis, indikator hidrasi tanah real-time, sinkronisasi cuaca lokal, reminder siram harian.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Konsultasi Kesehatan Daun</b>", styles['TableCellBold']),
            Paragraph("Staf botani nursery kewalahan melayani pertanyaan diagnosa daun menguning atau hama tanaman satu per satu di chat.", styles['TableCell']),
            Paragraph("<b>AI Plant Doctor</b>: Analisis foto daun otomatis via Ciora AI Vision & asistensi tanya-jawab 24/7 dengan kuota 100x konsultasi per hari.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Pengambilan di Toko (POS)</b>", styles['TableCellBold']),
            Paragraph("Pencocokan nama manual di meja kasir rawan salah serah tanaman kepada pembeli yang salah.", styles['TableCell']),
            Paragraph("<b>Store Pickup Desk</b>: Kode verifikasi 6 digit unik; pencarian instan dan tombol serah terima (handover) real-time di konsol kasir.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Logistik & Pengiriman</b>", styles['TableCellBold']),
            Paragraph("Status kurir manual; pelanggan sering komplain karena cemas tanaman layu dalam perjalanan.", styles['TableCell']),
            Paragraph("Pelacakan publik via token rahasia (Order Tracking), status iklim aman, update kurir, dan tombol adopsi pasca tanaman diterima.", styles['TableCell'])
        ]
    ]
    t_prob = Table(problems_table, colWidths=[95, 205, 215])
    t_prob.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_prob)

    story.append(PageBreak())

    # =========================================================================
    # BAB 4: GAMBARAN KESELURUHAN APLIKASI & ARSITEKTUR
    # =========================================================================
    story.append(Paragraph("BAB 4 — GAMBARAN KESELURUHAN APLIKASI", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Arsitektur Plantify Official dibangun di atas pondasi modern monolithic berbasis Laravel 13 dan Tailwind CSS 4 dengan struktur 3 pilar modul yang saling berinteraksi secara mulus:",
        styles['DocBody']
    ))

    arch_rows = [
        [Paragraph("Pilar Modul", styles['TableHead']), Paragraph("Tujuan & Ruang Lingkup", styles['TableHead']), Paragraph("Komponen & Aliran Data Kunci", styles['TableHead'])],
        [
            Paragraph("<b>1. Living Storefront & E-Commerce</b>", styles['TableCellBold']),
            Paragraph("Menyediakan etalase botanical yang elegan, responsif terhadap perangkat desktop/tablet/mobile, dengan penyaringan tanaman akurat.", styles['TableCell']),
            Paragraph("Home showcase, Living Catalog (`/shop`), Filter intensitas cahaya/hewan peliharaan/kesulitan, Product Detail, Cart Session, 1-Click Buy Now, Checkout Engine.", styles['TableCell'])
        ],
        [
            Paragraph("<b>2. Botanical Intelligence & Care Hub</b>", styles['TableCellBold']),
            Paragraph("Memberikan pendampingan botani berbasis AI dan personalisasi pemeliharaan tanaman pelanggan.", styles['TableCell']),
            Paragraph("Ciora AI Vision Gateway (diagnosa daun), Botanical Assistant (chat), Digital Garden (tracking kelembapan & jadwal pupuk), Weather Cache Service (integrasi cuaca lokal).", styles['TableCell'])
        ],
        [
            Paragraph("<b>3. Nursery Operations & Back-Office</b>", styles['TableCellBold']),
            Paragraph("Pusat kendali internal bagi staf toko, kasir POS, staf logistik, dan pengelola nursery.", styles['TableCell']),
            Paragraph("Dashboard metrik, Manajemen Order, Bank Transfer Verification Queue (approval slip TF), Store Pickup POS (kode 6 digit), Delivery Dispatch, Audit Logs, Store Settings.", styles['TableCell'])
        ]
    ]
    t_arch = Table(arch_rows, colWidths=[115, 195, 205])
    t_arch.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_arch)
    story.append(Spacer(1, 8))

    story.append(Paragraph("<b>Alur Pertukaran Data Antarmodul:</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "• <i>Katalog &rarr; Checkout:</i> Data spesimen tanaman (harga, stok, interval siram, snapshot ilmiah) ditransfer ke entri CartItem dan diteruskan menjadi Order & OrderItem terikat transaksi.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Pembayaran &rarr; Antrean Operasional:</i> Pesanan berstatus <code>bank_transfer</code> mengalir langsung ke modul Verifikasi Admin; pesanan berstatus <code>store_pickup</code> men-generate kode 6-digit dan mengalir ke meja kasir POS.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Fulfillment &rarr; Digital Garden:</i> Begitu tanaman diserahterimakan atau dikirimkan, pelanggan dapat mengklik <b>Adopt to My Garden</b> sehingga relasi <code>UserGarden</code> terbentuk secara otomatis lengkap dengan jadwal siram pertama.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 8))

    # =========================================================================
    # BAB 5: DAFTAR SELURUH MENU DAN SUBMENU
    # =========================================================================
    story.append(Paragraph("BAB 5 — DAFTAR SELURUH MENU DAN SUBMENU", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Inventarisasi menyeluruh berdasarkan 63 rute aktual aplikasi Plantify Official:",
        styles['DocBody']
    ))

    menus_table = [
        [Paragraph("Menu / URL Route", styles['TableHead']), Paragraph("Fungsi Utama", styles['TableHead']), Paragraph("Fitur & Tindakan di Dalamnya", styles['TableHead']), Paragraph("Hak Akses", styles['TableHead'])],
        # Storefront
        [Paragraph("<b>Home</b> (`/`)", styles['TableCellBold']), Paragraph("Halaman etalase utama", styles['TableCell']), Paragraph("Hero banner, kategori unggulan, katalog sorotan, showcase AI Plant Doctor, footer lokasi Bantul DIY", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Shop Plants</b> (`/shop`)", styles['TableCellBold']), Paragraph("Katalog lengkap botani", styles['TableCell']), Paragraph("Pencarian nama tanaman, filter intensitas cahaya, filter pet-safe, filter tingkat kesulitan, sorting harga", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Product Detail</b> (`/product/{slug}`)", styles['TableCellBold']), Paragraph("Profil spesimen tanaman", styles['TableCell']), Paragraph("Galeri foto, parameter perawatan (cahaya, air, suhu), deskripsi botani, tombol Add to Cart, tombol <b>Buy Now &rarr;</b>", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Shopping Cart</b> (`/cart`)", styles['TableCellBold']), Paragraph("Keranjang belanja", styles['TableCell']), Paragraph("Review daftar belanja, ubah kuantitas tanaman, hapus item, ringkasan subtotal & estimasi pajak, CTA Checkout", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Checkout</b> (`/checkout`)", styles['TableCellBold']), Paragraph("Penyelesaian pesanan", styles['TableCell']), Paragraph("Form pengiriman/pickup, opsi Kado Botanical Gift, metode bayar (Stripe / Bank Transfer / Pay in Store), upload bukti transfer", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Order Success</b> (`/order/success/{token}`)", styles['TableCellBold']), Paragraph("Konfirmasi pesanan", styles['TableCell']), Paragraph("Nomor resi unik, instruksi rekening nursery, status pembayaran, token pelacakan, link upload susulan", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Track Order</b> (`/track-order`)", styles['TableCellBold']), Paragraph("Pelacakan pesanan publik", styles['TableCell']), Paragraph("Pencarian nomor order/token, timeline tahapan pemrosesan, kode pickup POS, tombol <i>Adopt to Garden</i>", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        [Paragraph("<b>Claim Gift</b> (`/gift/{token}`)", styles['TableCellBold']), Paragraph("Penerimaan kado botani", styles['TableCell']), Paragraph("Pesan personal pengirim, konfirmasi nama penerima, aktivasi adopsi otomatis ke kebun digital penerima", styles['TableCell']), Paragraph("Penerima Hadiah", styles['TableCell'])],
        [Paragraph("<b>About Nursery</b> (`/about`)", styles['TableCellBold']), Paragraph("Profil nursery fisik", styles['TableCell']), Paragraph("Lokasi Bantul DIY, jam operasional pickup (Selasa-Minggu), filosofi organik, kontak WhatsApp & email", styles['TableCell']), Paragraph("Semua Pengunjung", styles['TableCell'])],
        # Member
        [Paragraph("<b>Digital Garden</b> (`/garden`)", styles['TableCellBold']), Paragraph("Kebun digital koleksi", styles['TableCell']), Paragraph("Daftar tanaman teradopsi, circular hydration gauge, tombol <b>Water Now</b>, tombol <b>Fertilize Now</b>, sinkronisasi cuaca", styles['TableCell']), Paragraph("Customer / Member", styles['TableCell'])],
        [Paragraph("<b>AI Doctor & Care</b> (`/care`)", styles['TableCellBold']), Paragraph("Pusat konsultasi botani", styles['TableCell']), Paragraph("Upload foto daun sakit (Ciora AI Vision), konsultasi asisten tanya-jawab (Ciora AI Chat), counter kuota 100x/hari", styles['TableCell']), Paragraph("Customer / Member", styles['TableCell'])],
        # Admin
        [Paragraph("<b>Admin Dashboard</b> (`/admin`)", styles['TableCellBold']), Paragraph("Ikhtisar operasional", styles['TableCell']), Paragraph("Statistik revenue, total pesanan, spesies aktif di garden, badge pending verifikasi transfer, quick actions", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Admin Orders</b> (`/admin/orders`)", styles['TableCellBold']), Paragraph("Manajemen pesanan", styles['TableCell']), Paragraph("Daftar pesanan tabular, filter status pembayaran, filter kurir/pickup, rincian detail, tombol proses refund", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Bank Verifications</b> (`/admin/verifications`)", styles['TableCellBold']), Paragraph("Verifikasi transfer manual", styles['TableCell']), Paragraph("Daftar slip transfer masuk, peninjau bukti gambar/PDF via storage aman, tombol Approve & Reject dengan grace period", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Store Pickup POS</b> (`/admin/pickup`)", styles['TableCellBold']), Paragraph("Kasir pengambilan toko", styles['TableCell']), Paragraph("Input kode pickup 6-digit pelanggan, pencarian nama, verifikasi barang, tombol Serah Terima (Handover)", styles['TableCell']), Paragraph("Administrator / Kasir", styles['TableCell'])],
        [Paragraph("<b>Delivery Dispatch</b> (`/admin/delivery`)", styles['TableCellBold']), Paragraph("Manajemen pengiriman", styles['TableCell']), Paragraph("Antrean packing tanaman, input nomor resi kurir eksternal, update status Dikirim (Shipped) & Selesai (Delivered)", styles['TableCell']), Paragraph("Administrator / Kurir", styles['TableCell'])],
        [Paragraph("<b>Plant Inventory</b> (`/admin/plants`)", styles['TableCellBold']), Paragraph("Katalog stok tanaman", styles['TableCell']), Paragraph("Daftar spesies botani, stok fisik, harga & harga diskon, status aktif/non-aktif, parameter penyiraman", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Store Settings</b> (`/admin/settings`)", styles['TableCellBold']), Paragraph("Konfigurasi toko & POS", styles['TableCell']), Paragraph("Pengaturan alamat nursery (Bantul DIY), tarif ongkir flat, jam buka pickup, nomor rekening bank, timezone", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
    ]
    t_menus = Table(menus_table, colWidths=[95, 80, 260, 80])
    t_menus.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_menus)

    story.append(PageBreak())

    # =========================================================================
    # BAB 6: DETAIL FITUR PER MENU (10 POIN SPESIFIKASI SISTEMATIS)
    # =========================================================================
    story.append(Paragraph("BAB 6 — DETAIL FITUR PER MENU", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Berikut adalah dokumentasi terperinci untuk 6 fitur inti platform Plantify Official, disusun dalam 10 poin spesifikasi sistematis:",
        styles['DocBody']
    ))

    # Feature 1: AI Plant Doctor
    story.append(Paragraph("6.1 Fitur AI Plant Doctor & Visual Leaf Diagnosis", styles['DocHeading2']))
    f1_data = [
        [Paragraph("<b>1. Nama Fitur</b>", styles['TableCellBold']), Paragraph("AI Plant Doctor Diagnostic Engine (Ciora AI Vision)", styles['TableCell'])],
        [Paragraph("<b>2. Tujuan Fitur</b>", styles['TableCellBold']), Paragraph("Mendeteksi penyakit, kekurangan nutrisi, atau stres lingkungan pada tanaman hias secara instan.", styles['TableCell'])],
        [Paragraph("<b>3. Masalah Diselesaikan</b>", styles['TableCellBold']), Paragraph("Menghilangkan ketergantungan pada diagnosis manual lambat; mencegah tanaman mati karena penanganan keliru.", styles['TableCell'])],
        [Paragraph("<b>4. Cara Akses</b>", styles['TableCellBold']), Paragraph("Menu navigasi atas <b>AI Doctor & Care</b> (`/care`), login sebagai member.", styles['TableCell'])],
        [Paragraph("<b>5. Alur Langkah</b>", styles['TableCellBold']), Paragraph("Member mengambil/mengunggah foto daun &rarr; klik 'Diagnose Foliage' &rarr; sistem memproses &rarr; diagnosa & resep muncul.", styles['TableCell'])],
        [Paragraph("<b>6. Data Masukan</b>", styles['TableCellBold']), Paragraph("File foto daun (JPEG/PNG/WebP, max 5MB), teks catatan gejala opsional.", styles['TableCell'])],
        [Paragraph("<b>7. Proses Backend</b>", styles['TableCellBold']), Paragraph("Validasi kuota harian &rarr; konversi Base64 &rarr; payload Ciora Vision Gateway (`https://ciora.id/v1`) &rarr; fallback botani otomatis jika offline &rarr; simpan riwayat di `ai_diagnoses`.", styles['TableCell'])],
        [Paragraph("<b>8. Hasil / Output</b>", styles['TableCellBold']), Paragraph("Nama kondisi terdeteksi, skor tingkat keparahan (mild/moderate/severe), panduan pemulihan organik 3 langkah.", styles['TableCell'])],
        [Paragraph("<b>9. Batasan & Validasi</b>", styles['TableCellBold']), Paragraph("Kuota 100x konsultasi per hari (zona waktu Asia/Jakarta); file harus berupa gambar valid.", styles['TableCell'])],
        [Paragraph("<b>10. Status Aktual</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Terintegrasi</b> (Koneksi Ciora AI aktif dengan resilient botanical fallback)", styles['BadgeSuccess'])],
    ]
    t_f1 = Table(f1_data, colWidths=[120, 395])
    t_f1.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), C_BG_CARD),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_f1)
    story.append(Spacer(1, 6))

    # Feature 2: 1-Click Buy Now & Direct Checkout
    story.append(Paragraph("6.2 Fitur Direct Buy Now (1-Click Instant Checkout)", styles['DocHeading2']))
    f2_data = [
        [Paragraph("<b>1. Nama Fitur</b>", styles['TableCellBold']), Paragraph("Direct Buy Now Purchase Action", styles['TableCell'])],
        [Paragraph("<b>2. Tujuan Fitur</b>", styles['TableCellBold']), Paragraph("Mempercepat konversi belanja dengan memotong alur peninjauan keranjang belanja.", styles['TableCell'])],
        [Paragraph("<b>3. Masalah Diselesaikan</b>", styles['TableCellBold']), Paragraph("Mencegah pembatalan belanja (cart abandonment) akibat proses checkout yang terlalu panjang.", styles['TableCell'])],
        [Paragraph("<b>4. Cara Akses</b>", styles['TableCellBold']), Paragraph("Tombol <b>Buy Now &rarr;</b> pada halaman detail spesimen tanaman (`/product/{slug}`).", styles['TableCell'])],
        [Paragraph("<b>5. Alur Langkah</b>", styles['TableCellBold']), Paragraph("Pilih kuantitas &rarr; klik 'Buy Now &rarr;' &rarr; sistem otomatis memasukkan item &rarr; langsung membuka `/checkout`.", styles['TableCell'])],
        [Paragraph("<b>6. Data Masukan</b>", styles['TableCellBold']), Paragraph("`plant_id`, `quantity`, `action=buy_now`.", styles['TableCell'])],
        [Paragraph("<b>7. Proses Backend</b>", styles['TableCellBold']), Paragraph("`CartController::add()` mendeteksi flag `buy_now`, meng-update item, dan langsung merespons dengan HTTP 302 ke `/checkout`.", styles['TableCell'])],
        [Paragraph("<b>8. Hasil / Output</b>", styles['TableCellBold']), Paragraph("Halaman checkout terbuka dengan item terkait siap dikonfirmasi alamat dan pembayarannya.", styles['TableCell'])],
        [Paragraph("<b>9. Batasan & Validasi</b>", styles['TableCellBold']), Paragraph("Kuantitas dibatasi stok fisik aktual yang tersedia di database.", styles['TableCell'])],
        [Paragraph("<b>10. Status Aktual</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Berfungsi Penuh</b>", styles['BadgeSuccess'])],
    ]
    t_f2 = Table(f2_data, colWidths=[120, 395])
    t_f2.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), C_BG_CARD),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_f2)

    story.append(PageBreak())

    # Feature 3: Manual Bank Transfer with Proof Upload
    story.append(Paragraph("6.3 Fitur Manual Bank Transfer & Upload Bukti Pembayaran", styles['DocHeading2']))
    f3_data = [
        [Paragraph("<b>1. Nama Fitur</b>", styles['TableCellBold']), Paragraph("Direct Proof Upload & Manual Bank Verification SLA", styles['TableCell'])],
        [Paragraph("<b>2. Tujuan Fitur</b>", styles['TableCellBold']), Paragraph("Mengakomodasi metode transfer bank lokal secara terstruktur dengan pelampiran bukti langsung.", styles['TableCell'])],
        [Paragraph("<b>3. Masalah Diselesaikan</b>", styles['TableCellBold']), Paragraph("Meniadakan risiko slip palsu dan kerumitan mencocokkan chat bukti transfer di luar sistem.", styles['TableCell'])],
        [Paragraph("<b>4. Cara Akses</b>", styles['TableCellBold']), Paragraph("Pilih opsi <b>Bank Transfer</b> saat berada di form checkout (`/checkout`).", styles['TableCell'])],
        [Paragraph("<b>5. Alur Langkah</b>", styles['TableCellBold']), Paragraph("Transfer ke rekening nursery &rarr; isi nama bank & pemilik &rarr; unggah file slip &rarr; order berstatus `awaiting_verification`.", styles['TableCell'])],
        [Paragraph("<b>6. Data Masukan</b>", styles['TableCellBold']), Paragraph("`sender_bank_name`, `sender_account_name`, file `payment_proof` (JPG, PNG, WebP, PDF max 4MB).", styles['TableCell'])],
        [Paragraph("<b>7. Proses Backend</b>", styles['TableCellBold']), Paragraph("File disimpan ke private storage `transfer_proofs/` &rarr; order diberi status `awaiting_verification` &rarr; masuk antrean admin.", styles['TableCell'])],
        [Paragraph("<b>8. Hasil / Output</b>", styles['TableCellBold']), Paragraph("Halaman sukses dengan pesan verifikasi maks 2 jam; notifikasi di dashboard staf.", styles['TableCell'])],
        [Paragraph("<b>9. Batasan & Validasi</b>", styles['TableCellBold']), Paragraph("Tipe file divalidasi MIME type; toleransi penolakan (grace period) 12 jam untuk re-upload.", styles['TableCell'])],
        [Paragraph("<b>10. Status Aktual</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Teruji End-to-End</b>", styles['BadgeSuccess'])],
    ]
    t_f3 = Table(f3_data, colWidths=[120, 395])
    t_f3.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), C_BG_CARD),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_f3)
    story.append(Spacer(1, 6))

    # Feature 4: Digital Garden
    story.append(Paragraph("6.4 Fitur My Digital Garden (Smart Hydration & Weather Sync)", styles['DocHeading2']))
    f4_data = [
        [Paragraph("<b>1. Nama Fitur</b>", styles['TableCellBold']), Paragraph("Digital Garden Living Plant Care Tracker", styles['TableCell'])],
        [Paragraph("<b>2. Tujuan Fitur</b>", styles['TableCellBold']), Paragraph("Membantu pemilik tanaman memelihara tanaman di rumah dengan jadwal penyiraman adaptif.", styles['TableCell'])],
        [Paragraph("<b>3. Masalah Diselesaikan</b>", styles['TableCellBold']), Paragraph("Tingginya angka kematian tanaman akibat lupa siram atau terlalu sering menyiram (overwatering).", styles['TableCell'])],
        [Paragraph("<b>4. Cara Akses</b>", styles['TableCellBold']), Paragraph("Menu <b>My Digital Garden</b> (`/garden`), atau tombol 'Adopt to Garden' pada halaman pelacakan order.", styles['TableCell'])],
        [Paragraph("<b>5. Alur Langkah</b>", styles['TableCellBold']), Paragraph("Buka `/garden` &rarr; lihat indikator kelembapan tanah &rarr; klik 'Water Now' setelah menyiram &rarr; jadwal diperbarui.", styles['TableCell'])],
        [Paragraph("<b>6. Data Masukan</b>", styles['TableCellBold']), Paragraph("Aksi penyiraman (POST `/garden/{id}/water`), aksi pemupukan (POST `/garden/{id}/fertilize`), catatan nickname tanaman.", styles['TableCell'])],
        [Paragraph("<b>7. Proses Backend</b>", styles['TableCellBold']), Paragraph("Menghitung selisih waktu penyiraman &rarr; mengintegrasikan kelembapan udara kota Bantul/DIY &rarr; menghitung estimasi hidrasi tanah.", styles['TableCell'])],
        [Paragraph("<b>8. Hasil / Output</b>", styles['TableCellBold']), Paragraph("Circular gauge hidrasi tanah (0-100%), badge status kebutuhan air, tanggal jatuh tempo penyiraman berikutnya.", styles['TableCell'])],
        [Paragraph("<b>9. Batasan & Validasi</b>", styles['TableCellBold']), Paragraph("Hanya dapat diakses oleh member pemilik tanaman teradopsi.", styles['TableCell'])],
        [Paragraph("<b>10. Status Aktual</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Berfungsi Penuh</b>", styles['BadgeSuccess'])],
    ]
    t_f4 = Table(f4_data, colWidths=[120, 395])
    t_f4.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), C_BG_CARD),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_f4)

    story.append(PageBreak())

    # =========================================================================
    # BAB 7: DAFTAR KOMPONEN ANTARMUKA (UI COMPONENTS)
    # =========================================================================
    story.append(Paragraph("BAB 7 — DAFTAR KOMPONEN ANTARMUKA (UI COMPONENTS)", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Aplikasi Plantify Official memanfaatkan desain antarmuka berbasis Tailwind CSS 4 dengan kustomisasi visual 'Fernwick Glasshouse' dan interaktivitas Alpine.js. Berikut adalah inventarisasi komponen UI aktual:",
        styles['DocBody']
    ))

    ui_components = [
        [Paragraph("Komponen UI", styles['TableHead']), Paragraph("Lokasi Penggunaan", styles['TableHead']), Paragraph("Interaksi & Karakteristik Desain", styles['TableHead']), Paragraph("Manfaat bagi Pengguna", styles['TableHead'])],
        [
            Paragraph("<b>Sticky Header & is_active Highlight</b>", styles['TableCellBold']),
            Paragraph("Layout Global (`app.blade.php`)", styles['TableCell']),
            Paragraph("Backdrop blur, transisi Alpine.js, penanda rute aktif (`border-b-2 border-ochre font-bold`).", styles['TableCell']),
            Paragraph("Navigasi konsisten, pengguna langsung tahu posisi halaman yang sedang dibuka.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Quick Demo Access Card</b>", styles['TableCellBold']),
            Paragraph("Halaman Login (`login.blade.php`)", styles['TableCell']),
            Paragraph("Dua tombol instan: 'Sign in as Admin' & 'Sign in as Customer' dengan autofill & submit otomatis.", styles['TableCell']),
            Paragraph("Evaluasi demo cepat tanpa perlu mengetik kredensial secara manual.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Botanical Specimen Card</b>", styles['TableCellBold']),
            Paragraph("Home & Catalog (`home`, `shop`)", styles['TableCell']),
            Paragraph("Gambar tanaman responsif, badge stok, badge diskon, tag pet-safe, indikator tingkat kesulitan.", styles['TableCell']),
            Paragraph("Informasi visual tanaman lengkap dalam satu pandangan sebelum klik detail.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Interactive Filter Panel</b>", styles['TableCellBold']),
            Paragraph("Katalog (`shop.blade.php`)", styles['TableCell']),
            Paragraph("Checkbox kriteria cahaya (low/medium/bright), toggle ramah hewan, dropdown kategori.", styles['TableCell']),
            Paragraph("Penyaringan cepat sesuai kondisi ruangan tempat tinggal pembeli.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Action Strip (Buy Now vs Cart)</b>", styles['TableCellBold']),
            Paragraph("Detail Produk (`product.blade.php`)", styles['TableCell']),
            Paragraph("Tombol sekunder 'Add to Cart' berdampingan dengan tombol primer 'Buy Now &rarr;'.", styles['TableCell']),
            Paragraph("Memberikan fleksibilitas belanja akumulatif maupun pembelian kilat.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Bank Receipt Dropzone & Preview</b>", styles['TableCellBold']),
            Paragraph("Checkout (`checkout/index.blade.php`)", styles['TableCell']),
            Paragraph("Input file dengan Alpine.js visual image preview, validasi format gambar/PDF.", styles['TableCell']),
            Paragraph("Kepastian bahwa bukti pembayaran yang dipilih sudah benar sebelum dikirim.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Hydration Ring Gauge</b>", styles['TableCellBold']),
            Paragraph("Digital Garden (`garden.blade.php`)", styles['TableCell']),
            Paragraph("Indikator sirkular persentase kelembapan tanah, perubahan warna dari hijau ke kuning/merah saat kering.", styles['TableCell']),
            Paragraph("Pemahaman visual instan terhadap urgensi penyiraman tanaman.", styles['TableCell'])
        ],
        [
            Paragraph("<b>AI Diagnosis Camera / Dropzone</b>", styles['TableCellBold']),
            Paragraph("AI Doctor (`care.blade.php`)", styles['TableCell']),
            Paragraph("Drag-and-drop foto daun, live preview, animasi loading saat Ciora AI menganalisis gambar.", styles['TableCell']),
            Paragraph("Pengalaman diagnosa modern layaknya berkonsultasi langsung ke klinik botani.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Pickup POS Verification Modal</b>", styles['TableCellBold']),
            Paragraph("Admin Pickup (`admin/pickup`)", styles['TableCell']),
            Paragraph("Kotak input kode 6-digit besar berjarak, dialog konfirmasi serah terima fisik.", styles['TableCell']),
            Paragraph("Mencegah kekeliruan kasir saat menyerahkan tanaman ke kurir/pelanggan.", styles['TableCell'])
        ]
    ]
    t_ui = Table(ui_components, colWidths=[105, 95, 175, 140])
    t_ui.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 4),
        ('RIGHTPADDING', (0,0), (-1,-1), 4),
    ]))
    story.append(t_ui)

    story.append(PageBreak())

    # =========================================================================
    # BAB 8: ROLE DAN HAK AKSES PENGGUNA
    # =========================================================================
    story.append(Paragraph("BAB 8 — ROLE DAN HAK AKSES PENGGUNA", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Sistem menerapkan kontrol akses berbasis peran (Role-Based Access Control) dengan 3 tingkatan hak akses yang terdefinisi ketat dalam middleware `auth` dan `admin`:",
        styles['DocBody']
    ))

    rbac_matrix = [
        [Paragraph("Modul / Fungsi Sistem", styles['TableHead']), Paragraph("Guest (Publik)", styles['TableHead']), Paragraph("Customer / Member", styles['TableHead']), Paragraph("Administrator / Staf", styles['TableHead'])],
        [Paragraph("Browsing Living Catalog & Detail", styles['TableCellBold']), Paragraph("Ya (Melihat)", styles['TableCell']), Paragraph("Ya (Melihat)", styles['TableCell']), Paragraph("Ya (Melihat)", styles['TableCell'])],
        [Paragraph("Shopping Cart & Direct Buy Now", styles['TableCellBold']), Paragraph("Ya (Membuat & Mengubah)", styles['TableCell']), Paragraph("Ya (Membuat & Mengubah)", styles['TableCell']), Paragraph("Ya (Simulasi Belanja)", styles['TableCell'])],
        [Paragraph("Penyelesaian Checkout & Upload Slip", styles['TableCellBold']), Paragraph("Ya (Sebagai Guest)", styles['TableCell']), Paragraph("Ya (Auto-fill Profil)", styles['TableCell']), Paragraph("Ya (Testing Pesanan)", styles['TableCell'])],
        [Paragraph("Pelacakan Pesanan via Token", styles['TableCellBold']), Paragraph("Ya (Via URL Resi)", styles['TableCell']), Paragraph("Ya (Via Riwayat / URL)", styles['TableCell']), Paragraph("Ya (Akses Seluruh Order)", styles['TableCell'])],
        [Paragraph("Klaim Hadiah (Botanical Gift)", styles['TableCellBold']), Paragraph("Ya (Via Token Hadiah)", styles['TableCell']), Paragraph("Ya (Teradopsi ke Garden)", styles['TableCell']), Paragraph("Ya (Akses Kelola)", styles['TableCell'])],
        [Paragraph("My Digital Garden Care Tracking", styles['TableCellBold']), Paragraph("Tidak (Harus Login)", styles['TableCell']), Paragraph("<b>Penuh</b> (Kelola Koleksi)", styles['TableCell']), Paragraph("Ya (Testing Member)", styles['TableCell'])],
        [Paragraph("AI Plant Doctor (100x Quota)", styles['TableCellBold']), Paragraph("Terbatas (Info Panduan)", styles['TableCell']), Paragraph("<b>Penuh</b> (100x Konsultasi)", styles['TableCell']), Paragraph("<b>Penuh</b> (Tanpa Batas)", styles['TableCell'])],
        [Paragraph("Admin Console & Dashboard Metrik", styles['TableCellBold']), Paragraph("Tidak (Ditolak)", styles['TableCell']), Paragraph("Tidak (Ditolak 403)", styles['TableCell']), Paragraph("<b>Penuh</b> (Akses Eksklusif)", styles['TableCell'])],
        [Paragraph("Verifikasi Transfer Bank Masuk", styles['TableCellBold']), Paragraph("Tidak (Ditolak)", styles['TableCell']), Paragraph("Tidak (Ditolak 403)", styles['TableCell']), Paragraph("<b>Penuh</b> (Approve / Reject)", styles['TableCell'])],
        [Paragraph("Kasir Pengambilan Toko (POS Pickup)", styles['TableCellBold']), Paragraph("Tidak (Ditolak)", styles['TableCell']), Paragraph("Tidak (Ditolak 403)", styles['TableCell']), Paragraph("<b>Penuh</b> (Handover Pesanan)", styles['TableCell'])],
        [Paragraph("Delivery Dispatch & Input Resi", styles['TableCellBold']), Paragraph("Tidak (Ditolak)", styles['TableCell']), Paragraph("Tidak (Ditolak 403)", styles['TableCell']), Paragraph("<b>Penuh</b> (Kirim / Selesai)", styles['TableCell'])],
        [Paragraph("Manajemen Katalog & Stok Tanaman", styles['TableCellBold']), Paragraph("Tidak (Ditolak)", styles['TableCell']), Paragraph("Tidak (Ditolak 403)", styles['TableCell']), Paragraph("<b>Penuh</b> (Update Stok & Status)", styles['TableCell'])],
        [Paragraph("Pengaturan Toko, Bank, & Alamat", styles['TableCellBold']), Paragraph("Tidak (Ditolak)", styles['TableCell']), Paragraph("Tidak (Ditolak 403)", styles['TableCell']), Paragraph("<b>Penuh</b> (Konfigurasi Sistem)", styles['TableCell'])],
    ]
    t_rbac = Table(rbac_matrix, colWidths=[155, 115, 125, 120])
    t_rbac.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_rbac)

    story.append(PageBreak())

    # =========================================================================
    # BAB 9: ALUR KERJA END-TO-END (6 WORKFLOWS)
    # =========================================================================
    story.append(Paragraph("BAB 9 — ALUR KERJA END-TO-END", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Berikut adalah 6 skenario alur kerja utama aplikasi dari tahap awal hingga penyelesaian:",
        styles['DocBody']
    ))

    # Workflow 1
    story.append(Paragraph("<b>Workflow 1: Pembelian Online & Pengiriman Kurir (Standard Delivery)</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Pelanggan memilih tanaman & kuantitas di `/shop` atau `/product/{slug}` &rarr; klik <i>Add to Cart</i>.<br/>"
        "2. Di halaman `/checkout`, pelanggan memilih opsi <b>Delivery to Address</b> dan melengkapi alamat tujuan.<br/>"
        "3. Sistem menghitung subtotal + flat rate delivery fee + pajak (8%) secara otomatis.<br/>"
        "4. Pembayaran diselesaikan (Stripe Demo auto-paid atau Bank Transfer) &rarr; order berstatus `processing`.<br/>"
        "5. Tim nursery memproses packing tanaman dengan material aman iklim di modul `/admin/delivery`.<br/>"
        "6. Staf memasukkan nama ekspedisi & nomor resi &rarr; status berubah menjadi `shipped`.<br/>"
        "7. Kurir menyelesaikan pengantaran &rarr; status menjadi `delivered` &rarr; pelanggan dapat mengadopsi tanaman ke Digital Garden.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 2
    story.append(Paragraph("<b>Workflow 2: Direct Buy Now & Pengambilan di Meja Kasir (Store Pickup POS)</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Pelanggan membuka detail tanaman (misal Monstera Deliciosa) &rarr; klik <b>Buy Now &rarr;</b>.<br/>"
        "2. Sistem langsung melompat ke `/checkout`; pelanggan memilih metode <b>Store Pickup (Bantul Nursery)</b>.<br/>"
        "3. Ongkir otomatis Rp 0; sistem men-generate <b>Kode Pickup 6 Digit Unik</b> (contoh: <code>PK-948123</code>).<br/>"
        "4. Pelanggan datang ke nursery Bantul DIY pada jam operasional (Selasa-Minggu 08:30-17:00 WIB).<br/>"
        "5. Kasir membuka `/admin/pickup`, memasukkan kode 6-digit &rarr; data tanaman diverifikasi &rarr; klik <i>Handover</i>.<br/>"
        "6. Pesanan selesai seketika; pelanggan membawa pulang tanaman dalam kondisi prima.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 3
    story.append(Paragraph("<b>Workflow 3: Pembayaran Transfer Bank Manual & Verifikasi Staf Nursery</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Pelanggan memilih metode <i>Manual Bank Transfer</i> pada form checkout.<br/>"
        "2. Sistem menampilkan daftar rekening resmi nursery (BCA, Mandiri, BNI) beserta nominal total transfer.<br/>"
        "3. Pelanggan mentransfer via mobile banking, mengisi nama bank & pengirim, serta mengunggah foto bukti struk.<br/>"
        "4. Order tersimpan dengan status `awaiting_verification` dan langsung muncul di antrean `/admin/verifications`.<br/>"
        "5. Staf memeriksa keaslian bukti transfer melalui storage lokal aman.<br/>"
        "6. Jika valid, staf klik <b>Approve</b> &rarr; status pembayaran berubah menjadi `paid` dan order menjadi `processing`.<br/>"
        "7. Jika tidak valid, staf klik <b>Reject</b> dengan alasan tertulis &rarr; pelanggan diberi grace period 12 jam untuk re-upload.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 4
    story.append(Paragraph("<b>Workflow 4: Pembelian Kado Tanaman (Botanical Gift) & Klaim Hadiah</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Pembeli mencentang opsi <i>'This is a gift for someone special'</i> saat checkout.<br/>"
        "2. Pembeli memasukkan nama penerima, email penerima, dan pesan kado personal (hingga 500 karakter).<br/>"
        "3. Setelah order dibayar, sistem men-generate token unik hadiah dan mencatat masa berlaku klaim 30 hari.<br/>"
        "4. Penerima membuka tautan `/gift/{token}` &rarr; melihat pesan hangat pengirim dan foto tanaman kado.<br/>"
        "5. Penerima mengklik <b>Claim Gift & Adopt</b> &rarr; tanaman langsung berpindah ke Digital Garden akun penerima.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 5
    story.append(Paragraph("<b>Workflow 5: Diagnosa Kesehatan Tanaman via Ciora AI Vision</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Member mendapati daun tanaman menguning atau bercak hitam &rarr; membuka menu `/care`.<br/>"
        "2. Member mengunggah foto daun melalui kamera ponsel atau file uploader.<br/>"
        "3. Frontend mengirim gambar ke endpoint POST `/ai/diagnose`.<br/>"
        "4. Backend memeriksa kuota harian (maks 100x), memanggil Ciora Vision Gateway API, dan menganalisis gejala.<br/>"
        "5. Hasil diagnosis tampil dalam hitungan detik: nama penyakit, skor keparahan, dan resep perawatan organik.<br/>"
        "6. Member dapat melanjutkan konsultasi mendalam lewat widget Botanical Chatbot di halaman yang sama.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 6
    story.append(Paragraph("<b>Workflow 6: Siklus Perawatan Harian Digital Garden Berbasis Cuaca</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Member membuka `/garden` setiap pagi untuk memantau status koleksi tanaman hidupnya.<br/>"
        "2. Sistem mengecek cache cuaca lokal (suhu udara & kelembapan) dan menghitung sisa hidrasi tanah.<br/>"
        "3. Tanaman dengan hidrasi di bawah 25% memunculkan indikator visual kuning/merah <i>Needs Watering</i>.<br/>"
        "4. Member menyiram tanaman fisik &rarr; mengklik tombol <b>Water Now</b>.<br/>"
        "5. Sistem me-reset hidrasi ke 100% dan memajukan jadwal jatuh tempo siram sesuai interval spesies (misal 7 hari).",
        styles['DocBullet']
    ))

    story.append(PageBreak())

    # =========================================================================
    # BAB 10: DATABASE, INTEGRASI, DAN OTOMATISASI
    # =========================================================================
    story.append(Paragraph("BAB 10 — DATABASE, INTEGRASI, DAN OTOMATISASI", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "<b>Struktur Database (28 Tabel Relasional):</b> Skema database Plantify dirancang memenuhi kaidah Third Normal Form (3NF) dengan integritas referensial kuat:",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "• <i>Entitas Pengguna & Akses:</i> <code>users</code> (role, timezone, detail profil), <code>password_reset_tokens</code>, <code>sessions</code>, <code>audit_logs</code> (pencatatan mutasi data).<br/>"
        "• <i>Katalog Botani:</i> <code>categories</code>, <code>plants</code> (parameter cahaya, interval siram, pet-safe, stok), <code>plant_images</code>.<br/>"
        "• <i>Transaksi & Pesanan:</i> <code>cart_items</code>, <code>orders</code> (tracking token, payment method/status, order status, pickup code), <code>order_items</code>, <code>bank_accounts</code>, <code>payment_methods</code>, <code>refunds</code>.<br/>"
        "• <i>Care & AI Hub:</i> <code>user_gardens</code> (riwayat siram & pupuk), <code>gifts</code>, <code>ai_diagnoses</code>, <code>ai_chats</code>, <code>ai_usage_logs</code>, <code>weather_caches</code>, <code>store_settings</code>.<br/>"
        "• <i>Queue & Infrastruktur:</i> <code>jobs</code>, <code>job_batches</code>, <code>failed_jobs</code>, <code>cache</code>, <code>cache_locks</code>, <code>stripe_webhook_events</code>.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph("<b>Integrasi API Eksternal yang Beroperasi:</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. <b>Ciora AI Gateway (`https://ciora.id/v1`):</b> Menggunakan model `ciora-ai-free` untuk asisten botani dan `ciora-vision-free` untuk diagnosa visual daun. Dilengkapi kuota 100x/hari dan fallback cerdas ke botanical expert engine jika terjadi gangguan jaringan.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "2. <b>OpenWeatherMap API & Weather Cache:</b> Menyinkronkan suhu dan kelembapan real-time untuk penyesuaian jadwal hidrasi tanaman di Digital Garden, dengan fallback kondisi iklim lokal tropis DI Yogyakarta.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "3. <b>Stripe Checkout API:</b> Terintegrasi dengan webhook signature validation; beroperasi dalam Demo Mode otomatis untuk memfasilitasi evaluasi demo tanpa pemotongan kartu kredit riil.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph("<b>Otomatisasi Latar Belakang (Scheduled Background Jobs):</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "• <code>SendWateringRemindersJob</code>: Mengevaluasi koleksi tanaman member setiap pagi dan mengirimkan peringatan siram jika hidrasi tanah telah melewati batas aman.<br/>"
        "• <code>CancelExpiredOrdersJob</code>: Membatalkan pesanan transfer bank yang belum dibayar dalam batas waktu kadaluarsa (24 jam) dan mengembalikan stok fisik tanaman ke katalog.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph("<b>Standar Keamanan Sistem:</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "Password dienkripsi menggunakan standar industri Bcrypt/Argon2id; seluruh form dilindungi CSRF token validation; URL verifikasi email menggunakan kriptografi signed URL; bukti transfer bank tersimpan di private storage yang hanya dapat dibuka melalui rute aman admin (`/admin/proof-view`).",
        styles['DocBody']
    ))
    story.append(Spacer(1, 6))

    # =========================================================================
    # BAB 11: LAPORAN, MONITORING, DAN OUTPUT
    # =========================================================================
    story.append(Paragraph("BAB 11 — LAPORAN, MONITORING, DAN OUTPUT", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    reports_data = [
        [Paragraph("Output / Laporan", styles['TableHead']), Paragraph("Informasi yang Ditampilkan", styles['TableHead']), Paragraph("Tujuan & Manfaat Operasional", styles['TableHead'])],
        [
            Paragraph("<b>Executive Dashboard Metrics</b>", styles['TableCellBold']),
            Paragraph("Total Revenue akumulatif, Total Orders, Active Species teradopsi, Pending Bank Verifications badge.", styles['TableCell']),
            Paragraph("Pemantauan performa harian nursery dalam satu layar sekilas bagi pemilik bisnis.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Orders Management Log</b>", styles['TableCellBold']),
            Paragraph("Daftar pesanan kronologis, status pembayaran, filter metode pemenuhan (delivery vs pickup), rincian item.", styles['TableCell']),
            Paragraph("Pelacakan riwayat transaksi, pencarian pesanan komplain, dan pemrosesan refund dana.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Bank Verification Queue</b>", styles['TableCellBold']),
            Paragraph("Antrean slip transfer bank yang menunggu approval, nama bank & pengirim, timestamp submisi.", styles['TableCell']),
            Paragraph("Memastikan tidak ada pembayaran pelanggan yang tertunda verifikasinya melebihi SLA 2 jam.", styles['TableCell'])
        ],
        [
            Paragraph("<b>POS Pickup Daily Register</b>", styles['TableCellBold']),
            Paragraph("Daftar pesanan siap ambil, kode 6 digit, nama penjemput, timestamp serah terima kasir.", styles['TableCell']),
            Paragraph("Menghilangkan kekeliruan serah fisik barang dan memudahkan rekonsiliasi stok harian.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Dokumen Proposal PDF (`/proposal-pdf`)</b>", styles['TableCellBold']),
            Paragraph("Dokumen komprehensif 15 bab arsitektur sistem, spesifikasi fitur, dan panduan teknis operasional.", styles['TableCell']),
            Paragraph("Bahan presentasi profesional kepada klien, pimpinan perusahaan, dan calon investor.", styles['TableCell'])
        ]
    ]
    t_rep = Table(reports_data, colWidths=[120, 205, 190])
    t_rep.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_rep)

    story.append(PageBreak())

    # =========================================================================
    # BAB 12: KEUNGGULAN DAN MANFAAT APLIKASI
    # =========================================================================
    story.append(Paragraph("BAB 12 — KEUNGGULAN DAN MANFAAT APLIKASI", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Implementasi platform Plantify Official menghadirkan keunggulan diferensiatif yang dapat dibuktikan secara nyata melalui fungsionalitas sistem:",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "1. <b>Domain-Specific Botanical UX:</b> Bukan sekadar toko online generik. Seluruh arsitektur data dibangun berdasarkan karakteristik botani nyata—kebutuhan sinar matahari, toleransi terhadap hewan peliharaan, interval air, dan tingkat keahlian merawat.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "2. <b>Retensi Pelanggan Jangka Panjang (High LTV):</b> Fitur Digital Garden mengubah pembeli pasif menjadi pengguna aktif harian yang terus berinteraksi dengan platform untuk memantau jadwal siram dan pupuk.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "3. <b>Efisiensi Layanan Pelanggan melalui AI:</b> Ciora AI Vision & Chat menjawab 80%+ pertanyaan dasar perawatan tanaman secara instan 24 jam sehari, meringankan beban staf botani nursery.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "4. <b>Kecepatan Transaksi dengan 'Buy Now':</b> Mengakomodasi tren belanja impulsif pecinta tanaman dengan alur 1-klik langsung ke pembayaran tanpa friksi.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "5. <b>Operasional POS & Logistik Fisik Tanpa Celah:</b> Verifikasi kode pickup 6 digit dan antrean dispatch iklim-aman meminimalkan risiko salah serah dan kerusakan tanaman hidup.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 6))

    # =========================================================================
    # BAB 13: STATUS IMPLEMENTASI DAN RUANG LINGKUP
    # =========================================================================
    story.append(Paragraph("BAB 13 — STATUS IMPLEMENTASI DAN RUANG LINGKUP", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    status_data = [
        [Paragraph("Modul / Fitur", styles['TableHead']), Paragraph("Status Aktual", styles['TableHead']), Paragraph("Catatan & Bukti Verifikasi", styles['TableHead'])],
        [Paragraph("Living Catalog & Filter Botani", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/shop`; filter cahaya, pet-safe, & kesulitan berfungsi normal.", styles['TableCell'])],
        [Paragraph("Direct Buy Now Action", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/product/{slug}`; redirect instan 302 ke `/checkout` terverifikasi.", styles['TableCell'])],
        [Paragraph("Manual Bank Transfer + Upload Proof", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Upload slip struk, storage lokal, & antrean approval admin berfungsi penuh.", styles['TableCell'])],
        [Paragraph("Stripe Checkout Payment", styles['TableCellBold']), Paragraph("<b>Demo Mode</b>", styles['BadgeWarning']), Paragraph("Terintegrasi dengan auto-settlement bypass untuk kelancaran evaluasi demo.", styles['TableCell'])],
        [Paragraph("Store Pickup POS Desk (6-digit code)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/admin/pickup`; kode unik ter-generate dan diverifikasi kasir.", styles['TableCell'])],
        [Paragraph("Delivery Dispatch & Shipping Status", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/admin/delivery`; update status shipped & delivered.", styles['TableCell'])],
        [Paragraph("My Digital Garden (Water/Fertilize)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/garden`; circular gauge, cuaca lokal Bantul DIY, update jadwal.", styles['TableCell'])],
        [Paragraph("AI Plant Doctor (Ciora Vision & Chat)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Terkoneksi ke gateway Ciora AI dengan kuota 100x/hari & fallback cerdas.", styles['TableCell'])],
        [Paragraph("Botanical Gift & Claim Engine", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/gift/{token}`; pesan kado personal & adopsi otomatis ke garden.", styles['TableCell'])],
        [Paragraph("Quick Demo Access Login Card", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Aktif di `/login`; tombol 1-klik Admin & Customer dengan autofill kredensial.", styles['TableCell'])],
        [Paragraph("Proposal PDF Generator (`/proposal-pdf`)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Endpoint inline PDF dokumen 15 bab siap unduh & cetak di browser.", styles['TableCell'])],
    ]
    t_stat = Table(status_data, colWidths=[150, 85, 280])
    t_stat.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BOX', (0,0), (-1,-1), 1, C_BORDER),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
    ]))
    story.append(t_stat)

    story.append(PageBreak())

    # =========================================================================
    # BAB 14: PENGEMBANGAN LANJUTAN DAN PENYESUAIAN KEBUTUHAN
    # =========================================================================
    story.append(Paragraph("BAB 14 — PENGEMBANGAN LANJUTAN DAN PENYESUAIAN KEBUTUHAN", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    # Required core narrative statement
    mandate_box = [
        [Paragraph(
            "<b>KOMITMEN PENGEMBANGAN & FLEKSIBILITAS SISTEM:</b><br/>"
            "Web app ini masih terbuka untuk pengembangan lebih lanjut dan dapat disesuaikan dengan kebutuhan, alur kerja, "
            "serta tantangan operasional perusahaan. Fitur, menu, integrasi, laporan, dan proses bisnis dapat dievaluasi serta "
            "disempurnakan berdasarkan kebutuhan nyata pengguna.<br/><br/>"
            "Pengembangan dilakukan secara bertahap dengan memprioritaskan permasalahan yang paling penting. Dengan pendekatan "
            "tersebut, sistem dapat terus berkembang menjadi solusi yang lebih relevan, mempermudah pekerjaan sehari-hari, "
            "mengurangi proses manual yang tidak diperlukan, meningkatkan keteraturan data, dan membantu menyelesaikan kendala "
            "operasional secara lebih efektif.<br/><br/>"
            "Kami terbuka untuk berdiskusi dan menyesuaikan sistem agar solusi yang dikembangkan benar-benar sesuai dengan "
            "kebutuhan perusahaan, bukan sekadar menambah fitur yang belum tentu diperlukan.",
            styles['CalloutText']
        )]
    ]
    t_mandate = Table(mandate_box, colWidths=[515])
    t_mandate.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor("#ECFDF5")), # Mint tint
        ('BOX', (0,0), (-1,-1), 1, C_MINT),
        ('TOPPADDING', (0,0), (-1,-1), 8),
        ('BOTTOMPADDING', (0,0), (-1,-1), 8),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_mandate)
    story.append(Spacer(1, 8))

    story.append(Paragraph("<b>Opsi Arah Pengembangan Potensial (Roadmap Kemitraan):</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "Berikut adalah beberapa potensi pengembangan modular yang dapat diimplementasikan berdasarkan kesepakatan kebutuhan spesifik:",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "• <i>Integrasi IoT Soil Sensor Hardware:</i> Menghubungkan sensor kelembapan tanah nirkabel (Zigbee / ESP32) di pot tanaman pelanggan secara otomatis ke Digital Garden tanpa pencatatan manual.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>WhatsApp Business API Notifications:</i> Otomatisasi pengiriman pesan pengingat siram dan link pelacakan kurir langsung ke nomor WhatsApp pelanggan.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Plant Subscription Box (Bibit Bulanan):</i> Modul langganan berulang bulanan untuk pengiriman tanaman mini, pupuk organik, dan pot keramik eksklusif secara terjadwal.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Multi-Branch Nursery Inventory:</i> Dukungan multi-gudang dan beberapa gerai cabang fisik nursery dengan alokasi stok terdesentralisasi.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 8))

    # =========================================================================
    # BAB 15: PENUTUP DAN KONTAK RESMI
    # =========================================================================
    story.append(Paragraph("BAB 15 — PENUTUP DAN KONTAK", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Platform Plantify Official telah terbukti mampu menyatukan ekosistem e-commerce botani, operasional kasir fisik POS, serta pendampingan kecerdasan buatan dalam satu platform harmonis. Kami menyampaikan kesiapan penuh untuk:",
        styles['DocBody']
    ))
    story.append(Paragraph("• Mendiskusikan kebutuhan khusus alur operasional nursery atau bisnis tanaman Anda.", styles['DocBullet']))
    story.append(Paragraph("• Mendemonstrasikan fungsi aplikasi secara langsung (live interactive demo walkthrough).", styles['DocBullet']))
    story.append(Paragraph("• Mengidentifikasi tantangan unik tata kelola persediaan tanaman yang belum terakomodasi.", styles['DocBullet']))
    story.append(Paragraph("• Menentukan skala prioritas implementasi bertahap sesuai anggaran dan target pertumbuhan bisnis.", styles['DocBullet']))
    story.append(Spacer(1, 10))

    closing_contact = [
        [Paragraph("<b>Informasi Kontak & Deployment Resmi:</b>", styles['TableHead']), Paragraph("", styles['TableHead'])],
        [Paragraph("<b>Entitas Nursery:</b>", styles['TableCellBold']), Paragraph("Plantify Official Nursery & Botanical Care Center", styles['TableCell'])],
        [Paragraph("<b>Alamat Kantor & Store:</b>", styles['TableCellBold']), Paragraph("Jl. Agrowisata Hijau No. 24, Botanical District, Bantul, Special Region of Yogyakarta", styles['TableCell'])],
        [Paragraph("<b>Jam Operasional Desk:</b>", styles['TableCellBold']), Paragraph("Selasa &ndash; Minggu, 08:30 &ndash; 17:00 WIB (Senin Tutup untuk Pemeliharaan Tanaman)", styles['TableCell'])],
        [Paragraph("<b>Live Demo Web App:</b>", styles['TableCellBold']), Paragraph("https://demo.ramadadawah.my.id", styles['TableCell'])],
        [Paragraph("<b>Endpoint Proposal PDF:</b>", styles['TableCellBold']), Paragraph("https://demo.ramadadawah.my.id/proposal-pdf", styles['TableCell'])],
        [Paragraph("<b>Tim Pengembang:</b>", styles['TableCellBold']), Paragraph("Adanix Software Engineering & Botanical Digital Solutions", styles['TableCell'])],
        [Paragraph("<b>Email Resmi:</b>", styles['TableCellBold']), Paragraph("nixresponding@gmail.com", styles['TableCell'])],
        [Paragraph("<b>Repositori Proyek:</b>", styles['TableCellBold']), Paragraph("https://github.com/adanix", styles['TableCell'])],
    ]
    t_close = Table(closing_contact, colWidths=[140, 375])
    t_close.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), C_FOREST),
        ('BACKGROUND', (0,1), (-1,-1), C_BG_CARD),
        ('BOX', (0,0), (-1,-1), 1, C_OCHRE),
        ('INNERGRID', (0,0), (-1,-1), 0.5, C_BORDER),
        ('TOPPADDING', (0,0), (-1,-1), 4),
        ('BOTTOMPADDING', (0,0), (-1,-1), 4),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_close)
    story.append(Spacer(1, 20))

    story.append(Paragraph(
        "<i>Dokumen proposal ini disusun secara otomatis berdasarkan audit arsitektur dan implementasi aktual basis kode Plantify Official. Seluruh hak cipta dilindungi undang-undang.</i>",
        styles['TableCell']
    ))

    # Build document using NumberedCanvas and custom cover background on Page 1
    doc.build(story, canvasmaker=NumberedCanvas, onFirstPage=draw_cover_background)
    print(f"Successfully generated proposal PDF: {filename}")

if __name__ == '__main__':
    output_dir = '/opt/adanix/laravel/plantify/src/public'
    output_pdf = os.path.join(output_dir, 'PROPOSAL_PENGEMBANGAN_PLANTIFY.pdf')
    if len(sys.argv) > 1:
        output_pdf = sys.argv[1]
    
    os.makedirs(os.path.dirname(output_pdf), exist_ok=True)
    build_pdf(output_pdf)
