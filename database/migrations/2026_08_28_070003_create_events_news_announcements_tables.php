<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 100);
            $table->string('slug');
            $table->string('color_code', 20)->default('#047857');
            $table->timestamps();

            $table->unique(['mosque_id', 'slug']);
        });

        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->foreignUuid('event_category_id')->nullable()->constrained('event_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('speaker_name')->nullable();
            $table->string('speaker_title')->nullable();
            $table->dateTime('start_datetime')->index();
            $table->dateTime('end_datetime')->nullable();
            $table->string('location')->default('Ruang Utama Masjid');
            $table->string('livestream_url', 500)->nullable();
            $table->text('description');
            $table->string('banner_url', 500)->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('registered_participants')->default(0);
            $table->boolean('is_registration_open')->default(false);
            $table->string('status', 30)->default('UPCOMING')->index(); // UPCOMING, ONGOING, COMPLETED, CANCELLED
            $table->timestamps();

            $table->unique(['mosque_id', 'slug']);
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->cascadeOnDelete()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('attendance_status', 30)->default('REGISTERED'); // REGISTERED, ATTENDED, CANCELLED
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('news_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 100);
            $table->string('slug');
            $table->timestamps();

            $table->unique(['mosque_id', 'slug']);
        });

        Schema::create('news', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->foreignUuid('news_category_id')->nullable()->constrained('news_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('cover_image_url', 500)->nullable();
            $table->uuid('author_id')->nullable()->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->integer('views_count')->default(0);
            $table->timestamps();

            $table->unique(['mosque_id', 'slug']);
            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('title');
            $table->text('body');
            $table->string('priority', 30)->default('NORMAL'); // NORMAL, HIGH, URGENT
            $table->boolean('is_pinned')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('title');
            $table->string('media_type', 20)->default('IMAGE'); // IMAGE, YOUTUBE
            $table->string('media_url', 500);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_categories');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_categories');
    }
};
