<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('document_number', 100)->unique();
            $table->string('document_type', 50)->index(); // DONATION_RECEIPT, ZAKAT_RECEIPT, WAQF_RECEIPT, ACTIVITY_LETTER, RECOMMENDATION_LETTER, FINANCIAL_REPORT
            $table->string('title');
            $table->string('file_path', 500)->nullable();
            $table->uuid('issuer_id')->index();
            $table->string('verification_code', 64)->unique()->index();
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->text('revocation_reason')->nullable();
            $table->jsonb('payload_snapshot')->nullable();
            $table->timestamps();

            $table->foreign('issuer_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('qr_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('code_type', 50)->index(); // MOSQUE_PROFILE, DONATION_CAMPAIGN, DOCUMENT_VERIFY, EVENT_RSVP
            $table->string('target_url', 500);
            $table->string('token', 64)->unique()->index();
            $table->integer('scan_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('qr_scans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('qr_code_id')->constrained('qr_codes')->cascadeOnDelete()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('scanned_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_scans');
        Schema::dropIfExists('qr_codes');
        Schema::dropIfExists('documents');
    }
};
