<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mosques', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kemenag_id', 50)->nullable()->unique();
            $table->string('name')->index();
            $table->string('slug')->unique();
            $table->string('type', 50)->default('JAMI')->index(); // RAYA, AGUNG, BESAR, JAMI, MUSHOLLA
            $table->string('status', 30)->default('PENDING')->index(); // PENDING, VERIFIED, SUSPENDED
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('address_line');
            $table->string('province', 100)->index();
            $table->string('city', 100)->index();
            $table->string('district', 100);
            $table->string('village', 100);
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('banner_url', 500)->nullable();
            $table->jsonb('bank_accounts')->nullable();
            $table->string('qris_url', 500)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('mosque_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->unique();
            $table->text('history')->nullable();
            $table->text('vision')->nullable();
            $table->jsonb('mission')->nullable();
            $table->integer('capacity')->default(0);
            $table->decimal('land_area_sqm', 10, 2)->nullable();
            $table->decimal('building_area_sqm', 10, 2)->nullable();
            $table->string('legal_status', 100)->nullable();
            $table->jsonb('social_media')->nullable();
            $table->timestamps();
        });

        Schema::create('mosque_facilities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 150);
            $table->string('category', 50)->index(); // IBADAH, SANITASI, MULTIMEDIA, AKSESIBILITAS, UMUM
            $table->integer('quantity')->default(1);
            $table->string('condition', 30)->default('EXCELLENT'); // EXCELLENT, GOOD, FAIR, POOR
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('mosque_staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('name');
            $table->string('position', 100)->index(); // Ketua, Wakil, Sekretaris, Bendahara, etc.
            $table->string('department', 100)->nullable(); // Idarah, Imarah, Ri'ayah
            $table->integer('period_start');
            $table->integer('period_end');
            $table->string('phone_number', 30)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mosque_staff');
        Schema::dropIfExists('mosque_facilities');
        Schema::dropIfExists('mosque_profiles');
        Schema::dropIfExists('mosques');
    }
};
