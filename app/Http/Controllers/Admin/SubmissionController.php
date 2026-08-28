<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Services\SubmissionService;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function __construct(
        protected TenantManager $tenantManager,
        protected SubmissionService $submissionService
    ) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();

        $submissions = Submission::where('mosque_id', $mosque->id)
            ->with(['applicant', 'reviews.reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.submissions.index', compact('mosque', 'submissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'proposed_amount' => 'nullable|numeric|min:0',
            'description' => 'required|string',
        ]);

        $this->submissionService->createSubmission($validated, Auth::user(), $mosque->id);

        return back()->with('success', 'Pengajuan berhasil diajukan untuk ditinjau.');
    }

    public function review(Request $request, Submission $submission): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:APPROVE,REJECT,REVISION_REQUESTED',
            'notes' => 'nullable|string',
        ]);

        $this->submissionService->processReview($submission, Auth::user(), $validated['decision'], $validated['notes'] ?? null);

        return back()->with('success', 'Keputusan review pengajuan berhasil disimpan.');
    }
}
