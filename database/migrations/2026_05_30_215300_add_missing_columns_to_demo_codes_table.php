<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing code sent flag columns to demo_codes table
     * to match the DemoCode model expectations.
     */
    public function up(): void
    {
        if (Schema::hasTable('demo_codes')) {
            Schema::table('demo_codes', function (Blueprint $table) {
                // Only add columns if they don't already exist
                if (!Schema::hasColumn('demo_codes', 'has_code1_sent')) {
                    $table->boolean('has_code1_sent')->default(0)->after('is_code_to_save_vote_usable');
                }
                if (!Schema::hasColumn('demo_codes', 'has_code2_sent')) {
                    $table->boolean('has_code2_sent')->default(0)->after('has_code1_sent');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('demo_codes')) {
            Schema::table('demo_codes', function (Blueprint $table) {
                if (Schema::hasColumn('demo_codes', 'has_code1_sent')) {
                    $table->dropColumn('has_code1_sent');
                }
                if (Schema::hasColumn('demo_codes', 'has_code2_sent')) {
                    $table->dropColumn('has_code2_sent');
                }
            });
        }
    }
};
