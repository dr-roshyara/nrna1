<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 1: Real Voting Enforcement - Database Layer
 *
 * Adds composite foreign keys to enforce organisation_id consistency:
 *
 * 1. votes → elections (election_id, organisation_id)
 *    Ensures every vote belongs to an election in the SAME organisation
 *
 * 2. results → votes (vote_id, organisation_id)
 *    Ensures every result belongs to a vote in the SAME organisation
 *
 * CRITICAL ENFORCEMENT:
 * - Vote cannot reference election from different organisation
 * - Result cannot reference vote from different organisation
 * - Cascade delete ensures data integrity on election/vote deletion
 *
 * DEPENDENCIES:
 * - Both organisation_id columns MUST be NOT NULL (from previous migrations)
 * - Elections table MUST have (id, organisation_id) unique constraint
 * - Votes table MUST have (id, organisation_id) unique constraint
 *
 * This creates an IMPENETRABLE boundary where data integrity is
 * enforced at the database level - no application code can violate it.
 */
class AddCompositeForeignKeysToVotingTables extends Migration
{
    public function up()
    {
        // Step 1: Add composite unique indexes to parent tables if they don't exist
        // These are required for foreign key constraints to work

        // Check if elections table has composite unique index on (id, organisation_id)
        $elections_index_exists = DB::select("SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_NAME = 'elections'
            AND COLUMN_NAME IN ('id', 'organisation_id')
            AND SEQ_IN_INDEX = 2");

        if (empty($elections_index_exists)) {
            // Add composite unique index to elections
            DB::statement('ALTER TABLE elections ADD UNIQUE INDEX elections_id_organisation_id_unique (id, organisation_id)');
        }

        // Check if votes table has composite unique index on (id, organisation_id)
        $votes_index_exists = DB::select("SELECT 1 FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_NAME = 'votes'
            AND COLUMN_NAME IN ('id', 'organisation_id')
            AND SEQ_IN_INDEX = 2");

        if (empty($votes_index_exists)) {
            // Add composite unique index to votes
            DB::statement('ALTER TABLE votes ADD UNIQUE INDEX votes_id_organisation_id_unique (id, organisation_id)');
        }

        // Step 2: Drop existing single-column FKs if they exist
        $votes_fk_exists = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'votes' AND COLUMN_NAME = 'election_id'
            AND CONSTRAINT_NAME LIKE '%election_id%' AND REFERENCED_TABLE_NAME IS NOT NULL");

        if (!empty($votes_fk_exists)) {
            DB::statement('ALTER TABLE votes DROP FOREIGN KEY ' . $votes_fk_exists[0]->CONSTRAINT_NAME);
        }

        $results_fk_exists = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'results' AND COLUMN_NAME = 'vote_id'
            AND CONSTRAINT_NAME LIKE '%vote_id%' AND REFERENCED_TABLE_NAME IS NOT NULL");

        if (!empty($results_fk_exists)) {
            DB::statement('ALTER TABLE results DROP FOREIGN KEY ' . $results_fk_exists[0]->CONSTRAINT_NAME);
        }

        // Step 3: Add composite foreign keys
        Schema::table('votes', function (Blueprint $table) {
            // Add composite foreign key: (election_id, organisation_id) → elections(id, organisation_id)
            // This ensures a vote cannot reference an election from a different organisation
            $table->foreign(['election_id', 'organisation_id'])
                  ->references(['id', 'organisation_id'])
                  ->on('elections')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });

        Schema::table('results', function (Blueprint $table) {
            // Add composite foreign key: (vote_id, organisation_id) → votes(id, organisation_id)
            // This ensures a result cannot reference a vote from a different organisation
            $table->foreign(['vote_id', 'organisation_id'])
                  ->references(['id', 'organisation_id'])
                  ->on('votes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('results', function (Blueprint $table) {
            // Drop composite foreign key on results
            $table->dropForeign(['vote_id', 'organisation_id']);

            // Restore single-column foreign key
            $table->foreign('vote_id')
                  ->references('id')
                  ->on('votes')
                  ->onDelete('cascade');
        });

        Schema::table('votes', function (Blueprint $table) {
            // Drop composite foreign key on votes
            $table->dropForeign(['election_id', 'organisation_id']);

            // Restore single-column index (foreign key dropped, but index remains from earlier migration)
            // Note: We don't restore the single FK because the earlier migration doesn't create one
            // The election_id column has an index, which is sufficient for rollback compatibility
        });
    }
}
