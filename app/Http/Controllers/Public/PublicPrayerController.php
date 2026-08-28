<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ImamSchedule;
use App\Models\KhatibSchedule;
use App\Models\PrayerSchedule;
use App\Services\MosqueService;
use App\Services\PrayerScheduleService;
use Carbon\Carbon;
use Illuminate\View\View;

class PublicPrayerController extends Controller
{
    public function __construct(
        protected MosqueService $mosqueService,
        protected PrayerScheduleService $prayerService
    ) {}

    public function index(string $slug): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $todaySchedule = $this->prayerService->getTodaySchedule($mosque);

        $monthSchedules = PrayerSchedule::where('mosque_id', $mosque->id)
            ->whereMonth('schedule_date', Carbon::now()->month)
            ->whereYear('schedule_date', Carbon::now()->year)
            ->orderBy('schedule_date')
            ->get();

        $khatibList = KhatibSchedule::where('mosque_id', $mosque->id)
            ->whereDate('schedule_date', '>=', Carbon::today()->toDateString())
            ->orderBy('schedule_date')
            ->take(4)
            ->get();

        $imamList = ImamSchedule::where('mosque_id', $mosque->id)
            ->whereDate('schedule_date', '>=', Carbon::today()->toDateString())
            ->orderBy('schedule_date')
            ->take(5)
            ->get();

        return view('public.prayers.index', compact('mosque', 'todaySchedule', 'monthSchedules', 'khatibList', 'imamList'));
    }
}
