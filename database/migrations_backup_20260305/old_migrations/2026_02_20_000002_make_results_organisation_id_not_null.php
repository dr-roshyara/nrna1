<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Phase 1: Real Voting Enforcement - Database Layer
 *
 * Makes results.organisation_id NOT NULL to enforce that all vote results
 * MUST belong to an organisation and match their parent vote's organisation.
 *
 * CRITICAL: This is a hard database boundary preventing any result from
 * being saved without matching the parent vote's organisation context.
 *
 * Dependency: results.vote_id MUST already be NOT NULL
 * Constraint: results.organisation_id MUST always match votes.organisation_id
 *             (enforced via composite foreign key in next migration)
 */
class MakeResultsOrganisationIdNotNull extends Migration
{
    public function up()
    {
        // Use raw SQL to change organisation_id to NOT NULL
        // This avoids Doctrine DBAL dependency issues
        DB::statement('ALTER TABLE results MODIFY organisation_id BIGINT UNSIGNED NOT NULL');
    }

    public function down()
    {
        // Rollback: Make nullable again for development/testing
        DB::statement('ALTER TABLE results MODIFY organisation_id BIGINT UNSIGNED NULL');
    }
}
