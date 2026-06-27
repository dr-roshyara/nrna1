<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE contributions ADD COLUMN proof_type_new VARCHAR(50)");
            DB::statement("UPDATE contributions SET proof_type_new = proof_type::text");
            DB::statement("ALTER TABLE contributions DROP COLUMN proof_type");
            DB::statement("ALTER TABLE contributions RENAME COLUMN proof_type_new TO proof_type");
            DB::statement("ALTER TABLE contributions ADD CONSTRAINT contributions_proof_type_check CHECK (proof_type IN ('self_report','photo','document','third_party','community_attestation','institutional'))");
        } else {
            DB::statement("
                ALTER TABLE contributions
                MODIFY COLUMN proof_type
                ENUM('self_report','photo','document','third_party','community_attestation','institutional')
                NOT NULL DEFAULT 'self_report'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("
            UPDATE contributions SET proof_type = 'third_party'
            WHERE proof_type = 'community_attestation'
        ");

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE contributions DROP CONSTRAINT contributions_proof_type_check");
            DB::statement("ALTER TABLE contributions ADD COLUMN proof_type_new VARCHAR(50)");
            DB::statement("UPDATE contributions SET proof_type_new = proof_type");
            DB::statement("ALTER TABLE contributions DROP COLUMN proof_type");
            DB::statement("ALTER TABLE contributions RENAME COLUMN proof_type_new TO proof_type");
            DB::statement("ALTER TABLE contributions ADD CONSTRAINT contributions_proof_type_check CHECK (proof_type IN ('self_report','photo','document','third_party','institutional'))");
        } else {
            DB::statement("
                ALTER TABLE contributions
                MODIFY COLUMN proof_type
                ENUM('self_report','photo','document','third_party','institutional')
                NOT NULL DEFAULT 'self_report'
            ");
        }
    }
};
