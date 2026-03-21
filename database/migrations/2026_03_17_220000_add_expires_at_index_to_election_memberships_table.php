<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add an index on expires_at for the election_memberships table.
 *
 * The eligibleVoters() query filters with:
 *   WHERE expires_at IS NULL OR expires_at > NOW()
 *
 * Without an index, this requires a full table scan as membership counts grow.
 * A regular index on expires_at allows MySQL to use a range scan for the
 * expires_at > NOW() branch; the IS NULL branch uses the same index efficiently
 * because NULL values are included in a standard B-tree index in MySQL.
 *
 * Note: MySQL 8.0+ supports partial/functional indexes, but a standard index
 * is used here for maximum compatibility across MySQL 5.7+ and MariaDB.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->index('expires_at', 'idx_em_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropIndex('idx_em_expires_at');
        });
    }
};
