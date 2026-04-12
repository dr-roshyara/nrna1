<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires ALTER COLUMN to redefine the full ENUM list
        DB::statement("
            ALTER TABLE contributions
            MODIFY COLUMN proof_type
            ENUM('self_report','photo','document','third_party','community_attestation','institutional')
            NOT NULL DEFAULT 'self_report'
        ");
    }

    public function down(): void
    {
        // Remove community_attestation — any existing rows with that value
        // must be migrated first (safe for fresh installs, requires data review in production)
        DB::statement("
            UPDATE contributions SET proof_type = 'third_party'
            WHERE proof_type = 'community_attestation'
        ");

        DB::statement("
            ALTER TABLE contributions
            MODIFY COLUMN proof_type
            ENUM('self_report','photo','document','third_party','institutional')
            NOT NULL DEFAULT 'self_report'
        ");
    }
};
