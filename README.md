<p align="center">
  <img src="https://raw.githubusercontent.com/lucide-icons/lucide/main/icons/landmark.svg" width="80" height="80" alt="Masjid Indonesia Logo" />
</p>

<h1 align="center">MASJID INDONESIA</h1>

<p align="center">
  <strong>Platform Digital Tata Kelola, Transparansi Keuangan Kas, Layanan Jamaah, dan Verifikasi Dokumen Masjid Nasional</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13" />
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+" />
  <img src="https://img.shields.io/badge/PostgreSQL-Neon_Serverless-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="Neon PostgreSQL" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Tests-100%25_Passing-047857?style=for-the-badge&logo=githubactions&logoColor=white" alt="Tests Passing" />
  <img src="https://img.shields.io/badge/License-MIT-D4AF37?style=for-the-badge" alt="License MIT" />
</p>

---

## 📖 Tentang Aplikasi

**MASJID INDONESIA** adalah platform *enterprise-grade* berbasis multi-tenant yang dirancang untuk mendigitalisasi operasional masjid di seluruh Indonesia. Platform ini mengusung filosofi desain **Modern Islamic Minimalism** (Emerald & Gold) dan menjamin akuntabilitas pengelolaan dana umat melalui pembukuan kas terbuka, penjadwalan ibadah otomatis, penggalangan infaq berbasis QRIS, serta sistem verifikasi dokumen ber-QR anti-pemalsuan (*anti-doxxing cryptographic verification*).

---

## ✨ Fitur-Fitur Utama

