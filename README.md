# 🏘️ Website Profil Desa Warurejo

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind-4.1-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Alpine.js-3.15-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Status-Production%20Ready-success?style=flat-square" alt="Status">
  <img src="https://img.shields.io/badge/Tests-468%20Methods-brightgreen?style=flat-square" alt="Tests">
  <img src="https://img.shields.io/badge/Test%20Files-39-brightgreen?style=flat-square" alt="Test Files">
  <img src="https://img.shields.io/badge/Security-Hardened-blue?style=flat-square" alt="Security">
  <img src="https://img.shields.io/badge/API-REST%20v1-orange?style=flat-square" alt="API">
</p>

> **Website profil desa modern dengan arsitektur enterprise-level, dilengkapi fitur manajemen konten, galeri, publikasi dokumen, forum pengaduan masyarakat, analitik pengunjung, dan penjadwalan konten otomatis.**

---

## 📋 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Arsitektur](#-arsitektur)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Development](#-development)
- [Testing](#-testing)
- [API Documentation](#-api-documentation)
- [Security](#-security)
- [Performance](#-performance)
- [Deployment](#-deployment)
- [Developer](#-developer)
- [Lisensi](#-lisensi)

---

## 🎯 Tentang Project

Website Profil Desa Warurejo adalah aplikasi web modern yang dirancang khusus untuk mengelola dan menampilkan informasi desa secara digital. Dibangun dengan standar enterprise-level menggunakan **Repository + Service Pattern**, aplikasi ini menyediakan dashboard admin yang powerful, tampilan publik yang responsif, dan sistem penjadwalan konten otomatis.

### ⭐ Highlights

- **🏗️ Arsitektur Enterprise:** Repository + Service Pattern untuk maintainability maksimal
- **🔒 Security Hardened:** Custom HTML Sanitizer, Rate Limiting, CSRF Protection
- **⚡ High Performance:** Multi-layer caching system dengan auto-invalidation
- **🧪 Comprehensive Testing:** 468 test methods dalam 39 file test
- **🌐 REST API Ready:** 22 endpoints dengan autentikasi Laravel Sanctum
- **📅 Scheduled Publishing:** Auto-publish konten terjadwal via middleware
- **📊 Visitor Analytics:** Tracking pengunjung harian dengan chart interaktif
- **📢 Forum Pengaduan:** Sistem pengaduan masyarakat dengan balasan admin

---

## ✨ Fitur Utama

### 🎨 Halaman Publik

| Modul | Fitur |
|-------|-------|
| **Homepage** | Hero section, statistik desa real-time, berita terbaru, potensi unggulan, galeri foto |
| **Profil Desa** | Visi & Misi, Sejarah, Struktur Organisasi (hierarchical), Peta Lokasi (Google Maps) |
| **Berita** | Pagination, view counter, advanced search, real-time autocomplete, SEO optimized |
| **Potensi Desa** | 7 kategori (Pertanian, Pariwisata, UMKM, Peternakan, Perikanan, Kerajinan, Lainnya), WhatsApp contact, multi-foto |
| **Galeri** | 4 kategori (Kegiatan, Infrastruktur, Budaya, Umum), lightbox viewer, lazy loading |
| **Publikasi** | Download dokumen PDF, preview, kategori (APBDes, RPJMDes, RKPDes), download tracking |
| **Pengaduan** | Submit pengaduan publik, tracking status, detail balasan admin dengan lampiran |
| **Peta Desa** | Halaman peta interaktif desa |
| **Kontak** | Informasi kontak & sosial media desa |

### 🔐 Panel Admin

| Modul | Fitur |
|-------|-------|
| **Dashboard** | Statistik konten real-time, chart pengunjung (Chart.js), filter periode, content chart per tahun |
| **Berita** | Full CRUD, TinyMCE editor, image upload + auto-resize, HTML sanitization, status draft/scheduled/published, bulk delete |
| **Potensi** | CRUD dengan multi-foto upload, 7 kategori, WhatsApp integration, status draft/scheduled/published |
| **Galeri** | Single & bulk upload (hingga 10 foto), image compression, 4 kategori, status management |
| **Publikasi** | Upload PDF, preview & download, 3 kategori, penjadwalan publikasi otomatis |
| **Pengaduan** | Daftar pengaduan masuk, sistem balasan admin, upload lampiran bukti resolusi |
| **Struktur Organisasi** | Hierarchical tree (Kepala Desa → Sekretaris → Kaur → Kasi → Kadus → Staff), foto upload, toggle active |
| **Admin Profile** | Update info, ganti password, upload foto profil, reset password (lupa password) |

### 📅 Sistem Penjadwalan Konten

Konten (Berita, Galeri, Publikasi, Potensi) mendukung 3 status lifecycle:
- **Draft** — Konten tersimpan tapi belum dipublikasikan
- **Scheduled** — Konten akan auto-publish pada tanggal/waktu yang ditentukan
- **Published** — Konten sudah aktif dan tampil di halaman publik

Auto-publishing dijalankan oleh middleware `PublishScheduledContent` yang memeriksa konten terjadwal setiap request admin, dengan throttle 60 detik untuk performa.

### 📊 Visitor Analytics

- Tracking pengunjung unik berdasarkan device fingerprint (SHA-256)
- Statistik harian disimpan di tabel `daily_visitor_stats`
- Chart pengunjung bulanan & tahunan di dashboard admin
- Filter periode (bulan/tahun) via AJAX endpoint
- Excludes admin & livewire routes dari tracking

---

## 🛠️ Tech Stack

### Backend

| Komponen | Teknologi |
|----------|-----------|
| **Framework** | Laravel 12.x |
| **PHP** | 8.2+ |
| **Database** | MySQL 8.0+ (production) / SQLite (testing) |
| **Authentication** | Laravel Sanctum 4.2 |
| **Image Processing** | Intervention Image 3.11 |
| **HTML Purification** | Mews Purifier 3.4 |
| **API Documentation** | L5-Swagger 9.0 |
| **SEO** | Spatie Laravel Sitemap |

### Frontend

| Komponen | Teknologi |
|----------|-----------|
| **CSS Framework** | Tailwind CSS 4.1 |
| **JavaScript** | Alpine.js 3.15 |
| **Build Tool** | Vite 7.0 |
| **Rich Text Editor** | TinyMCE |
| **Charts** | Chart.js |

### Development Tools

| Tool | Versi |
|------|-------|
| PHPUnit | 11.5+ |
| ParaTest | 7.8 |
| Laravel Pint | 1.24 |
| Laravel Pail | 1.2.2 |
| Mockery | 1.6 |
| Faker | 1.23 |

---

## 🏗️ Arsitektur

### Design Pattern: Repository + Service Layer

```
┌─────────────────────────────────────────────────┐
│                   HTTP Request                   │
└──────────────────┬──────────────────────────────┘
                   │
           ┌───────▼────────┐
           │  FormRequest   │  ← Validation Layer
           └───────┬────────┘
                   │
           ┌───────▼────────┐
           │   Controller   │  ← HTTP Logic & Response
           └───────┬────────┘
                   │
           ┌───────▼────────┐
           │    Service     │  ← Business Logic, Caching, Sanitization
           └───────┬────────┘
                   │
           ┌───────▼────────┐
           │   Repository   │  ← Data Access & Query Scopes
           └───────┬────────┘
                   │
           ┌───────▼────────┐
           │  Eloquent Model│  ← Database Interaction
           └────────────────┘
```

### Directory Structure

```
app/
├── Console/                    # Artisan Commands
├── Helpers/
│   └── SEOHelper.php           # SEO utilities
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # 9 Admin Controllers
│   │   │   ├── AuthController
│   │   │   ├── DashboardController
│   │   │   ├── BeritaController
│   │   │   ├── PotensiController
│   │   │   ├── GaleriController
│   │   │   ├── PublikasiController
│   │   │   ├── PengaduanController
│   │   │   ├── StrukturOrganisasiController
│   │   │   └── ProfileController
│   │   ├── Api/V1/             # 7 API Controllers
│   │   │   ├── AuthController
│   │   │   ├── BeritaController
│   │   │   ├── GaleriController
│   │   │   ├── PotensiController
│   │   │   ├── ProfilDesaController
│   │   │   ├── PublikasiController
│   │   │   └── StatistikController
│   │   └── Public/             # 7 Public Controllers
│   │       ├── HomeController
│   │       ├── BeritaController
│   │       ├── PotensiController
│   │       ├── GaleriController
│   │       ├── ProfilController
│   │       ├── PengaduanController
│   │       └── KontakController
│   ├── Middleware/
│   │   ├── AdminAuthenticate
│   │   ├── RedirectIfAdmin
│   │   ├── PublishScheduledContent  ← Auto-publish terjadwal
│   │   ├── TrackVisitor             ← Visitor analytics
│   │   ├── ApiCacheMiddleware       ← Cache API responses
│   │   ├── ApiVersionNotice         ← API versioning header
│   │   └── SecurityHeaders          ← Security response headers
│   └── Requests/               # 6 Form Requests
├── Models/                     # 14 Eloquent Models
├── Repositories/               # 5 Repositories + Contracts
│   ├── BaseRepository
│   ├── BeritaRepository
│   ├── GaleriRepository
│   ├── PotensiDesaRepository
│   └── StrukturOrganisasiRepository
└── Services/                   # 8 Service Classes
    ├── BeritaService
    ├── GaleriService
    ├── PotensiDesaService
    ├── StrukturOrganisasiService
    ├── ImageUploadService
    ├── ImageCompressionService
    ├── HtmlSanitizerService
    └── VisitorStatisticsService
```

---

## 📥 Instalasi

### System Requirements

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 20.x
- MySQL >= 8.0 atau SQLite
- Apache/Nginx web server

### Quick Start

```bash
# 1. Clone repository
git clone https://github.com/Just-Fajar/web-profil-warurejo.git
cd web-profil-warurejo

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warurejo
DB_USERNAME=root
DB_PASSWORD=

# 5. Migrate & seed database
php artisan migrate
php artisan db:seed

# 6. Storage link
php artisan storage:link

# 7. Build assets & run
npm run build
php artisan serve
```

Buka browser: `http://localhost:8000`

### Default Admin Account

```
Email: admin@warurejo.com
Password: password
```

**⚠️ PENTING:** Ganti password default setelah login pertama!

---

## ⚙️ Konfigurasi

### Environment Variables

```env
# Application
APP_NAME="Desa Warurejo"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warurejo
DB_USERNAME=root
DB_PASSWORD=

# Cache
CACHE_STORE=file          # file (dev), redis (production)
CACHE_PROFIL_TTL=86400    # 1 day
CACHE_BERITA_TTL=3600     # 1 hour
CACHE_POTENSI_TTL=21600   # 6 hours
CACHE_GALERI_TTL=10800    # 3 hours

# Session & Queue
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

---

## 💻 Development

### Development Server

```bash
# Run all services concurrently (recommended)
composer run dev

# Or individually:
php artisan serve          # Laravel server
npm run dev                # Vite HMR
php artisan queue:listen   # Queue worker
```

### Database Management

```bash
php artisan migrate:fresh --seed   # Fresh migration + seed
php artisan migrate:rollback       # Rollback last migration
```

### Code Style

```bash
./vendor/bin/pint           # Fix code style (Laravel Pint)
./vendor/bin/pint --test    # Check without fixing
```

### Cache Management

```bash
php artisan optimize:clear  # Clear all cache
php artisan optimize        # Cache everything (production)
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run specific file or method
php artisan test tests/Unit/Services/BeritaServiceTest.php
php artisan test --filter test_create_berita_sanitizes_html_content

# Parallel testing
php artisan test --parallel
```

### Test Infrastructure

```
Total Test Files: 40

tests/
├── TestCase.php                        # Base test (SQLite YEAR/MONTH function registration)
├── Feature/                            # 19 test files
│   ├── Admin/                          # 9 admin CRUD & auth test files
│   │   ├── AuthAdminTest               # Login, logout, guard protection
│   │   ├── DashboardTest               # Dashboard access, AJAX charts
│   │   ├── BeritaCrudTest              # Full CRUD, bulk delete, validation
│   │   ├── PotensiCrudTest             # CRUD, multi-foto, status workflow
│   │   ├── GaleriCrudTest              # CRUD, bulk upload, status workflow
│   │   ├── PublikasiCrudTest           # CRUD, PDF upload, kategori filter
│   │   ├── PengaduanAdminTest          # Reply, lampiran upload
│   │   ├── StrukturOrganisasiCrudTest  # Hierarchical CRUD
│   │   └── AdminProfileTest            # Profile update, password, photo
│   ├── Api/V1/                         # 2 API test files
│   │   ├── ApiEndpointsV1Test          # All API endpoint responses
│   │   └── ApiPerformanceV1Test        # API response time & rate limiting
│   ├── Security/                       # 1 security test file
│   │   └── SecurityHardeningTest       # Security headers, XSS, CSRF
│   ├── HomePageTest                    # Homepage rendering & data
│   ├── BeritaPageTest                  # Public berita pages
│   ├── GaleriPageTest                  # Public galeri pages
│   ├── PotensiPageTest                 # Public potensi pages
│   ├── PengaduanPublikTest             # Public complaint submission
│   ├── PublicPagesTest                 # All public page accessibility
│   └── ExampleTest
│
└── Unit/                               # 21 test files
    ├── Models/                         # 10 model test files
    │   ├── AdminModelTest
    │   ├── BeritaModelTest
    │   ├── GaleriModelTest
    │   ├── PublikasiModelTest
    │   ├── PengaduanModelTest
    │   ├── PengaduanBalasanModelTest
    │   ├── ProfilDesaModelTest
    │   ├── StrukturOrganisasiModelTest
    │   ├── VisitorModelTest
    │   └── DailyVisitorStatModelTest
    ├── Services/                       # 8 service test files
    │   ├── BeritaServiceTest
    │   ├── GaleriServiceTest
    │   ├── PotensiDesaServiceTest
    │   ├── StrukturOrganisasiServiceTest
    │   ├── HtmlSanitizerServiceTest
    │   ├── ImageUploadServiceTest
    │   ├── ImageCompressionServiceTest
    │   └── VisitorStatisticsServiceTest
    ├── Middleware/                      # 2 middleware test files
    │   ├── PublishScheduledContentTest  # Auto-publish & throttle
    │   └── TrackVisitorTest            # Visitor tracking & exclusion
    └── ExampleTest
```

### Test Coverage Areas

| Layer | Cakupan |
|-------|---------|
| **Models** | Scopes, accessors, relationships, casts, constants, status badges |
| **Services** | Business logic, cache management, HTML sanitization, image processing, XSS prevention |
| **Controllers** | Full CRUD operations, validation, bulk delete, file upload, auth guards |
| **Middleware** | Scheduled content publishing, visitor tracking, throttling |
| **Public Pages** | Page rendering, data display, pagination, 404 handling |
| **API** | Authentication, endpoint responses, rate limiting |

### Model Factories

| Factory | States |
|---------|--------|
| `AdminFactory` | default |
| `BeritaFactory` | published, draft, popular |
| `GaleriFactory` | published, draft, scheduled |
| `PotensiDesaFactory` | published, draft, kategori |
| `PublikasiFactory` | published, draft, kategori, tahun |
| `PengaduanFactory` | default |
| `PengaduanBalasanFactory` | default |
| `ProfilDesaFactory` | default |
| `StrukturOrganisasiFactory` | default |

---

## 📚 API Documentation

### Base URL

```
Development: http://localhost:8000/api/v1
Production:  https://warurejo.desa.id/api/v1
```

### Endpoints

**Authentication:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/api/v1/login` | Get API token |
| `POST` | `/api/v1/logout` | Revoke token 🔒 |
| `POST` | `/api/v1/logout-all` | Revoke all tokens 🔒 |
| `GET` | `/api/v1/me` | User info 🔒 |
| `GET` | `/api/v1/tokens` | List tokens 🔒 |

**Berita:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/v1/berita` | List (paginated, search, filter) |
| `GET` | `/api/v1/berita/latest` | Latest articles |
| `GET` | `/api/v1/berita/popular` | Popular articles |
| `GET` | `/api/v1/berita/{slug}` | Single article |

**Potensi:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/v1/potensi` | List (paginated, search) |
| `GET` | `/api/v1/potensi/featured` | Featured items |
| `GET` | `/api/v1/potensi/{slug}` | Single item |

**Galeri:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/v1/galeri` | List (paginated, filter) |
| `GET` | `/api/v1/galeri/latest` | Latest galleries |
| `GET` | `/api/v1/galeri/categories` | Available categories |
| `GET` | `/api/v1/galeri/{id}` | Single gallery |

**Publikasi:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/v1/publikasi` | List dokumen |
| `GET` | `/api/v1/publikasi/categories` | Kategori dokumen |
| `GET` | `/api/v1/publikasi/years` | Tahun tersedia |
| `GET` | `/api/v1/publikasi/{id}` | Detail dokumen |
| `GET` | `/api/v1/publikasi/{id}/download` | Download file |

**Profil & Statistik:**

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/v1/profil` | Profil desa |
| `GET` | `/api/v1/statistik/summary` | Ringkasan statistik |

### Authentication Example

```bash
# Get token
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@warurejo.com", "password": "password"}'

# Use token
curl -X GET http://localhost:8000/api/v1/me \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Rate Limiting

- **Public endpoints:** 60 requests/minute
- **Authenticated endpoints:** 120 requests/minute

### Swagger Documentation

```bash
php artisan l5-swagger:generate
# Access: http://localhost:8000/api/documentation
```

📖 **Detail lengkap:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

---

## 🔒 Security

### Implemented Security Features

| Feature | Implementasi |
|---------|-------------|
| **Rate Limiting** | 5 attempts/minute pada admin login |
| **HTML Sanitizer** | Custom sanitizer: removes `<script>`, `<iframe>`, event handlers, dangerous protocols |
| **CSRF Protection** | Semua form dilindungi `@csrf` token |
| **XSS Prevention** | Blade escaping `{{ }}`, HTML sanitization on save |
| **SQL Injection Prevention** | Eloquent ORM parameterized queries |
| **File Upload Security** | Type validation, 2MB size limit, MIME verification |
| **Authentication** | Custom admin guard, bcrypt hashing (cost 12) |
| **HTTPS Redirect** | `URL::forceScheme('https')` di production |



---

## ⚡ Performance

### Optimization Features

| Fitur | Detail |
|-------|--------|
| **Multi-layer Caching** | Profil (1 hari), Berita (1 jam), Potensi (6 jam), Galeri (3 jam), SEO (1 hari) |
| **Auto Cache Invalidation** | Cache otomatis di-clear saat CRUD operations |
| **Database Indexes** | Composite indexes pada status, slug, published_at |
| **Eager Loading** | N+1 query prevention via `with()` |
| **Image Optimization** | Auto-resize (max 1200px), thumbnail (300px), 85% quality compression |
| **Lazy Loading** | `loading="lazy"` pada semua images |
| **Asset Bundling** | Vite build dengan minification & code splitting |



---

## 🚀 Deployment

### VPS/Dedicated Server (Ubuntu)

```bash
# 1. Clone & install
git clone https://github.com/Just-Fajar/web-profil-warurejo.git
cd web-profil-warurejo
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 2. Configure
cp .env.example .env
php artisan key:generate
# Edit .env → APP_ENV=production, APP_DEBUG=false

# 3. Database
php artisan migrate --force
php artisan db:seed --force

# 4. Permissions & optimize
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
php artisan optimize
php artisan storage:link

# 5. Cron job
# * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1

# 6. SSL
sudo certbot --nginx -d warurejo.desa.id
```

### Deployment Checklist

- [ ] `APP_ENV=production` & `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure production database (MySQL)
- [ ] Install SSL certificate
- [ ] Setup cron job untuk scheduler
- [ ] Test all features

📖 **Detail lengkap:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)

---

## 📊 Project Statistics

```
Eloquent Models:            14
Controllers:                25 (9 Admin + 7 Public + 7 API + 2 Shared)
Services:                   8
Repositories:               5
Form Requests:              6
Custom Middleware:           7
Database Seeders:           9
Model Factories:            10
Test Files:                 40
```

---

## 📖 Dokumentasi Tambahan

| Dokumen | Deskripsi |
|---------|-----------|
| [API_DOCUMENTATION.md](API_DOCUMENTATION.md) | Dokumentasi lengkap REST API |
| [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md) | Panduan deployment production |

---

## 👨‍💻 Developer

<div align="center">

### Just Fajar

[![GitHub](https://img.shields.io/badge/GitHub-Just--Fajar-181717?style=for-the-badge&logo=github)](https://github.com/Just-Fajar)
[![Email](https://img.shields.io/badge/Email-Contact-D14836?style=for-the-badge&logo=gmail&logoColor=white)](mailto:muhammadfajar.a123@gmail.com)

**Full-Stack Developer | Laravel Specialist**

</div>

### Skills & Expertise

- **Backend:** Laravel, PHP, RESTful API
- **Frontend:** Tailwind CSS, Alpine.js, Blade Templating, Vite
- **Database:** MySQL, SQLite
- **Architecture:** Repository Pattern, Service Layer, SOLID Principles
- **Testing:** PHPUnit, Feature Tests, Unit Tests
- **Tools:** Git, Composer, NPM, Vite

---

## 📄 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

```
MIT License

Copyright (c) 2025-2026 Just Fajar

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com) - The PHP Framework for Web Artisans
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript framework
- [TinyMCE](https://www.tiny.cloud) - Rich text editor
- [Chart.js](https://www.chartjs.org) - JavaScript charting
- [Intervention Image](http://image.intervention.io) - PHP Image Manipulation
- [Spatie](https://spatie.be) - Laravel Packages

---

## 📞 Support

- 🐛 Laporkan bug di [GitHub Issues](https://github.com/Just-Fajar/web-profil-warurejo/issues)
- 💬 Diskusi di [GitHub Discussions](https://github.com/Just-Fajar/web-profil-warurejo/discussions)
- 📧 Email: muhammadfajar.a123@gmail.com

---

<div align="center">

**Made with ❤️ by [Just Fajar](https://github.com/Just-Fajar)**

**© 2025-2026 Just Fajar. All rights reserved.**

</div>
