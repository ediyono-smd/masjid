<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $events = Event::where('mosque_id', $mosque->id)
            ->with(['category', 'registrations'])
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);

        $categories = EventCategory::where('mosque_id', $mosque->id)->get();

        return view('admin.events.index', compact('mosque', 'events', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'event_category_id' => 'nullable|exists:event_categories,id',
            'title' => 'required|string|max:255',
            'speaker_name' => 'nullable|string|max:255',
            'speaker_title' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'max_participants' => 'nullable|integer|min:1',
            'is_registration_open' => 'nullable|boolean',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['slug'] = Str::slug($validated['title']) . '-' . strtolower(Str::random(4));
        $validated['is_registration_open'] = $request->boolean('is_registration_open');

        Event::create($validated);

        return back()->with('success', 'Agenda kajian / kegiatan berhasil dipublikasikan.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }
}
