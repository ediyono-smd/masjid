<?php

namespace Database\Seeders;

use App\Enums\DocumentType;
use App\Enums\MosqueStatus;
use App\Enums\MosqueType;
use App\Enums\RoleEnum;
use App\Enums\SubmissionCategory;
use App\Enums\SubmissionStage;
use App\Enums\TransactionType;
use App\Models\Announcement;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookLoan;
use App\Models\Congregation;
use App\Models\Document;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\DonationPayment;
use App\Models\Donor;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Examination;
use App\Models\ExpenseCategory;
use App\Models\FinancialTransaction;
use App\Models\IncomeCategory;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\KhatibSchedule;
use App\Models\Khutbah;
use App\Models\MaintenanceRecord;
use App\Models\Mosque;
use App\Models\MosqueFacility;
use App\Models\MosqueProfile;
use App\Models\MosqueStaff;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\PrayerSchedule;
use App\Models\PrayerSetting;
use App\Models\QrCode;
use App\Models\SocialDistribution;
use App\Models\SocialProgram;
use App\Models\SocialRecipient;
use App\Models\Submission;
use App\Models\SubmissionReview;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerActivity;
use App\Models\WaqfDonation;
use App\Models\ZakatPayment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles and Permissions
        $roles = [
            RoleEnum::SUPER_ADMIN->value,
            RoleEnum::MOSQUE_ADMIN->value,
            RoleEnum::CHAIRMAN->value,
            RoleEnum::SECRETARY->value,
            RoleEnum::TREASURER->value,
            RoleEnum::OPERATOR->value,
            RoleEnum::IMAM->value,
            RoleEnum::KHATIB->value,
            RoleEnum::MUADZIN->value,
            RoleEnum::JAMAAH->value,
            RoleEnum::DONOR->value,
            RoleEnum::VOLUNTEER->value,
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        $permissions = [
            'manage_platform',
            'manage_mosque_profile',
            'manage_staff',
            'manage_facilities',
            'manage_prayers',
            'manage_events',
            'manage_news',
            'manage_congregations',
            'manage_donations',
            'manage_finances',
            'manage_social_programs',
            'manage_zakat',
            'manage_inventories',
            'manage_library',
            'manage_submissions',
            'approve_submissions',
            'issue_documents',
            'view_audit_logs',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        $superAdminRole = Role::findByName(RoleEnum::SUPER_ADMIN->value);
        $superAdminRole->syncPermissions(Permission::all());

        $mosqueAdminRole = Role::findByName(RoleEnum::MOSQUE_ADMIN->value);
        $mosqueAdminRole->syncPermissions(Permission::where('name', '!=', 'manage_platform')->get());

        // 2. Subscription Plans
        $planFree = SubscriptionPlan::create([
            'name' => 'FREE',
            'display_name' => 'Paket Perintis (Gratis)',
            'price_monthly' => 0.00,
            'price_yearly' => 0.00,
            'max_jamaah' => 200,
            'max_storage_mb' => 500,
            'features' => ['Profil Publik', 'Jadwal Shalat', 'Kajian & Berita', 'Donasi QRIS'],
            'is_active' => true,
        ]);

        $planPro = SubscriptionPlan::create([
            'name' => 'PRO',
            'display_name' => 'Paket Profesional',
            'price_monthly' => 150000.00,
            'price_yearly' => 1500000.00,
            'max_jamaah' => 5000,
            'max_storage_mb' => 10000,
            'features' => ['Semua Fitur Free', 'Buku Kas Lengkap', 'QR Verification Digital', 'Laporan PDF Otomatis', 'Manajemen Inventaris'],
            'is_active' => true,
        ]);

        // 3. Super Admin User
        $superAdmin = User::create([
            'name' => 'Super Administrator Platform',
            'email' => 'superadmin@masjidindonesia.id',
            'phone_number' => '081122334455',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $superAdmin->assignRole(RoleEnum::SUPER_ADMIN->value);

        // 4. Five Realistic Mosques Across Indonesia
        $mosquesData = [
            [
                'name' => 'Masjid Raya Al-Jabbar',
                'slug' => 'al-jabbar-bandung',
                'type' => MosqueType::RAYA,
                'status' => MosqueStatus::VERIFIED,
                'kemenag_id' => '01.1.32.73.01.00001',
                'email' => 'sekretariat@al-jabbar.id',
                'phone' => '022-7801234',
                'address_line' => 'Jl. Cimincrang No.14, Cimenerang, Kec. Gedebage',
                'province' => 'Jawa Barat',
                'city' => 'Kota Bandung',
                'district' => 'Gedebage',
                'village' => 'Cimenerang',
                'postal_code' => '40294',
                'latitude' => -6.947942,
                'longitude' => 107.703774,
                'verified_at' => now()->subMonths(10),
            ],
            [
                'name' => 'Masjid Agung Surakarta',
                'slug' => 'masjid-agung-surakarta',
                'type' => MosqueType::AGUNG,
                'status' => MosqueStatus::VERIFIED,
                'kemenag_id' => '01.2.33.72.01.00002',
                'email' => 'takmir@masjidagungsurakarta.id',
                'phone' => '0271-654321',
                'address_line' => 'Jl. Masjid Agung No. 1, Kauman, Kec. Pasar Kliwon',
                'province' => 'Jawa Tengah',
                'city' => 'Kota Surakarta',
                'district' => 'Pasar Kliwon',
                'village' => 'Kauman',
                'postal_code' => '57112',
                'latitude' => -7.575278,
                'longitude' => 110.828611,
                'verified_at' => now()->subMonths(8),
            ],
            [
                'name' => 'Masjid Jami\' Al-Ikhlas Samarinda',
                'slug' => 'al-ikhlas-samarinda',
                'type' => MosqueType::JAMI,
                'status' => MosqueStatus::VERIFIED,
                'kemenag_id' => '01.3.64.72.02.00003',
                'email' => 'info@alikhlas-samarinda.org',
                'phone' => '0541-741234',
                'address_line' => 'Jl. Pahlawan No. 45, Dadi Mulya, Kec. Samarinda Ulu',
                'province' => 'Kalimantan Timur',
                'city' => 'Kota Samarinda',
                'district' => 'Samarinda Ulu',
                'village' => 'Dadi Mulya',
                'postal_code' => '75123',
                'latitude' => -0.494823,
                'longitude' => 117.143615,
                'verified_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Masjid Al-Falah Surabaya',
                'slug' => 'al-falah-surabaya',
                'type' => MosqueType::BESAR,
                'status' => MosqueStatus::VERIFIED,
                'kemenag_id' => '01.2.35.78.03.00004',
                'email' => 'info@alfalahsurabaya.id',
                'phone' => '031-567890',
                'address_line' => 'Jl. Raya Darmo No. 137A, Darmo, Kec. Wonokromo',
                'province' => 'Jawa Timur',
                'city' => 'Kota Surabaya',
                'district' => 'Wonokromo',
                'village' => 'Darmo',
                'postal_code' => '60241',
                'latitude' => -7.291389,
                'longitude' => 112.736944,
                'verified_at' => now()->subMonths(4),
            ],
            [
                'name' => 'Masjid Cut Meutia Menteng',
                'slug' => 'cut-meutia-jakarta',
                'type' => MosqueType::JAMI,
                'status' => MosqueStatus::VERIFIED,
                'kemenag_id' => '01.3.31.71.01.00005',
                'email' => 'sekretariat@masjidcutmeutia.com',
                'phone' => '021-310456',
                'address_line' => 'Jl. Taman Cut Mutiah No. 1, Menteng',
                'province' => 'DKI Jakarta',
                'city' => 'Kota Jakarta Pusat',
                'district' => 'Menteng',
                'village' => 'Kebon Sirih',
                'postal_code' => '10340',
                'latitude' => -6.187222,
                'longitude' => 106.833333,
                'verified_at' => now()->subMonths(2),
            ],
        ];

        $createdMosques = [];

        foreach ($mosquesData as $index => $mData) {
            $mosque = Mosque::create($mData);
            $createdMosques[] = $mosque;

            // Subscription
            Subscription::create([
                'mosque_id' => $mosque->id,
                'plan_id' => $index === 0 ? $planPro->id : $planFree->id,
                'status' => 'ACTIVE',
                'starts_at' => now()->subMonths(3),
                'ends_at' => now()->addMonths(9),
                'auto_renew' => true,
            ]);

            // Profile
            MosqueProfile::create([
                'mosque_id' => $mosque->id,
                'history' => 'Didirikan sebagai pusat peradaban, ibadah berjamaah, dan pemberdayaan ekonomi umat.',
                'vision' => 'Menjadi masjid yang makmur, mandiri, dan mencerahkan peradaban umat.',
                'mission' => [
                    'Menyelenggarakan ibadah shalat berjamaah yang tertib dan khusyuk.',
                    'Mengembangkan program dakwah, pendidikan Al-Qur\'an, dan kajian keislaman kontemporer.',
                    'Memberdayakan ekonomi dhuafa dan mustahiq berbasis dana ZISWAF yang transparan.',
                ],
                'capacity' => 1500 + ($index * 2000),
                'land_area_sqm' => 2500.00 + ($index * 1500.00),
                'building_area_sqm' => 1800.00 + ($index * 1200.00),
                'legal_status' => 'Tanah Wakaf Bersertifikat BPN',
                'social_media' => [
                    'instagram' => '@' . $mosque->slug,
                    'youtube' => 'Masjid ' . $mosque->name,
                ],
            ]);

            // Facilities
            MosqueFacility::create([
                'mosque_id' => $mosque->id,
                'name' => 'Ruang Shalat Utama Ber-AC',
                'category' => 'IBADAH',
                'quantity' => 1,
                'condition' => 'EXCELLENT',
                'description' => 'Dilengkapi karpet tebal permadani Turki dan pendingin udara sentral.',
                'icon' => 'air-vent',
            ]);

            MosqueFacility::create([
                'mosque_id' => $mosque->id,
                'name' => 'Tempat Wudhu & Toilet Ramah Difabel',
                'category' => 'SANITASI',
                'quantity' => 24,
                'condition' => 'EXCELLENT',
                'description' => 'Fasilitas wudhu bersih dengan jalur khusus kursi roda.',
                'icon' => 'droplets',
            ]);

            MosqueFacility::create([
                'mosque_id' => $mosque->id,
                'name' => 'Sound System & Live Streaming Digital',
                'category' => 'MULTIMEDIA',
                'quantity' => 1,
                'condition' => 'EXCELLENT',
                'description' => 'Mixer digital 32 channel dan kamera PTZ 4K untuk siaran langsung.',
                'icon' => 'mic',
            ]);

            // Prayer Settings
            PrayerSetting::create([
                'mosque_id' => $mosque->id,
                'calculation_method' => 'KEMENAG',
                'fajr_angle' => 20.00,
                'isha_angle' => 18.00,
                'fajr_offset_minutes' => 2,
                'dhuhr_offset_minutes' => 2,
                'asr_offset_minutes' => 2,
                'maghrib_offset_minutes' => 2,
                'isha_offset_minutes' => 2,
                'iqamah_delay_minutes' => [
                    'fajr' => 15,
                    'dhuhr' => 10,
                    'asr' => 10,
                    'maghrib' => 7,
                    'isha' => 10,
                ],
            ]);

            // Prayer Schedules (Today and Next 7 Days)
            for ($d = -2; $d <= 5; $d++) {
                $date = Carbon::today()->addDays($d);
                PrayerSchedule::create([
                    'mosque_id' => $mosque->id,
                    'schedule_date' => $date->toDateString(),
                    'imsak' => '04:18:00',
                    'fajr' => '04:28:00',
                    'sunrise' => '05:44:00',
                    'dhuhr' => '11:47:00',
                    'asr' => '15:06:00',
                    'maghrib' => '17:48:00',
                    'isha' => '18:58:00',
                ]);
            }

            // Categories
            $catKajian = EventCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Kajian Rutin & Tematik',
                'slug' => 'kajian-rutin',
                'color_code' => '#047857',
            ]);

            $catPHBI = EventCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Peringatan Hari Besar Islam (PHBI)',
                'slug' => 'phbi',
                'color_code' => '#D4AF37',
            ]);

            $catNews = NewsCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Warta & Kegiatan Masjid',
                'slug' => 'warta-kegiatan',
            ]);

            $incInfaq = IncomeCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Infaq Kotak Amal Jumat & QRIS',
                'code' => 'INC-INFAQ',
            ]);

            $incZakat = IncomeCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Penerimaan Zakat Fitrah & Maal',
                'code' => 'INC-ZAKAT',
            ]);

            $expOps = ExpenseCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Operasional Listrik, Air & Kebersihan',
                'code' => 'EXP-OPS',
            ]);

            $expHonor = ExpenseCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Honorarium Imam, Khatib & Marbot',
                'code' => 'EXP-HONOR',
            ]);

            $invCat = InventoryCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Peralatan Elektronik & Audio',
                'code' => 'INV-ELEC',
            ]);

            $bookCat = BookCategory::create([
                'mosque_id' => $mosque->id,
                'name' => 'Fiqih & Ushul Fiqih',
                'code' => 'BK-FIQIH',
            ]);
        }

        // 5. Create Staff and Operational Users for Mosque #1 (Al-Jabbar) and Mosque #2 (Surakarta)
        $primaryMosque = $createdMosques[0]; // Al-Jabbar

        // Admin Masjid
        $adminUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Ustadz Muhammad Arifin, S.Pd.I',
            'email' => 'admin@al-jabbar.id',
            'phone_number' => '081234567890',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $adminUser->assignRole(RoleEnum::MOSQUE_ADMIN->value);

        // Chairman (Ketua Takmir)
        $chairmanUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Drs. KH. Ahmad Fauzi, M.Ag',
            'email' => 'ketua@al-jabbar.id',
            'phone_number' => '081298765432',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $chairmanUser->assignRole(RoleEnum::CHAIRMAN->value);

        // Treasurer (Bendahara)
        $treasurerUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'H. Bambang Sutrisno, SE, Ak',
            'email' => 'bendahara@al-jabbar.id',
            'phone_number' => '081345678901',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $treasurerUser->assignRole(RoleEnum::TREASURER->value);

        // Secretary
        $secretaryUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Ahmad Rabbani, S.Kom',
            'email' => 'sekretaris@al-jabbar.id',
            'phone_number' => '081356789012',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $secretaryUser->assignRole(RoleEnum::SECRETARY->value);

        // Operator
        $operatorUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Fajar Pratama (Multimedia)',
            'email' => 'operator@al-jabbar.id',
            'phone_number' => '081367890123',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $operatorUser->assignRole(RoleEnum::OPERATOR->value);

        // Imam Rawatib
        $imamUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Ustadz Syakir Al-Hafizh',
            'email' => 'imam@al-jabbar.id',
            'phone_number' => '081378901234',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $imamUser->assignRole(RoleEnum::IMAM->value);

        // Khatib
        $khatibUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Prof. Dr. KH. Abdullah Gymnastiar',
            'email' => 'khatib@al-jabbar.id',
            'phone_number' => '081389012345',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $khatibUser->assignRole(RoleEnum::KHATIB->value);

        // Donatur
        $donorUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'H. Hendra Wijaya',
            'email' => 'donatur@gmail.com',
            'phone_number' => '081198765432',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $donorUser->assignRole(RoleEnum::DONOR->value);

        // Jamaah
        $jamaahUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Budi Santoso',
            'email' => 'jamaah@gmail.com',
            'phone_number' => '081211223344',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $jamaahUser->assignRole(RoleEnum::JAMAAH->value);

        // Relawan
        $volunteerUser = User::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Rizky Ramadhan',
            'email' => 'relawan@al-jabbar.id',
            'phone_number' => '081233445566',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $volunteerUser->assignRole(RoleEnum::VOLUNTEER->value);

        // Staff Profiles
        MosqueStaff::create([
            'mosque_id' => $primaryMosque->id,
            'user_id' => $chairmanUser->id,
            'name' => $chairmanUser->name,
            'position' => 'Ketua Takmir',
            'department' => 'Idarah (Manajemen)',
            'period_start' => 2024,
            'period_end' => 2029,
            'phone_number' => $chairmanUser->phone_number,
        ]);

        MosqueStaff::create([
            'mosque_id' => $primaryMosque->id,
            'user_id' => $treasurerUser->id,
            'name' => $treasurerUser->name,
            'position' => 'Bendahara Kas',
            'department' => 'Keuangan & Aset',
            'period_start' => 2024,
            'period_end' => 2029,
            'phone_number' => $treasurerUser->phone_number,
        ]);

        MosqueStaff::create([
            'mosque_id' => $primaryMosque->id,
            'user_id' => $secretaryUser->id,
            'name' => $secretaryUser->name,
            'position' => 'Sekretaris Takmir',
            'department' => 'Administrasi & Humas',
            'period_start' => 2024,
            'period_end' => 2029,
            'phone_number' => $secretaryUser->phone_number,
        ]);

        // 6. Events / Kajian
        $eventCategory = EventCategory::where('mosque_id', $primaryMosque->id)->first();
        Event::create([
            'mosque_id' => $primaryMosque->id,
            'event_category_id' => $eventCategory->id,
            'title' => 'Kajian Riyadhus Shalihin: Membangun Kejujuran dalam Muamalah',
            'slug' => 'kajian-riyadhus-shalihin-kejujuran',
            'speaker_name' => 'Ustadz Dr. Firanda Andirja, Lc., M.A.',
            'speaker_title' => 'Pakar Fiqih & Tafsir Islam',
            'start_datetime' => Carbon::now()->addDays(2)->setHour(18)->setMinute(30),
            'end_datetime' => Carbon::now()->addDays(2)->setHour(20)->setMinute(30),
            'location' => 'Ruang Utama Masjid Raya Al-Jabbar',
            'description' => 'Pembahasan mendalam bab kejujuran dalam perdagangan dan kehidupan sehari-hari berdasar kitab Riyadhus Shalihin.',
            'is_registration_open' => true,
            'status' => 'UPCOMING',
        ]);

        Event::create([
            'mosque_id' => $primaryMosque->id,
            'event_category_id' => $eventCategory->id,
            'title' => 'Tabligh Akbar Menyambut Bulan Suci Ramadhan 1448 H',
            'slug' => 'tabligh-akbar-ramadhan',
            'speaker_name' => 'Ustadz Abdul Somad, Lc., D.E.S.A., Ph.D.',
            'speaker_title' => 'Dai Nasional & Ulama Hadits',
            'start_datetime' => Carbon::now()->addDays(10)->setHour(8)->setMinute(30),
            'end_datetime' => Carbon::now()->addDays(10)->setHour(11)->setMinute(45),
            'location' => 'Plaza Utama Masjid Al-Jabbar',
            'description' => 'Persiapan ruhiyah dan amaliyah menyambut bulan mulia Ramadhan.',
            'is_registration_open' => true,
            'status' => 'UPCOMING',
        ]);

        // 7. News & Announcements
        $newsCat = NewsCategory::where('mosque_id', $primaryMosque->id)->first();
        News::create([
            'mosque_id' => $primaryMosque->id,
            'news_category_id' => $newsCat->id,
            'title' => 'Penyaluran Bantuan Sembako Berkah untuk 250 Keluarga Dhuafa',
            'slug' => 'penyaluran-sembako-berkah-250-keluarga',
            'summary' => 'Takmir Masjid sukses menyalurkan paket sembako senilai total Rp50 juta kepada mustahiq sekitar lingkungan masjid.',
            'content' => 'Alhamdulillah, berkat infaq dan sedekah dari para donatur dan jamaah, Masjid Al-Jabbar telah menyalurkan 250 paket sembako lengkap berisikan beras 5kg, minyak goreng, gula, dan kebutuhan pokok lainnya.',
            'author_id' => $adminUser->id,
            'is_published' => true,
            'published_at' => now()->subDays(3),
            'views_count' => 342,
        ]);

        Announcement::create([
            'mosque_id' => $primaryMosque->id,
            'title' => 'Pemberitahuan: Pelaksanaan Shalat Gerhana Bulan (Khusuf)',
            'body' => 'Insya Allah shalat gerhana bulan akan dilaksanakan secara berjamaah pada malam Ahad, 15 Safar ba\'da shalat Isya dilanjutkan dengan khutbah gerhana.',
            'priority' => 'HIGH',
            'is_pinned' => true,
            'start_date' => now()->subDays(1)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'is_active' => true,
        ]);

        // 8. Donation Campaigns & Donations with Verification Tokens
        $campaignMenara = DonationCampaign::create([
            'mosque_id' => $primaryMosque->id,
            'title' => 'Sedekah Jariyah: Pembangunan Fasilitas Air Bersih & Wudhu',
            'slug' => 'fasilitas-air-bersih-wudhu',
            'category' => 'WAKAF',
            'target_amount' => 75000000.00,
            'collected_amount' => 48500000.00,
            'donor_count' => 86,
            'start_date' => now()->subMonths(1)->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'description' => 'Program pengadaan filter air RO modern dan penambahan 20 kran wudhu hemat air untuk kenyamanan jamaah.',
            'is_featured' => true,
            'status' => 'ACTIVE',
        ]);

        $verifCode1 = 'DON-' . strtoupper(Str::random(12));
        $donation1 = Donation::create([
            'mosque_id' => $primaryMosque->id,
            'campaign_id' => $campaignMenara->id,
            'donor_name' => 'Bpk. Ahmad Suhendra',
            'donor_phone' => '081234567899',
            'donor_email' => 'suhendra@example.com',
            'is_anonymous' => false,
            'amount' => 1500000.00,
            'doa_message' => 'Semoga berkah untuk keluarga kami dan menjadi amal jariyah almarhum orang tua.',
            'payment_method' => 'QRIS',
            'payment_channel' => 'BCA QRIS',
            'status' => 'VERIFIED',
            'verification_code' => $verifCode1,
            'paid_at' => now()->subDays(2),
            'verified_at' => now()->subDays(2),
            'verified_by_id' => $treasurerUser->id,
        ]);

        DonationPayment::create([
            'donation_id' => $donation1->id,
            'payment_gateway' => 'QRIS_STATIC',
            'transaction_ref' => 'TRX-' . strtoupper(Str::random(10)),
            'amount' => 1500000.00,
            'paid_at' => now()->subDays(2),
        ]);

        // Digital Document for Donation Receipt
        Document::create([
            'mosque_id' => $primaryMosque->id,
            'document_number' => 'KWIT/2026/08/0012',
            'document_type' => DocumentType::DONATION_RECEIPT,
            'title' => 'e-Kwitansi Donasi Resmi Fasilitas Air Bersih',
            'issuer_id' => $treasurerUser->id,
            'verification_code' => $verifCode1,
            'issued_at' => now()->subDays(2),
            'payload_snapshot' => [
                'donor_name' => 'Bpk. Ahmad Suhendra',
                'amount' => 1500000.00,
                'amount_words' => 'Satu Juta Lima Ratus Ribu Rupiah',
                'campaign_title' => $campaignMenara->title,
                'mosque_name' => $primaryMosque->name,
                'status' => 'VERIFIED',
            ],
        ]);

        QrCode::create([
            'mosque_id' => $primaryMosque->id,
            'code_type' => 'DOCUMENT_VERIFY',
            'target_url' => '/verify/' . $verifCode1,
            'token' => $verifCode1,
            'scan_count' => 5,
            'last_scanned_at' => now()->subHours(4),
        ]);

        // 9. Financial Transactions (Kas Masjid)
        $incCat = IncomeCategory::where('mosque_id', $primaryMosque->id)->first();
        $expCat = ExpenseCategory::where('mosque_id', $primaryMosque->id)->first();

        FinancialTransaction::create([
            'mosque_id' => $primaryMosque->id,
            'transaction_type' => TransactionType::INCOME,
            'income_category_id' => $incCat->id,
            'donation_id' => $donation1->id,
            'amount' => 1500000.00,
            'transaction_date' => now()->subDays(2)->toDateString(),
            'reference_number' => 'KAS-IN-202608-01',
            'description' => 'Penerimaan Infaq Fasilitas Air Bersih a.n Bpk. Ahmad Suhendra',
            'recipient_or_payer' => 'Bpk. Ahmad Suhendra',
            'payment_channel' => 'QRIS',
            'recorded_by_id' => $treasurerUser->id,
            'status' => 'APPROVED',
        ]);

        FinancialTransaction::create([
            'mosque_id' => $primaryMosque->id,
            'transaction_type' => TransactionType::INCOME,
            'income_category_id' => $incCat->id,
            'amount' => 12450000.00,
            'transaction_date' => now()->subDays(5)->toDateString(),
            'reference_number' => 'KAS-IN-202608-02',
            'description' => 'Perolehan Tromol Kotak Amal Shalat Jumat Pekan Lalu',
            'recipient_or_payer' => 'Kotak Infaq Jamaah Jumat',
            'payment_channel' => 'CASH',
            'recorded_by_id' => $treasurerUser->id,
            'status' => 'APPROVED',
        ]);

        FinancialTransaction::create([
            'mosque_id' => $primaryMosque->id,
            'transaction_type' => TransactionType::EXPENSE,
            'expense_category_id' => $expCat->id,
            'amount' => 3850000.00,
            'transaction_date' => now()->subDays(4)->toDateString(),
            'reference_number' => 'KAS-OUT-202608-01',
            'description' => 'Pembayaran Tagihan Listrik PLN & Air PDAM Bulan Ini',
            'recipient_or_payer' => 'PLN & PDAM Kota Bandung',
            'payment_channel' => 'BANK_TRANSFER',
            'recorded_by_id' => $treasurerUser->id,
            'status' => 'APPROVED',
        ]);

        // 10. Social Program & ZISWAF
        $socialProg = SocialProgram::create([
            'mosque_id' => $primaryMosque->id,
            'name' => 'Program Beasiswa Santri Tahfizh Dhuafa 2026',
            'slug' => 'beasiswa-santri-tahfizh-2026',
            'category' => 'BEASISWA',
            'description' => 'Bantuan biaya SPP dan kitab bagi santri tahfizh berprestasi dari keluarga prasejahtera.',
            'budget' => 20000000.00,
            'realized_amount' => 12000000.00,
            'target_recipients_count' => 20,
            'actual_recipients_count' => 12,
            'start_date' => now()->subMonths(2)->toDateString(),
            'status' => 'ACTIVE',
        ]);

        $recipient1 = SocialRecipient::create([
            'mosque_id' => $primaryMosque->id,
            'full_name' => 'Ibu Siti Aminah',
            'nik_masked' => '3273************',
            'phone' => '085712345678',
            'address' => 'RT 04 / RW 02 Kel. Cimenerang',
            'asnaf_category' => 'FAKIR',
            'dependents_count' => 3,
            'notes' => 'Janda dhuafa dengan 3 anak usia sekolah',
            'status' => 'VERIFIED',
        ]);

        SocialDistribution::create([
            'program_id' => $socialProg->id,
            'recipient_id' => $recipient1->id,
            'distribution_date' => now()->subDays(10)->toDateString(),
            'package_description' => 'Penyaluran Bantuan Pendidikan Tahap 1 + Paket Sembako',
            'nominal_value' => 1000000.00,
            'distributed_by_id' => $volunteerUser->id,
        ]);

        // 11. Zakat & Waqf
        $zakatVerifCode = 'ZKT-' . strtoupper(Str::random(12));
        ZakatPayment::create([
            'mosque_id' => $primaryMosque->id,
            'muzakki_name' => 'H. Hendra Wijaya',
            'muzakki_phone' => '081198765432',
            'muzakki_email' => 'donatur@gmail.com',
            'zakat_type' => 'MAAL',
            'amount_rupiah' => 5000000.00,
            'souls_count' => 1,
            'payment_date' => now()->subDays(7)->toDateString(),
            'payment_method' => 'TRANSFER',
            'verification_code' => $zakatVerifCode,
            'received_by_id' => $treasurerUser->id,
            'notes' => 'Zakat perniagaan tahunan',
        ]);

        Document::create([
            'mosque_id' => $primaryMosque->id,
            'document_number' => 'ZIS/2026/08/0005',
            'document_type' => DocumentType::ZAKAT_RECEIPT,
            'title' => 'Bukti Penerimaan Zakat Maal Resmi',
            'issuer_id' => $treasurerUser->id,
            'verification_code' => $zakatVerifCode,
            'issued_at' => now()->subDays(7),
            'payload_snapshot' => [
                'muzakki_name' => 'H. Hendra Wijaya',
                'zakat_type' => 'Zakat Maal (Perniagaan)',
                'amount' => 5000000.00,
                'mosque_name' => $primaryMosque->name,
                'status' => 'VERIFIED',
            ],
        ]);

        // 12. Inventaris & Perpustakaan
        $invItem1 = Inventory::create([
            'mosque_id' => $primaryMosque->id,
            'category_id' => InventoryCategory::where('mosque_id', $primaryMosque->id)->first()->id,
            'item_code' => 'INV-ELEC-001',
            'name' => 'Wireless Microphone Shure Beta 58A (Set 4 Mic)',
            'quantity' => 4,
            'unit' => 'Set',
            'acquisition_date' => now()->subMonths(6)->toDateString(),
            'acquisition_source' => 'PURCHASE',
            'acquisition_cost' => 8500000.00,
            'room_location' => 'Ruang Audio & Mihrab',
            'condition' => 'GOOD',
            'notes' => 'Digunakan khusus untuk Imam dan Khatib Jumat',
        ]);

        MaintenanceRecord::create([
            'inventory_id' => $invItem1->id,
            'maintenance_date' => now()->subMonth()->toDateString(),
            'issue_description' => 'Pembersihan kapsul mic dan penggantian receiver antenna',
            'action_taken' => 'Replaced antenna cable and serviced by ProAudio Bandung',
            'vendor_name' => 'ProAudio Bandung',
            'cost' => 350000.00,
            'status' => 'COMPLETED',
            'recorded_by_id' => $operatorUser->id,
        ]);

        $book1 = Book::create([
            'mosque_id' => $primaryMosque->id,
            'category_id' => BookCategory::where('mosque_id', $primaryMosque->id)->first()->id,
            'book_code' => 'BK-001',
            'title' => 'Al-Mughni Karya Ibnu Qudamah (Jilid 1 - 10)',
            'author' => 'Imam Ibnu Qudamah Al-Maqdisi',
            'publisher' => 'Darul Kutub Al-Ilmiyyah Beirut',
            'year_published' => 2020,
            'language' => 'Arab',
            'copies_total' => 3,
            'copies_available' => 2,
            'shelf_location' => 'Rak A1 - Perpustakaan Lantai 2',
        ]);

        BookLoan::create([
            'book_id' => $book1->id,
            'borrower_name' => 'Ust. Syakir Al-Hafizh',
            'borrower_phone' => '081378901234',
            'loan_date' => now()->subDays(5)->toDateString(),
            'due_date' => now()->addDays(9)->toDateString(),
            'status' => 'BORROWED',
            'notes' => 'Peminjaman untuk rujukan materi kultum Subuh',
            'processed_by_id' => $operatorUser->id,
        ]);

        // 13. Submission / Approval Workflow
        $sub1 = Submission::create([
            'mosque_id' => $primaryMosque->id,
            'submission_number' => 'AJ-SUB-2026-004',
            'category' => SubmissionCategory::PEMBELIAN,
            'title' => 'Pengadaan Vacuum Cleaner Heavy Duty Karpet Masjid',
            'proposed_amount' => 4500000.00,
            'description' => 'Dibutuhkan 1 unit vacuum cleaner industri kapasitas 60 Liter untuk menjaga kebersihan karpet ruang shalat utama.',
            'applicant_id' => $operatorUser->id,
            'current_stage' => SubmissionStage::APPROVED,
        ]);

        SubmissionReview::create([
            'submission_id' => $sub1->id,
            'reviewer_id' => $treasurerUser->id,
            'stage' => 'TREASURER',
            'decision' => 'APPROVE',
            'notes' => 'Anggaran kas operasional mencukupi untuk pembelian alat ini.',
            'reviewed_at' => now()->subDays(1),
        ]);

        SubmissionReview::create([
            'submission_id' => $sub1->id,
            'reviewer_id' => $chairmanUser->id,
            'stage' => 'CHAIRMAN',
            'decision' => 'APPROVE',
            'notes' => 'Disetujui. Silakan dibeli melalui vendor terpercaya.',
            'reviewed_at' => now(),
        ]);

        // Khatib Schedule
        KhatibSchedule::create([
            'mosque_id' => $primaryMosque->id,
            'schedule_date' => Carbon::now()->next(Carbon::FRIDAY)->toDateString(),
            'assigned_name' => 'Prof. Dr. KH. Abdullah Gymnastiar',
            'title_or_theme' => 'Kunci Hati yang Selamat di Era Digital',
            'phone' => '081389012345',
            'muadzin_name' => 'Bpk. Bilal Hasan',
            'bilal_name' => 'Bpk. Bilal Hasan',
            'user_id' => $khatibUser->id,
            'status' => 'CONFIRMED',
        ]);

        // Khutbah bank
        Khutbah::create([
            'mosque_id' => $primaryMosque->id,
            'title' => 'Menjaga Amanah dan Transparansi dalam Berjamaah',
            'preacher_name' => 'Drs. KH. Ahmad Fauzi, M.Ag',
            'delivery_date' => now()->subDays(7)->toDateString(),
            'theme' => 'Muamalah & Kepemimpinan',
            'summary' => 'Khutbah tentang pentingnya amanah kepengurusan dan transparansi umat berlandaskan QS An-Nisa ayat 58.',
            'content' => 'Sesungguhnya Allah menyuruh kamu menyampaikan amanat kepada yang berhak menerimanya, dan apabila menetapkan hukum di antara manusia supaya kamu menetapkan dengan adil...',
            'is_published' => true,
        ]);
    }
}
