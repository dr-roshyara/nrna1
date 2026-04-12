<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * MySQL does not support modifying ENUM values via Schema::table()
     * cleanly, so we use a raw ALTER TABLE statement.
     * SQLite does not support MODIFY COLUMN or ENUM — skip on SQLite (test env).
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended', 'ended') NOT NULL DEFAULT 'active'");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended') NOT NULL DEFAULT 'active'");
        }
    }
};
