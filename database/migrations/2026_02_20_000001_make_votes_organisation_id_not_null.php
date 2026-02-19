<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Phase 1: Real Voting Enforcement - Database Layer
 *
 * Makes votes.organisation_id NOT NULL to enforce that all REAL votes
 * MUST belong to an organisation.
 *
 * CRITICAL: This is a hard database boundary preventing any vote from
 * being saved without a valid organisation context.
 *
 * Migration Strategy:
 * 1. First, ensure no NULL organisation_ids exist in production data
 * 2. Change constraint to NOT NULL
 * 3. Keep single-column index (will be part of composite FK in next migration)
 */
class MakeVotesOrganisationIdNotNull extends Migration
{
    public function up()
    {
        // Use raw SQL to change organisation_id to NOT NULL
        // This avoids Doctrine DBAL dependency issues
        DB::statement('ALTER TABLE votes MODIFY organisation_id BIGINT UNSIGNED NOT NULL');
    }

    public function down()
    {
        // Rollback: Make nullable again for development/testing
        DB::statement('ALTER TABLE votes MODIFY organisation_id BIGINT UNSIGNED NULL');
    }
}
