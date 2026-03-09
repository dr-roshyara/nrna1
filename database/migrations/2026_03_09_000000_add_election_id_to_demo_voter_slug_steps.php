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
        Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
            // Add election_id column if it doesn't exist
            if (!Schema::hasColumn('demo_voter_slug_steps', 'election_id')) {
                $table->uuid('election_id')->nullable()->after('voter_slug_id');
                $table->foreign('election_id')
                      ->references('id')
                      ->on('elections')
                      ->onDelete('cascade');
                $table->index('election_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_voter_slug_steps', function (Blueprint $table) {
            // Drop foreign key and column if they exist
            if (Schema::hasColumn('demo_voter_slug_steps', 'election_id')) {
                $table->dropForeign(['election_id']);
                $table->dropIndex(['election_id']);
                $table->dropColumn('election_id');
            }
        });
    }
};
