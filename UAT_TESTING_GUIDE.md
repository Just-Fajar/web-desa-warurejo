# 🧪 User Acceptance Testing (UAT) Guide - Website Desa Warurejo

**Version:** 1.0  
**Testing Phase:** Pre-Production  
**Last Updated:** 28 November 2025

---

## 📋 Table of Contents

1. [UAT Overview](#uat-overview)
2. [Testing Team & Roles](#testing-team--roles)
3. [Testing Environment](#testing-environment)
4. [Test Scenarios](#test-scenarios)
5. [Bug Reporting](#bug-reporting)
6. [Content Review Checklist](#content-review-checklist)
7. [Acceptance Criteria](#acceptance-criteria)
8. [Final Sign-Off](#final-sign-off)

---

## 🎯 UAT Overview

### **Tujuan UAT**

User Acceptance Testing dilakukan untuk memastikan:
- ✅ Semua fitur berfungsi sesuai kebutuhan pengguna
- ✅ Interface mudah digunakan (user-friendly)
- ✅ Konten website akurat dan berkualitas
- ✅ Website siap untuk production deployment

### **Metode Testing**

- **Black Box Testing**: Test tanpa melihat code
- **End-to-End Testing**: Test alur lengkap dari perspektif user
- **Exploratory Testing**: Test bebas untuk menemukan bug
- **Content Validation**: Review kualitas konten

### **Durasi UAT**

| Phase | Duration | Participants |
|-------|----------|--------------|
| **Phase 1: Initial Testing** | 2 hari | Admin, Content Editor |
| **Phase 2: Bug Fixing** | 1 hari | Developer |
| **Phase 3: Re-testing** | 1 hari | Admin, Content Editor |
| **Phase 4: Final Review** | 0.5 hari | All Stakeholders |

---

## 👥 Testing Team & Roles

### **UAT Team**

| Role | Name | Responsibilities |
|------|------|------------------|
| **UAT Lead** | _____________ | Coordinate testing, compile reports |
| **Admin Tester** | _____________ | Test admin panel, CRUD operations |
| **Content Editor** | _____________ | Test content management, review content quality |
| **End User** | _____________ | Test public pages from user perspective |
| **Developer** | _____________ | Fix reported bugs, provide support |

### **Contact Information**

| Person | Email | Phone |
|--------|-------|-------|
| UAT Lead | lead@warurejo.desa.id | 08xx-xxxx-xxxx |
| Developer | dev@warurejo.desa.id | 08xx-xxxx-xxxx |

---

## 🖥️ Testing Environment

### **Test Server**

```
URL: https://test.warurejo.desa.id
Username: admin_test
Password: Test123!@#
```

### **Test Accounts**

| Role | Username | Password | Access Level |
|------|----------|----------|--------------|
| Admin | admin_test | Test123!@# | Full access |
| Content Editor | editor_test | Test123!@# | Content only |

### **Test Data**

- Database sudah berisi sample data
- Test images tersedia di folder `/test-assets/`
- Sample documents di folder `/test-docs/`

### **Browsers to Test**

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Edge (latest)
- ✅ Safari (Mac/iOS)

### **Devices to Test**

- ✅ Desktop (1920x1080)
- ✅ Tablet (iPad, 1024x768)
- ✅ Mobile (iPhone, Android)

---

## 🧪 Test Scenarios

### **1. Public Website Testing**

#### **Scenario 1.1: Homepage**

**Test Case ID:** UAT-PUB-001  
**Priority:** Critical  
**Preconditions:** None

**Test Steps:**
1. Buka homepage `https://test.warurejo.desa.id`
2. Verifikasi hero section tampil dengan baik
3. Check navbar sticky saat scroll
4. Klik menu Profil > Visi Misi
5. Verifikasi halaman Visi Misi terbuka
6. Kembali ke homepage
7. Scroll ke section Berita Terbaru
8. Klik salah satu berita
9. Verifikasi halaman detail berita terbuka

**Expected Results:**
- ✅ Homepage load < 3 detik
- ✅ Semua images tampil
- ✅ Navbar sticky berfungsi
- ✅ Navigation lancar
- ✅ Berita dapat dibuka
- ✅ Counter animation berjalan

**Actual Results:** ___________________________________

**Status:** [ ] Pass [ ] Fail [ ] Blocked

**Notes/Issues:** ___________________________________

---

#### **Scenario 1.2: Search & Filter Berita**

**Test Case ID:** UAT-PUB-002  
**Priority:** High

**Test Steps:**
1. Buka halaman Berita
2. Ketik "desa" di search box
3. Verifikasi autocomplete suggestions muncul
4. Pilih suggestion atau Enter
5. Verifikasi hasil search muncul
6. Clear search
7. Gunakan date range filter
8. Verifikasi hasil filter sesuai
9. Change sort order (Latest/Popular)
10. Verifikasi hasil sort berubah

**Expected Results:**
- ✅ Autocomplete real-time berfungsi
- ✅ Search results akurat
- ✅ Date filter berfungsi
- ✅ Sort berfungsi
- ✅ Pagination lancar

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

#### **Scenario 1.3: Galeri Lightbox**

**Test Case ID:** UAT-PUB-003  
**Priority:** Medium

**Test Steps:**
1. Buka halaman Galeri
2. Klik thumbnail galeri
3. Verifikasi modal/lightbox terbuka
4. Klik next/prev di lightbox
5. Test zoom functionality
6. Close lightbox dengan X atau ESC
7. Test di mobile (swipe)

**Expected Results:**
- ✅ Lightbox smooth
- ✅ Navigation berfungsi
- ✅ Zoom berfungsi
- ✅ Mobile swipe berfungsi
- ✅ Loading smooth

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

#### **Scenario 1.4: Mobile Responsiveness**

**Test Case ID:** UAT-PUB-004  
**Priority:** Critical

**Test Steps:**
1. Buka website di mobile (375px width)
2. Test hamburger menu
3. Test navigation
4. Scroll test pages
5. Test touch interactions
6. Test forms (if any)

**Expected Results:**
- ✅ Layout tidak break
- ✅ Menu berfungsi
- ✅ Touch target cukup besar
- ✅ Scroll smooth
- ✅ No horizontal scroll

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

### **2. Admin Panel Testing**

#### **Scenario 2.1: Login & Authentication**

**Test Case ID:** UAT-ADM-001  
**Priority:** Critical

**Test Steps:**
1. Buka `/admin/login`
2. Test login dengan credentials salah
3. Verifikasi error message
4. Test login dengan credentials benar
5. Verifikasi redirect ke dashboard
6. Check session timeout
7. Test logout
8. Test "Remember Me"

**Expected Results:**
- ✅ Invalid login ditolak
- ✅ Valid login berhasil
- ✅ Session stabil
- ✅ Logout berfungsi
- ✅ Remember me berfungsi

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

#### **Scenario 2.2: Dashboard Analytics**

**Test Case ID:** UAT-ADM-002  
**Priority:** High

**Test Steps:**
1. Login sebagai admin
2. Verifikasi dashboard cards menampilkan data
3. Check visitor chart (by year)
4. Check content chart
5. Verify recent activities
6. Test quick actions

**Expected Results:**
- ✅ All cards tampil data real
- ✅ Charts interactive
- ✅ Data akurat
- ✅ Loading smooth

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

#### **Scenario 2.3: CRUD Berita**

**Test Case ID:** UAT-ADM-003  
**Priority:** Critical

**Test Steps:**

**Create:**
1. Klik "Tambah Berita"
2. Isi form:
   - Judul: "Test Berita UAT"
   - Konten: "Konten test dengan <b>HTML</b>"
   - Upload gambar (2MB)
   - Status: Draft
3. Submit
4. Verifikasi berita masuk list
5. Check slug auto-generated

**Update:**
6. Klik Edit pada berita test
7. Ubah judul menjadi "Test Berita UAT Edited"
8. Change status ke Published
9. Update
10. Verifikasi perubahan tersimpan

**Delete:**
11. Klik Delete
12. Verifikasi konfirmasi muncul
13. Confirm delete
14. Verifikasi berita terhapus

**Bulk Delete:**
15. Select multiple berita
16. Bulk delete
17. Verifikasi semua terhapus

**Expected Results:**
- ✅ Create berhasil
- ✅ Image upload & optimize
- ✅ Slug auto-generate
- ✅ HTML sanitization berjalan
- ✅ Update berhasil
- ✅ Delete berhasil
- ✅ Bulk delete berhasil
- ✅ Cache invalidation

**Status:** [ ] Pass [ ] Fail [ ] Blocked

**Issues Found:** ___________________________________

---

#### **Scenario 2.4: Upload Multiple Images (Galeri)**

**Test Case ID:** UAT-ADM-004  
**Priority:** High

**Test Steps:**
1. Go to Galeri > Tambah Galeri
2. Upload 5 images sekaligus
3. Verifikasi preview muncul
4. Arrange urutan images (drag & drop)
5. Submit
6. Check galeri di list
7. Open galeri di public page
8. Verifikasi semua images tampil

**Expected Results:**
- ✅ Multiple upload berfungsi
- ✅ Preview images
- ✅ Drag & drop urutan
- ✅ Images optimized
- ✅ Tampil di public

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

#### **Scenario 2.5: Profile Management**

**Test Case ID:** UAT-ADM-005  
**Priority:** Medium

**Test Steps:**
1. Go to Profile
2. Edit nama, email
3. Upload foto profil
4. Save
5. Verifikasi perubahan
6. Change password
7. Logout
8. Login dengan password baru
9. Delete foto profil

**Expected Results:**
- ✅ Update profile berhasil
- ✅ Photo upload berhasil
- ✅ Password change berhasil
- ✅ Can login dengan password baru
- ✅ Delete photo berhasil

**Status:** [ ] Pass [ ] Fail [ ] Blocked

---

### **3. Cross-Browser Testing**

| Test Case | Chrome | Firefox | Edge | Safari |
|-----------|--------|---------|------|--------|
| Homepage load | [ ] | [ ] | [ ] | [ ] |
| Navigation | [ ] | [ ] | [ ] | [ ] |
| Forms | [ ] | [ ] | [ ] | [ ] |
| Animations | [ ] | [ ] | [ ] | [ ] |
| Modal/Lightbox | [ ] | [ ] | [ ] | [ ] |
| Admin CRUD | [ ] | [ ] | [ ] | [ ] |

---

### **4. Performance Testing**

#### **Page Load Performance**

| Page | Target | Actual | Status |
|------|--------|--------|--------|
| Homepage | < 3s | ___s | [ ] Pass [ ] Fail |
| Berita List | < 2s | ___s | [ ] Pass [ ] Fail |
| Berita Detail | < 2s | ___s | [ ] Pass [ ] Fail |
| Galeri | < 3s | ___s | [ ] Pass [ ] Fail |
| Admin Dashboard | < 2s | ___s | [ ] Pass [ ] Fail |

**Tools:** Google PageSpeed Insights, GTmetrix

---

### **5. Security Testing**

#### **Scenario 5.1: SQL Injection Test**

**Test Steps:**
1. Try SQL injection di search: `' OR '1'='1`
2. Verifikasi tidak ada data leak
3. Check error handling

**Expected:** No SQL injection vulnerability

**Status:** [ ] Pass [ ] Fail

---

#### **Scenario 5.2: XSS Test**

**Test Steps:**
1. Try inject script di form: `<script>alert('XSS')</script>`
2. Submit
3. Verifikasi script di-sanitize

**Expected:** Script removed/escaped

**Status:** [ ] Pass [ ] Fail

---

#### **Scenario 5.3: CSRF Test**

**Test Steps:**
1. Inspect form
2. Verifikasi ada CSRF token
3. Submit tanpa token
4. Verifikasi ditolak

**Expected:** CSRF protection active

**Status:** [ ] Pass [ ] Fail

---

#### **Scenario 5.4: File Upload Security**

**Test Steps:**
1. Try upload .php file
2. Try upload .exe file
3. Try upload file > 10MB

**Expected:** Only allowed file types, size validation

**Status:** [ ] Pass [ ] Fail

---

## 🐛 Bug Reporting

### **Bug Report Template**

```markdown
**Bug ID:** BUG-001
**Reported By:** [Your Name]
**Date:** [Date]
**Severity:** [ ] Critical [ ] High [ ] Medium [ ] Low

**Test Case ID:** UAT-XXX-000

**Summary:**
[Brief description of the bug]

**Steps to Reproduce:**
1. Step 1
2. Step 2
3. Step 3

**Expected Result:**
[What should happen]

**Actual Result:**
[What actually happened]

**Screenshots:**
[Attach screenshots if available]

**Browser/Device:**
- Browser: Chrome 120
- OS: Windows 11
- Device: Desktop

**Additional Notes:**
[Any additional information]

**Status:** [ ] Open [ ] In Progress [ ] Fixed [ ] Closed
**Assigned To:** [Developer Name]
**Fix Version:** [Version number]
```

### **Bug Severity Levels**

| Level | Description | Example |
|-------|-------------|---------|
| **Critical** | System crash, data loss | Database error, login broken |
| **High** | Major functionality broken | CRUD operations fail, images not upload |
| **Medium** | Minor functionality issue | Pagination error, filter tidak sempurna |
| **Low** | Cosmetic issues | Typo, minor alignment issue |

### **Bug Tracking**

Use Google Sheets or Excel template:

| Bug ID | Test Case | Summary | Severity | Status | Assigned To | Fixed Date |
|--------|-----------|---------|----------|--------|-------------|------------|
| BUG-001 | UAT-PUB-001 | Homepage slow load | High | Open | Developer | - |
| BUG-002 | UAT-ADM-003 | Upload fails >2MB | Critical | Fixed | Developer | 2025-11-29 |

---

## ✅ Content Review Checklist

### **1. Text Content**

- [ ] **Profil Desa**
  - [ ] Visi Misi akurat & up-to-date
  - [ ] Sejarah lengkap
  - [ ] Struktur Organisasi sesuai
  - [ ] No typos atau grammar errors
  
- [ ] **Berita**
  - [ ] Judul menarik & SEO-friendly
  - [ ] Konten informatif & lengkap
  - [ ] Tanggal publish sesuai
  - [ ] Author information benar
  - [ ] No broken links
  
- [ ] **Potensi Desa**
  - [ ] Deskripsi jelas & detail
  - [ ] Data potensi akurat
  - [ ] Gambar representative
  
- [ ] **Publikasi**
  - [ ] Dokumen lengkap (APBDes, RPJMDes, RKPDes)
  - [ ] File dapat didownload
  - [ ] Tahun sesuai
  - [ ] Kategori benar

### **2. Images & Media**

- [ ] All images optimized (< 200KB)
- [ ] Images relevant to content
- [ ] No copyright violations
- [ ] Alt text descriptive
- [ ] Responsive images working
- [ ] Thumbnails clear

### **3. SEO Check**

- [ ] Meta titles descriptive (< 60 chars)
- [ ] Meta descriptions compelling (< 160 chars)
- [ ] H1 tags appropriate
- [ ] Internal links working
- [ ] URL slugs SEO-friendly
- [ ] Sitemap generated

### **4. Accessibility**

- [ ] Color contrast sufficient
- [ ] Font sizes readable
- [ ] Alt text on images
- [ ] Keyboard navigation
- [ ] Screen reader friendly
- [ ] ARIA labels where needed

### **5. Legal & Compliance**

- [ ] Privacy policy available
- [ ] Contact information accurate
- [ ] Copyright notices
- [ ] Social media links working
- [ ] Terms of service (if needed)

---

## 🎯 Acceptance Criteria

### **Functional Requirements**

- [ ] All CRUD operations working
- [ ] Authentication & authorization secure
- [ ] File uploads functioning
- [ ] Search & filters accurate
- [ ] Pagination smooth
- [ ] API endpoints working (if applicable)

### **Non-Functional Requirements**

- [ ] Page load < 3 seconds
- [ ] Mobile responsive
- [ ] Cross-browser compatible
- [ ] No critical security vulnerabilities
- [ ] No critical bugs
- [ ] User-friendly interface

### **Content Requirements**

- [ ] All content accurate
- [ ] No typos or grammar errors
- [ ] Images optimized
- [ ] SEO optimized
- [ ] Legal requirements met

### **Pass Criteria**

**Website can proceed to production if:**
- ✅ 0 Critical bugs
- ✅ ≤ 2 High severity bugs (with fix plan)
- ✅ All acceptance criteria met
- ✅ Stakeholder approval

---

## 📝 Final Sign-Off

### **UAT Summary Report**

**Testing Period:** [Start Date] - [End Date]

**Test Cases Executed:** ____ / ____  
**Pass Rate:** ____%

**Bugs Summary:**
- Critical: ____
- High: ____
- Medium: ____
- Low: ____

**Overall Status:** [ ] Approved [ ] Conditionally Approved [ ] Rejected

**Comments:**
___________________________________________
___________________________________________
___________________________________________

### **Signatures**

```
UAT Lead: ___________________   Date: __________
Signature: ___________________

Content Editor: ___________________   Date: __________
Signature: ___________________

End User Representative: ___________________   Date: __________
Signature: ___________________

Project Manager: ___________________   Date: __________
Signature: ___________________

Client/Stakeholder: ___________________   Date: __________
Signature: ___________________

Developer: ___________________   Date: __________
Signature: ___________________
```

---

## 📋 Post-UAT Actions

### **If Approved:**
1. Schedule production deployment
2. Prepare rollback plan
3. Setup monitoring
4. Create user training materials
5. Announce launch date

### **If Conditionally Approved:**
1. Fix identified high-priority bugs
2. Re-test affected areas
3. Get final approval
4. Proceed with deployment

### **If Rejected:**
1. Analyze root causes
2. Create comprehensive fix plan
3. Schedule re-testing
4. Communicate new timeline

---

**UAT Guide Complete! 🧪**

**Next Steps:**
1. Distribute this guide to UAT team
2. Setup test environment
3. Create bug tracking sheet
4. Schedule UAT sessions
5. Conduct testing
6. Review results
7. Get sign-off

**For deployment after UAT, see:** [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
