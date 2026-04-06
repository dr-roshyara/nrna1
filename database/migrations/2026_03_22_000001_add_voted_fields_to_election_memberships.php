<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->boolean('has_voted')->default(false)->after('status');
            $table->timestamp('voted_at')->nullable()->after('has_voted');
            $table->index('has_voted', 'idx_em_has_voted');
        });

        // Backfill: any membership whose user has a VoterSlug with status='voted'
        // for the same election was already voted before this migration ran.
        // Uses EXISTS + subquery (not JOIN) to avoid duplicate rows when a user
        // somehow has multiple voter_slugs with status='voted' for the same election.
        if (Schema::hasTable('voter_slugs')) {
            DB::statement("
                UPDATE election_memberships
                SET has_voted = 1,
                    voted_at  = (
                        SELECT MIN(voter_slugs.updated_at)
                        FROM voter_slugs
                        WHERE voter_slugs.user_id     = election_memberships.user_id
                          AND voter_slugs.election_id = election_memberships.election_id
                          AND voter_slugs.status      = 'voted'
                    ),
                    status = 'inactive'
                WHERE has_voted = 0
                  AND EXISTS (
                      SELECT 1 FROM voter_slugs
                      WHERE voter_slugs.user_id     = election_memberships.user_id
                        AND voter_slugs.election_id = election_memberships.election_id
                        AND voter_slugs.status      = 'voted'
                  )
            ");
        }
    }

    public function down(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropIndex('idx_em_has_voted');
            $table->dropColumn(['has_voted', 'voted_at']);
        });
    }
};
