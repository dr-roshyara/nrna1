<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add id column to demo_voter_slug_steps if missing
     *
     * Some older migrations may have created this table without an id column,
     * causing "Field 'id' doesn't have a default value" errors on insert.
     */
    public function up(): void
    {
        if (Schema::hasTable('demo_voter_slug_steps') && !Schema::hasColumn('demo_voter_slug_steps', 'id')) {
            Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
                $table->id()->first();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('demo_voter_slug_steps') && Schema::hasColumn('demo_voter_slug_steps', 'id')) {
            Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
                $table->dropColumn('id');
            });
        }
    }
};
