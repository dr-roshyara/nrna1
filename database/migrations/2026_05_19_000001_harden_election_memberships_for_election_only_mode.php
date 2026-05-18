<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Phase C.1: Harden ElectionMemberships for Election-Only Mode
 *
 * CRITICAL CHANGES:
 * 1. Add SoftDeletes (deleted_at column)
 *    - Allows soft-deleted voters to be re-imported without unique constraint collision
 *    - Preserves audit trail
 *
 * 2. Drop composite FK to user_organisation_roles
 *    - Root bug: FK blocks election-only mode (users only have OrganisationUser, not UserOrganisationRole)
 *    - Application-level VoterQualificationPolicy takes over this validation
 *
 * 3. Replace hard unique constraint with partial unique index
 *    - WHERE deleted_at IS NULL — allows same (user_id, election_id) when first is soft-deleted
 *    - Supports re-import workflow seamlessly
 *
 * WARNING: After this migration, application code MUST validate that users exist in:
 * - organisation_users table (election-only mode)
 * - members table with paid/exempt fees (full membership mode)
 *
 * The database FK is gone. Integrity is now application responsibility.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            // Step 1: Add SoftDeletes column
            $table->softDeletes();
        });

        // Step 2: Drop composite FK to user_organisation_roles
        // This FK blocks election-only mode where users only have OrganisationUser records
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropForeign(['user_id', 'organisation_id']);
        });

        // Step 3: Drop hard unique constraint (will be replaced with partial index)
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropUnique('unique_user_election');
        });

        // Step 4: Create partial unique index (PostgreSQL)
        // This index excludes soft-deleted rows, allowing re-import
        // Syntax: WHERE deleted_at IS NULL
        //
        // For MySQL: Use a generated column workaround if needed (not implemented here)
        // For SQLite: Partial indices work natively
        DB::statement('
            CREATE UNIQUE INDEX uq_user_election_active
            ON election_memberships (user_id, election_id)
            WHERE deleted_at IS NULL
        ');
    }

    public function down(): void
    {
        // Step 1: Drop partial unique index
        DB::statement('DROP INDEX IF EXISTS uq_user_election_active');

        // Step 2: Re-create hard unique constraint
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->unique(['user_id', 'election_id'], 'unique_user_election');
        });

        // Step 3: Re-create composite FK
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->foreign(['user_id', 'organisation_id'])
                ->references(['user_id', 'organisation_id'])
                ->on('user_organisation_roles')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        // Step 4: Drop SoftDeletes column
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
