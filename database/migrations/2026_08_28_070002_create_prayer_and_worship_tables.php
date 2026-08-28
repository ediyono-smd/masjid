<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->unique();
            $table->string('calculation_method', 50)->default('KEMENAG'); // KEMENAG, MWL, ISNA, EGYPT
            $table->decimal('fajr_angle', 5, 2)->default(20.00);
            $table->decimal('isha_angle', 5, 2)->default(18.00);
            $table->integer('fajr_offset_minutes')->default(2);
            $table->integer('dhuhr_offset_minutes')->default(2);
            $table->integer('asr_offset_minutes')->default(2);
            $table->integer('maghrib_offset_minutes')->default(2);
            $table->integer('isha_offset_minutes')->default(2);
            $table->jsonb('iqamah_delay_minutes')->nullable(); // e.g. {"fajr": 15, "dhuhr": 10, "asr": 10, "maghrib": 7, "isha": 10}
            $table->timestamps();
        });

        Schema::create('prayer_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->date('schedule_date')->index();
            $table->time('imsak');
            $table->time('fajr');
            $table->time('sunrise');
            $table->time('dhuhr');
            $table->time('asr');
            $table->time('maghrib');
            $table->time('isha');
            $table->timestamps();

            $table->unique(['mosque_id', 'schedule_date']);
        });

        Schema::create('imam_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->date('schedule_date')->index();
            $table->string('prayer_name', 30); // FAJR, DHUHR, ASR, MAGHRIB, ISHA, TARAWIH, EID
            $table->string('assigned_name');
            $table->string('phone', 30)->nullable();
            $table->uuid('user_id')->nullable()->index();
            $table->string('status', 30)->default('CONFIRMED'); // CONFIRMED, PENDING, REPLACED
            $table->string('substitute_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('khatib_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->date('schedule_date')->index();
            $table->string('assigned_name');
            $table->string('title_or_theme')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('muadzin_name')->nullable();
            $table->string('bilal_name')->nullable();
            $table->uuid('user_id')->nullable()->index();
            $table->string('status', 30)->default('CONFIRMED'); // CONFIRMED, PENDING, REPLACED
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('khutbahs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('title');
            $table->string('preacher_name');
            $table->date('delivery_date')->index();
            $table->string('theme')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('audio_video_url', 500)->nullable();
            $table->string('pdf_attachment_url', 500)->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khutbahs');
        Schema::dropIfExists('khatib_schedules');
        Schema::dropIfExists('imam_schedules');
        Schema::dropIfExists('prayer_schedules');
        Schema::dropIfExists('prayer_settings');
    }
};
