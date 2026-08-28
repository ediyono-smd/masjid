<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 100);
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->foreignUuid('category_id')->nullable()->constrained('inventory_categories')->nullOnDelete();
            $table->string('item_code', 50);
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->string('unit', 30)->default('Unit'); // Unit, Pcs, Set, Roll, Buah
            $table->date('acquisition_date')->nullable();
            $table->string('acquisition_source', 50)->default('PURCHASE'); // PURCHASE, WAQF, DONATION, GRANT
            $table->decimal('acquisition_cost', 15, 2)->default(0.00);
            $table->string('room_location', 100)->nullable(); // Ruang Utama, Gudang, Mihrab, Kantor
            $table->string('condition', 30)->default('GOOD')->index(); // GOOD, FAIR, POOR, BROKEN
            $table->text('notes')->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->timestamps();

            $table->unique(['mosque_id', 'item_code']);
        });

        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('inventory_id')->constrained('inventories')->cascadeOnDelete()->index();
            $table->date('maintenance_date')->index();
            $table->text('issue_description');
            $table->text('action_taken')->nullable();
            $table->string('vendor_name')->nullable();
            $table->decimal('cost', 15, 2)->default(0.00);
            $table->date('next_maintenance_date')->nullable();
            $table->string('status', 30)->default('COMPLETED'); // IN_PROGRESS, COMPLETED
            $table->uuid('recorded_by_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('recorded_by_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('book_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->string('name', 100);
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('mosque_id')->constrained('mosques')->cascadeOnDelete()->index();
            $table->foreignUuid('category_id')->nullable()->constrained('book_categories')->nullOnDelete();
            $table->string('book_code', 50);
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('year_published')->nullable();
            $table->string('language', 50)->default('Indonesia'); // Indonesia, Arab, Inggris
            $table->integer('copies_total')->default(1);
            $table->integer('copies_available')->default(1);
            $table->string('shelf_location', 100)->nullable();
            $table->string('cover_url', 500)->nullable();
            $table->timestamps();

            $table->unique(['mosque_id', 'book_code']);
        });

        Schema::create('book_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('book_id')->constrained('books')->cascadeOnDelete()->index();
            $table->foreignUuid('congregation_id')->nullable()->constrained('congregations')->nullOnDelete();
            $table->string('borrower_name');
            $table->string('borrower_phone', 30);
            $table->date('loan_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->string('status', 30)->default('BORROWED')->index(); // BORROWED, RETURNED, OVERDUE, LOST
            $table->text('notes')->nullable();
            $table->uuid('processed_by_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('processed_by_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_loans');
        Schema::dropIfExists('books');
        Schema::dropIfExists('book_categories');
        Schema::dropIfExists('maintenance_records');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('inventory_categories');
    }
};
