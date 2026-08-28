<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('congregations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('name');
            $table->string('nik_masked', 30)->nullable();
            $table->string('phone', 30)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('gender', 10)->default('L'); // L, P
            $table->text('address')->nullable();
            $table->string('rt_rw', 20)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->string('special_skills')->nullable();
            $table->boolean('is_head_of_family')->default(false);
            $table->integer('family_members_count')->default(1);
            $table->boolean('is_mustahiq')->default(false);
            $table->string('status', 30)->default('ACTIVE'); // ACTIVE, INACTIVE, MOVED, DECEASED
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('donors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('name');
            $table->string('phone', 30)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('category', 50)->default('INDIVIDUAL'); // INDIVIDUAL, CORPORATE, COMMUNITY
            $table->decimal('total_donated', 15, 2)->default(0.00);
            $table->integer('donation_count')->default(0);
            $table->timestamp('last_donated_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('volunteers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->uuid('user_id')->nullable()->index();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('expertise', 150)->nullable(); // Media, Konsumsi, Medis, Logistik, Keamanan
            $table->string('status', 30)->default('ACTIVE'); // ACTIVE, STANDBY, INACTIVE
            $table->integer('total_hours')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('volunteer_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('volunteer_id')->constrained('volunteers')->cascadeOnDelete()->index();
            $table->string('activity_name');
            $table->date('activity_date');
            $table->integer('hours_spent')->default(2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_activities');
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('donors');
        Schema::dropIfExists('congregations');
    }
};
