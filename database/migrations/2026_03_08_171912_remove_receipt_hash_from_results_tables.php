<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove receipt_hash from results tables.
     *
     * Receipt_hash belongs ONLY in votes/demo_votes tables for voter verification.
     * Results tables should NOT duplicate this field - they can join to votes
     * table if verification is needed.
     *
     * This fixes the data duplication and architectural inconsistency.
     */
    public function up(): void
    {
        // Remove from results table
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'receipt_hash')) {
                $table->dropColumn('receipt_hash');
            }
        });

        // Remove from demo_results table
        Schema::table('demo_results', function (Blueprint $table) {
            if (Schema::hasColumn('demo_results', 'receipt_hash')) {
                $table->dropColumn('receipt_hash');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore to results table
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'receipt_hash')) {
                $table->string('receipt_hash', 64)
                    ->nullable()
                    ->after('candidacy_id')
                    ->index()
                    ->comment('Hash for vote verification (deprecated - use votes.receipt_hash)');
            }
        });

        // Restore to demo_results table
        Schema::table('demo_results', function (Blueprint $table) {
            if (!Schema::hasColumn('demo_results', 'receipt_hash')) {
                $table->string('receipt_hash', 64)
                    ->nullable()
                    ->after('candidacy_id')
                    ->index()
                    ->comment('Hash for demo vote verification (deprecated - use demo_votes.receipt_hash)');
            }
        });
    }
};
