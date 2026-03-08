<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add critical columns for vote anonymity and fraud detection to demo_votes:
     * - cast_at: Timestamp when vote was cast
     * - vote_hash: SHA256 hash using code->id (NOT user_id) for anonymity bridge
     * - no_vote_posts: Array of posts where voter abstained
     * - device_fingerprint_hash: Privacy-preserving device hash for fraud detection
     * - device_metadata_anonymized: Anonymized device metadata (no PII)
     * - voting_code: Audit trail code linking code to vote (without exposing identity)
     */
    public function up(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Vote casting timestamp
            if (!Schema::hasColumn('demo_votes', 'cast_at')) {
                $table->timestamp('cast_at')
                    ->nullable()
                    ->after('receipt_hash')
                    ->comment('Timestamp when vote was cast');
            }

            // Vote anonymity hash (uses code->id NOT user_id)
            if (!Schema::hasColumn('demo_votes', 'vote_hash')) {
                $table->string('vote_hash', 64)
                    ->nullable()
                    ->after('cast_at')
                    ->index()
                    ->comment('SHA256 hash using code->id for anonymity');
            }

            // Posts where voter abstained
            if (!Schema::hasColumn('demo_votes', 'no_vote_posts')) {
                $table->json('no_vote_posts')
                    ->nullable()
                    ->after('vote_hash')
                    ->comment('Array of post IDs where voter abstained');
            }

            // Fraud detection: device fingerprinting
            if (!Schema::hasColumn('demo_votes', 'device_fingerprint_hash')) {
                $table->string('device_fingerprint_hash', 64)
                    ->nullable()
                    ->after('no_vote_posts')
                    ->index()
                    ->comment('Privacy-preserving SHA256 device hash');
            }

            // Fraud detection: anonymized device metadata
            if (!Schema::hasColumn('demo_votes', 'device_metadata_anonymized')) {
                $table->json('device_metadata_anonymized')
                    ->nullable()
                    ->after('device_fingerprint_hash')
                    ->comment('Anonymized device analytics (browser, OS, etc)');
            }

            // Audit trail: anonymity bridge
            if (!Schema::hasColumn('demo_votes', 'voting_code')) {
                $table->string('voting_code', 64)
                    ->nullable()
                    ->after('device_metadata_anonymized')
                    ->index()
                    ->comment('Audit trail code from DemoCode record');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            $table->dropColumn([
                'cast_at',
                'vote_hash',
                'no_vote_posts',
                'device_fingerprint_hash',
                'device_metadata_anonymized',
                'voting_code',
            ]);
        });
    }
};
