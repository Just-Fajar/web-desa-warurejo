# 📚 API Documentation - Website Desa Warurejo

**Version:** 1.0.0  
**Base URL:** `/api/v1`  
**Authentication:** Laravel Sanctum (Bearer Token)

---

## 📋 Daftar Isi

1. [Getting Started](#getting-started)
2. [Authentication](#authentication)
3. [Berita (News)](#berita-news)
4. [Potensi Desa](#potensi-desa)
5. [Galeri](#galeri)
6. [Publikasi](#publikasi)
7. [Profil Desa](#profil-desa)
8. [Statistik](#statistik)
9. [Response Format](#response-format)
10. [Error Handling](#error-handling)

---

## 🚀 Getting Started

### Base URL

```
Development: http://localhost:8000/api/v1
Production:  https://your-domain.com/api/v1
```

### Content Type

Semua request dan response menggunakan `application/json`.

### Swagger (Interactive Docs)

```
http://localhost:8000/api/documentation
```

### Rate Limiting

**60 request per menit per IP.**

Response header:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
```

---

## 🔐 Authentication

### Login

```http
POST /login
```

**Request Body:**
```json
{
    "email": "admin@warurejo.desa.id",
    "password": "password",
    "device_name": "MyApp"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "admin": {
            "id": 1,
            "nama": "Administrator",
            "email": "admin@warurejo.desa.id"
        },
        "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

### Menggunakan Token

Sertakan token di header `Authorization`:

```http
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Protected Endpoints (Butuh Token)

```
POST   /logout         Logout (revoke current token)
POST   /logout-all     Logout semua device
GET    /me             Info user yang login
GET    /tokens         List semua active tokens
```

---

## 📰 Berita (News)

> Semua endpoint berita bersifat **public** (tidak perlu token).

### List Berita

```http
GET /berita
```

| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `search` | string | - | Cari di judul & konten |
| `from_date` | date | - | Filter dari tanggal |
| `to_date` | date | - | Filter sampai tanggal |
| `per_page` | integer | 15 | Item per halaman |
| `page` | integer | 1 | Nomor halaman |

### Detail Berita

```http
GET /berita/{slug}
```

### Berita Terbaru

```http
GET /berita/latest?limit=5
```

### Berita Terpopuler

```http
GET /berita/popular?limit=5
```

---

## 🌾 Potensi Desa

> Semua endpoint potensi bersifat **public**.

### List Potensi

```http
GET /potensi
```

| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `search` | string | - | Cari di nama & deskripsi |
| `per_page` | integer | 15 | Item per halaman |
| `page` | integer | 1 | Nomor halaman |

### Detail Potensi

```http
GET /potensi/{slug}
```

### Potensi Unggulan

```http
GET /potensi/featured?limit=6
```

---

## 🖼️ Galeri

> Semua endpoint galeri bersifat **public**.

### List Galeri

```http
GET /galeri
```

| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `kategori` | string | - | Filter berdasarkan kategori |
| `search` | string | - | Cari di judul & deskripsi |
| `per_page` | integer | 15 | Item per halaman |
| `page` | integer | 1 | Nomor halaman |

**Kategori tersedia:** `kegiatan`, `infrastruktur`, `wisata`, `umkm`, `lainnya`

### Detail Galeri

```http
GET /galeri/{id}
```

### Galeri Terbaru

```http
GET /galeri/latest?limit=6
```

### List Kategori

```http
GET /galeri/categories
```

---

## 📄 Publikasi

> Semua endpoint publikasi bersifat **public**.

### List Publikasi

```http
GET /publikasi
```

### Detail Publikasi

```http
GET /publikasi/{id}
```

### Download File

```http
GET /publikasi/{id}/download
```

### List Kategori

```http
GET /publikasi/categories
```

### List Tahun

```http
GET /publikasi/years
```

---

## 🏘️ Profil Desa

```http
GET /profil
```

Mengembalikan data profil desa (visi, misi, sejarah, dll).

---

## 📊 Statistik

```http
GET /statistik/summary
```

Mengembalikan ringkasan statistik website.

---

## 📦 Response Format

### Success

```json
{
    "success": true,
    "data": { ... },
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    },
    "links": {
        "first": "...?page=1",
        "last": "...?page=5",
        "prev": null,
        "next": "...?page=2"
    }
}
```

### Error

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

---

## ❌ Error Handling

| Code | Deskripsi |
|------|-----------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized (token tidak valid) |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Rate Limit Exceeded |
| 500 | Internal Server Error |

---

## 🧪 Testing dengan cURL

### Login

```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@warurejo.desa.id","password":"password","device_name":"cURL"}'
```

### Get Berita (Public)

```bash
curl http://localhost:8000/api/v1/berita \
  -H "Accept: application/json"
```

### Get User Info (Authenticated)

```bash
curl http://localhost:8000/api/v1/me \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```
