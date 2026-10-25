#!/usr/bin/env python3
"""
Plantify Official — Enterprise Web App Proposal PDF Generator (US English Edition)
Generates an executive, publication-grade proposal PDF document covering:
15 comprehensive chapters with Cover Page, Transmittal Information, Executive Summary,
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
        self.drawString(135, 842 - 32, "— Botanical Nursery E-Commerce & Horticultural Intelligence Platform")
        self.drawRightString(595 - 40, 842 - 32, "CONFIDENTIAL • TECHNICAL PROPOSAL")

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
        self.drawRightString(595 - 40, 26, f"Page {self._pageNumber} of {page_count}")
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
    # CHAPTER 1: PROFESSIONAL COVER & DOCUMENT CONTROL
    # =========================================================================
    story.append(Spacer(1, 15))
    story.append(Paragraph("TECHNICAL PROPOSAL & ENTERPRISE SOLUTION SPECIFICATION", styles['CoverRefBadge']))
    story.append(Paragraph("PLANTIFY OFFICIAL", styles['CoverMainTitle']))
    story.append(Paragraph(
        "Integrated Botanical Nursery E-Commerce, Living Care Tracking, "
        "Specimen Adoption, & AI Horticultural Intelligence Platform",
        styles['CoverSubtitle']
    ))
    story.append(Spacer(1, 140))

    # Transmittal & Meta Table
    cover_meta = [
        [Paragraph("<b>Product Name:</b>", styles['TableCellBold']), Paragraph("Plantify Official — Botanical Living Hub", styles['TableCell'])],
        [Paragraph("<b>Live Web Application:</b>", styles['TableCellBold']), Paragraph("https://demo.ramadadawah.my.id", styles['TableCell'])],
        [Paragraph("<b>Physical Nursery Desk:</b>", styles['TableCellBold']), Paragraph("Jl. Agrowisata Hijau No. 24, Botanical District, Bantul, Special Region of Yogyakarta", styles['TableCell'])],
        [Paragraph("<b>Document Classification:</b>", styles['TableCellBold']), Paragraph("Enterprise Technical Proposal & System Architecture Specification", styles['TableCell'])],
        [Paragraph("<b>Document Version:</b>", styles['TableCellBold']), Paragraph("Version 2.2 — Production & Interactive Demo Edition", styles['TableCell'])],
        [Paragraph("<b>Release Date:</b>", styles['TableCellBold']), Paragraph("October 26, 2026", styles['TableCell'])],
        [Paragraph("<b>Target Audience:</b>", styles['TableCellBold']), Paragraph("Houseplant Enthusiasts, Botanical Gift Buyers, & Nursery Operations Management", styles['TableCell'])],
        [Paragraph("<b>Core Stack:</b>", styles['TableCellBold']), Paragraph("Laravel 13, Tailwind CSS 4, Alpine.js, Ciora AI Vision & Chat Gateway", styles['TableCell'])],
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
        [Paragraph("<b>EXECUTIVE PROPOSAL SUMMARY:</b> This document presents a comprehensive system overview, complete menu and submenu inventories, detailed feature specifications, role-based access control matrices, end-to-end purchasing and post-purchase care workflows, and active artificial intelligence capabilities (Ciora AI 100x quota) currently operating on the live Plantify Official platform.", styles['CalloutText'])]
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
    # CHAPTER 2: EXECUTIVE SUMMARY
    # =========================================================================
    story.append(Paragraph("CHAPTER 2 — EXECUTIVE SUMMARY", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "<b>Plantify Official</b> is a specialized digital platform designed to modernize the operations of artisanal botanical nurseries and plant care centers. Unlike conventional retail e-commerce stores that treat houseplants as inert consumer goods, Plantify is engineered around the principle of <i>Living Catalog & Lifetime Care Retention</i>—bridging specimen purchasing with continuous post-purchase care guidance.",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "<b>Core Problems Solved:</b> Generic e-commerce platforms fail to support living botanical specimens. Everyday plant parents frequently suffer post-purchase plant mortality due to unfamiliarity with light requirements, soil hydration intervals, and humidity needs. Simultaneously, traditional nursery stores face operational bottlenecks verifying manual bank transfer receipts, orchestrating in-store pickup desk handovers, and preventing plant damage during regional transit.",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "<b>Integrated Solution Architecture:</b> Plantify delivers three interlinked operational pillars: (1) <i>Curated Living Storefront</i> featuring multi-criteria light/pet-safe filtering and instant 1-click checkout (Buy Now &rarr; Direct Checkout), (2) <i>Botanical Intelligence & Care Hub</i> powered by Ciora AI Vision (visual leaf pathology diagnosis) and Ciora AI Chat (real-time horticultural consultation) with an expanded 100x daily evaluation quota, and (3) <i>Nursery Operations & POS Back-Office</i> enabling rapid 2-hour bank transfer approvals, 6-digit pickup verification, and climate-safe delivery dispatch.",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "<b>Strategic Business Value:</b> Through the <i>Digital Garden Adoption</i> mechanism, every purchased plant or received Botanical Gift is adopted directly into the customer's digital sanctuary. The platform automatically calibrates watering intervals based on local weather conditions, dramatically boosting plant survival rates, turning single transactions into lifetime customer relationships, and establishing the nursery as a trusted horticultural authority.",
        styles['DocBody']
    ))
    story.append(Spacer(1, 8))

    # =========================================================================
    # CHAPTER 3: PROBLEM IDENTIFICATION & PROPOSED SOLUTIONS
    # =========================================================================
    story.append(Paragraph("CHAPTER 3 — PROBLEM IDENTIFICATION & PROPOSED SOLUTIONS", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Rigorous auditing of traditional nursery retail and standard online store operations revealed six foundational challenges and their direct digital solutions within Plantify:",
        styles['DocBody']
    ))

    problems_table = [
        [Paragraph("Operational Aspect", styles['TableHead']), Paragraph("Before Plantify (Legacy / Manual Processes)", styles['TableHead']), Paragraph("After Plantify (Integrated Digital Solution)", styles['TableHead'])],
        [
            Paragraph("<b>E-Commerce Purchasing Workflow</b>", styles['TableCellBold']),
            Paragraph("Cumbersome cart review required for single items; high friction causes cart abandonment among high-intent buyers.", styles['TableCell']),
            Paragraph("Prominent <b>Buy Now &rarr;</b> button bypasses cart detour straight to checkout; streamlined gift recipient fields.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Payment Settlement & Slip Handling</b>", styles['TableCellBold']),
            Paragraph("Manual transfer slips sent across disconnected messaging apps (WhatsApp), prone to tampering and verification delays.", styles['TableCell']),
            Paragraph("Direct image/PDF upload on checkout form with preview; dedicated staff verification queue with a 2-hour SLA.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Post-Purchase Plant Survival</b>", styles['TableCellBold']),
            Paragraph("Buyers lack watering schedules and care guidance; root rot and dehydration cause high specimen mortality.", styles['TableCell']),
            Paragraph("<b>Digital Garden</b>: Instant plant adoption, circular soil hydration gauges, local weather sync, and care logs.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Plant Health Consultations</b>", styles['TableCellBold']),
            Paragraph("Nursery botanists are overwhelmed answering individual inquiries regarding yellowing leaves and pest infestations.", styles['TableCell']),
            Paragraph("<b>AI Plant Doctor</b>: Automated leaf photo diagnosis via Ciora AI Vision & 24/7 conversational advice (100x quota/day).", styles['TableCell'])
        ],
        [
            Paragraph("<b>Physical In-Store Pickup (POS)</b>", styles['TableCellBold']),
            Paragraph("Manual name verification at pickup counter causes misplacement and handing over incorrect plant specimens.", styles['TableCell']),
            Paragraph("<b>Store Pickup Desk</b>: Unique 6-digit pickup code verification with instant order lookup and one-click handover.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Logistics & Climate-Safe Delivery</b>", styles['TableCellBold']),
            Paragraph("Opaque shipping updates leave customers anxious regarding living plant health during transit.", styles['TableCell']),
            Paragraph("Tokenized public order tracking, climate-safe dispatch notes, courier tracking, and immediate post-delivery adoption.", styles['TableCell'])
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
    # CHAPTER 4: OVERALL SYSTEM ARCHITECTURE & MODULE ECOSYSTEM
    # =========================================================================
    story.append(Paragraph("CHAPTER 4 — OVERALL SYSTEM ARCHITECTURE & MODULE ECOSYSTEM", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Plantify Official is engineered as a modern, high-performance web application powered by Laravel 13 and Tailwind CSS 4, structured across three cohesive pillars:",
        styles['DocBody']
    ))

    arch_rows = [
        [Paragraph("Pillar Module", styles['TableHead']), Paragraph("Strategic Scope & Purpose", styles['TableHead']), Paragraph("Key Components & Data Lifecycles", styles['TableHead'])],
        [
            Paragraph("<b>1. Living Storefront & E-Commerce</b>", styles['TableCellBold']),
            Paragraph("Delivers an artisanal botanical shopping experience with responsive rendering across desktop, tablet, and mobile screens.", styles['TableCell']),
            Paragraph("Homepage showcase, Living Catalog (`/shop`), light & pet-safe filters, Specimen Detail, Cart Session, 1-Click Buy Now, Multi-Gateway Checkout.", styles['TableCell'])
        ],
        [
            Paragraph("<b>2. Botanical Intelligence & Care Hub</b>", styles['TableCellBold']),
            Paragraph("Empowers plant parents with AI-driven visual diagnostics and personalized digital plant maintenance.", styles['TableCell']),
            Paragraph("Ciora AI Vision Gateway (leaf diagnosis), Ciora Botanical Assistant (chat), Digital Garden (soil hydration & fertilizer tracking), Weather Cache Service.", styles['TableCell'])
        ],
        [
            Paragraph("<b>3. Nursery Operations & POS Back-Office</b>", styles['TableCellBold']),
            Paragraph("Centralized operations portal for nursery desk staff, retail POS cashiers, dispatchers, and store administrators.", styles['TableCell']),
            Paragraph("Executive Metrics, Order Fulfillment, Bank Verification Queue (slip audit), Store Pickup POS (6-digit code), Dispatch Queue, Store Settings.", styles['TableCell'])
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

    story.append(Paragraph("<b>Cross-Module Data Exchange & Event Lifecycles:</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "• <i>Catalog &rarr; Checkout:</i> Botanical attributes (pricing, stock, scientific name, watering interval days) flow into `CartItem` and persist as snapshot data within `Order` and `OrderItem` records upon order placement.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Payment &rarr; Operational Queue:</i> Orders selecting `bank_transfer` transition into `awaiting_verification` and immediately populate the Admin Verification Queue; orders selecting `store_pickup` generate a secure 6-digit pickup code.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Fulfillment &rarr; Digital Garden:</i> Upon physical handover or courier delivery, customers click <b>Adopt to My Garden</b>, instantly initializing a `UserGarden` profile with calibrated hydration metrics.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 8))

    # =========================================================================
    # CHAPTER 5: COMPREHENSIVE MENU & SUBMENU DIRECTORY
    # =========================================================================
    story.append(Paragraph("CHAPTER 5 — COMPREHENSIVE MENU & SUBMENU DIRECTORY", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Complete inventory of all 19 active application menus and submenus across the 63 registered routes:",
        styles['DocBody']
    ))

    menus_table = [
        [Paragraph("Menu / URL Route", styles['TableHead']), Paragraph("Primary Function", styles['TableHead']), Paragraph("Features & Actions Available", styles['TableHead']), Paragraph("Access Level", styles['TableHead'])],
        # Storefront
        [Paragraph("<b>Home</b> (`/`)", styles['TableCellBold']), Paragraph("Public nursery storefront", styles['TableCell']), Paragraph("Hero banner, category chips, curated catalog, AI Plant Doctor showcase, Bantul DIY footer", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Shop Plants</b> (`/shop`)", styles['TableCellBold']), Paragraph("Living botanical catalog", styles['TableCell']), Paragraph("Keyword search, light requirements filter, pet-safe filter, care difficulty filter, price sorting", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Product Detail</b> (`/product/{slug}`)", styles['TableCellBold']), Paragraph("Plant specimen profile", styles['TableCell']), Paragraph("Photo gallery, botanical care parameters (light/water/temp), Add to Cart, direct <b>Buy Now &rarr;</b> button", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Shopping Cart</b> (`/cart`)", styles['TableCellBold']), Paragraph("Active shopping bag", styles['TableCell']), Paragraph("Item review, quantity adjustment, remove item, subtotal & tax estimation, Proceed to Checkout CTA", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Checkout</b> (`/checkout`)", styles['TableCellBold']), Paragraph("Order placement engine", styles['TableCell']), Paragraph("Delivery vs Pickup selector, Botanical Gift options, multi-payment options, bank slip upload dropzone", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Order Success</b> (`/order/success/{token}`)", styles['TableCellBold']), Paragraph("Order confirmation", styles['TableCell']), Paragraph("Unique order number, official nursery bank accounts, payment status badge, follow-up slip upload link", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Track Order</b> (`/track-order`)", styles['TableCellBold']), Paragraph("Public order tracker", styles['TableCell']), Paragraph("Order number/token search, visual fulfillment timeline, 6-digit pickup code, <i>Adopt to Garden</i> button", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        [Paragraph("<b>Claim Gift</b> (`/gift/{token}`)", styles['TableCellBold']), Paragraph("Botanical gift portal", styles['TableCell']), Paragraph("Personal gift note display, recipient verification, 1-click adoption into recipient's Digital Garden", styles['TableCell']), Paragraph("Gift Recipient", styles['TableCell'])],
        [Paragraph("<b>About Nursery</b> (`/about`)", styles['TableCellBold']), Paragraph("Nursery physical profile", styles['TableCell']), Paragraph("Bantul DIY location, pickup hours (Tue-Sun), organic cultivation philosophy, WhatsApp & email contacts", styles['TableCell']), Paragraph("Public / All Visitors", styles['TableCell'])],
        # Member
        [Paragraph("<b>Digital Garden</b> (`/garden`)", styles['TableCellBold']), Paragraph("Living collection sanctuary", styles['TableCell']), Paragraph("Adopted plants dashboard, circular hydration gauges, <b>Water Now</b>, <b>Fertilize Now</b>, weather sync", styles['TableCell']), Paragraph("Registered Customer", styles['TableCell'])],
        [Paragraph("<b>AI Doctor & Care</b> (`/care`)", styles['TableCellBold']), Paragraph("Botanical care intelligence", styles['TableCell']), Paragraph("Leaf pathology photo upload (Ciora AI Vision), conversational botanical assistant (Chat), 100x quota meter", styles['TableCell']), Paragraph("Registered Customer", styles['TableCell'])],
        # Admin
        [Paragraph("<b>Admin Dashboard</b> (`/admin`)", styles['TableCellBold']), Paragraph("Executive overview", styles['TableCell']), Paragraph("Total revenue, total orders, active species count, pending bank verification badge, quick action shortcuts", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Admin Orders</b> (`/admin/orders`)", styles['TableCellBold']), Paragraph("Order lifecycle management", styles['TableCell']), Paragraph("Tabular order history, payment status filter, fulfillment filter, order item snapshots, refund processor", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Bank Verifications</b> (`/admin/verifications`)", styles['TableCellBold']), Paragraph("Manual transfer audit", styles['TableCell']), Paragraph("Receipt slip queue, isolated image/PDF viewer, 1-click Approve action, Reject with grace period", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Store Pickup POS</b> (`/admin/pickup`)", styles['TableCellBold']), Paragraph("Retail counter POS desk", styles['TableCell']), Paragraph("6-digit pickup code search, customer identity confirmation, plant readiness verification, Handover button", styles['TableCell']), Paragraph("Administrator / Cashier", styles['TableCell'])],
        [Paragraph("<b>Delivery Dispatch</b> (`/admin/delivery`)", styles['TableCellBold']), Paragraph("Logistics fulfillment", styles['TableCell']), Paragraph("Climate-safe packing queue, carrier name & tracking number input, Mark Shipped & Mark Delivered", styles['TableCell']), Paragraph("Administrator / Courier", styles['TableCell'])],
        [Paragraph("<b>Plant Catalog</b> (`/admin/plants`)", styles['TableCellBold']), Paragraph("Living specimen inventory", styles['TableCell']), Paragraph("Botanical species list, live stock quantities, retail & discounted pricing, active/inactive toggle", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
        [Paragraph("<b>Store Settings</b> (`/admin/settings`)", styles['TableCellBold']), Paragraph("Nursery store configuration", styles['TableCell']), Paragraph("Bantul DIY physical address, delivery flat fee, store pickup hours, bank account manager, timezone", styles['TableCell']), Paragraph("Administrator", styles['TableCell'])],
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
    # CHAPTER 6: DETAILED FEATURE SPECIFICATIONS (10-POINT CRITERIA)
    # =========================================================================
    story.append(Paragraph("CHAPTER 6 — DETAILED FEATURE SPECIFICATIONS (10-POINT CRITERIA)", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "In-depth technical breakdown for the core features of the Plantify Official application, structured across 10 systematic points:",
        styles['DocBody']
    ))

    # Feature 1: AI Plant Doctor
    story.append(Paragraph("6.1 AI Plant Doctor & Visual Foliage Pathology Diagnosis", styles['DocHeading2']))
    f1_data = [
        [Paragraph("<b>1. Feature Name</b>", styles['TableCellBold']), Paragraph("AI Plant Doctor Diagnostic Engine (Ciora AI Vision)", styles['TableCell'])],
        [Paragraph("<b>2. Primary Objective</b>", styles['TableCellBold']), Paragraph("Instantly identify plant diseases, nutrient deficiencies, or environmental stress from leaf photos.", styles['TableCell'])],
        [Paragraph("<b>3. Problem Solved</b>", styles['TableCellBold']), Paragraph("Eliminates delayed diagnosis; prevents specimen death caused by improper or overdue treatment.", styles['TableCell'])],
        [Paragraph("<b>4. Access Path</b>", styles['TableCellBold']), Paragraph("Top navigation link <b>AI Doctor & Care</b> (`/care`), authenticated customer session.", styles['TableCell'])],
        [Paragraph("<b>5. Step-by-Step Workflow</b>", styles['TableCellBold']), Paragraph("Customer captures/uploads leaf photo &rarr; clicks 'Diagnose Foliage' &rarr; system analyzes &rarr; diagnosis card renders.", styles['TableCell'])],
        [Paragraph("<b>6. Input Data</b>", styles['TableCellBold']), Paragraph("Foliage image file (JPEG/PNG/WebP, up to 5MB), optional user notes describing observed symptoms.", styles['TableCell'])],
        [Paragraph("<b>7. Backend Processing</b>", styles['TableCellBold']), Paragraph("Verifies daily quota (100x/day) &rarr; Base64 conversion &rarr; calls Ciora Vision Gateway (`https://ciora.id/v1`) &rarr; automated botanical expert fallback if network issues occur &rarr; logs to `ai_diagnoses`.", styles['TableCell'])],
        [Paragraph("<b>8. User Output</b>", styles['TableCellBold']), Paragraph("Detected pathological condition, severity score (mild/moderate/severe), 3-step organic recovery regimen.", styles['TableCell'])],
        [Paragraph("<b>9. Guardrails & Validasi</b>", styles['TableCellBold']), Paragraph("Daily limit of 100 consultations per user (Asia/Jakarta timezone reset); valid image MIME validation.", styles['TableCell'])],
        [Paragraph("<b>10. Current Status</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Operational</b> (Active Ciora AI integration with resilient botanical fallback)", styles['BadgeSuccess'])],
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

    # Feature 2: 1-Click Buy Now
    story.append(Paragraph("6.2 Direct Buy Now Action (1-Click Instant Checkout)", styles['DocHeading2']))
    f2_data = [
        [Paragraph("<b>1. Feature Name</b>", styles['TableCellBold']), Paragraph("Direct Buy Now Purchase Action", styles['TableCell'])],
        [Paragraph("<b>2. Primary Objective</b>", styles['TableCellBold']), Paragraph("Accelerate purchase conversions by bypassing intermediate cart navigation for immediate checkout.", styles['TableCell'])],
        [Paragraph("<b>3. Problem Solved</b>", styles['TableCellBold']), Paragraph("Minimizes cart abandonment caused by multi-step ordering friction on single-item plant purchases.", styles['TableCell'])],
        [Paragraph("<b>4. Access Path</b>", styles['TableCellBold']), Paragraph("Primary action button <b>Buy Now &rarr;</b> on specimen detail pages (`/product/{slug}`).", styles['TableCell'])],
        [Paragraph("<b>5. Step-by-Step Workflow</b>", styles['TableCellBold']), Paragraph("Select quantity &rarr; click 'Buy Now &rarr;' &rarr; item automatically added to cart session &rarr; instant redirect to `/checkout`.", styles['TableCell'])],
        [Paragraph("<b>6. Input Data</b>", styles['TableCellBold']), Paragraph("`plant_id`, `quantity`, form parameter `action=buy_now`.", styles['TableCell'])],
        [Paragraph("<b>7. Backend Processing</b>", styles['TableCellBold']), Paragraph("`CartController::add()` intercepts `buy_now` flag, syncs session cart, and emits an HTTP 302 redirect to `checkout.index`.", styles['TableCell'])],
        [Paragraph("<b>8. User Output</b>", styles['TableCellBold']), Paragraph("Immediate presentation of checkout form with order summary ready for payment and shipping confirmation.", styles['TableCell'])],
        [Paragraph("<b>9. Guardrails & Validasi</b>", styles['TableCellBold']), Paragraph("Requested quantity validated against live physical specimen stock in the database.", styles['TableCell'])],
        [Paragraph("<b>10. Current Status</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Fully Operational</b>", styles['BadgeSuccess'])],
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
    story.append(Paragraph("6.3 Manual Bank Transfer Settlement & Receipt Slip Upload", styles['DocHeading2']))
    f3_data = [
        [Paragraph("<b>1. Feature Name</b>", styles['TableCellBold']), Paragraph("Direct Proof Upload & 2-Hour Bank Verification SLA", styles['TableCell'])],
        [Paragraph("<b>2. Primary Objective</b>", styles['TableCellBold']), Paragraph("Provide a secure, structured channel for manual domestic bank transfers with integrated receipt proof.", styles['TableCell'])],
        [Paragraph("<b>3. Problem Solved</b>", styles['TableCellBold']), Paragraph("Eliminates fake receipts and the operational overhead of tracking payment slips across WhatsApp chats.", styles['TableCell'])],
        [Paragraph("<b>4. Access Path</b>", styles['TableCellBold']), Paragraph("Payment method selector <b>Manual Bank Transfer</b> on `/checkout`.", styles['TableCell'])],
        [Paragraph("<b>5. Step-by-Step Workflow</b>", styles['TableCellBold']), Paragraph("Transfer to nursery account &rarr; enter bank and sender name &rarr; upload slip &rarr; order placed as `awaiting_verification`.", styles['TableCell'])],
        [Paragraph("<b>6. Input Data</b>", styles['TableCellBold']), Paragraph("`sender_bank_name`, `sender_account_name`, file `payment_proof` (JPG, PNG, WebP, PDF max 4MB).", styles['TableCell'])],
        [Paragraph("<b>7. Backend Processing</b>", styles['TableCellBold']), Paragraph("Stores slip in private disk `transfer_proofs/` &rarr; order assigned `awaiting_verification` &rarr; enters staff audit queue.", styles['TableCell'])],
        [Paragraph("<b>8. User Output</b>", styles['TableCellBold']), Paragraph("Confirmation page notifying 2-hour staff verification SLA; immediate visibility in the admin review queue.", styles['TableCell'])],
        [Paragraph("<b>9. Guardrails & Validasi</b>", styles['TableCellBold']), Paragraph("MIME type validation; 12-hour grace period if rejected by staff to allow uploading corrected proof.", styles['TableCell'])],
        [Paragraph("<b>10. Current Status</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Verified End-to-End</b>", styles['BadgeSuccess'])],
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
    story.append(Paragraph("6.4 My Digital Garden (Smart Hydration & Weather Sync)", styles['DocHeading2']))
    f4_data = [
        [Paragraph("<b>1. Feature Name</b>", styles['TableCellBold']), Paragraph("Digital Garden Living Plant Care Tracker", styles['TableCell'])],
        [Paragraph("<b>2. Primary Objective</b>", styles['TableCellBold']), Paragraph("Empower customers to sustain healthy houseplants at home with adaptive care schedules.", styles['TableCell'])],
        [Paragraph("<b>3. Problem Solved</b>", styles['TableCellBold']), Paragraph("Prevents plant death resulting from overwatering or forgetting hydration and fertilization intervals.", styles['TableCell'])],
        [Paragraph("<b>4. Access Path</b>", styles['TableCellBold']), Paragraph("Navigation link <b>My Digital Garden</b> (`/garden`), or 'Adopt to Garden' on the tracking page.", styles['TableCell'])],
        [Paragraph("<b>5. Step-by-Step Workflow</b>", styles['TableCellBold']), Paragraph("Open `/garden` &rarr; review soil hydration gauges &rarr; click 'Water Now' after watering &rarr; schedule updates dynamically.", styles['TableCell'])],
        [Paragraph("<b>6. Input Data</b>", styles['TableCellBold']), Paragraph("Action `POST /garden/{id}/water`, action `POST /garden/{id}/fertilize`, customized specimen nicknames.", styles['TableCell'])],
        [Paragraph("<b>7. Backend Processing</b>", styles['TableCellBold']), Paragraph("Computes elapsed hours since watering &rarr; synchronizes ambient humidity from Bantul DIY weather cache &rarr; calculates remaining hydration percentage.", styles['TableCell'])],
        [Paragraph("<b>8. User Output</b>", styles['TableCellBold']), Paragraph("Visual circular hydration gauge (0-100%), urgent care alert badges, next watering due date.", styles['TableCell'])],
        [Paragraph("<b>9. Guardrails & Validasi</b>", styles['TableCellBold']), Paragraph("Accessible strictly by authenticated members owning adopted specimens.", styles['TableCell'])],
        [Paragraph("<b>10. Current Status</b>", styles['TableCellBold']), Paragraph("<b>Implemented & Fully Operational</b>", styles['BadgeSuccess'])],
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
    # CHAPTER 7: USER INTERFACE (UI) COMPONENT DESIGN SYSTEM
    # =========================================================================
    story.append(Paragraph("CHAPTER 7 — USER INTERFACE (UI) COMPONENT DESIGN SYSTEM", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Plantify Official utilizes a custom 'Fernwick Glasshouse' aesthetic built on Tailwind CSS 4 and Alpine.js. Below is an inventory of active UI components deployed across the application:",
        styles['DocBody']
    ))

    ui_components = [
        [Paragraph("UI Component", styles['TableHead']), Paragraph("Application Location", styles['TableHead']), Paragraph("Interaction Pattern & Design Mechanics", styles['TableHead']), Paragraph("User & Operational Benefit", styles['TableHead'])],
        [
            Paragraph("<b>Sticky Header & is_active Highlighting</b>", styles['TableCellBold']),
            Paragraph("Global Layout (`app.blade.php`)", styles['TableCell']),
            Paragraph("Backdrop blur effect, Alpine cloak, active route styling (`border-b-2 border-ochre font-bold`).", styles['TableCell']),
            Paragraph("Consistent navigation orientation; users immediately identify their current section.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Quick Demo Access Card</b>", styles['TableCellBold']),
            Paragraph("Login View (`login.blade.php`)", styles['TableCell']),
            Paragraph("Two one-click buttons: 'Sign in as Admin' & 'Sign in as Customer' with automated form submit.", styles['TableCell']),
            Paragraph("Enables friction-free evaluator walkthroughs without manual credential typing.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Botanical Specimen Card</b>", styles['TableCellBold']),
            Paragraph("Home & Catalog (`home`, `shop`)", styles['TableCell']),
            Paragraph("Responsive photography, stock pills, discount badges, pet-safe icons, care difficulty tags.", styles['TableCell']),
            Paragraph("Comprehensive specimen assessment at a single glance before clicking detail views.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Multi-Criteria Botanical Filter Panel</b>", styles['TableCellBold']),
            Paragraph("Catalog (`shop.blade.php`)", styles['TableCell']),
            Paragraph("Sunlight checkboxes (low/medium/bright), pet-safe toggle, category dropdown, price slider.", styles['TableCell']),
            Paragraph("Empowers buyers to filter specimens tailored precisely to their living room environment.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Action Strip (Buy Now vs Cart)</b>", styles['TableCellBold']),
            Paragraph("Specimen Detail (`product.blade.php`)", styles['TableCell']),
            Paragraph("Secondary 'Add to Cart' button placed alongside primary 'Buy Now &rarr;' action button.", styles['TableCell']),
            Paragraph("Provides flexibility for multi-item exploration or instant single-plant checkout.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Receipt Slip Dropzone & Preview</b>", styles['TableCellBold']),
            Paragraph("Checkout (`checkout/index.blade.php`)", styles['TableCell']),
            Paragraph("Drag-and-drop file uploader with Alpine.js image preview and MIME validation.", styles['TableCell']),
            Paragraph("Eliminates mistaken uploads before submitting orders to staff verification.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Circular Hydration Gauge</b>", styles['TableCellBold']),
            Paragraph("Digital Garden (`garden.blade.php`)", styles['TableCell']),
            Paragraph("Dynamic SVG circular progress ring changing from green to amber/red as soil moisture depletes.", styles['TableCell']),
            Paragraph("Immediate visual cues preventing overwatering and dehydration.", styles['TableCell'])
        ],
        [
            Paragraph("<b>AI Diagnosis Camera Dropzone</b>", styles['TableCellBold']),
            Paragraph("AI Doctor (`care.blade.php`)", styles['TableCell']),
            Paragraph("Interactive image dropzone, live thumbnail preview, and animated processing skeleton.", styles['TableCell']),
            Paragraph("Delivers an intuitive clinical diagnostic experience directly in the browser.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Pickup Handover Verification Modal</b>", styles['TableCellBold']),
            Paragraph("Admin Pickup (`admin/pickup`)", styles['TableCell']),
            Paragraph("Prominent 6-digit code input with confirmation dialog before final physical plant handover.", styles['TableCell']),
            Paragraph("Prevents cashier mistakes when handing over plants to courier drivers or customers.", styles['TableCell'])
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
    # CHAPTER 8: USER ROLES & ACCESS CONTROL MATRIX (RBAC)
    # =========================================================================
    story.append(Paragraph("CHAPTER 8 — USER ROLES & ACCESS CONTROL MATRIX (RBAC)", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Plantify enforces strict Role-Based Access Control (RBAC) across three distinct user tiers governed by Laravel's `auth` and `admin` middleware:",
        styles['DocBody']
    ))

    rbac_matrix = [
        [Paragraph("Module / System Capability", styles['TableHead']), Paragraph("Guest (Public Visitor)", styles['TableHead']), Paragraph("Customer (Registered Member)", styles['TableHead']), Paragraph("Administrator / Staff", styles['TableHead'])],
        [Paragraph("Browse Living Catalog & Specimen Profiles", styles['TableCellBold']), Paragraph("Yes (Read-Only)", styles['TableCell']), Paragraph("Yes (Read-Only)", styles['TableCell']), Paragraph("Yes (Read-Only)", styles['TableCell'])],
        [Paragraph("Shopping Bag & Direct Buy Now Actions", styles['TableCellBold']), Paragraph("Yes (Session-Based)", styles['TableCell']), Paragraph("Yes (User-Linked)", styles['TableCell']), Paragraph("Yes (Demo Testing)", styles['TableCell'])],
        [Paragraph("Checkout Order Placement & Slip Upload", styles['TableCellBold']), Paragraph("Yes (Guest Order)", styles['TableCell']), Paragraph("Yes (Profile Autofill)", styles['TableCell']), Paragraph("Yes (Demo Testing)", styles['TableCell'])],
        [Paragraph("Tokenized Order Tracking", styles['TableCellBold']), Paragraph("Yes (Via Order Token)", styles['TableCell']), Paragraph("Yes (Account History)", styles['TableCell']), Paragraph("Yes (Global Order Access)", styles['TableCell'])],
        [Paragraph("Botanical Gift Claiming Portal", styles['TableCellBold']), Paragraph("Yes (Via Gift Token)", styles['TableCell']), Paragraph("Yes (Adoption to Garden)", styles['TableCell']), Paragraph("Yes (Staff Audit)", styles['TableCell'])],
        [Paragraph("My Digital Garden Living Care Tracker", styles['TableCellBold']), Paragraph("No (Requires Auth)", styles['TableCell']), Paragraph("<b>Full</b> (Manage Collection)", styles['TableCell']), Paragraph("Yes (Staff Testing)", styles['TableCell'])],
        [Paragraph("AI Plant Doctor Consultations (100x Quota)", styles['TableCellBold']), Paragraph("Limited (Overview Info)", styles['TableCell']), Paragraph("<b>Full</b> (100x Daily Quota)", styles['TableCell']), Paragraph("<b>Full</b> (Unlimited Quota)", styles['TableCell'])],
        [Paragraph("Admin Console Dashboard & Metrics", styles['TableCellBold']), Paragraph("No (Blocked)", styles['TableCell']), Paragraph("No (HTTP 403 Forbidden)", styles['TableCell']), Paragraph("<b>Full Access</b>", styles['TableCell'])],
        [Paragraph("Bank Transfer Slip Verification Queue", styles['TableCellBold']), Paragraph("No (Blocked)", styles['TableCell']), Paragraph("No (HTTP 403 Forbidden)", styles['TableCell']), Paragraph("<b>Full</b> (Approve / Reject)", styles['TableCell'])],
        [Paragraph("Retail POS Pickup Counter (6-Digit Code)", styles['TableCellBold']), Paragraph("No (Blocked)", styles['TableCell']), Paragraph("No (HTTP 403 Forbidden)", styles['TableCell']), Paragraph("<b>Full</b> (Handover Verification)", styles['TableCell'])],
        [Paragraph("Logistics Delivery Dispatch & Courier Resi", styles['TableCellBold']), Paragraph("No (Blocked)", styles['TableCell']), Paragraph("No (HTTP 403 Forbidden)", styles['TableCell']), Paragraph("<b>Full</b> (Dispatch & Deliver)", styles['TableCell'])],
        [Paragraph("Plant Inventory & Specimen Parameters", styles['TableCellBold']), Paragraph("No (Blocked)", styles['TableCell']), Paragraph("No (HTTP 403 Forbidden)", styles['TableCell']), Paragraph("<b>Full</b> (Stock & Price Manager)", styles['TableCell'])],
        [Paragraph("Store Settings, Bank Accounts, & Hours", styles['TableCellBold']), Paragraph("No (Blocked)", styles['TableCell']), Paragraph("No (HTTP 403 Forbidden)", styles['TableCell']), Paragraph("<b>Full</b> (System Configuration)", styles['TableCell'])],
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
    # CHAPTER 9: END-TO-END WORKFLOWS (6 DETAILED SCENARIOS)
    # =========================================================================
    story.append(Paragraph("CHAPTER 9 — END-TO-END WORKFLOWS (6 DETAILED SCENARIOS)", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Documented sequential business workflows illustrating how data, payments, and specimens transition through the platform:",
        styles['DocBody']
    ))

    # Workflow 1
    story.append(Paragraph("<b>Workflow 1: Online Purchasing & Climate-Safe Delivery Dispatch</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Customer selects a specimen and quantity in `/shop` or `/product/{slug}` &rarr; clicks <i>Add to Cart</i>.<br/>"
        "2. On `/checkout`, customer selects <b>Delivery to Address</b> and enters shipping information.<br/>"
        "3. System automatically calculates subtotal + flat rate delivery fee ($15) + tax (8%).<br/>"
        "4. Customer completes payment (Stripe Demo auto-paid or Bank Transfer) &rarr; order transitions to `processing`.<br/>"
        "5. Nursery staff packs the plant with root hydration wrapping and insulation at `/admin/delivery`.<br/>"
        "6. Staff inputs carrier name and tracking number &rarr; status updates to `shipped`.<br/>"
        "7. Courier delivers the package &rarr; order marks `delivered` &rarr; customer adopts specimen to Digital Garden.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 2
    story.append(Paragraph("<b>Workflow 2: Direct Buy Now & In-Store Nursery Counter Pickup (POS 6-Digit Code)</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Customer views specimen profile (e.g., Monstera Deliciosa) &rarr; clicks <b>Buy Now &rarr;</b>.<br/>"
        "2. System routes directly to `/checkout`; customer selects <b>Store Pickup (Bantul Nursery)</b>.<br/>"
        "3. Delivery fee is zeroed; system automatically issues a <b>Unique 6-Digit Pickup Code</b> (e.g., <code>PK-948123</code>).<br/>"
        "4. Customer visits the Bantul DIY nursery during operating hours (Tue-Sun 08:30-17:00 WIB).<br/>"
        "5. Cashier opens `/admin/pickup`, enters the 6-digit code &rarr; reviews specimen &rarr; clicks <i>Handover</i>.<br/>"
        "6. Order completes immediately; customer receives their plant in pristine condition.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 3
    story.append(Paragraph("<b>Workflow 3: Manual Bank Transfer, Slip Upload, and Staff Verification SLA</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Customer chooses <i>Manual Bank Transfer</i> on the checkout form.<br/>"
        "2. System displays official nursery accounts (BCA, Mandiri, BNI) and the exact order total.<br/>"
        "3. Customer transfers funds, enters sender bank & account name, and uploads receipt slip image/PDF.<br/>"
        "4. Order is recorded as `awaiting_verification` and immediately enters the `/admin/verifications` queue.<br/>"
        "5. Nursery staff audits the transfer slip through secure local storage.<br/>"
        "6. If valid, staff clicks <b>Approve</b> &rarr; payment marks `paid` and order advances to `processing`.<br/>"
        "7. If invalid, staff clicks <b>Reject</b> with feedback &rarr; customer is granted a 12-hour grace period to re-upload.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 4
    story.append(Paragraph("<b>Workflow 4: Botanical Gift Purchase & Digital Garden Token Claiming</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Buyer marks <i>'This is a gift for someone special'</i> during checkout.<br/>"
        "2. Buyer supplies recipient name, recipient email, and a personalized gift message (up to 500 characters).<br/>"
        "3. Once order is settled, system generates a unique gift token valid for 30 days.<br/>"
        "4. Recipient visits `/gift/{token}` &rarr; reads the personalized note and views the gifted specimen.<br/>"
        "5. Recipient clicks <b>Claim Gift & Adopt</b> &rarr; plant transfers immediately to recipient's Digital Garden.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 5
    story.append(Paragraph("<b>Workflow 5: Plant Foliage Diagnosis via Ciora AI Vision & Organic Recovery</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Member notices yellowing foliage or leaf spotting &rarr; navigates to `/care`.<br/>"
        "2. Member uploads leaf photography via mobile camera or desktop file uploader.<br/>"
        "3. Frontend dispatches image to POST `/ai/diagnose`.<br/>"
        "4. Backend verifies quota (100x/day), communicates with Ciora Vision Gateway, and analyzes pathology.<br/>"
        "5. Diagnostic card renders within seconds: identified condition, severity rating, and 3-step organic recovery regimen.<br/>"
        "6. Member continues conversation with the Botanical Chatbot on the same page for follow-up care.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    # Workflow 6
    story.append(Paragraph("<b>Workflow 6: Daily Digital Garden Care & Weather-Adaptive Watering Cycle</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. Member opens `/garden` to inspect their living plant collection.<br/>"
        "2. System reads ambient temperature and humidity from the Bantul DIY weather cache to evaluate soil hydration.<br/>"
        "3. Specimens below 25% hydration trigger an amber/red <i>Needs Watering</i> badge.<br/>"
        "4. Member waters the physical specimen &rarr; clicks <b>Water Now</b>.<br/>"
        "5. System resets hydration to 100% and calculates the next watering due date based on species parameters.",
        styles['DocBullet']
    ))

    story.append(PageBreak())

    # =========================================================================
    # CHAPTER 10: DATABASE ARCHITECTURE, INTEGRATIONS, & AUTOMATION
    # =========================================================================
    story.append(Paragraph("CHAPTER 10 — DATABASE ARCHITECTURE, INTEGRATIONS, & AUTOMATION", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "<b>Relational Database Schema (28 Normalized Tables):</b> The database is normalized to Third Normal Form (3NF) to ensure referential integrity and performance:",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "• <i>Identity & Governance:</i> <code>users</code> (role, timezone, profile), <code>password_reset_tokens</code>, <code>sessions</code>, <code>audit_logs</code> (mutation audits).<br/>"
        "• <i>Botanical Catalog:</i> <code>categories</code>, <code>plants</code> (sunlight, watering intervals, pet-safety, stock), <code>plant_images</code>.<br/>"
        "• <i>Commerce & Orders:</i> <code>cart_items</code>, <code>orders</code> (tracking token, payment method/status, order status, pickup code), <code>order_items</code>, <code>bank_accounts</code>, <code>payment_methods</code>, <code>refunds</code>.<br/>"
        "• <i>Care & AI Ecosystem:</i> <code>user_gardens</code> (hydration logs), <code>gifts</code>, <code>ai_diagnoses</code>, <code>ai_chats</code>, <code>ai_usage_logs</code>, <code>weather_caches</code>, <code>store_settings</code>.<br/>"
        "• <i>Queue & Infrastructure:</i> <code>jobs</code>, <code>job_batches</code>, <code>failed_jobs</code>, <code>cache</code>, <code>cache_locks</code>, <code>stripe_webhook_events</code>.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph("<b>Active External API Integrations:</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "1. <b>Ciora AI Gateway (`https://ciora.id/v1`):</b> Uses `ciora-ai-free` for botanical chat and `ciora-vision-free` for visual diagnosis. Configured with a generous 100x daily consultation quota and resilient botanical expert fallbacks if network interruptions occur.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "2. <b>OpenWeatherMap API & Weather Cache:</b> Synchronizes real-time temperature and relative humidity for adaptive watering schedules in Digital Garden, calibrated for the tropical climate of DI Yogyakarta.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "3. <b>Stripe Checkout Gateway:</b> Equipped with webhook signature verification; runs with Demo Mode auto-settlement to enable seamless end-to-end evaluation without credit card charges.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph("<b>Automated Background Tasks (Scheduled Jobs):</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "• <code>SendWateringRemindersJob</code>: Evaluates customer garden specimens each morning and dispatches watering alerts when soil hydration drops below threshold.<br/>"
        "• <code>CancelExpiredOrdersJob</code>: Automatically cancels unverified bank transfer orders exceeding the 24-hour expiration window and restores physical stock to the catalog.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 4))

    story.append(Paragraph("<b>Security & Cryptographic Standards:</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "Passwords hashed using Argon2id/Bcrypt; all forms protected with CSRF tokens; email verification utilizes cryptographic signed URLs; bank transfer slips are isolated in private non-public storage accessible strictly through authenticated admin proxy routes (`/admin/proof-view`).",
        styles['DocBody']
    ))
    story.append(Spacer(1, 6))

    # =========================================================================
    # CHAPTER 11: REPORTING, MONITORING, & OPERATIONAL OUTPUTS
    # =========================================================================
    story.append(Paragraph("CHAPTER 11 — REPORTING, MONITORING, & OPERATIONAL OUTPUTS", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    reports_data = [
        [Paragraph("Operational Report / Output", styles['TableHead']), Paragraph("Key Data & Visualizations Presented", styles['TableHead']), Paragraph("Strategic Purpose & Management Value", styles['TableHead'])],
        [
            Paragraph("<b>Executive Dashboard Metrics</b>", styles['TableCellBold']),
            Paragraph("Cumulative revenue, total orders, active adopted species, pending bank verification badges.", styles['TableCell']),
            Paragraph("Single-screen operational visibility for nursery business owners.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Orders Management Log</b>", styles['TableCellBold']),
            Paragraph("Chronological order directory, payment statuses, fulfillment filters (delivery vs pickup), item details.", styles['TableCell']),
            Paragraph("Transaction auditing, customer support reconciliation, and refund processing.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Bank Verification Queue</b>", styles['TableCellBold']),
            Paragraph("Real-time list of submitted transfer slips, sender bank name, account name, submission timestamp.", styles['TableCell']),
            Paragraph("Enforces the 2-hour staff verification SLA and prevents fulfillment bottlenecks.", styles['TableCell'])
        ],
        [
            Paragraph("<b>POS Daily Pickup Register</b>", styles['TableCellBold']),
            Paragraph("Ready-for-pickup queue, unique 6-digit codes, collector names, cashier handover timestamps.", styles['TableCell']),
            Paragraph("Eliminates inventory handover errors and facilitates daily stock balancing.", styles['TableCell'])
        ],
        [
            Paragraph("<b>Web App Proposal PDF (`/proposal-pdf`)</b>", styles['TableCellBold']),
            Paragraph("Complete 15-chapter architecture specification, feature breakdowns, and operational workflows.", styles['TableCell']),
            Paragraph("Executive collateral ready for prospective clients, investors, and leadership teams.", styles['TableCell'])
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
    # CHAPTER 12: COMPETITIVE ADVANTAGES & BUSINESS BENEFITS
    # =========================================================================
    story.append(Paragraph("CHAPTER 12 — COMPETITIVE ADVANTAGES & BUSINESS BENEFITS", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "Plantify Official delivers distinct commercial and operational advantages substantiated by real system capabilities:",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "1. <b>Botanical Domain-Specific UX:</b> Far from generic retail e-commerce, every data point reflects genuine plant needs—lighting categories, pet toxicity, watering intervals, and difficulty levels.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "2. <b>High Customer Lifetime Value (LTV):</b> Digital Garden transforms passive shoppers into daily active users who regularly consult watering schedules and care logs.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "3. <b>AI-Driven Horticultural Support Efficiency:</b> Ciora AI Vision & Chat resolve 80%+ of everyday plant health inquiries 24/7, freeing expert botanists for high-value propagation.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "4. <b>Friction-Free Purchasing with 'Buy Now':</b> Capitalizes on impulse buying by eliminating intermediate cart hurdles with an instant 1-click checkout flow.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "5. <b>Error-Free In-Store POS & Climate Logistics:</b> 6-digit pickup verification and insulated shipping guidelines safeguard living specimens and eliminate misplaced orders.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 6))

    # =========================================================================
    # CHAPTER 13: IMPLEMENTATION STATUS & SCOPE MATRIX
    # =========================================================================
    story.append(Paragraph("CHAPTER 13 — IMPLEMENTATION STATUS & SCOPE MATRIX", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    status_data = [
        [Paragraph("Module / System Feature", styles['TableHead']), Paragraph("Implementation Status", styles['TableHead']), Paragraph("Verification Evidence & Notes", styles['TableHead'])],
        [Paragraph("Living Catalog & Botanical Filters", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/shop`; sunlight, pet-safe, and difficulty filters verified.", styles['TableCell'])],
        [Paragraph("Direct Buy Now Action", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/product/{slug}`; instant HTTP 302 redirect to `/checkout` verified.", styles['TableCell'])],
        [Paragraph("Manual Bank Transfer + Proof Upload", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Slip upload, private disk storage, and staff audit queue verified.", styles['TableCell'])],
        [Paragraph("Stripe Checkout Payment", styles['TableCellBold']), Paragraph("<b>Demo Mode</b>", styles['BadgeWarning']), Paragraph("Active with automated payment bypass for friction-free demo evaluation.", styles['TableCell'])],
        [Paragraph("Store Pickup POS Desk (6-Digit Code)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/admin/pickup`; code generation and handover verified.", styles['TableCell'])],
        [Paragraph("Delivery Dispatch & Tracking Status", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/admin/delivery`; carrier tracking and status transitions verified.", styles['TableCell'])],
        [Paragraph("My Digital Garden (Water/Fertilize)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/garden`; hydration gauges, Bantul DIY weather sync verified.", styles['TableCell'])],
        [Paragraph("AI Plant Doctor (Ciora Vision & Chat)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Connected to Ciora AI Gateway with 100x daily quota and fallback engine.", styles['TableCell'])],
        [Paragraph("Botanical Gift & Claim Engine", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/gift/{token}`; personalized note display and garden adoption verified.", styles['TableCell'])],
        [Paragraph("Quick Demo Access Login Card", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Active at `/login`; 1-click Admin & Customer authentication buttons verified.", styles['TableCell'])],
        [Paragraph("Proposal PDF Generator (`/proposal-pdf`)", styles['TableCellBold']), Paragraph("<b>Implemented</b>", styles['BadgeSuccess']), Paragraph("Dedicated inline browser endpoint serving complete 15-chapter publication PDF.", styles['TableCell'])],
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
    # CHAPTER 14: FUTURE EXTENSIBILITY & OPERATIONAL CUSTOMIZATION
    # =========================================================================
    story.append(Paragraph("CHAPTER 14 — FUTURE EXTENSIBILITY & OPERATIONAL CUSTOMIZATION", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    # Required core narrative statement in US English
    mandate_box = [
        [Paragraph(
            "<b>PLATFORM EXTENSIBILITY & ENTERPRISE ADAPTABILITY COMMITMENT:</b><br/>"
            "This web application remains open for further development and can be customized to match your enterprise's "
            "operational needs, workflows, and business challenges. Features, menus, integrations, reporting tools, and "
            "business processes can be evaluated and refined based on real-world user demands.<br/><br/>"
            "Development is carried out iteratively by prioritizing the most critical operational challenges first. Through this "
            "approach, the system continuously evolves into an increasingly relevant digital solution—streamlining everyday operations, "
            "eliminating unnecessary manual processes, improving data integrity, and resolving operational bottlenecks more effectively.<br/><br/>"
            "We welcome discussions to tailor the platform so that every developed capability directly serves your enterprise's "
            "objectives, rather than merely adding unneeded complexity.",
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

    story.append(Paragraph("<b>Potential Roadmap Extensions (Strategic Partnerships):</b>", styles['DocHeading3']))
    story.append(Paragraph(
        "The modular architecture of Plantify allows seamless integration of advanced enterprise capabilities upon mutual agreement:",
        styles['DocBody']
    ))
    story.append(Paragraph(
        "• <i>IoT Wireless Soil Moisture Sensor Sync:</i> Direct hardware integration with Zigbee or ESP32 soil probes transmitting real-time moisture data straight into the Digital Garden.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>WhatsApp Business API Notifications:</i> Automated delivery of watering reminders, shipment tracking, and order confirmations directly to customer phones.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Botanical Subscription Box (Monthly Seedlings):</i> Recurring billing subscription module for monthly curated indoor plants, organic fertilizers, and handcrafted planters.",
        styles['DocBullet']
    ))
    story.append(Paragraph(
        "• <i>Multi-Branch Nursery Inventory Decentralization:</i> Centralized multi-warehouse management across regional nursery outlets with intelligent localized stock allocation.",
        styles['DocBullet']
    ))
    story.append(Spacer(1, 8))

    # =========================================================================
    # CHAPTER 15: CONCLUSION & OFFICIAL CONTACT DIRECTORY
    # =========================================================================
    story.append(Paragraph("CHAPTER 15 — CONCLUSION & OFFICIAL CONTACT DIRECTORY", styles['DocHeading1']))
    story.append(HRFlowable(width="100%", thickness=1, color=C_OCHRE, spaceBefore=2, spaceAfter=8))
    
    story.append(Paragraph(
        "The Plantify Official platform unites living plant commerce, physical retail POS execution, and artificial intelligence into a singular digital ecosystem. We stand fully prepared to:",
        styles['DocBody']
    ))
    story.append(Paragraph("• Conduct an interactive demonstration and walkthrough tailored to your enterprise's needs.", styles['DocBullet']))
    story.append(Paragraph("• Identify unique operational workflows or inventory challenges not yet accommodated.", styles['DocBullet']))
    story.append(Paragraph("• Define development priorities aligned with your business roadmap and budget.", styles['DocBullet']))
    story.append(Paragraph("• Deliver a customized, enterprise-grade deployment with full production support.", styles['DocBullet']))
    story.append(Spacer(1, 10))

    closing_contact = [
        [Paragraph("<b>Official Enterprise Contact Directory:</b>", styles['TableHead']), Paragraph("", styles['TableHead'])],
        [Paragraph("<b>Operating Entity:</b>", styles['TableCellBold']), Paragraph("Plantify Official Nursery & Botanical Care Center", styles['TableCell'])],
        [Paragraph("<b>Physical Nursery Address:</b>", styles['TableCellBold']), Paragraph("Jl. Agrowisata Hijau No. 24, Botanical District, Bantul, Special Region of Yogyakarta", styles['TableCell'])],
        [Paragraph("<b>Nursery Operating Hours:</b>", styles['TableCellBold']), Paragraph("Tuesday &ndash; Sunday, 08:30 &ndash; 17:00 WIB (Closed Mondays for Specimen Care)", styles['TableCell'])],
        [Paragraph("<b>Live Web Application:</b>", styles['TableCellBold']), Paragraph("https://demo.ramadadawah.my.id", styles['TableCell'])],
        [Paragraph("<b>Proposal PDF Endpoint:</b>", styles['TableCellBold']), Paragraph("https://demo.ramadadawah.my.id/proposal-pdf", styles['TableCell'])],
        [Paragraph("<b>Engineering Team:</b>", styles['TableCellBold']), Paragraph("Adanix Software Engineering & Botanical Digital Solutions", styles['TableCell'])],
        [Paragraph("<b>Official Email:</b>", styles['TableCellBold']), Paragraph("nixresponding@gmail.com", styles['TableCell'])],
        [Paragraph("<b>Repository:</b>", styles['TableCellBold']), Paragraph("https://github.com/adanix", styles['TableCell'])],
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
        "<i>This technical proposal is compiled automatically from real-world codebase audits and live system implementations of Plantify Official. All rights reserved.</i>",
        styles['TableCell']
    ))

    # Build document using NumberedCanvas and custom cover background on Page 1
    doc.build(story, canvasmaker=NumberedCanvas, onFirstPage=draw_cover_background)
    print(f"Successfully generated proposal PDF (en-US): {filename}")

if __name__ == '__main__':
    output_dir = '/opt/adanix/laravel/plantify/src/public'
    output_pdf = os.path.join(output_dir, 'PROPOSAL_PENGEMBANGAN_PLANTIFY.pdf')
    if len(sys.argv) > 1:
        output_pdf = sys.argv[1]
    
    os.makedirs(os.path.dirname(output_pdf), exist_ok=True)
    build_pdf(output_pdf)
