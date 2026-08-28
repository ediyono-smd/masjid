<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImamSchedule;
use App\Models\KhatibSchedule;
use App\Models\Khutbah;
use App\Models\PrayerSchedule;
use App\Models\PrayerSetting;
use App\Services\TenantManager;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrayerScheduleController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $setting = PrayerSetting::firstOrCreate(['mosque_id' => $mosque->id]);

        $schedules = PrayerSchedule::where('mosque_id', $mosque->id)
            ->whereDate('schedule_date', '>=', Carbon::today()->subDays(2))
            ->orderBy('schedule_date')
            ->take(14)
            ->get();

        $khatibs = KhatibSchedule::where('mosque_id', $mosque->id)
            ->orderBy('schedule_date', 'desc')
            ->take(10)
            ->get();

        $imams = ImamSchedule::where('mosque_id', $mosque->id)
            ->orderBy('schedule_date', 'desc')
            ->take(10)
            ->get();

        return view('admin.prayers.index', compact('mosque', 'setting', 'schedules', 'khatibs', 'imams'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'calculation_method' => 'required|string',
            'fajr_offset_minutes' => 'required|integer',
            'dhuhr_offset_minutes' => 'required|integer',
            'asr_offset_minutes' => 'required|integer',
            'maghrib_offset_minutes' => 'required|integer',
            'isha_offset_minutes' => 'required|integer',
        ]);

        PrayerSetting::updateOrCreate(['mosque_id' => $mosque->id], $validated);

        return back()->with('success', 'Pengaturan hisab & offset waktu shalat berhasil disimpan.');
    }

    public function storeKhatib(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'schedule_date' => 'required|date',
            'assigned_name' => 'required|string|max:255',
            'title_or_theme' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'muadzin_name' => 'nullable|string|max:255',
            'bilal_name' => 'nullable|string|max:255',
        ]);

        $validated['mosque_id'] = $mosque->id;
        KhatibSchedule::create($validated);

        return back()->with('success', 'Jadwal Khatib Jumat berhasil ditambahkan.');
    }
}
