<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add device fingerprinting columns for fraud detection
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Add device_fingerprint_hash if missing
            if (!Schema::hasColumn('demo_codes', 'device_fingerprint_hash')) {
                $table->string('device_fingerprint_hash', 64)
                    ->nullable()
                    ->after('client_ip')
                    ->index();
            }

            // Ensure device_metadata_anonymized exists (should be from earlier migration)
            if (!Schema::hasColumn('demo_codes', 'device_metadata_anonymized')) {
                $table->json('device_metadata_anonymized')
                    ->nullable()
                    ->after('device_fingerprint_hash');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            if (Schema::hasColumn('demo_codes', 'device_fingerprint_hash')) {
                $table->dropIndex(['device_fingerprint_hash']);
                $table->dropColumn('device_fingerprint_hash');
            }

            if (Schema::hasColumn('demo_codes', 'device_metadata_anonymized')) {
                $table->dropColumn('device_metadata_anonymized');
            }
        });
    }
};
