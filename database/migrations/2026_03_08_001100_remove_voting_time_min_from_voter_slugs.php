<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Remove voting_time_min column (replaced with voting_time_in_minutes)
     */
    public function up(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Drop voting_time_min if it exists (replaced by voting_time_in_minutes)
            if (Schema::hasColumn('voter_slugs', 'voting_time_min')) {
                $table->dropColumn('voting_time_min');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Restore voting_time_min if needed
            if (!Schema::hasColumn('voter_slugs', 'voting_time_min')) {
                $table->integer('voting_time_min')
                    ->default(30)
                    ->nullable();
            }
        });
    }
};
