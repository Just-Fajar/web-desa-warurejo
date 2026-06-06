Kamu adalah senior software engineer dengan 10+ tahun pengalaman di Laravel dan PHP. Kamu pernah bekerja di perusahaan teknologi skala besar dan sudah review ribuan pull request.

Lakukan code review menyeluruh dan jujur terhadap project saya. Jangan basa-basi dan jangan terlalu memuji. Saya ingin review yang setara dengan review dari senior engineer di perusahaan teknologi profesional — jujur, tajam, dan konstruktif.

---

## KONTEKS PROJECT

Nama project: Website Profil Desa Warurejo
Framework: Laravel 12.x + Blade + Tailwind CSS 4 + Alpine.js
Database: MySQL 8.0
Pattern: Repository + Service Layer
Auth: Custom guard 'admin' (model Admin, bukan User)
Testing: PHPUnit (40 test files, ~492 test methods)
API: Laravel Sanctum + 23 endpoints (api_v1.php)
Server: Nginx + PHP-FPM 8.2

---

## AREA YANG HARUS DIREVIEW

### 1. ARCHITECTURE & DESIGN

Evaluasi:
- Apakah Repository + Service Pattern diimplementasikan dengan benar dan konsisten?
- Apakah ada God Class (class yang melakukan terlalu banyak hal)?
- Apakah ada Anemic Domain Model (model yang hanya berisi getter/setter tanpa logic)?
- Apakah Separation of Concerns terjaga dengan baik?
- Apakah ada circular dependency antar class?
- Apakah struktur folder mengikuti Laravel convention?
- Apakah BaseRepository digunakan dengan benar?
- Apakah Service Layer tidak bocor logic ke Controller?

Berikan penilaian:
[GRADE: A/B/C/D/F] dengan penjelasan spesifik dan contoh kode yang bermasalah.

---

### 2. CODE QUALITY

Evaluasi setiap aspek berikut:

SOLID Principles:
- Single Responsibility: apakah setiap class punya 1 tanggung jawab?
- Open/Closed: apakah mudah extend tanpa modify?
- Liskov Substitution: apakah inheritance digunakan dengan benar?
- Interface Segregation: apakah ada interface yang terlalu gemuk?
- Dependency Inversion: apakah dependen pada abstraksi, bukan implementasi?

