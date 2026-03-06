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
        Schema::table('codes', function (Blueprint $table) {
            // Add device fingerprinting columns for fraud detection
            $table->string('device_fingerprint_hash')->nullable();
            $table->json('device_metadata_anonymized')->nullable();

            // Add indexes for efficient device-based queries
            $table->index('device_fingerprint_hash');
            $table->index(['device_fingerprint_hash', 'election_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->dropIndex(['device_fingerprint_hash', 'election_id']);
            $table->dropIndex(['device_fingerprint_hash']);
            $table->dropColumn('device_fingerprint_hash');
            $table->dropColumn('device_metadata_anonymized');
        });
    }
};
