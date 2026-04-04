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
        Schema::create('membership_applications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('membership_type_id')->constrained('membership_types')->restrictOnDelete();

            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])
                  ->default('draft');

            $table->json('application_data')->nullable();

            // Fix 2: auto-expire after configured days
            $table->timestamp('expires_at')->nullable();

            // Fix 3: optimistic locking — prevents concurrent approval race condition
            $table->unsignedInteger('lock_version')->default(0);

            $table->timestamp('submitted_at')->nullable();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // One active (pending) application per user per organisation
            $table->index(['organisation_id', 'user_id']);
            $table->index(['organisation_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_applications');
    }
};
