# UI/UX DESIGN SYSTEM & WIREFRAME SPECIFICATION
## MASJID INDONESIA — Digital Mosque Management Platform

**Document Version:** 1.0.0  
**Design Philosophy:** Modern Islamic Minimalism  
**Target Devices:** Mobile (360px+), Tablet (768px+), Laptop (1024px+), Ultra-wide (1440px+)  

---

## 1. DESIGN TOKENS & ATOMIC FOUNDATION

### 1.1 Color Palette
Desain mengusung kesegaran nuansa hijau zamrud Islami dipadukan dengan aksen emas elegan dan kanvas abu-abu modern yang bersih:

| Token Name | Hex Code | Tailwind Equivalent | Kegunaan Utama |
|---|---|---|---|
| **Primary Emerald** | `#047857` | `emerald-700` | Header navbar, tombol aksi utama, badge aktif, aksen brand |
| **Primary Hover** | `#065F46` | `emerald-800` | State hover tombol & navigasi |
| **Accent Gold** | `#D4AF37` | `amber-500` / custom | Highlight fitur unggulan, ornamen pembatas, bintang/rating, badge VIP |
| **Canvas Background** | `#F8FAFC` | `slate-50` | Warna latar belakang seluruh halaman |
| **Surface Card** | `#FFFFFF` | `white` | Latar belakang kontainer kartu, tabel, dan modal dialog |
| **Text Primary** | `#0F172A` | `slate-900` | Teks judul, label utama, angka saldo |
| **Text Muted** | `#64748B` | `slate-500` | Teks pendukung, timestamp, caption, subtitle |
| **Border Stroke** | `#E2E8F0` | `slate-200` | Garis batas kartu, pemisah tabel, border input form |
| **Success State** | `#10B981` | `emerald-500` | Transaksi berhasil, dokumen valid, status approved |
| **Warning State** | `#F59E0B` | `amber-500` | Menunggu persetujuan (pending review), inventaris butuh perbaikan |
| **Danger State** | `#EF4444` | `red-500` | Pengajuan ditolak, dokumen dicabut (revoked), stok habis |

### 1.2 Typography System
- **Headings & Title Display:** `font-family: 'Poppins', sans-serif; font-weight: 600, 700;`
- **Body & Data Tables:** `font-family: 'Inter', sans-serif; font-weight: 400, 500, 600;`
- **Arabic Calligraphy / Verses (Optional):** `font-family: 'Amiri', serif;`

---

## 2. PUBLIC PORTAL PAGES & WIREFRAMES

### 2.1 Public Homepage (`/` & `/masjid/{slug}`)
```text
+-----------------------------------------------------------------------------------+
|  [LOGO] MASJID AGUNG SURAKARTA      [Beranda] [Jadwal] [Kajian] [Donasi] [Kontak] |
+-----------------------------------------------------------------------------------+
|  [ HERO BANNER DENGAN FOTO MASJID & OVERLAY EMERALD ]                             |
|  "Digitalisasi Masjid, Menguatkan Umat"                                           |
|                                                                                   |
|  +----------------------- WIDGET JADWAL SHALAT HARI INI ------------------------+ |
|  | SUBUH 04:28 | DZUHUR 11:45 | ASHAR 15:02 | MAGHRIB 17:46 | ISYA 18:55 (NEXT) | |
|  +-------------------------------------------------------------------------------+ |
|  [ Tombol: Donasi Sekarang ]   [ Tombol: Lihat Agenda Kajian Pekan Ini ]          |
+-----------------------------------------------------------------------------------+
|  +-- PENGUMUMAN PENTING (PINNED) -----------------------------------------------+ |
|  | [!] Peringatan Maulid Nabi SAW & Santunan Yatim - Ahad, 15 Robi'ul Awwal      | |
|  +-------------------------------------------------------------------------------+ |
|                                                                                   |
|  === PROGRAM DONASI & INFAQ UNGGULAN ===                                          |
|  [Card 1: Renovasi Menara]    [Card 2: Sedekah Subuh]     [Card 3: Santunan Dhuafa]|
|  Target: Rp50.000.000         Target: Rutin Harian        Terkumpul: Rp12.500.000 |
|  [===== 75% ======]           [===== 100% =====]          [===== 40% ======]      |
|  [ Donasi Cepat ]             [ Donasi Cepat ]            [ Donasi Cepat ]        |
|                                                                                   |
|  === AGENDA KAJIAN TERDEKAT ===                                                   |
|  [Poster 1: Kajian Riyadhis Shalihin - Ustadz Dr. Abdullah - Sabtu Ba'da Maghrib] |
|  [Poster 2: Kajian Fiqih Muamalah - Ustadz Hanan - Ahad 08:30 WIB]                |
|                                                                                   |
|  === TRANSPARANSI KAS MASJID MINGGU INI ===                                       |
|  Saldo Kas Utama: Rp48.250.000 | Pemasukan: +Rp6.400.000 | Pengeluaran: -Rp2.100.000 |
|  [ Unduh Laporan Keuangan Lengkap (PDF) ]                                         |
+-----------------------------------------------------------------------------------+
|  FOOTER: Profil Singkat Masjid, Peta Lokasi Google Maps, Media Sosial, Kontak WA  |
+-----------------------------------------------------------------------------------+
```

