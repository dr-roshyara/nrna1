<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds performance indexes to optimize common queries in the voting system:
     * - voter_slugs: Fast slug lookups, user active status checks, expiration cleanup
     * - elections: Filter by org/status/date, type/status queries
     * - codes: Code1 lookups, user voting eligibility checks
     */
    public function up(): void
    {
        // Voter Slugs Indexes
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Fast slug lookups during vote initiation
            // Used in: VerifyVoterSlug middleware, VoterSlugService
            $table->index('slug', 'idx_slug_lookup');

            // Check user's active voting sessions
            // Used in: VoterSlugService for finding user's active slugs
            $table->index(['user_id', 'is_active', 'expires_at'], 'idx_user_active_expires');

            // Cleanup expired slugs
            // Used in: Scheduler task to deactivate expired slugs
            $table->index(['expires_at', 'is_active'], 'idx_expires_cleanup');
        });

        // Elections Indexes
        Schema::table('elections', function (Blueprint $table) {
            // Query elections by organisation, status, and date range
            // Used in: DemoElectionResolver, ElectionService for finding active elections
            $table->index(['organisation_id', 'status', 'start_date'], 'idx_org_status_date');

            // Filter by election type and status
            // Used in: Finding demo vs real elections, active elections
            $table->index(['type', 'status'], 'idx_type_status');
        });

        // Codes Indexes
        Schema::table('codes', function (Blueprint $table) {
            // Fast code1 lookups during vote entry step
            // Used in: DemoCodeController, CodeService for validating codes
            $table->index('code1', 'idx_code1_lookup');

            // Check if user can vote now
            // Used in: CodeService, VoterSlugService for permission checks
            $table->index(['user_id', 'can_vote_now'], 'idx_user_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Voter Slugs Indexes
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_slug_lookup');
            $table->dropIndexIfExists('idx_user_active_expires');
            $table->dropIndexIfExists('idx_expires_cleanup');
        });

        // Elections Indexes
        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_org_status_date');
            $table->dropIndexIfExists('idx_type_status');
        });

        // Codes Indexes
        Schema::table('codes', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_code1_lookup');
            $table->dropIndexIfExists('idx_user_active');
        });
    }
};
