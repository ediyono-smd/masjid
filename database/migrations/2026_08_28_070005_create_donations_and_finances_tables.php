<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('title');
            $table->string('slug');
            $table->string('category', 50)->default('INFAQ')->index(); // INFAQ, WAKAF, YATIM, RENOVASI, RAMADHAN, BENCANA
            $table->decimal('target_amount', 15, 2)->nullable();
            $table->decimal('collected_amount', 15, 2)->default(0.00);
            $table->integer('donor_count')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('cover_image_url', 500)->nullable();
            $table->text('description');
            $table->boolean('is_featured')->default(false);
            $table->string('status', 30)->default('ACTIVE')->index(); // ACTIVE, COMPLETED, PAUSED
            $table->timestamps();

            $table->unique(['mosque_id', 'slug']);
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->foreignUuid('campaign_id')->nullable()->constrained('donation_campaigns')->nullOnDelete();
            $table->uuid('donor_id')->nullable()->index();
            $table->string('donor_name');
            $table->string('donor_phone', 30)->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->decimal('amount', 15, 2);
            $table->text('doa_message')->nullable();
            $table->string('payment_method', 50)->default('QRIS'); // QRIS, BANK_TRANSFER, CASH, VA
            $table->string('payment_channel', 50)->nullable();
            $table->string('status', 30)->default('PENDING')->index(); // PENDING, PAID, VERIFIED, CANCELLED
            $table->string('verification_code', 64)->unique()->index();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->uuid('verified_by_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('donors')->nullOnDelete();
            $table->foreign('verified_by_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('donation_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('donation_id')->constrained('donations')->cascadeOnDelete()->index();
            $table->string('payment_gateway', 50)->default('MANUAL'); // MANUAL, MIDTRANS, XENDIT, TRIPAY
            $table->string('transaction_ref', 100)->nullable()->index();
            $table->decimal('amount', 15, 2);
            $table->string('proof_file_url', 500)->nullable();
            $table->jsonb('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('income_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 100);
            $table->string('code', 50)->nullable(); // e.g. INC-01, KOTAK-JUMAT
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 100);
            $table->string('code', 50)->nullable(); // e.g. EXP-01, LISTRIK-AIR, HONOR-IMAM
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('transaction_type', 20)->index(); // INCOME, EXPENSE
            $table->foreignUuid('income_category_id')->nullable()->constrained('income_categories')->nullOnDelete();
            $table->foreignUuid('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete();
            $table->foreignUuid('donation_id')->nullable()->constrained('donations')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date')->index();
            $table->string('reference_number', 100)->nullable()->index();
            $table->text('description');
            $table->string('recipient_or_payer')->nullable();
            $table->string('payment_channel', 50)->default('CASH'); // CASH, BANK_TRANSFER, QRIS
            $table->string('proof_attachment_url', 500)->nullable();
            $table->uuid('recorded_by_id')->index();
            $table->uuid('verified_by_id')->nullable()->index();
            $table->string('status', 30)->default('APPROVED')->index(); // DRAFT, PENDING_REVIEW, APPROVED, REJECTED
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('recorded_by_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('verified_by_id')->references('id')->on('users')->nullOnDelete();
            $table->index(['mosque_id', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('income_categories');
        Schema::dropIfExists('donation_payments');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('donation_campaigns');
    }
};
