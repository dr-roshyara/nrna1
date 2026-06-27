<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            // Add amount column if it doesn't exist
            if (!Schema::hasColumn('membership_fees', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_method');
            }

            // Add currency column if it doesn't exist
            if (!Schema::hasColumn('membership_fees', 'payment_currency')) {
                $table->char('payment_currency', 3)->default('EUR')->after('payment_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            if (Schema::hasColumn('membership_fees', 'payment_amount')) {
                $table->dropColumn('payment_amount');
            }

            if (Schema::hasColumn('membership_fees', 'payment_currency')) {
                $table->dropColumn('payment_currency');
            }
        });
    }
};
