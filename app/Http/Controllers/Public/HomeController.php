<?php

namespace App\Http\Controllers\Public;

use App\Enums\MosqueStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Mosque;
use App\Models\News;
use App\Services\MosqueService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(protected MosqueService $mosqueService) {}

    public function index(Request $request): View
    {
        $search = $request->query('q');
        $province = $request->query('province');

        $mosques = $this->mosqueService->getVerifiedMosques(6, $search, $province);

        $stats = \Illuminate\Support\Facades\Cache::remember('home_stats', 300, function () {
            return [
                'total_mosques' => Mosque::where('status', MosqueStatus::VERIFIED)->count(),
                'total_events' => Event::where('status', 'UPCOMING')->count(),
                'total_donations' => (float) Donation::where('status', 'VERIFIED')->sum('amount'),
                'total_provinces' => Mosque::where('status', MosqueStatus::VERIFIED)->distinct('province')->count('province'),
            ];
        });

        $featuredEvents = Event::where('status', 'UPCOMING')
            ->with(['mosque', 'category'])
            ->orderBy('start_datetime')
            ->take(3)
            ->get();

        $latestNews = News::where('is_published', true)
            ->with(['mosque', 'category'])
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        $provinces = Mosque::where('status', MosqueStatus::VERIFIED)
            ->whereNotNull('province')
            ->distinct()
            ->pluck('province')
            ->filter()
            ->sort()
            ->values()
            ->all();

        return view('public.home', compact('mosques', 'stats', 'featuredEvents', 'latestNews', 'provinces', 'search', 'province'));
    }
}
