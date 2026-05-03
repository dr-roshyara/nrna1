<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add metadata JSON column to incomes table for Finance projection audit trail.
     *
     * Stores: event_id, transaction_reference, payment_method, paidAt, currency
     * Used by: CreateIncomeFromFeePaidProjection listener
     */
    public function up(): void
    {
        if (!Schema::hasColumn('incomes', 'metadata')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->json('metadata')
                    ->nullable()
                    ->after('period_to')
                    ->comment('Event audit trail: event_id, transaction_reference, payment_method, paidAt, currency');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('incomes', 'metadata')) {
            Schema::table('incomes', function (Blueprint $table) {
                $table->dropColumn('metadata');
            });
        }
    }
};
