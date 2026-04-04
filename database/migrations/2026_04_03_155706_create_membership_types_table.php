<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membership_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();

            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->char('fee_currency', 3)->default('EUR');

            // null = lifetime membership (no expiry)
            $table->unsignedSmallInteger('duration_months')->nullable();

            $table->boolean('requires_approval')->default(true);

            // JSON schema for dynamic application form fields
            $table->json('form_schema')->nullable();

            $table->boolean('is_active')->default(true);
            $table->smallInteger('sort_order')->default(0);

            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organisation_id', 'slug']);
            $table->index(['organisation_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};
