# Plantify Official — Artisanal Botanical Nursery & Living Sanctuary Platform

[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS 4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)](https://alpinejs.dev)
[![MySQL 8.0](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Ciora AI](https://img.shields.io/badge/Ciora_AI-Vision_%26_Chat-1E3A2F?style=for-the-badge&logo=openai&logoColor=white)](https://ciora.id)
[![License: MIT](https://img.shields.io/badge/License-MIT-D69E2E?style=for-the-badge)](LICENSE)

> **Live Production**: [https://plantify.ramadadawah.my.id](https://plantify.ramadadawah.my.id)  
> **Executive Proposal Document (PDF)**: [https://plantify.ramadadawah.my.id/proposal-pdf](https://plantify.ramadadawah.my.id/proposal-pdf)  
> **Physical Nursery Desk**: Jl. Agrowisata Hijau No. 24, Botanical District, Bantul, Special Region of Yogyakarta  
> **Repository**: [https://github.com/Adanix/tanaman-shop](https://github.com/Adanix/tanaman-shop)

---

## 🌿 Table of Contents

- [About the Platform](#-about-the-platform)
- [System Architecture & 3 Core Pillars](#-system-architecture--3-core-pillars)
- [Key Features](#-key-features)
  - [1. Curated Living Storefront & Direct Buy Now](#1-curated-living-storefront--direct-buy-now)
  - [2. Multi-Channel Checkout & Proof Verification](#2-multi-channel-checkout--proof-verification)
  - [3. Retail In-Store POS Pickup Counter](#3-retail-in-store-pos-pickup-counter)
  - [4. My Digital Garden (Smart Hydration Sync)](#4-my-digital-garden-smart-hydration-sync)
  - [5. AI Plant Doctor & Botanical Chatbot](#5-ai-plant-doctor--botanical-chatbot)
  - [6. Botanical Gift Claiming Engine](#6-botanical-gift-claiming-engine)
  - [7. Executive Nursery Operations Console](#7-executive-nursery-operations-console)
- [User Roles & Access Control](#-user-roles--access-control)
- [Tech Stack](#-tech-stack)
- [Quick Start & Local Installation](#-quick-start--local-installation)
- [Demo Credentials](#-demo-credentials)
- [Proposal PDF Generator](#-proposal-pdf-generator)
- [License & Credits](#-license--credits)

---

## 🍃 About the Platform

**Plantify Official** is a modern e-commerce and living care platform engineered specifically for artisanal botanical nurseries and plant collectors. Unlike conventional retail platforms that treat houseplants as inanimate consumer goods, Plantify is built around the philosophy of **Living Catalog & Lifetime Care Retention**.

Every specimen purchase initiates an ongoing care relationship. Customers adopt plants into their personalized **Digital Garden**, receiving adaptive watering reminders synchronized with local weather conditions and 24/7 visual disease diagnosis powered by **Ciora AI Vision & Chat**.

---

## 🏛 System Architecture & 3 Core Pillars

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           PLANTIFY ECOSYSTEM                            │
├───────────────────────┬─────────────────────────┬───────────────────────┤
│ 1. Living Storefront  │ 2. Botanical Care Hub   │ 3. Nursery Operations │
├───────────────────────┼─────────────────────────┼───────────────────────┤
│ • Curated Catalog     │ • My Digital Garden     │ • Executive Metrics   │
│ • Sunlight Filters    │ • Soil Hydration Gauge  │ • POS Pickup Counter  │
│ • 1-Click Buy Now     │ • Ciora AI Vision Diag  │ • Slip Audit Queue    │
│ • Direct Checkout     │ • Botanical Assistant   │ • Dispatch Tracker    │
│ • Gift Personalizer   │ • Weather Cache Sync    │ • Plant Inventory     │
└───────────────────────┴─────────────────────────┴───────────────────────┘
```

1. **Curated Living Storefront**: High-aesthetic catalog with domain-specific botanical filters (light intensity, pet toxicity, care difficulty) and rapid purchase flows.
2. **Botanical Intelligence & Care Hub**: Member sanctuary delivering soil hydration tracking, automated care logs, and artificial intelligence diagnostics.
3. **Nursery Operations & POS Back-Office**: Staff control desk managing retail counter handovers with 6-digit codes, domestic bank transfer slip verifications with 2-hour SLAs, and insulated climate-safe delivery dispatches.

---

## ✨ Key Features

### 1. Curated Living Storefront & Direct Buy Now
- **Sunlight & Care Filters**: Filter specimens by light exposure (Low, Medium, Bright Filtered), pet toxicity (*Pet-Friendly Botanicals*), and care difficulty (*Beginner-Friendly*).
- **1-Click "Buy Now &rarr;"**: High-intent buyers can bypass the shopping cart detour and transition directly to the checkout form in a single click.
- **Specimen Profiles**: Complete botanical care strips showing scientific family names, watering interval cycles, and native humidity parameters.

### 2. Multi-Channel Checkout & Proof Verification
- **Manual Domestic Bank Transfer**: Direct bank transfer to official nursery accounts (BCA, Mandiri, BNI) with integrated receipt image/PDF upload (`payment_proof`) and Alpine.js visual preview.
- **2-Hour Staff Verification SLA**: Orders transition into `awaiting_verification` and immediately populate the staff audit queue. Rejections include a 12-hour grace period for slip re-upload.
- **Stripe Checkout Integration**: Seamless card payments with an automated Demo Mode bypass for test walkthroughs without real card charges.

### 3. Retail In-Store POS Pickup Counter
- **6-Digit Cryptographic Pickup Code**: Store pickup orders generate a unique code (e.g., `PK-948123`) presented to the customer on order tracking screens.
- **Cashier POS Desk (`/admin/pickup`)**: Nursery cashiers enter the code, verify plant health, and complete instant physical handover.

### 4. My Digital Garden (Smart Hydration Sync)
- **Living Specimen Adoption**: Purchased or gifted plants transfer into the member's private garden with custom nicknames and care histories.
- **Dynamic Circular Hydration Gauges**: Soil moisture percentages (0–100%) adjust automatically based on elapsed hours and ambient weather data (Bantul DIY).
- **One-Click Actions**: Record watering (`Water Now`) and fertilization (`Fertilize Now`) events with dynamic recalculation of next due dates.

### 5. AI Plant Doctor & Botanical Chatbot
- **Ciora AI Vision Pathology Diagnosis**: Upload leaf photography to detect chlorosis, fungal leaf spots, or pest infestations within seconds, accompanied by a 3-step organic recovery regimen.
- **Conversational Botanical Care Assistant**: Real-time Q&A chatbot answering horticultural questions 24/7.
- **Generous 100x Daily Quota**: Configured with an expanded daily limit of 100 consultations per user for extensive evaluation.
- **Resilient Fallback Engine**: If upstream gateway keys encounter rate limits, the system seamlessly activates a structured botanical expert fallback so the application never fails.

### 6. Botanical Gift Claiming Engine
- **Gift Purchases**: Check out living specimens as gifts with personalized dedication notes (up to 500 characters) and recipient contact details.
- **Tokenized Claim Portal (`/gift/{token}`)**: Recipients view their gift card note, confirm delivery details, and adopt the specimen directly into their Digital Garden.

### 7. Executive Nursery Operations Console
- **Executive Metrics**: Live overview of total revenue, cumulative orders, active adopted garden specimens, and pending bank verification badges.
- **Order Lifecycle Management**: Complete timeline tracking from `awaiting_payment` &rarr; `processing` &rarr; `shipped` / `ready_for_pickup` &rarr; `delivered`.
- **Plant Catalog Manager**: Adjust physical nursery stock, sale pricing, and activation toggles.
- **Store & POS Configuration**: Configure physical store address (Bantul, DIY), pickup operating hours, bank accounts, and delivery flat fees.

---

## 👥 User Roles & Access Control

| Capability / Module | Guest (Public) | Customer (Member) | Administrator / Staff |
|---|:---:|:---:|:---:|
| Browse Living Catalog & Specimen Detail | Read-Only | Read-Only | Read-Only |
| Shopping Bag & 1-Click Buy Now | Session-Based | User-Linked | Full Access |
| Multi-Channel Checkout & Proof Upload | Guest Order | Profile Autofill | Full Access |
| Tokenized Order & Delivery Tracking | Via Token | Account History | Global Access |
| Botanical Gift Claiming Portal | Via Token | Auto-Adopt | Staff Review |
| My Digital Garden Living Care Hub | ❌ Auth Required | **Full Access** | Staff Testing |
| AI Plant Doctor (100x Daily Quota) | Limited Info | **100x / Day** | **Unlimited** |
| Executive Admin Dashboard & Metrics | ❌ Blocked | ❌ 403 Forbidden | **Full Access** |
| Bank Transfer Verification Queue | ❌ Blocked | ❌ 403 Forbidden | **Approve / Reject** |
| Store Pickup POS Handover (6-Digit Code) | ❌ Blocked | ❌ 403 Forbidden | **Full Handover** |
| Delivery Dispatch & Carrier Resi Input | ❌ Blocked | ❌ 403 Forbidden | **Full Dispatch** |
| Plant Inventory & Store Settings | ❌ Blocked | ❌ 403 Forbidden | **Full Manager** |

---

## 🛠 Tech Stack

- **Backend**: Laravel 13.17 (PHP 8.5 FPM + Nginx)
- **Frontend**: Tailwind CSS 4.0, Alpine.js, Livewire 3.5, Vite 8.0
- **Database**: MySQL 8.0 (28 Normalized Relational Tables)
- **AI Integrations**: Ciora AI Gateway (`https://ciora.id/v1`, models `ciora-ai-free` & `ciora-vision-free`)
- **Weather API**: OpenWeatherMap integration with localized weather cache
- **Payments**: Stripe API with Demo Mode auto-settlement & Manual Bank Transfer engine
- **Document Engine**: Python ReportLab 5.1.0 generator producing publication-grade 12-page executive PDFs

---

## 🚀 Quick Start & Local Installation

### Prerequisites
- PHP >= 8.3 with extensions (`pdo_mysql`, `curl`, `mbstring`, `openssl`, `xml`, `zip`)
- Composer >= 2.x
- Node.js >= 20.x & npm
- MySQL >= 8.0

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone git@github.com:Adanix/tanaman-shop.git
cd tanaman-shop

# 2. Install PHP and JavaScript dependencies
composer install
npm install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Database configuration (configure your DB credentials in .env first)
php artisan migrate --seed

# 5. Create storage symlink
php artisan storage:link

# 6. Build frontend assets with Tailwind CSS 4
npm run build

# 7. Start the development server
php artisan serve
```

---

## 🔑 Demo Credentials

The login page (`/login`) includes **Quick Demo Access Cards** that automatically populate credentials and sign in with a single click:

| Role | Email Address | Password | Primary Console |
|---|---|---|---|
| **Store Administrator** | `admin@plantify.test` | `Plantify2026!` | `/admin` (Nursery Console) |
| **Registered Customer** | `customer@plantify.test` | `Plantify2026!` | `/garden` (Digital Sanctuary) |

---

## 📄 Proposal PDF Generator

Plantify includes a dedicated Python generator script that compiles a 15-chapter publication-grade proposal PDF with automated `NumberedCanvas` page numbering (`Page X of 12`):

```bash
# Compile proposal PDF
python3 scripts/generate_proposal_pdf.py

# Output location:
# public/PROPOSAL_PENGEMBANGAN_PLANTIFY.pdf
```

The document is served directly inline in browsers at:
`GET /proposal-pdf` &rarr; [https://plantify.ramadadawah.my.id/proposal-pdf](https://plantify.ramadadawah.my.id/proposal-pdf)

---

## 📜 License & Credits

- Developed by **Adanix Software Engineering & Botanical Digital Solutions**.
- Open-sourced under the [MIT License](LICENSE).
- Botanical Nursery Headquarters: **Jl. Agrowisata Hijau No. 24, Botanical District, Bantul, Special Region of Yogyakarta**.
- Official Contact: `nixresponding@gmail.com` • [GitHub Repository](https://github.com/Adanix/tanaman-shop)
