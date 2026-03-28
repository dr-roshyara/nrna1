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
        Schema::table('votes', function (Blueprint $table) {
            // Add no_vote_option to match demo_votes table structure
            // Tracks if a voter abstained from all posts (important for turnout calculations)
            $table->tinyInteger('no_vote_option')->default(0)->after('receipt_hash');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropColumn('no_vote_option');
        });
    }
};