### 2.2 Public QR Verification Portal (`/verify/{verification_code}`)
```text
+-----------------------------------------------------------------------------------+
|                           MASJID INDONESIA VERIFIKASI DIGITAL                     |
+-----------------------------------------------------------------------------------+
|                                                                                   |
|                        +------------------------------+                           |
|                        |   [V] DOKUMEN RESMI TERVERIFIKASI   |                    |
|                        |          STATUS: AKTIF & SAH         |                    |
|                        +------------------------------+                           |
|                                                                                   |
|   Nomor Dokumen    : DOK-2026/08/MJS-00129                                        |
|   Jenis Dokumen    : Bukti Penerimaan Infaq & Donasi Digital                      |
|   Masjid Penerbit  : Masjid Al-Falah Surabaya                                     |
|   Tanggal Terbit   : 28 Agustus 2026 14:30 WIB                                    |
|   Nama Donatur     : Bpk. Ahmad S******* (Nama disamarkan untuk privasi)          |
|   Nominal Donasi   : Rp500.000,- (Lunas via QRIS)                                 |
|   Peruntukan       : Infaq Operasional & Pembangunan Tempat Wudhu                 |
|   Penandatangan    : Bendahara Takmir Masjid Al-Falah                             |
|                                                                                   |
|   [ Tombol: Unduh Salinan e-Kwitansi PDF ]   [ Tombol: Kunjungi Profil Masjid ]   |
|                                                                                   |
|   Catatan Keamanan: Halaman ini memvalidasi keaslian dokumen digital yang diterbitkan|
|   resmi oleh takmir masjid melalui sistem tersentralisasi MASJID INDONESIA.       |
+-----------------------------------------------------------------------------------+
```

---

## 3. ADMIN DASHBOARD SPECIFICATION

### 3.1 Layout Anatomy
- **Left Sidebar:** Dark slate / Emerald accent, fixed collapsible navigation with grouped modules.
- **Top Header:** Breadcrumb navigation, Quick Search bar (Spotlight modal), Active Mosque switcher, Notification Center, User Profile dropdown.
- **Main Canvas:** Responsive container (`max-w-7xl mx-auto px-4 py-6`) with smooth card elevations.

### 3.2 Dashboard Wireframe Structure
```text
+----------------+------------------------------------------------------------------+
| MASJID INDO    | [Search...]   (Masjid Al-Ikhlas) [Bell (3)] [Foto Profil User v] |
+----------------+------------------------------------------------------------------+
| [DASHBOARD]    | Dashboard Utama Masjid                                           |
|                |                                                                  |
| -- OPERASIONAL | +----------------+ +----------------+ +----------------+ +----+  |
| [Profil Masjid]| | Saldo Kas Masj | | Total Donasi   | | Jamaah Aktif   | | .. |  |
| [Struktur Staf]| | Rp84.500.000   | | Rp14.200.000   | | 642 Orang      | |    |  |
| [Fasilitas]    | +----------------+ +----------------+ +----------------+ +----+  |
|                |                                                                  |
| -- IBADAH      | +-- GRAFIK KEUANGAN (12 BULAN) -----+ +-- JADWAL PETUGAS PEKAN --+ |
| [Jadwal Shalat]| | [ Chart: Pemasukan vs Pengeluaran] | | Khatib Jumat: Ust. Ridwan| |
| [Jadwal Petugas| |                                   | | Imam Rawatib: Bpk. Hasan | |
| [Agenda Kajian]| +-----------------------------------+ +--------------------------+ |
|                |                                                                  |
| -- KEUANGAN    | +-- TRANSAKSI TERAKHIR -------------+ +-- PENGAJUAN BUTUH ACC ---+ |
| [Buku Kas]     | | 28/08 Infaq Tromol   +Rp1.200.000 | | [!] Pembelian Sound Mic  | |
| [Donasi & Infaq| | 27/08 Service AC     -Rp  450.000 | | [!] Santunan 10 Anak Yatm| |
| [Laporan PDF]  | | [Lihat Semua Transaksi]           | | [Review Sekarang]        | |
|                | +-----------------------------------+ +--------------------------+ |
| -- SOSIAL      |                                                                  |
| [Zakat Fitrah] |                                                                  |
| [Inventaris]   |                                                                  |
| [Pengaturan]   |                                                                  |
+----------------+------------------------------------------------------------------+
```

---

## 4. ACCESSIBILITY & RESPONSIVE BEHAVIOR
1. **Touch Targets:** Seluruh tombol pada tampilan ponsel memiliki ukuran minimal `48px x 48px` untuk memudahkan pengurus masjid lanjut usia.
2. **Keyboard Navigation:** Dukungan penuh navigasi `Tab`, `Shift+Tab`, `Enter`, dan `Esc` pada seluruh dialog modal.
3. **Contrast Compliance:** Rasio kontras teks utama terhadap background minimal $7:1$ (AAA standard).