DRY (Don't Repeat Yourself):
- Identifikasi semua duplikasi kode yang ada
- Tunjukkan contoh spesifik dengan nama file dan baris

KISS (Keep It Simple Stupid):
- Identifikasi kode yang over-engineered
- Identifikasi kode yang under-engineered (terlalu simpel untuk masalah kompleks)

Naming:
- Apakah nama variable, method, class sudah deskriptif?
- Apakah ada nama yang misleading atau ambigu?
- Apakah konsisten dalam Bahasa (Indonesia vs Inggris)?

Complexity:
- Apakah ada method dengan cyclomatic complexity tinggi (> 10)?
- Apakah ada method yang terlalu panjang (> 50 baris)?
- Apakah ada nested condition yang bisa disederhanakan?

Berikan contoh kode bermasalah dan versi yang lebih baik.

---

### 3. SECURITY REVIEW

Ini critical — jangan lewatkan satu pun:

Authentication & Authorization:
- Apakah semua route admin dilindungi middleware dengan benar?
- Apakah ada privilege escalation vulnerability?
- Apakah session management sudah aman?
- Apakah ada hardcoded credentials?

Input Validation:
- Apakah semua user input divalidasi sebelum diproses?
- Apakah Form Request digunakan secara konsisten?
- Apakah ada unvalidated redirect?

Injection:
- Apakah ada raw SQL query yang rentan SQL injection?
- Apakah ada command injection risk?
- Apakah ada path traversal risk di file upload?

Output:
- Apakah semua output di-escape dengan benar?
- Apakah HtmlSanitizerService digunakan di semua tempat yang perlu?
- Apakah ada XSS vulnerability?

File Upload:
- Apakah validasi file upload sudah ketat?
- Apakah ada risk file execution di storage?

API:
- Apakah ada endpoint yang expose data sensitif?
- Apakah rate limiting sudah diterapkan di semua endpoint?
- Apakah Sanctum token management sudah benar?

Berikan severity level untuk setiap issue: [CRITICAL] [HIGH] [MEDIUM] [LOW]

---

### 4. PERFORMANCE REVIEW

Database:
- Identifikasi semua N+1 query yang ada (tunjukkan file dan baris)
- Identifikasi semua SELECT * yang tidak perlu
- Apakah eager loading digunakan dengan konsisten?
- Apakah ada missing index yang terdeteksi dari kode query?

Caching:
- Apakah cache sudah digunakan di semua tempat yang seharusnya?
- Apakah ada cache invalidation yang tidak tepat (terlalu agresif atau kurang)?
- Apakah ada data yang di-cache padahal tidak perlu?

Memory:
- Apakah ada collection besar yang di-load sekaligus tanpa pagination?
- Apakah ada memory leak potential?

Laravel Specific:
- Apakah ada query di dalam loop?
- Apakah paginate() digunakan di tempat yang tepat?
- Apakah chunk() digunakan untuk data besar?

---

### 5. TESTING REVIEW

Evaluasi:
- Coverage 77% — area mana yang tidak ter-cover? Apakah area kritis sudah ter-cover?
- Apakah test yang ada menguji behavior atau hanya implementation detail?
- Apakah ada test yang fragile (mudah broken karena perubahan tidak terkait)?
- Apakah Factory sudah lengkap dan realistis?
- Apakah ada test case penting yang missing (edge case, error case)?
- Apakah test isolation terjaga (setiap test independen)?
- Apakah ada hardcoded data di test yang seharusnya pakai factory?
- Apakah mock/stub digunakan dengan tepat?

Berikan:
- Daftar area kritis yang HARUS punya test tapi belum ada
- Contoh test case yang lemah dan versi yang lebih baik

---

### 6. LARAVEL BEST PRACTICES

Periksa kepatuhan terhadap Laravel conventions:

Eloquent:
- Apakah relationship didefinisikan dengan benar?
- Apakah scope digunakan untuk query yang sering diulang?
- Apakah accessor/mutator digunakan dengan tepat?
- Apakah fillable/guarded dikonfigurasi dengan benar?
- Apakah ada mass assignment vulnerability?

Service Provider:
- Apakah binding di container dilakukan dengan benar?
- Apakah ada Service Provider yang terlalu gemuk?

Middleware:
- Apakah middleware single responsibility?
- Apakah urutan middleware sudah benar?

Request:
- Apakah Form Request digunakan untuk semua validasi?
- Apakah authorization di Form Request sudah benar?

Response:
- Apakah response type konsisten (redirect vs JSON)?
- Apakah status code HTTP sudah tepat?

Config:
- Apakah tidak ada hardcoded value yang seharusnya di config?
- Apakah env() hanya dipanggil di config file, bukan di kode langsung?

---

### 7. ERROR HANDLING

Evaluasi:
- Apakah semua exception di-handle dengan benar?
- Apakah ada silent fail (catch exception tapi tidak dilakukan apa-apa)?
- Apakah error message cukup informatif untuk debugging tapi tidak membocorkan info sensitif?
- Apakah ada try-catch yang terlalu luas (catch Exception saja tanpa spesifik)?
- Apakah return type nullable sudah di-handle dengan null check?
- Apakah ada potential null pointer dereference?

---

### 8. API DESIGN REVIEW

Evaluasi REST API yang sudah ada:

Consistency:
- Apakah naming convention endpoint konsisten?
- Apakah HTTP method digunakan dengan tepat (GET, POST, PUT, PATCH, DELETE)?
- Apakah response format konsisten di semua endpoint?
- Apakah HTTP status code sudah tepat?

Resource Design:
- Apakah API Resource digunakan di semua endpoint?
- Apakah ada field sensitif yang ter-expose?
- Apakah response terlalu gemuk (berisi data yang tidak diperlukan)?

Versioning:
- Apakah versioning sudah diterapkan dengan benar?
- Apakah ada breaking change yang tidak di-handle?

---

### 9. DEPENDENCY & PACKAGE REVIEW

Evaluasi:
- Apakah semua package yang diinstall benar-benar digunakan?
- Apakah ada package yang outdated atau punya known vulnerability?
- Apakah ada package yang bisa digantikan dengan fitur bawaan Laravel?
- Apakah dev dependencies tidak ikut ter-install di production?

---

### 10. MAINTAINABILITY & READABILITY

Evaluasi:
- Apakah kode mudah dibaca oleh developer baru?
- Apakah komentar ada di tempat yang perlu dan tidak ada di tempat yang tidak perlu?
- Apakah ada magic number atau magic string yang tidak jelas?
- Apakah ada kode yang sudah tidak terpakai (dead code)?
- Apakah ada TODO/FIXME comment yang tertinggal lama?
- Apakah konsisten dalam penggunaan Bahasa (Indonesia vs Inggris) di komentar dan pesan?

---

## FORMAT LAPORAN REVIEW

Buat laporan dengan struktur ini:

=== CODE REVIEW REPORT — Desa Warurejo ===
Reviewed by: Senior Engineer Analysis
Date: [tanggal]
Files reviewed: [jumlah]

--- EXECUTIVE SUMMARY ---
Overall Grade: [A/B/C/D/F]
[3-4 kalimat ringkasan kondisi keseluruhan kode]

--- SCORECARD ---
| Area                  | Grade | Keterangan Singkat         |
|---|---|---|
| Architecture          | ?     |                            |
| Code Quality          | ?     |                            |
| Security              | ?     |                            |
| Performance           | ?     |                            |
| Testing               | ?     |                            |
| Laravel Best Practice | ?     |                            |
| Error Handling        | ?     |                            |
| API Design            | ?     |                            |
| Maintainability       | ?     |                            |

--- CRITICAL ISSUES (harus diperbaiki sebelum production) ---
[daftar issue critical dengan file, baris, dan contoh perbaikan]

--- HIGH PRIORITY (perbaiki dalam sprint ini) ---
[daftar issue high priority]

--- MEDIUM PRIORITY (perbaiki dalam 2 sprint ke depan) ---
[daftar issue medium priority]

--- LOW PRIORITY / NICE TO HAVE ---
[daftar saran improvement]

--- POSITIVE FINDINGS (yang sudah bagus) ---
[daftar hal yang sudah diimplementasikan dengan baik — jujur, bukan basa-basi]

--- TOP 5 QUICK WINS ---
[5 perbaikan yang mudah dilakukan tapi dampaknya besar]

--- REKOMENDASI REFACTORING ---
[saran refactoring jangka panjang jika ada]

=== AKHIR REPORT ===

---

## INSTRUKSI TAMBAHAN

1. JUJUR — jika kodenya buruk, katakan dengan jelas dan tunjukkan contohnya
2. SPESIFIK — setiap issue harus disertai nama file, nama method, dan baris jika memungkinkan
3. ACTIONABLE — setiap issue harus disertai contoh perbaikan konkret, bukan hanya kritik
4. PRIORITIZED — bedakan mana yang critical, high, medium, low
5. BALANCED — jika ada yang bagus, sebutkan juga — tapi tidak berlebihan
6. NO BULLSHIT — jangan bilang "kode ini sangat bagus" jika ada masalah serius
7. CONTEXT-AWARE — pertimbangkan ini adalah website desa, bukan startup unicorn

Jika ada issue yang sama muncul di banyak tempat, sebutkan satu contoh lalu tulis
"dan X tempat lainnya" — jangan listing semua satu per satu.

Saya ingin tahu kondisi sebenarnya dari kode saya, bukan validasi.