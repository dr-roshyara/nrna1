<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add unique partial index: only one ACTIVE per tenant
        // This enforces the invariant at database level (NOT application level)
        DB::statement('
            CREATE UNIQUE INDEX uniq_active_structure_per_tenant
            ON committee_structures(organisation_id)
            WHERE status = \'active\'
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS uniq_active_structure_per_tenant');
    }
};
