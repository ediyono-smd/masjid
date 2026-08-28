<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\DonationCampaign;
use App\Models\Event;
use App\Models\FinancialTransaction;
use App\Models\Gallery;
use App\Models\KhatibSchedule;
use App\Models\Mosque;
use App\Models\News;
use App\Services\FinanceService;
use App\Services\MosqueService;
use App\Services\PrayerScheduleService;
use Carbon\Carbon;
use Illuminate\View\View;

class MosquePortalController extends Controller
{
    public function __construct(
        protected MosqueService $mosqueService,
        protected PrayerScheduleService $prayerService,
        protected FinanceService $financeService
    ) {}

    public function show(string $slug): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $todaySchedule = $this->prayerService->getTodaySchedule($mosque);

        $upcomingEvents = Event::where('mosque_id', $mosque->id)
            ->where('status', 'UPCOMING')
            ->with('category')
            ->orderBy('start_datetime')
            ->take(3)
            ->get();

        $activeCampaigns = DonationCampaign::where('mosque_id', $mosque->id)
            ->where('status', 'ACTIVE')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $latestNews = News::where('mosque_id', $mosque->id)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $pinnedAnnouncements = Announcement::where('mosque_id', $mosque->id)
            ->where('is_active', true)
            ->where('is_pinned', true)
            ->get();

        $khatibJumat = KhatibSchedule::where('mosque_id', $mosque->id)
            ->whereDate('schedule_date', '>=', Carbon::today()->toDateString())
            ->orderBy('schedule_date')
            ->first();

        $financeSummary = $this->financeService->getBalanceSummary($mosque->id);

        $recentTransactions = FinancialTransaction::where('mosque_id', $mosque->id)
            ->where('status', 'APPROVED')
            ->with(['incomeCategory', 'expenseCategory'])
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();

        $galleries = Gallery::where('mosque_id', $mosque->id)->take(6)->get();

        return view('public.mosque_portal', compact(
            'mosque',
            'todaySchedule',
            'upcomingEvents',
            'activeCampaigns',
            'latestNews',
            'pinnedAnnouncements',
            'khatibJumat',
            'financeSummary',
            'recentTransactions',
            'galleries'
        ));
    }
}
