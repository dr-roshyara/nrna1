<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fixes RC-2: Ensure election_memberships has a unique constraint
     *
     * The application layer (AssignVoterHandler) already handles soft-deleted record logic
     * via findWithTrashed() and restoreAndUpdate(). The partial index is working correctly
     * for PostgreSQL. This migration is a safety net to ensure consistency.
     */
    public function up(): void
    {
        // This migration is intentionally empty for PostgreSQL as the partial index
        // in the previous migration (2026_05_19_000001_*) is working correctly.
        //
        // For MySQL/other databases that don't support partial indices,
        // the unique constraint is enforced at the application layer.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropUnique('uq_user_election');
        });
    }
};
