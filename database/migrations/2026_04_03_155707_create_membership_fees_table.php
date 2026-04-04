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
        Schema::create('membership_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignUuid('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignUuid('membership_type_id')->constrained('membership_types')->restrictOnDelete();

            $table->decimal('amount', 10, 2);
            $table->char('currency', 3)->default('EUR');

            // Snapshot of fee at time of creation — preserves history when type changes
            $table->decimal('fee_amount_at_time', 10, 2);
            $table->char('currency_at_time', 3)->default('EUR');

            $table->string('period_label', 50)->nullable();  // e.g. "2025", "2025-2026"
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->enum('status', ['pending', 'paid', 'waived', 'overdue'])->default('pending');

            $table->string('payment_method', 50)->nullable();
            $table->string('payment_reference', 200)->nullable();

            // Fix 4: idempotency key — prevents duplicate payment recording
            $table->string('idempotency_key', 100)->nullable()->unique();

            $table->foreignUuid('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['organisation_id', 'status']);
            $table->index(['member_id', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_fees');
    }
};
