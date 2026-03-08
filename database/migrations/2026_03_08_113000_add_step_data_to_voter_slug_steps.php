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
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            // Add step_data column to store JSON metadata about each voting step
            if (!Schema::hasColumn('voter_slug_steps', 'step_data')) {
                $table->json('step_data')->nullable()->after('completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            if (Schema::hasColumn('voter_slug_steps', 'step_data')) {
                $table->dropColumn('step_data');
            }
        });
    }
};
