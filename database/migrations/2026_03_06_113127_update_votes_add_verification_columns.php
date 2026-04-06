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
        Schema::table('votes', function (Blueprint $table) {
            // Drop old vote_hash column
            // $table->dropColumn('vote_hash');

            // Add new verification columns
            $table->string('receipt_hash')->unique()->after('election_id');
            $table->string('participation_proof')->nullable()->after('receipt_hash');
            $table->text('encrypted_vote')->nullable()->after('participation_proof');

            // Add device fingerprinting for fraud detection (privacy-preserving)
            $table->string('device_fingerprint_hash')->nullable()->after('encrypted_vote');
            $table->json('device_metadata_anonymized')->nullable()->after('device_fingerprint_hash');

            // Add indexes for new columns
            $table->index('receipt_hash');
            $table->index('participation_proof');
            $table->index('device_fingerprint_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['receipt_hash']);
            $table->dropIndex(['participation_proof']);
            $table->dropIndex(['device_fingerprint_hash']);

            // Drop new columns
            $table->dropColumn(['receipt_hash', 'participation_proof', 'encrypted_vote', 'device_fingerprint_hash', 'device_metadata_anonymized']);

            // Restore old vote_hash column
            $table->string('vote_hash')->unique()->after('election_id');
        });
    }
};
