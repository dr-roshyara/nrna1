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
        } elseif (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE members ADD COLUMN status_new VARCHAR(50)");
            DB::statement("UPDATE members SET status_new = status::text");
            DB::statement("ALTER TABLE members DROP COLUMN status");
            DB::statement("ALTER TABLE members RENAME COLUMN status_new TO status");
            DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status IN ('active', 'expired', 'suspended', 'ended'))");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended') NOT NULL DEFAULT 'active'");
        } elseif (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("UPDATE members SET status = 'active' WHERE status = 'ended'");
            DB::statement("ALTER TABLE members DROP CONSTRAINT members_status_check");
            DB::statement("ALTER TABLE members ADD COLUMN status_new VARCHAR(50)");
            DB::statement("UPDATE members SET status_new = status");
            DB::statement("ALTER TABLE members DROP COLUMN status");
            DB::statement("ALTER TABLE members RENAME COLUMN status_new TO status");
            DB::statement("ALTER TABLE members ADD CONSTRAINT members_status_check CHECK (status IN ('active', 'expired', 'suspended'))");
        }
    }
};
