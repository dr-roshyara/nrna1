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
        Schema::create('membership_renewals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignUuid('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignUuid('membership_type_id')->constrained('membership_types')->restrictOnDelete();
            $table->foreignUuid('renewed_by')->constrained('users')->restrictOnDelete();

            $table->timestamp('old_expires_at')->nullable();
            $table->timestamp('new_expires_at')->nullable();

            // Linked fee created alongside this renewal (nullable for admin-waived renewals)
            $table->foreignUuid('fee_id')->nullable()->constrained('membership_fees')->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['organisation_id', 'member_id']);
            $table->index('new_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_renewals');
    }
};
