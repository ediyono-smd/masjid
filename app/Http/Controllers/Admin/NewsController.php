<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $newsList = News::where('mosque_id', $mosque->id)
            ->with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = NewsCategory::where('mosque_id', $mosque->id)->get();

        return view('admin.news.index', compact('mosque', 'newsList', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'news_category_id' => 'nullable|exists:news_categories,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['author_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . strtolower(Str::random(4));
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        News::create($validated);

        return back()->with('success', 'Warta / Berita masjid berhasil diterbitkan.');
    }
}
