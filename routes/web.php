<?php

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\CongregationController as AdminCongregationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DonationCampaignController as AdminCampaignController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\LibraryController as AdminLibraryController;
use App\Http\Controllers\Admin\MosqueProfileController as AdminProfileController;
use App\Http\Controllers\Admin\MosqueStaffController as AdminStaffController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PrayerScheduleController as AdminPrayerController;
use App\Http\Controllers\Admin\SocialProgramController as AdminSocialController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\ZakatController as AdminZakatController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MosquePortalController;
use App\Http\Controllers\Public\PublicDonationController;
use App\Http\Controllers\Public\PublicEventController;
use App\Http\Controllers\Public\PublicPrayerController;
use App\Http\Controllers\Public\VerificationController;
use App\Http\Controllers\SuperAdmin\AuditLogController as SuperAdminAuditController;
use App\Http\Controllers\SuperAdmin\MosqueManagementController as SuperAdminMosqueController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. PUBLIC PORTAL ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/verify/{code}', [VerificationController::class, 'verify'])->name('verify');

// Public Mosque Profiles & Features
Route::prefix('masjid/{slug}')->group(function () {
    Route::get('/', [MosquePortalController::class, 'show'])->name('public.mosque');
    Route::get('/jadwal', [PublicPrayerController::class, 'index'])->name('public.prayers');
    Route::get('/kajian', [PublicEventController::class, 'index'])->name('public.events');
    Route::get('/kajian/{eventSlug}', [PublicEventController::class, 'show'])->name('public.events.show');
    Route::post('/kajian/{eventSlug}/daftar', [PublicEventController::class, 'register'])->name('public.events.register');
    
    // Donations
    Route::get('/donasi', [PublicDonationController::class, 'index'])->name('public.donations');
    Route::get('/donasi/{campaignSlug}', [PublicDonationController::class, 'show'])->name('public.donations.show');
    Route::post('/donasi', [PublicDonationController::class, 'store'])->name('public.donations.store');
    Route::get('/donasi-sukses/{code}', [PublicDonationController::class, 'success'])->name('public.donations.success');
});

// ==========================================
// 2. AUTHENTICATION ROUTES
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// 3. ADMIN MOSQUE DASHBOARD ROUTES
// ==========================================
Route::prefix('admin')->middleware(['auth', 'mosque.tenant'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Profile & Facilities
    Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/fasilitas', [AdminProfileController::class, 'storeFacility'])->name('facilities.store');

    // Staff & Takmir
    Route::get('/pengurus', [AdminStaffController::class, 'index'])->name('staff.index');
    Route::post('/pengurus', [AdminStaffController::class, 'store'])->name('staff.store');
    Route::delete('/pengurus/{staff}', [AdminStaffController::class, 'destroy'])->name('staff.destroy');

    // Prayer & Duty Schedules
    Route::get('/jadwal-shalat', [AdminPrayerController::class, 'index'])->name('prayers.index');
    Route::post('/jadwal-shalat/settings', [AdminPrayerController::class, 'updateSettings'])->name('prayers.settings');
    Route::post('/jadwal-khatib', [AdminPrayerController::class, 'storeKhatib'])->name('prayers.khatib.store');

    // Events & Kajian
    Route::get('/kajian', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/kajian', [AdminEventController::class, 'store'])->name('events.store');
    Route::delete('/kajian/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // News & Announcements
    Route::get('/berita', [AdminNewsController::class, 'index'])->name('news.index');
    Route::post('/berita', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/pengumuman', [AdminAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/pengumuman', [AdminAnnouncementController::class, 'store'])->name('announcements.store');

    // Congregations
    Route::get('/jamaah', [AdminCongregationController::class, 'index'])->name('congregations.index');
    Route::post('/jamaah', [AdminCongregationController::class, 'store'])->name('congregations.store');

    // Donations
    Route::get('/campaigns', [AdminCampaignController::class, 'index'])->name('campaigns.index');
    Route::post('/campaigns', [AdminCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/donasi', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::post('/donasi/{donation}/verifikasi', [AdminDonationController::class, 'verify'])->name('donations.verify');

    // Finance & Cash Ledger
    Route::get('/keuangan', [AdminFinanceController::class, 'index'])->name('finances.index');
    Route::post('/keuangan', [AdminFinanceController::class, 'store'])->name('finances.store');
    Route::get('/keuangan/export-pdf', [AdminFinanceController::class, 'exportPdf'])->name('finances.export.pdf');

    // Social & Zakat
    Route::get('/sosial', [AdminSocialController::class, 'index'])->name('social.index');
    Route::post('/sosial/program', [AdminSocialController::class, 'storeProgram'])->name('social.program.store');
    Route::post('/sosial/penerima', [AdminSocialController::class, 'storeRecipient'])->name('social.recipient.store');
    Route::post('/sosial/salurkan', [AdminSocialController::class, 'storeDistribution'])->name('social.distribute.store');
    Route::get('/zakat', [AdminZakatController::class, 'index'])->name('zakat.index');
    Route::post('/zakat', [AdminZakatController::class, 'storeZakat'])->name('zakat.store');

    // Inventory & Library
    Route::get('/inventaris', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventaris', [AdminInventoryController::class, 'store'])->name('inventory.store');
    Route::post('/inventaris/{inventory}/maintenance', [AdminInventoryController::class, 'storeMaintenance'])->name('inventory.maintenance.store');
    Route::get('/perpustakaan', [AdminLibraryController::class, 'index'])->name('library.index');
    Route::post('/perpustakaan', [AdminLibraryController::class, 'store'])->name('library.store');
    Route::post('/perpustakaan/pinjam', [AdminLibraryController::class, 'storeLoan'])->name('library.loan.store');
    Route::post('/perpustakaan/kembalikan/{loan}', [AdminLibraryController::class, 'returnLoan'])->name('library.loan.return');

    // Submissions & Workflows
    Route::get('/pengajuan', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::post('/pengajuan', [AdminSubmissionController::class, 'store'])->name('submissions.store');
    Route::post('/pengajuan/{submission}/review', [AdminSubmissionController::class, 'review'])->name('submissions.review');
});

// ==========================================
// 4. SUPER ADMIN PLATFORM ROUTES
// ==========================================
Route::prefix('superadmin')->middleware(['auth', 'role:SUPER_ADMIN'])->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/masjid', [SuperAdminMosqueController::class, 'index'])->name('mosques.index');
    Route::post('/masjid/{mosque}/verifikasi', [SuperAdminMosqueController::class, 'verify'])->name('mosques.verify');
    Route::post('/masjid/{mosque}/suspend', [SuperAdminMosqueController::class, 'suspend'])->name('mosques.suspend');
    Route::post('/masjid/{mosque}/switch', [SuperAdminMosqueController::class, 'switchContext'])->name('mosques.switch');
    Route::get('/audit', [SuperAdminAuditController::class, 'index'])->name('audit.index');
});
