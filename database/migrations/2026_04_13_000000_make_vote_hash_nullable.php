<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DEPRECATED: Removed vote_hash references (replaced by receipt_hash)
     *
     * vote_hash has been completely removed in favor of receipt_hash.
     * This migration is now a no-op to maintain migration history.
     */
    public function up(): void
    {
        // No-op: vote_hash removed, using receipt_hash instead
    }

    public function down(): void
    {
        // No-op: vote_hash removed, using receipt_hash instead
    }
};
