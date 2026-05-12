<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_officers', function (Blueprint $table) {
            // Drop the old org-level constraint: one officer per org
            $table->dropUnique('election_officers_user_org_unique');

            // Add election-scoped constraint: one officer per (user + org + election)
            // In PostgreSQL, NULLs are treated as distinct for unique constraints,
            // so multiple org-wide slots (election_id = NULL) are allowed.
            $table->unique(
                ['user_id', 'organisation_id', 'election_id'],
                'election_officers_user_org_election_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('election_officers', function (Blueprint $table) {
            $table->dropUnique('election_officers_user_org_election_unique');
            $table->unique(['user_id', 'organisation_id'], 'election_officers_user_org_unique');
        });
    }
};
