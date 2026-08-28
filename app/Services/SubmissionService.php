<?php

namespace App\Services;

use App\Enums\SubmissionStage;
use App\Models\Submission;
use App\Models\SubmissionReview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmissionService
{
    public function createSubmission(array $data, User $applicant, string $mosqueId): Submission
    {
        $prefix = strtoupper(substr($data['category'] ?? 'SUB', 0, 3));
        $data['mosque_id'] = $mosqueId;
        $data['applicant_id'] = $applicant->id;
        $data['submission_number'] = $prefix . '-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $data['current_stage'] = SubmissionStage::SUBMITTED;

        return Submission::create($data);
    }

    public function processReview(Submission $submission, User $reviewer, string $decision, ?string $notes = null): Submission
    {
        return DB::transaction(function () use ($submission, $reviewer, $decision, $notes) {
            $stageName = 'REVIEW';
            $nextStage = $submission->current_stage;

            if ($reviewer->hasRole('OPERATOR')) {
                $stageName = 'OPERATOR';
                $nextStage = $decision === 'APPROVE' ? SubmissionStage::TREASURER_REVIEW : SubmissionStage::REJECTED;
            } elseif ($reviewer->hasRole('TREASURER')) {
                $stageName = 'TREASURER';
                $nextStage = $decision === 'APPROVE' ? SubmissionStage::CHAIRMAN_REVIEW : SubmissionStage::REJECTED;
            } elseif ($reviewer->hasRole(['CHAIRMAN', 'MOSQUE_ADMIN', 'SUPER_ADMIN'])) {
                $stageName = 'CHAIRMAN';
                $nextStage = $decision === 'APPROVE' ? SubmissionStage::APPROVED : SubmissionStage::REJECTED;
            }

            if ($decision === 'REVISION_REQUESTED') {
                $nextStage = SubmissionStage::DRAFT;
            }

            SubmissionReview::create([
                'submission_id' => $submission->id,
                'reviewer_id' => $reviewer->id,
                'stage' => $stageName,
                'decision' => $decision,
                'notes' => $notes,
                'reviewed_at' => now(),
            ]);

            $submission->update(['current_stage' => $nextStage]);

            return $submission->fresh(['reviews.reviewer', 'applicant']);
        });
    }
}
