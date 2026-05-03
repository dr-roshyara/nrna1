<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            // Add transaction_reference column if it doesn't exist
            if (!Schema::hasColumn('membership_fees', 'transaction_reference')) {
                $table->string('transaction_reference', 200)->nullable()->after('payment_method');
            }

            // Create unique constraint on (organisation_id, transaction_reference)
            $table->unique(['organisation_id', 'transaction_reference'], 'unique_txn_ref_per_org');
        });
    }

    public function down(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->dropUnique('unique_txn_ref_per_org');

            if (Schema::hasColumn('membership_fees', 'transaction_reference')) {
                $table->dropColumn('transaction_reference');
            }
        });
    }
};