### 1. 🌐 Portal Publik & Direktori Nasional
* **Direktori Masjid Nasional (`/`)**: Pencarian masjid berdasarkan nama kota, provinsi, dan tipologi (Jami', Besar, Agung, Raya). Dilengkapi metrik nasional *real-time*.
* **Portal Masjid Mandiri (`/masjid/{slug}`)**: Profil resmi masjid, ID SIMAS Kemenag RI, daya tampung jamaah, galeri, dan direktori fasilitas ibadah.
* **Jadwal Shalat Real-time**: Hitungan hisab astronomis Kemenag RI (Imsak, Subuh, Dzuhur, Ashar, Maghrib, Isya) dengan indikator shalat berikutnya (*Next Prayer Indicator*).
* **Khatib & Petugas Jumat**: Informasi khatib Jumat, tema khutbah, muadzin, dan bilal mingguan.
* **Transparansi Kas Terbuka**: Publikasi saldo kas berjalan serta mutasi pemasukan dan pengeluaran terverifikasi yang dapat diakses oleh jamaah.

### 2. 💳 Infaq & Donasi Online Terintegrasi
* **Program Donasi & Campaign (`/masjid/{slug}/donasi`)**: Progress bar pencapaian dana donasi, target nominal, dan hitung mundur batas waktu.
* **Checkout Cepat**: Pilihan nominal cepat (Rp25rb s/d Rp1jt), donasi atas nama pribadi atau *Hamba Allah* (anonim), dan fitur doa/pesan kebaikan donatur.
* **Multi-Payment Gateway**: Dukungan QRIS Nasional Dinamis/Statis serta Transfer Rekening Bank Kas Masjid.
* **e-Kwitansi Otomatis**: Penerbitan bukti setor infaq digital ber-QR code resmi setelah diverifikasi oleh bendahara.

### 3. 🛡️ Sistem Verifikasi Digital Kriptografis Ber-QR (`/verify/{code}`)
* **Validasi Keabsahan Dokumen**: Jamaah atau auditor dapat memindai QR code pada e-Kwitansi donasi, bukti setor ZISWAF, atau sertifikat untuk mengecek keaslian dokumen secara *real-time*.
* **Anti-Doxxing & Perlindungan Privasi**: Informasi sensitif (NIK, nomor HP lengkap, nomor rekening) disanitasi dan disensor secara otomatis.

### 4. 📊 Dashboard Administrasi & Manajemen Takmir (`/admin/*`)
* **Dashboard Ikhtisar**: Statistik keuangan, grafik tren arus kas 6 bulan ([Chart.js](https://www.chartjs.org/)), antrean proposal yang butuh persetujuan, dan transaksi kas terbaru.
* **Buku Kas & Jurnal Keuangan**: Pencatatan kas masuk/keluar, pos anggaran, filter tanggal/kategori, dan **Export Laporan Pertanggungjawaban Bulanan PDF (DomPDF)**.
* **Pengajuan Anggaran & Approval Engine**: Alur persetujuan proposal berjenjang (*Draft -> Review Bendahara -> Approval Ketua Takmir*).
* **Manajemen Zakat & Wakaf (ZISWAF)**: Kalkulator Zakat Fitrah otomatis (beras 2.5 kg atau uang tunai Rp45.000/jiwa), pencatatan Zakat Maal, dan penerbitan bukti setor ber-QR.
* **Database Jamaah & Program Sosial**: Pendataan kepala keluarga, database mustahiq (8 asnaf), dan pencatatan riwayat distribusi bantuan sembako/santunan.
* **Inventaris & Pemeliharaan Sarana**: Aset fisik masjid, status kondisi barang, lokasi ruangan, dan log servis berkala.
* **Perpustakaan Masjid**: Katalog kitab kuning & buku Islam, serta manajemen sirkulasi peminjaman jamaah.
* **Agenda Kajian & Warta Berita**: CMS publikasi majelis taklim lengkap dengan formulir RSVP peserta, artikel dakwah, dan pengumuman *pinned banner*.

### 5. 👑 Super Admin Platform Console (`/superadmin/*`)
* Monitoring statistik seluruh ekosistem masjid se-Indonesia.
* Moderasi dan verifikasi legalitas masjid tenant baru.
* Fitur *Context Switcher* untuk masuk dan membantu tata kelola masjid tertentu.
* Audit trail log keamanan (*activity tracking*) mencatat setiap mutasi keuangan dan autentikasi.

---

## 🏗️ Arsitektur & Teknologi

* **Backend Framework**: Laravel 13 (PHP 8.3+)
* **Database**: Serverless PostgreSQL via [Neon Tech](https://neon.tech/) (SNI SSL mode)
* **Frontend**: Blade Templates, Tailwind CSS, Alpine.js, Lucide Icons, Chart.js
* **Multi-Tenancy**: Single-Database Shared Schema dengan scoping otomatis via `TenantScope` & `BelongsToMosque`
* **Role-Based Access Control (RBAC)**: `spatie/laravel-permission` (12 Role Hierarkis: `SUPER_ADMIN`, `MOSQUE_ADMIN`, `CHAIRMAN`, `SECRETARY`, `TREASURER`, `OPERATOR`, `IMAM`, `KHATIB`, `MUADZIN`, `JAMAAH`, `DONOR`, `VOLUNTEER`)
* **PDF Engine**: `barryvdh/laravel-dompdf`

---

## 🚀 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
* PHP 8.3 atau lebih baru (ekstensi: `pdo_pgsql`, `pgsql`, `openssl`, `mbstring`, `gd`, `bcmath`)
* Composer 2.x
* PostgreSQL 16+ atau Akun [Neon Serverless PostgreSQL](https://neon.tech/)
* Node.js & NPM (opsional, aset Tailwind & Alpine sudah di-bundle)

### 2. Kloning Repositori
```bash
git clone https://github.com/username/masjid-indonesia.git
cd masjid-indonesia
```

### 3. Instal Dependensi Composer
```bash
composer install
```

### 4. Konfigurasi Environment (`.env`)
Salin file template `.env.example` ke `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database PostgreSQL pada file `.env`:
```dotenv
DB_CONNECTION=pgsql
DB_HOST=ep-frosty-resonance-auu6llq1-pooler.c-10.us-east-1.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=your_neon_password
DB_SSLMODE="require;options='endpoint=ep-frosty-resonance-auu6llq1'"
```

### 5. Jalankan Migrasi & Database Seeder
Jalankan migrasi skema 35+ tabel dan seed data awal (5 masjid nyata, 12 role, dan data simulasi transaksi kas):
```bash
php artisan migrate --seed
```

### 6. Jalankan Local Development Server
```bash
php artisan serve
```
Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## 🔑 Akun Uji Coba (Demo Credentials)

Semua akun pengurus dan masjid telah di-seed dengan kata sandi default: `password`

| Role / Jabatan | Email Login | Kata Sandi | Lingkup Hak Akses |
|---|---|---|---|
| **Super Admin** | `superadmin@masjidindonesia.id` | `password` | Akses penuh platform nasional & semua masjid |
| **Admin Masjid** | `admin@al-jabbar.id` | `password` | Manajemen penuh Masjid Raya Al-Jabbar Bandung |
| **Bendahara** | `bendahara@al-jabbar.id` | `password` | Pembukuan kas, verifikasi donasi & zakat |
| **Sekretaris Takmir** | `sekretaris@al-jabbar.id` | `password` | Jadwal ibadah, agenda kajian, dan warta berita |
| **Imam Rawatib** | `imam@al-jabbar.id` | `password` | Penjadwalan ibadah dan perpustakaan |

---

## 🧪 Menjalankan Pengujian Otomatis

Aplikasi dilengkapi *automated feature & unit tests* mencakup seluruh alur utama:
```bash
php artisan test
```

Hasil pengujian:
```text
   PASS  Tests\Unit\ExampleTest
   PASS  Tests\Feature\ExampleTest
   PASS  Tests\Feature\PublicPortalTest
   PASS  Tests\Feature\AuthenticationAndAdminTest

  Tests:    12 passed (23 assertions)
  Duration: ~2.4s
```

---

## 📁 Struktur Direktori Utama

```text
masjid/
├── app/
│   ├── Enums/          # Backed Enums (RoleEnum, MosqueType, TransactionType, dll.)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # Controller operasional takmir masjid
│   │   │   ├── Auth/        # Controller login & registrasi masjid
│   │   │   ├── Public/      # Controller landing page, portal masjid, verifikasi QR
│   │   │   └── SuperAdmin/  # Controller monitoring & audit nasional
│   │   └── Middleware/      # SetMosqueContext, EnsureUserBelongsToMosque
│   ├── Models/         # 35+ Eloquent Models (Mosque, FinancialTransaction, Donation, dll.)
│   ├── Scopes/         # TenantScope untuk isolasi query data per masjid
│   ├── Services/       # Business logic (FinanceService, DonationService, VerificationService, dll.)
│   └── Traits/         # HasUuid, BelongsToMosque
├── database/
│   ├── migrations/     # 14 file migrasi skema PostgreSQL
│   └── seeders/        # DatabaseSeeder dengan data realita masjid Indonesia
├── docs/               # Dokumentasi PRD, System Design, ERD, UI/UX, Deployment
├── resources/
│   └── views/
│       ├── admin/      # Blade template dashboard takmir
│       ├── auth/       # Blade template login & pendaftaran masjid
│       ├── layouts/    # Master layout (public, admin, auth)
│       ├── public/     # Blade template portal publik masjid & verifikasi QR
│       ├── reports/    # Template PDF laporan kas & e-Kwitansi (DomPDF)
│       └── superadmin/ # Blade template konsol super admin
└── routes/
    ├── auth.php        # Rute autentikasi
    └── web.php         # Rute publik, takmir admin, dan super admin
```

---

## 📚 Dokumentasi Lengkap Proyek

Dokumentasi teknis mendalam tersedia pada folder [`docs/`](./docs/):
* [docs/01-PRD.md](./docs/01-PRD.md) — *Product Requirements Document* & 12 personas.
* [docs/02-SYSTEM-DESIGN.md](./docs/02-SYSTEM-DESIGN.md) — *System Architecture & Multi-Tenant Isolation Design*.
* [docs/03-ERD.md](./docs/03-ERD.md) — *Database Entity Relationship Diagram (35+ Tables)*.
* [docs/04-UI-UX.md](./docs/04-UI-UX.md) — *Modern Islamic Minimalism Design Tokens & Wireframes*.
* [docs/05-DEPLOYMENT.md](./docs/05-DEPLOYMENT.md) — *Production Deployment Guide (Nginx, Systemd, Backup Runbook)*.

---

## 📄 Lisensi

Platform **MASJID INDONESIA** adalah perangkat lunak sumber terbuka yang dilisensikan di bawah [MIT License](LICENSE).
