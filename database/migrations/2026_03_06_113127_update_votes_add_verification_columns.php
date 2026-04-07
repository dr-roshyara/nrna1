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
            if (!Schema::hasColumn('votes', 'receipt_hash')) {
                $table->string('receipt_hash')->unique()->after('election_id');
                $table->index('receipt_hash');
            }
            if (!Schema::hasColumn('votes', 'participation_proof')) {
                $table->string('participation_proof')->nullable()->after('receipt_hash');
                $table->index('participation_proof');
            }
            if (!Schema::hasColumn('votes', 'encrypted_vote')) {
                $table->text('encrypted_vote')->nullable()->after('participation_proof');
            }
            if (!Schema::hasColumn('votes', 'device_fingerprint_hash')) {
                $table->text('device_fingerprint_hash')->nullable()->after('encrypted_vote');
            }
            if (!Schema::hasColumn('votes', 'device_metadata_anonymized')) {
                $table->json('device_metadata_anonymized')->nullable()->after('device_fingerprint_hash');
            }
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

            // Drop new columns
            $table->dropColumn(['receipt_hash', 'participation_proof', 'encrypted_vote', 'device_fingerprint_hash', 'device_metadata_anonymized']);

            // Restore old vote_hash column
            $table->string('vote_hash')->unique()->after('election_id');
        });
    }
};
