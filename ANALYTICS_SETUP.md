# 📊 Analytics Setup Guide - Website Desa Warurejo

**Version:** 1.0  
**Last Updated:** 28 November 2025

---

## 📋 Table of Contents

1. [Analytics Overview](#analytics-overview)
2. [Google Analytics 4 Setup](#google-analytics-4-setup)
3. [Internal Visitor Tracking](#internal-visitor-tracking)
4. [Search Console Integration](#search-console-integration)
5. [Event Tracking](#event-tracking)
6. [Custom Reports](#custom-reports)
7. [Privacy & GDPR](#privacy--gdpr)

---

## 🎯 Analytics Overview

### **What We Track**

| Category | Metrics | Purpose |
|----------|---------|---------|
| **Traffic** | Visitors, sessions, pageviews | Understand website usage |
| **User Behavior** | Bounce rate, time on site, pages/session | Improve UX |
| **Content Performance** | Popular pages, berita views | Content strategy |
| **Acquisition** | Traffic sources, referrals | Marketing effectiveness |
| **Conversions** | Downloads, form submissions | Goal tracking |
| **Technical** | Page speed, errors | Performance monitoring |

### **Analytics Stack**

1. **Google Analytics 4** - Comprehensive analytics
2. **Google Search Console** - SEO & search performance
3. **Internal Tracking** - Custom visitor statistics (already implemented)
4. **Admin Dashboard** - Real-time internal metrics

---

## 📈 Google Analytics 4 Setup

### **Step 1: Create Google Analytics Account**

1. **Go to:** https://analytics.google.com
2. **Sign in** with Google account
3. **Click:** "Start measuring"
4. **Create Account:**
   - Account name: `Desa Warurejo`
   - Account data sharing: (Choose preferences)
   - Click "Next"

5. **Create Property:**
   - Property name: `Website Desa Warurejo`
   - Reporting time zone: `(GMT+07:00) Jakarta`
   - Currency: `Indonesian Rupiah (IDR)`
   - Click "Next"

6. **Business Information:**
   - Industry category: `Government & Public Services`
   - Business size: `Small (1-10 employees)`
   - Click "Next"

7. **Business Objectives:**
   - Select: `Examine user behavior`
   - Click "Create"

8. **Accept** Terms of Service

---

### **Step 2: Setup Data Stream**

1. **Platform:** Select "Web"

2. **Website Details:**
   - Website URL: `https://warurejo.desa.id`
   - Stream name: `Warurejo Website`
   - Enhanced measurement: ✅ **ON** (recommended)

3. **Click:** "Create stream"

4. **Copy Measurement ID:** `G-XXXXXXXXXX`

---

### **Step 3: Install GA4 Tracking Code**

#### **Method 1: Add to Blade Template (Recommended)**

Edit `resources/views/layouts/public.blade.php`:

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Analytics 4 -->
    @if(config('app.env') === 'production')
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.measurement_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google_analytics.measurement_id') }}', {
            'anonymize_ip': true,
            'allow_google_signals': false,
            'allow_ad_personalization_signals': false
        });
    </script>
    @endif
    
    <title>@yield('title', 'Desa Warurejo')</title>
    
    <!-- Rest of head -->
</head>
```

Add to `config/services.php`:

```php
return [
    // ... existing services
    
    'google_analytics' => [
        'measurement_id' => env('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX'),
    ],
];
```

Add to `.env`:

```env
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

---

#### **Method 2: Google Tag Manager (Advanced)**

1. **Create GTM Account:** https://tagmanager.google.com
2. **Create Container:**
   - Container name: `Warurejo Website`
   - Target platform: Web
3. **Copy Container ID:** `GTM-XXXXXXX`
4. **Add GTM Code** to `<head>` and `<body>`

**Benefits of GTM:**
- Easy to add/manage tags without code changes
- Track custom events
- Add multiple analytics tools
- A/B testing integration

---

### **Step 4: Verify Installation**

1. **Open website** in browser
2. **Go to GA4:** Real-time reports
3. **Check:** Should see 1 active user (yourself)
4. **Test events:** Click around, check if events tracked

**Debug Mode:**

```html
<script>
    gtag('config', 'G-XXXXXXXXXX', {
        'debug_mode': true
    });
</script>
```

Use **Google Analytics Debugger** Chrome extension.

---

### **Step 5: Configure Enhanced Measurement**

**Auto-tracked Events:**
- ✅ Page views
- ✅ Scrolls (90% depth)
- ✅ Outbound clicks
- ✅ Site search
- ✅ Video engagement
- ✅ File downloads

**Go to:** Admin > Data Streams > [Your stream] > Enhanced measurement

Enable/disable as needed.

---

## 🔍 Google Search Console Integration

### **Setup Search Console**

1. **Go to:** https://search.google.com/search-console
2. **Add Property:**
   - URL prefix: `https://warurejo.desa.id`
   - Verify ownership (DNS, HTML file, or GA4)

3. **Submit Sitemap:**
   - Go to "Sitemaps"
   - Add sitemap URL: `https://warurejo.desa.id/sitemap.xml`
   - Submit

4. **Link to GA4:**
   - GA4 > Admin > Product Links
   - Link Search Console
   - Choose property
   - Confirm

---

## 🎯 Event Tracking

### **Track Custom Events**

#### **1. Track Berita Views**

Add to `resources/views/public/berita/show.blade.php`:

```blade
<script>
    gtag('event', 'view_berita', {
        'berita_id': '{{ $berita->id }}',
        'berita_title': '{{ $berita->judul }}',
        'category': 'berita'
    });
</script>
```

---

#### **2. Track Downloads (Publikasi)**

Add to download button:

```blade
<a href="{{ route('publikasi.download', $publikasi) }}" 
   onclick="gtag('event', 'file_download', {
       'file_name': '{{ $publikasi->judul }}',
       'file_category': '{{ $publikasi->kategori }}'
   });">
    Download PDF
</a>
```

---

#### **3. Track WhatsApp Clicks**

Add to WhatsApp button:

```blade
<a href="https://wa.me/{{ $phone }}" 
   target="_blank"
   onclick="gtag('event', 'contact', {
       'method': 'whatsapp'
   });">
    <i class="fab fa-whatsapp"></i> WhatsApp
</a>
```

---

#### **4. Track Search Queries**

Add to search form submission:

```javascript
document.getElementById('searchForm').addEventListener('submit', function(e) {
    const searchTerm = document.getElementById('search').value;
    
    gtag('event', 'search', {
        'search_term': searchTerm
    });
});
```

---

#### **5. Track Navigation Clicks**

```javascript
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', function() {
        gtag('event', 'navigation_click', {
            'link_text': this.textContent.trim(),
            'link_url': this.href
        });
    });
});
```

---

### **Standard GA4 Events**

| Event | Description | Parameters |
|-------|-------------|------------|
| `page_view` | User views page | `page_title`, `page_location` |
| `click` | User clicks element | `link_url`, `link_text` |
| `scroll` | User scrolls 90% | `percent_scrolled` |
| `file_download` | User downloads file | `file_name`, `file_extension` |
| `search` | User searches | `search_term` |
| `share` | User shares content | `content_type`, `item_id` |

---

## 📊 Internal Visitor Tracking (Already Implemented)

### **Current Implementation**

File: `app/Http/Middleware/TrackVisitor.php`

**What's Tracked:**
- ✅ IP address (hashed for privacy)
- ✅ User agent
- ✅ Visited page
- ✅ Referrer
- ✅ Session tracking
- ✅ Daily statistics aggregation

**Database Tables:**
- `visitors` - Individual visits
- `daily_visitor_stats` - Aggregated daily data

---

### **Admin Dashboard Analytics**

Already implemented in `app/Http/Controllers/Admin/DashboardController.php`:

**Metrics Displayed:**
1. **Total Visitors** (card)
2. **Visitor Chart by Year** (Chart.js)
3. **Content Statistics** (Berita, Potensi, Galeri, Publikasi)
4. **Recent Activities**

**API Endpoints:**
- `/admin/dashboard/visitor-chart/{year}` - Visitor data
- `/admin/dashboard/content-chart/{year}` - Content data

---

### **Custom Analytics Queries**

#### **Top 10 Most Visited Pages**

```php
use App\Models\Visitor;

$topPages = Visitor::select('visited_page', DB::raw('COUNT(*) as visits'))
    ->groupBy('visited_page')
    ->orderBy('visits', 'desc')
    ->limit(10)
    ->get();
```

#### **Unique Visitors Today**

```php
$todayVisitors = Visitor::whereDate('visited_at', today())
    ->distinct('ip_address')
    ->count('ip_address');
```

#### **Bounce Rate Calculation**

```php
$singlePageVisits = Visitor::select('session_id')
    ->groupBy('session_id')
    ->havingRaw('COUNT(*) = 1')
    ->count();

$totalSessions = Visitor::distinct('session_id')->count();

$bounceRate = ($singlePageVisits / $totalSessions) * 100;
```

---

## 📈 Custom Reports

### **Create Custom Dashboard Report**

Add to `resources/views/admin/analytics.blade.php`:

```blade
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Website Analytics</h1>
    
    <!-- Date Range Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="dateRangeForm" class="row g-3">
                <div class="col-md-4">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-4">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Visitors</h6>
                    <h2 id="totalVisitors">{{ number_format($totalVisitors) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Unique Visitors</h6>
                    <h2 id="uniqueVisitors">{{ number_format($uniqueVisitors) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Pageviews</h6>
                    <h2 id="pageviews">{{ number_format($pageviews) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Avg. Session Duration</h6>
                    <h2 id="avgDuration">{{ $avgDuration }}m</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Pages -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Top 10 Most Visited Pages</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Views</th>
                        <th>Unique Visitors</th>
                    </tr>
                </thead>
                <tbody id="topPagesTable">
                    @foreach($topPages as $page)
                    <tr>
                        <td>{{ $page->visited_page }}</td>
                        <td>{{ number_format($page->visits) }}</td>
                        <td>{{ number_format($page->unique_visitors) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Visitor Chart -->
    <div class="card">
        <div class="card-header">
            <h5>Visitor Trend</h5>
        </div>
        <div class="card-body">
            <canvas id="visitorTrendChart" height="80"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart implementation
const ctx = document.getElementById('visitorTrendChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($dates),
        datasets: [{
            label: 'Visitors',
            data: @json($visitorCounts),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endsection
```

---

## 🔐 Privacy & GDPR Compliance

### **Privacy Best Practices**

1. **Anonymize IP Addresses**

Already configured in GA4 setup:
```javascript
gtag('config', 'G-XXXXXXXXXX', {
    'anonymize_ip': true
});
```

2. **Cookie Consent Banner**

Add to `resources/views/layouts/public.blade.php`:

```blade
<!-- Cookie Consent -->
<div id="cookieConsent" class="cookie-consent" style="display:none;">
    <div class="cookie-content">
        <p>Website ini menggunakan cookies untuk meningkatkan pengalaman Anda. 
        Dengan melanjutkan, Anda menyetujui penggunaan cookies.</p>
        <button onclick="acceptCookies()" class="btn btn-primary">Setuju</button>
        <a href="/privacy-policy" class="btn btn-link">Pelajari Lebih Lanjut</a>
    </div>
</div>

<style>
.cookie-consent {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #2c3e50;
    color: white;
    padding: 20px;
    z-index: 9999;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
}
.cookie-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}
</style>

<script>
function acceptCookies() {
    localStorage.setItem('cookieConsent', 'true');
    document.getElementById('cookieConsent').style.display = 'none';
}

// Show banner if not accepted
if (!localStorage.getItem('cookieConsent')) {
    document.getElementById('cookieConsent').style.display = 'block';
}
</script>
```

3. **Privacy Policy Page**

Create `resources/views/public/privacy-policy.blade.php`:

```blade
@extends('layouts.public')

@section('title', 'Kebijakan Privasi')

@section('content')
<div class="container py-5">
    <h1>Kebijakan Privasi</h1>
    
    <h2>Pengumpulan Data</h2>
    <p>Website Desa Warurejo mengumpulkan data berikut:</p>
    <ul>
        <li>Data kunjungan (IP address yang dianonimkan, halaman yang dikunjungi)</li>
        <li>Data analytics melalui Google Analytics</li>
        <li>Cookies untuk meningkatkan pengalaman pengguna</li>
    </ul>
    
    <h2>Penggunaan Data</h2>
    <p>Data digunakan untuk:</p>
    <ul>
        <li>Memahami perilaku pengunjung</li>
        <li>Meningkatkan konten dan layanan</li>
        <li>Analisis statistik website</li>
    </ul>
    
    <h2>Hak Pengguna</h2>
    <p>Anda berhak untuk:</p>
    <ul>
        <li>Menolak penggunaan cookies</li>
        <li>Meminta penghapusan data pribadi</li>
        <li>Mengakses data yang kami simpan</li>
    </ul>
    
    <h2>Kontak</h2>
    <p>Untuk pertanyaan terkait privasi, hubungi: admin@warurejo.desa.id</p>
</div>
@endsection
```

---

## 📊 Analytics Checklist

- [ ] **Google Analytics 4 Setup**
  - [ ] Account created
  - [ ] Property configured
  - [ ] Data stream created
  - [ ] Tracking code installed
  - [ ] Installation verified
  - [ ] Enhanced measurement enabled

- [ ] **Search Console**
  - [ ] Property added
  - [ ] Ownership verified
  - [ ] Sitemap submitted
  - [ ] Linked to GA4

- [ ] **Event Tracking**
  - [ ] Page views
  - [ ] Berita views
  - [ ] File downloads
  - [ ] WhatsApp clicks
  - [ ] Search queries
  - [ ] Navigation clicks

- [ ] **Internal Analytics**
  - [ ] Visitor tracking active
  - [ ] Daily stats aggregation
  - [ ] Admin dashboard functional
  - [ ] Custom reports created

- [ ] **Privacy & Compliance**
  - [ ] IP anonymization enabled
  - [ ] Cookie consent banner
  - [ ] Privacy policy page
  - [ ] GDPR compliant

- [ ] **Monitoring**
  - [ ] Real-time reports working
  - [ ] Weekly review scheduled
  - [ ] Monthly reports automated

---

## 📈 Using Analytics Data

### **Weekly Review Checklist**

1. **Traffic Analysis**
   - Total visitors vs last week
   - Unique visitors trend
   - Peak traffic times

2. **Content Performance**
   - Top 10 most viewed berita
   - Most viewed potensi
   - Popular galeri

3. **User Behavior**
   - Average session duration
   - Bounce rate
   - Pages per session

4. **Acquisition**
   - Traffic sources (Direct, Organic, Referral, Social)
   - Top referrers
   - Search keywords (from Search Console)

5. **Technical**
   - Page load times
   - Error rates
   - Browser/device breakdown

### **Monthly Action Items**

Based on analytics data:
- 📝 Create content on popular topics
- 🔧 Fix high bounce rate pages
- 📱 Optimize for top devices
- 🔍 Improve SEO for search terms
- 📊 Update content strategy

---

**Analytics Setup Complete! 📊**

**Next Steps:**
1. Setup Google Analytics 4
2. Verify tracking working
3. Configure events
4. Review weekly reports
5. Optimize based on data

**For monitoring setup, see:** [MONITORING_SETUP.md](MONITORING_SETUP.md)
