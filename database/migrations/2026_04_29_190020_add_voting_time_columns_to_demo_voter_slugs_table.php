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
        Schema::table('demo_voter_slugs', function (Blueprint $table) {
            if (!Schema::hasColumn('demo_voter_slugs', 'voting_time_min')) {
                $table->integer('voting_time_min')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'voting_time_in_minutes')) {
                $table->integer('voting_time_in_minutes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_voter_slugs', function (Blueprint $table) {
            $table->dropColumn(['voting_time_min', 'voting_time_in_minutes']);
        });
    }
};
