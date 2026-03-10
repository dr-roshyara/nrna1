<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing step_data column to demo_voter_slug_steps table
     *
     * This column stores metadata about each step completion
     * (timestamps, form data, verification status, etc.)
     */
    public function up(): void
    {
        if (!Schema::hasColumn('demo_voter_slug_steps', 'step_data')) {
            Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
                $table->json('step_data')
                    ->nullable()
                    ->after('step')
                    ->comment('Step completion metadata and form data');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('demo_voter_slug_steps', 'step_data')) {
            Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
                $table->dropColumn('step_data');
            });
        }
    }
};
