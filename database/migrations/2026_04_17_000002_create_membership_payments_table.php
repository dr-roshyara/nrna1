<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create membership_payments table for audit trail and Income integration.
     * Each payment creates: MembershipPayment (audit) + Income (finance) + updates MembershipFee
     */
    public function up(): void
    {
        // Only create if table doesn't already exist (idempotent)
        if (! Schema::hasTable('membership_payments')) {
            Schema::create('membership_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Links to member and fee
            $table->uuid('member_id');
            $table->uuid('fee_id')->nullable();
            $table->uuid('organisation_id');

            // Payment details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method')->default('bank_transfer');
            $table->string('payment_reference')->nullable();
            $table->string('status')->default('completed');

            // Audit trail
            $table->uuid('recorded_by');

            // Links to Income record created by listener (incomes table uses bigint id)
            $table->unsignedBigInteger('income_id')->nullable();

            // Timestamps
            $table->timestamp('paid_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
            $table->foreign('fee_id')->references('id')->on('membership_fees')->nullOnDelete();
            $table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
            $table->foreign('recorded_by')->references('id')->on('users');
            $table->foreign('income_id')->references('id')->on('incomes')->nullOnDelete();

            // Indexes for common queries
            $table->index(['organisation_id', 'paid_at']);
            $table->index(['member_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_payments');
    }
};
