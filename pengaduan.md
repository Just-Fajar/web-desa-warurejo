Buatkan fitur Forum Pengaduan Publik untuk website profil desa menggunakan Laravel + Blade. Fitur ini adalah versi publik (forum) — berbeda dengan versi private yang sudah ada (via WhatsApp admin).

---

## KONSEP UTAMA

- Siapa saja bisa submit pengaduan TANPA perlu login/daftar akun
- Semua pengaduan tampil di halaman publik dan bisa dilihat siapa saja
- Identitas pelapor ditampilkan tersensor di publik, lengkap di admin
- Hanya admin yang bisa login, membalas pengaduan, dan mengubah status
- Publik hanya bisa melihat — tidak bisa ikut komentar (mencegah spam)

---

## DATABASE

### Tabel: pengaduan
- id (primary key)
- nama_pelapor (string, wajib)
- nomor_wa (string, wajib) — disimpan utuh, tidak tampil di publik
- judul (string, wajib)
- isi (text, wajib)
- lokasi_kejadian (string, wajib)
- lampiran (string, nullable) — path file JPG/PNG/PDF
- status (enum: 'Menunggu','Diproses','Selesai','Ditolak', default: 'Menunggu')
- timestamps

### Tabel: pengaduan_balasan
- id
- pengaduan_id (foreign key → pengaduan.id, onDelete cascade)
- isi (text, wajib)
- is_admin (boolean, default: true) — semua balasan dari admin
- timestamps

---

## MODEL

### Model: Pengaduan
- fillable: nama_pelapor, nomor_wa, judul, isi, lokasi_kejadian, lampiran, status
- relasi: hasMany(PengaduanBalasan::class)
- accessor getNamaSensorAttribute():
  Sensor nama pelapor — tampilkan 2 huruf pertama tiap kata, sisanya ganti '*'
  Contoh: "Budi Santoso" → "Bu** Sa*****"
  Gunakan helper function, bukan regex sederhana

### Model: PengaduanBalasan
- fillable: pengaduan_id, isi, is_admin
- relasi: belongsTo(Pengaduan::class)

---

## VALIDASI

### StorePengaduanRequest:
- nama_pelapor: required|string|max:100
- nomor_wa: required|string|max:20
- judul: required|string|max:255
- isi: required|string
- lokasi_kejadian: required|string|max:255
- lampiran: nullable|file|mimes:jpg,jpeg,png,pdf|max:5120

### StoreBalasanRequest (admin only):
- isi: required|string
- status: required|in:Menunggu,Diproses,Selesai,Ditolak

---

## CONTROLLER

### PengaduanController (publik)
- index(): tampilkan semua pengaduan status Aktif, urutkan terbaru, dengan pagination 10
- show($id): tampilkan detail satu pengaduan + balasan admin
- store(StorePengaduanRequest $request):
  * Upload lampiran ke storage/public/pengaduan jika ada
  * Simpan data pengaduan
  * Redirect ke halaman detail pengaduan yang baru dibuat
  * Tampilkan pesan sukses: "Pengaduan berhasil dikirim. Admin akan segera merespons."

### Admin\PengaduanController (admin, middleware auth)
- index(): list semua pengaduan dengan pagination + filter status
- show($id): detail pengaduan dengan semua data lengkap (nama & WA tidak disensor)
- storeBalasan(StoreBalasanRequest $request, $id):
  * Simpan balasan admin
  * Update status pengaduan sesuai pilihan admin
  * Redirect kembali ke halaman detail

---

## ROUTES

// Publik
Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
Route::get('/pengaduan/{id}', [PengaduanController::class, 'show'])->name('pengaduan.show');
Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');

// Admin (gunakan middleware auth yang sudah ada di project)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/pengaduan', [Admin\PengaduanController::class, 'index'])->name('admin.pengaduan.index');
    Route::get('/pengaduan/{id}', [Admin\PengaduanController::class, 'show'])->name('admin.pengaduan.show');
    Route::post('/pengaduan/{id}/balas', [Admin\PengaduanController::class, 'storeBalasan'])->name('admin.pengaduan.balas');
});

---

## TAMPILAN FRONTEND (Blade)

### 1. Halaman List Pengaduan (resources/views/pengaduan/index.blade.php)

