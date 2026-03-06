<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Rename voting_code to receipt_hash (for voter self-verification)
            $table->renameColumn('voting_code', 'receipt_hash');

            // Add participation_proof (IP + user hash for admin verification)
            $table->string('participation_proof')->nullable()->after('receipt_hash');

            // Add encrypted_vote (stores actual vote data)
            $table->text('encrypted_vote')->nullable()->after('participation_proof');

            // Add soft deletes column (required for BaseVote SoftDeletes trait)
            $table->softDeletes();

            // Add indexes for new columns
            $table->index('receipt_hash');
            $table->index('participation_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['receipt_hash']);
            $table->dropIndex(['participation_proof']);

            // Drop new columns
            $table->dropColumn(['participation_proof', 'encrypted_vote']);

            // Rename receipt_hash back to voting_code
            $table->renameColumn('receipt_hash', 'voting_code');
        });
    }
};
