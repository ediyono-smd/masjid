<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventRegistration;
use App\Services\MosqueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicEventController extends Controller
{
    public function __construct(protected MosqueService $mosqueService) {}

    public function index(string $slug): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $categories = EventCategory::where('mosque_id', $mosque->id)->get();
        $events = Event::where('mosque_id', $mosque->id)
            ->with('category')
            ->orderBy('start_datetime', 'asc')
            ->paginate(9);

        return view('public.events.index', compact('mosque', 'categories', 'events'));
    }

    public function show(string $slug, string $eventSlug): View
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $event = Event::where('mosque_id', $mosque->id)
            ->where('slug', $eventSlug)
            ->with('category')
            ->firstOrFail();

        return view('public.events.show', compact('mosque', 'event'));
    }

    public function register(Request $request, string $slug, string $eventSlug): RedirectResponse
    {
        $mosque = $this->mosqueService->findBySlug($slug);
        $event = Event::where('mosque_id', $mosque->id)
            ->where('slug', $eventSlug)
            ->firstOrFail();

        if (!$event->is_registration_open) {
            return back()->with('error', 'Pendaftaran untuk kajian/kegiatan ini sudah ditutup.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
        ]);

        EventRegistration::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'attendance_status' => 'REGISTERED',
        ]);

        $event->increment('registered_participants');

        return back()->with('success', 'Alhamdulillah, pendaftaran Anda berhasil!');
    }
}
