# 📚 API Documentation - Website Desa Warurejo

**Version:** 1.0.0  
**Base URL:** `https://warurejo.desa.id/api/v1`  
**Last Updated:** 28 November 2025

---

## 📋 Table of Contents

1. [Getting Started](#getting-started)
2. [Authentication](#authentication)
3. [Rate Limiting](#rate-limiting)
4. [API Endpoints](#api-endpoints)
5. [Response Format](#response-format)
6. [Error Handling](#error-handling)
7. [Code Examples](#code-examples)

---

## 🚀 Getting Started

### **Base URL**

```
Development: http://localhost/api/v1
Production: https://warurejo.desa.id/api/v1
```

### **Content Type**

All requests and responses use `application/json`.

### **Swagger Documentation**

Interactive API documentation is available at:
```
http://localhost/api/documentation
https://warurejo.desa.id/api/documentation
```

---

## 🔐 Authentication

API menggunakan **Laravel Sanctum** untuk authentication.

### **Login & Get Token**

**Endpoint:** `POST /login`

**Request:**
```json
{
    "email": "admin@warurejo.desa.id",
    "password": "password",
    "device_name": "MyApp v1.0"
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
            "email": "admin@warurejo.desa.id",
            "username": "admin"
        },
        "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

### **Using Token**

Include the token in the `Authorization` header:

```http
Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### **Protected Endpoints**

```
POST   /logout         - Logout (revoke current token)
POST   /logout-all     - Logout all devices
GET    /me             - Get authenticated user info
GET    /tokens         - List all active tokens
```

---

## ⏱️ Rate Limiting

**Global API Limit:** 60 requests per minute per IP address

**Headers:**
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
Retry-After: 60 (if rate limit exceeded)
```

**Rate Limit Exceeded Response:**
```json
{
    "message": "Too Many Attempts.",
    "exception": "Illuminate\\Http\\Exceptions\\ThrottleRequestsException"
}
```

---

## 📌 API Endpoints

### **1. Berita (News)**

#### **Get All Berita**

```http
GET /berita
```

**Query Parameters:**
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `search` | string | Search in judul & konten | `?search=desa` |
| `from_date` | date | Filter from date | `?from_date=2025-01-01` |
| `to_date` | date | Filter to date | `?to_date=2025-12-31` |
| `per_page` | integer | Items per page (default: 15) | `?per_page=20` |
| `page` | integer | Page number | `?page=2` |

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "judul": "Musyawarah Desa Tahun 2025",
            "slug": "musyawarah-desa-tahun-2025",
            "konten": "Stripped HTML content...",
            "konten_html": "<p>Full HTML content...</p>",
            "gambar": "https://warurejo.desa.id/storage/berita/image.jpg",
            "views": 150,
            "published_at": "2025-01-15T10:00:00+07:00",
            "author": {
                "id": 1,
                "nama": "Administrator"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75,
        "from": 1,
        "to": 15
    },
    "links": {
        "first": "http://localhost/api/v1/berita?page=1",
        "last": "http://localhost/api/v1/berita?page=5",
        "prev": null,
        "next": "http://localhost/api/v1/berita?page=2"
    }
}
```

#### **Get Single Berita**

```http
GET /berita/{slug}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "judul": "Musyawarah Desa Tahun 2025",
        "slug": "musyawarah-desa-tahun-2025",
        "konten": "<p>Full HTML content...</p>",
        "gambar": "https://warurejo.desa.id/storage/berita/image.jpg",
        "views": 151,
        "published_at": "2025-01-15T10:00:00+07:00",
        "created_at": "2025-01-10T08:00:00+07:00",
        "updated_at": "2025-01-15T10:00:00+07:00",
        "author": {
            "id": 1,
            "nama": "Administrator",
            "email": "admin@warurejo.desa.id"
        }
    }
}
```

#### **Get Latest Berita**

```http
GET /berita/latest?limit=5
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "judul": "Musyawarah Desa Tahun 2025",
            "slug": "musyawarah-desa-tahun-2025",
            "excerpt": "Stripped content limited to 150 chars...",
            "gambar": "https://warurejo.desa.id/storage/berita/image.jpg",
            "views": 150,
            "published_at": "2025-01-15T10:00:00+07:00",
            "author": "Administrator"
        }
    ]
}
```

#### **Get Popular Berita**

```http
GET /berita/popular?limit=5
```

---

### **2. Potensi Desa**

#### **Get All Potensi**

```http
GET /potensi
```

**Query Parameters:**
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `search` | string | Search in nama & deskripsi | `?search=pertanian` |
| `per_page` | integer | Items per page (default: 15) | `?per_page=20` |
| `page` | integer | Page number | `?page=2` |

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama": "Pertanian Padi Organik",
            "slug": "pertanian-padi-organik",
            "deskripsi": "Plain text description...",
            "deskripsi_html": "<p>HTML description...</p>",
            "gambar": "https://warurejo.desa.id/storage/potensi/image.jpg",
            "views": 200,
            "created_at": "2025-01-01T00:00:00+07:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 42
    }
}
```

#### **Get Single Potensi**

```http
GET /potensi/{slug}
```

#### **Get Featured Potensi**

```http
GET /potensi/featured?limit=6
```

---

### **3. Galeri**

#### **Get All Galeri**

```http
GET /galeri
```

**Query Parameters:**
| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `kategori` | string | Filter by category | `?kategori=kegiatan` |
| `search` | string | Search in judul & deskripsi | `?search=gotong` |
| `per_page` | integer | Items per page (default: 15) | `?per_page=20` |
| `page` | integer | Page number | `?page=2` |

**Available Categories:**
- `kegiatan`
- `infrastruktur`
- `wisata`
- `umkm`
- `lainnya`

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "judul": "Gotong Royong Bersih Desa",
            "deskripsi": "Kegiatan bersih-bersih lingkungan desa",
            "kategori": "kegiatan",
            "tanggal": "2025-01-10",
            "views": 100,
            "images": [
                {
                    "id": 1,
                    "path": "https://warurejo.desa.id/storage/galeri/img1.jpg",
                    "urutan": 1
                },
                {
                    "id": 2,
                    "path": "https://warurejo.desa.id/storage/galeri/img2.jpg",
                    "urutan": 2
                }
            ],
            "thumbnail": "https://warurejo.desa.id/storage/galeri/img1.jpg",
            "created_at": "2025-01-10T08:00:00+07:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 4,
        "per_page": 15,
        "total": 58
    }
}
```

#### **Get Single Galeri**

```http
GET /galeri/{id}
```

#### **Get Latest Galeri**

```http
GET /galeri/latest?limit=6
```

#### **Get Categories**

```http
GET /galeri/categories
```

**Response:**
```json
{
    "success": true,
    "data": [
        "kegiatan",
        "infrastruktur",
        "wisata",
        "umkm"
    ]
}
```

---

## 📊 Response Format

### **Success Response**

```json
{
    "success": true,
    "data": { ... },
    "meta": { ... },    // Optional (pagination)
    "links": { ... }    // Optional (pagination)
}
```

### **Error Response**

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

### **HTTP Status Codes**

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests (Rate Limit) |
| 500 | Internal Server Error |

### **Common Errors**

**401 Unauthorized:**
```json
{
    "message": "Unauthenticated."
}
```

**404 Not Found:**
```json
{
    "message": "No query results for model [App\\Models\\Berita]."
}
```

**422 Validation Error:**
```json
{
    "message": "The email field is required.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

---

## 💻 Code Examples

### **JavaScript (Fetch API)**

```javascript
// Login
const login = async () => {
    const response = await fetch('https://warurejo.desa.id/api/v1/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            email: 'admin@warurejo.desa.id',
            password: 'password',
            device_name: 'Web Browser'
        })
    });
    
    const data = await response.json();
    const token = data.data.token;
    
    // Store token
    localStorage.setItem('api_token', token);
    
    return token;
};

// Get Berita with Token
const getBerita = async () => {
    const token = localStorage.getItem('api_token');
    
    const response = await fetch('https://warurejo.desa.id/api/v1/berita', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
};

// Get Latest Berita (No Auth Required)
const getLatestBerita = async () => {
    const response = await fetch('https://warurejo.desa.id/api/v1/berita/latest?limit=5', {
        headers: {
            'Accept': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
};
```

### **PHP (cURL)**

```php
<?php

// Login
function login($email, $password) {
    $ch = curl_init('https://warurejo.desa.id/api/v1/login');
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'email' => $email,
        'password' => $password,
        'device_name' => 'PHP Client'
    ]));
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    return $data['data']['token'];
}

// Get Berita
function getBerita($token, $page = 1) {
    $ch = curl_init("https://warurejo.desa.id/api/v1/berita?page={$page}");
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$token}",
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Usage
$token = login('admin@warurejo.desa.id', 'password');
$berita = getBerita($token, 1);

foreach ($berita['data'] as $item) {
    echo $item['judul'] . "\n";
}
```

### **Python (Requests)**

```python
import requests

# Login
def login(email, password):
    url = 'https://warurejo.desa.id/api/v1/login'
    data = {
        'email': email,
        'password': password,
        'device_name': 'Python Client'
    }
    
    response = requests.post(url, json=data)
    return response.json()['data']['token']

# Get Berita
def get_berita(token, page=1):
    url = f'https://warurejo.desa.id/api/v1/berita?page={page}'
    headers = {
        'Authorization': f'Bearer {token}',
        'Accept': 'application/json'
    }
    
    response = requests.get(url, headers=headers)
    return response.json()

# Usage
token = login('admin@warurejo.desa.id', 'password')
berita = get_berita(token, 1)

for item in berita['data']:
    print(item['judul'])
```

---

## 🔧 Testing with cURL

### **Login**

```bash
curl -X POST https://warurejo.desa.id/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@warurejo.desa.id",
    "password": "password",
    "device_name": "cURL"
  }'
```

### **Get Berita (No Auth)**

```bash
curl -X GET https://warurejo.desa.id/api/v1/berita \
  -H "Accept: application/json"
```

### **Get User Info (With Auth)**

```bash
curl -X GET https://warurejo.desa.id/api/v1/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

### **Search Berita**

```bash
curl -X GET "https://warurejo.desa.id/api/v1/berita?search=desa&per_page=10" \
  -H "Accept: application/json"
```

---

## 📦 Postman Collection

Import this collection into Postman for easy testing:

**Collection URL:** `https://warurejo.desa.id/api/postman-collection.json`

Or create environment variables:
- `base_url`: `https://warurejo.desa.id/api/v1`
- `token`: (Your API token after login)

---

## 🔄 API Versioning

Current version: **v1**

Breaking changes will be released in new versions (v2, v3, etc.).  
Previous versions will be supported for at least 6 months.

---

## 📞 Support

**Technical Support:**
- Email: dev@warurejo.desa.id
- Documentation: https://warurejo.desa.id/api/documentation
- GitHub Issues: https://github.com/warurejo/api/issues

---

**API Documentation Complete! 📚**

For interactive documentation, visit: **http://localhost/api/documentation**
