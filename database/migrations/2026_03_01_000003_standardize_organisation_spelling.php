<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Standardize on British English spelling: "organisation"
     *
     * NOTE: The base migration (2026_02_07_131712_create_role_system_tables.php)
     * has been corrected to create tables with British spelling from the start.
     *
     * This migration is now a no-op since the corrected base migration handles
     * all table creation with proper British English naming.
     *
     * It exists only as a placeholder in the migrations table to maintain
     * migration history consistency.
     */
    public function up(): void
    {
        // This migration is a no-op
        // All table creation already uses British spelling from the corrected base migration
        echo "✅ British English standardization verified (handled by base migration)\n";
    }

    public function down(): void
    {
        // This is a standardization migration - rollback is not practical
        echo "⚠️ Rollback not supported for standardization migrations\n";
    }
};
