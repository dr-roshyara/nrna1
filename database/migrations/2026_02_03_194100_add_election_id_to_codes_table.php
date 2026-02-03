<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddElectionIdToCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds election_id to codes table to support multi-election voting.
     * This allows the same user to verify and vote in multiple elections.
     *
     * NO foreign key constraint - maintains independence from elections table.
     * Backward compatible: NULL election_id for existing codes (will default to first election in app).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Add election_id column (nullable for backward compatibility)
            $table->unsignedBigInteger('election_id')
                  ->nullable()
                  ->after('user_id')
                  ->comment('Reference to elections table - scopes verification codes per election');

            // Add index for frequent queries (election + user lookups)
            $table->index(['election_id', 'user_id']);

            // Add composite index for can_vote_now checks
            $table->index(['election_id', 'can_vote_now']);

            // Unique constraint: one code per user per election
            // This prevents duplicate verification codes in the same election
            $table->unique(['user_id', 'election_id']);
        });

        // Default existing codes to first election (demo election from seeder)
        // This maintains backward compatibility with existing voting flow
        DB::table('codes')
            ->whereNull('election_id')
            ->update([
                'election_id' => 1 // First election from ElectionSeeder
            ]);

        // Drop old unique constraint if it exists (was on user_id alone)
        // and recreate with election_id
        Schema::table('codes', function (Blueprint $table) {
            // Make election_id NOT NULL after data migration
            $table->unsignedBigInteger('election_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['election_id', 'user_id']);
            $table->dropIndex(['election_id', 'can_vote_now']);

            // Drop unique constraint
            $table->dropUnique(['user_id', 'election_id']);

            // Drop column
            $table->dropColumn('election_id');
        });
    }
}
