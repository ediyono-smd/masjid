<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('submission_number', 100)->unique();
            $table->string('category', 50)->index(); // KEGIATAN, DANA, PEMBELIAN, SOSIAL, MAINTENANCE
            $table->string('title');
            $table->decimal('proposed_amount', 15, 2)->nullable();
            $table->text('description');
            $table->string('attachment_url', 500)->nullable();
            $table->uuid('applicant_id')->index();
            $table->string('current_stage', 50)->default('DRAFT')->index(); // DRAFT, SUBMITTED, OPERATOR_REVIEW, TREASURER_REVIEW, CHAIRMAN_REVIEW, APPROVED, REJECTED, COMPLETED
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('applicant_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('submission_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('submission_id')->constrained('submissions')->cascadeOnDelete()->index();
            $table->uuid('reviewer_id')->index();
            $table->string('stage', 50); // OPERATOR, TREASURER, CHAIRMAN
            $table->string('decision', 30); // APPROVE, REJECT, REVISION_REQUESTED
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();

            $table->foreign('reviewer_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('examinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('examination_type', 50); // KEUANGAN, INVENTARIS, PROGRAM
            $table->string('title');
            $table->date('examination_date')->index();
            $table->uuid('examiner_id')->index();
            $table->jsonb('checklist_items'); // [{"item": "Fisik Kas", "status": "PASS", "notes": "Sesuai"}]
            $table->string('overall_result', 30)->default('PASS'); // PASS, NEEDS_CORRECTION, FAILED
            $table->text('recommendations')->nullable();
            $table->timestamps();

            $table->foreign('examiner_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examinations');
        Schema::dropIfExists('submission_reviews');
        Schema::dropIfExists('submissions');
    }
};
