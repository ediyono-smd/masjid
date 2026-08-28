<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialDistribution;
use App\Models\SocialProgram;
use App\Models\SocialRecipient;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SocialProgramController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();
        $programs = SocialProgram::where('mosque_id', $mosque->id)
            ->withCount('distributions')
            ->orderBy('created_at', 'desc')
            ->get();

        $recipients = SocialRecipient::where('mosque_id', $mosque->id)
            ->orderBy('full_name')
            ->paginate(15);

        return view('admin.social.index', compact('mosque', 'programs', 'recipients'));
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'budget' => 'required|numeric|min:0',
            'target_recipients_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'required|string',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . strtolower(Str::random(4));
        $validated['status'] = 'ACTIVE';

        SocialProgram::create($validated);

        return back()->with('success', 'Program sosial baru berhasil dibuat.');
    }

    public function storeRecipient(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
            'asnaf_category' => 'required|string',
            'dependents_count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['status'] = 'VERIFIED';

        SocialRecipient::create($validated);

        return back()->with('success', 'Data mustahiq penerima manfaat berhasil ditambahkan.');
    }

    public function storeDistribution(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:social_programs,id',
            'recipient_id' => 'required|exists:social_recipients,id',
            'distribution_date' => 'required|date',
            'package_description' => 'required|string',
            'nominal_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['distributed_by_id'] = Auth::id();

        SocialDistribution::create($validated);

        $program = SocialProgram::find($validated['program_id']);
        if ($program) {
            $program->increment('realized_amount', (float) $validated['nominal_value']);
            $program->increment('actual_recipients_count');
        }

        return back()->with('success', 'Penyaluran bantuan berhasil dicatat.');
    }
}
