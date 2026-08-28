<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name');
            $table->string('slug');
            $table->string('category', 50)->default('SANTUNAN'); // SANTUNAN, SEMBAKO, BEASISWA, KESEHATAN, TANGGAP_BENCANA
            $table->text('description');
            $table->decimal('budget', 15, 2)->default(0.00);
            $table->decimal('realized_amount', 15, 2)->default(0.00);
            $table->integer('target_recipients_count')->default(0);
            $table->integer('actual_recipients_count')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status', 30)->default('ACTIVE')->index(); // ACTIVE, COMPLETED, SUSPENDED
            $table->timestamps();

            $table->unique(['mosque_id', 'slug']);
        });

        Schema::create('social_recipients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('full_name');
            $table->string('nik_masked', 30)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address');
            $table->string('asnaf_category', 50)->default('MISKIN')->index(); // FAKIR, MISKIN, AMIL, MUALLAF, RIQAB, GHARIMIN, FISABILILLAH, IBNU_SABIL, YATIM, DHUAFA
            $table->integer('dependents_count')->default(0);
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('VERIFIED')->index(); // PENDING, VERIFIED, INACTIVE
            $table->timestamps();
        });

        Schema::create('social_distributions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('program_id')->index();
            $table->uuid('recipient_id')->index();
            $table->date('distribution_date')->index();
            $table->text('package_description');
            $table->decimal('nominal_value', 15, 2)->default(0.00);
            $table->string('proof_photo_url', 500)->nullable();
            $table->uuid('distributed_by_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('program_id', 'fk_soc_dist_program')->references('id')->on('social_programs')->cascadeOnDelete();
            $table->foreign('recipient_id', 'fk_soc_dist_recipient')->references('id')->on('social_recipients')->cascadeOnDelete();
            $table->foreign('distributed_by_id', 'fk_soc_dist_user')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('zakat_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('muzakki_name');
            $table->string('muzakki_phone', 30)->nullable();
            $table->string('muzakki_email')->nullable();
            $table->string('zakat_type', 50)->default('FITRAH_UANG')->index(); // FITRAH_BERAS, FITRAH_UANG, MAAL, FIDYAH, INFAQ_SHADAQAH
            $table->decimal('quantity_kg', 6, 2)->default(0.00);
            $table->decimal('amount_rupiah', 15, 2)->default(0.00);
            $table->integer('souls_count')->default(1);
            $table->date('payment_date')->index();
            $table->string('payment_method', 50)->default('CASH'); // CASH, QRIS, TRANSFER
            $table->string('verification_code', 64)->unique()->index();
            $table->uuid('received_by_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('received_by_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('waqf_donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('wakif_name');
            $table->string('wakif_phone', 30)->nullable();
            $table->string('waqf_type', 50)->default('UANG')->index(); // UANG, TANAH, BANGUNAN, KENDARAAN, PERLENGKAPAN
            $table->decimal('nominal_value', 15, 2)->default(0.00);
            $table->text('asset_description')->nullable();
            $table->string('pledge_document_url', 500)->nullable(); // Akta Ikrar Wakaf
            $table->string('verification_code', 64)->unique()->index();
            $table->date('waqf_date')->index();
            $table->uuid('received_by_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('received_by_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waqf_donations');
        Schema::dropIfExists('zakat_payments');
        Schema::dropIfExists('social_distributions');
        Schema::dropIfExists('social_recipients');
        Schema::dropIfExists('social_programs');
    }
};