Layout:
- Judul halaman: "Forum Pengaduan" + subjudul singkat
- Tombol "Buat Pengaduan" di kanan atas → arahkan ke form (bisa modal atau halaman baru)
- Filter chip status: Semua | Menunggu | Diproses | Selesai | Ditolak
- List card pengaduan, tiap card berisi:
  * Judul pengaduan
  * Badge status (warna berbeda tiap status)
  * Nama pelapor TERSENSOR (gunakan $pengaduan->nama_sensor)
  * Tanggal pengaduan
  * Lokasi kejadian
  * Cuplikan isi (2 baris, truncate)
  * Jumlah balasan admin
- Pagination

Warna badge status:
- Menunggu: kuning (#FEF3CD / #856404)
- Diproses: biru (#E6F1FB / #0C447C)
- Selesai: hijau (#EAF3DE / #3B6D11)
- Ditolak: merah muda (#FBEAF0 / #72243E)

### 2. Form Buat Pengaduan

Field (sesuai urutan):
1. Nama Pelapor (text, required)
2. Nomor WhatsApp (text, required) — placeholder: 08xxx atau +628xxx
3. Judul Pengaduan (text, required) — placeholder: Ringkas dan jelas
4. Isi Pengaduan (textarea, required, min-height 120px)
5. Lokasi Kejadian (text, required)
6. Lampiran (file input, opsional) — accept: .jpg,.jpeg,.png,.pdf, maks 5MB
   Tampilkan label "Lampiran (Opsional)" dengan keterangan format yang diterima

Tombol: "Kirim Pengaduan" (hijau, full width)

### 3. Halaman Detail Pengaduan (resources/views/pengaduan/show.blade.php)

Struktur section berurutan:

SECTION 1 — Info Pengaduan (card):
- Judul pengaduan (besar)
- Badge status
- Nama pelapor TERSENSOR
- Lokasi kejadian
- Tanggal dikirim

SECTION 2 — Isi Pengaduan (card):
- Tampilkan isi lengkap

SECTION 3 — Lampiran (tampilkan HANYA jika ada lampiran):
- Jika file gambar (jpg/png): tampilkan preview gambar, bisa diklik untuk zoom
- Jika file PDF: tampilkan ikon PDF + tombol "Unduh Lampiran"

SECTION 4 — Balasan Admin:
- Jika belum ada balasan: tampilkan pesan "Menunggu respons dari admin desa."
- Jika ada balasan: tampilkan tiap balasan dalam card dengan:
  * Label "Admin Desa" + badge hijau "ADMIN"
  * Isi balasan
  * Tanggal & jam balasan
  * Background sedikit berbeda (hijau sangat muda) untuk membedakan dari konten biasa

Catatan: TIDAK ada form balasan untuk publik — hanya admin yang bisa balas

Tombol bawah: "← Kembali ke Daftar Pengaduan"

---

## TAMPILAN ADMIN (Blade)

### Halaman List Admin (resources/views/admin/pengaduan/index.blade.php)
- Tabel dengan kolom: No | Judul | Nama Pelapor (LENGKAP) | Nomor WA | Status | Tanggal | Aksi
- Filter dropdown status
- Badge warna untuk status
- Tombol "Lihat Detail" tiap baris

### Halaman Detail Admin (resources/views/admin/pengaduan/show.blade.php)
- Tampilkan SEMUA data lengkap tanpa sensor:
  * Nama pelapor lengkap
  * Nomor WA lengkap + tombol "Hubungi via WA" (link wa.me)
  * Judul, isi, lokasi, lampiran
- Riwayat balasan (sama seperti frontend tapi tanpa sensor)
- Form balas admin:
  * Textarea isi balasan (required)
  * Dropdown ubah status: Menunggu / Diproses / Selesai / Ditolak
  * Tombol "Kirim Balasan & Update Status"

---

## CATATAN PENTING

1. Sensor nama di frontend:
   Gunakan accessor di Model, bukan logic di Blade.
   Contoh hasil: "Siti Rahayu" → "Si** Ra****"

2. Nomor WA:
   - Tidak ditampilkan di halaman publik sama sekali
   - Di admin: tampil lengkap + link wa.me (format: hilangkan 0 di depan, ganti 62)
   - Contoh: 081234567802 → wa.me/6281234567802

3. Upload lampiran:
   - Simpan di storage/app/public/pengaduan/
   - Jalankan php artisan storage:link
   - Cek ekstensi file untuk menentukan cara tampil (gambar vs PDF)

4. Keamanan:
   - Form publik tidak perlu auth, tapi tambahkan CSRF token (@csrf)
   - Semua route admin wajib middleware auth
   - Validasi file upload ketat (mimes + max size)

5. Gunakan session flash untuk pesan sukses/error setelah submit form