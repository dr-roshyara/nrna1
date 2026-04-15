<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One-time data migration: Convert active members with unpaid fees to exempt.
     *
     * Context: Members approved via the application flow were created with
     * fees_status='unpaid'. The voter eligibility query requires fees_status IN ('paid', 'exempt').
     * This migration fixes existing members so they appear in voters dropdown immediately.
     *
     * This is safe because:
     * - Only affects active members (status='active')
     * - Does not remove any member records
     * - Admins can still view fee records for audit trail
     * - Complements the new markPaid() authorization fix
     */
    public function up(): void
    {
        DB::table('members')
            ->where('fees_status', 'unpaid')
            ->where('status', 'active')
            ->update(['fees_status' => 'exempt']);
    }

    public function down(): void
    {
        // Intentionally left empty — data migration is one-directional.
        // To revert, manually update members back to 'unpaid'.
    }
};
