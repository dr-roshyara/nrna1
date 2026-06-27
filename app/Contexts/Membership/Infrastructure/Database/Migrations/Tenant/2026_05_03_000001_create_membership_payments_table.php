<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('member_id');
            $table->uuid('fee_id')->nullable();
            $table->uuid('organisation_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50)->default('bank_transfer');
            $table->string('status', 20)->default('completed');
            $table->string('transaction_reference')->nullable();
            $table->uuid('recorded_by');
            $table->timestamp('paid_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('fee_id')->references('id')->on('membership_fees')->nullable();
            $table->foreign('organisation_id')->references('id')->on('organisations');
            $table->foreign('recorded_by')->references('id')->on('users');

            // Performance indexes
            $table->index(['organisation_id', 'status', 'paid_at']);
            $table->index(['member_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_payments');
    }
};
