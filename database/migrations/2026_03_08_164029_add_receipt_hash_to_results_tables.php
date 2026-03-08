<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add receipt_hash column to results and demo_results tables
     * This enables vote result verification via receipt hashes
     */
    public function up(): void
    {
        // Add to main results table
        Schema::table('results', function (Blueprint $table) {
            if (!Schema::hasColumn('results', 'receipt_hash')) {
                $table->string('receipt_hash', 64)
                    ->nullable()
                    ->after('candidacy_id')
                    ->index()
                    ->comment('Hash for vote verification');
            }
        });

        // Add to demo_results table
        Schema::table('demo_results', function (Blueprint $table) {
            if (!Schema::hasColumn('demo_results', 'receipt_hash')) {
                $table->string('receipt_hash', 64)
                    ->nullable()
                    ->after('candidacy_id')
                    ->index()
                    ->comment('Hash for demo vote verification');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (Schema::hasColumn('results', 'receipt_hash')) {
                $table->dropColumn('receipt_hash');
            }
        });

        Schema::table('demo_results', function (Blueprint $table) {
            if (Schema::hasColumn('demo_results', 'receipt_hash')) {
                $table->dropColumn('receipt_hash');
            }
        });
    }
};
