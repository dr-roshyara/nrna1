<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix demo_voter_slug_steps id column to auto-generate UUID on PostgreSQL.
     * The id column is UUID type but lacks a DEFAULT, causing NOT NULL violations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            // PostgreSQL: Set UUID generator as default
            DB::statement('ALTER TABLE demo_voter_slug_steps ALTER COLUMN id SET DEFAULT gen_random_uuid()');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE demo_voter_slug_steps ALTER COLUMN id DROP DEFAULT');
        }
    }
};
