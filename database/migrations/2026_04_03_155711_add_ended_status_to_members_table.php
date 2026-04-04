<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * MySQL does not support modifying ENUM values via Schema::table()
     * cleanly, so we use a raw ALTER TABLE statement.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended', 'ended') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended') NOT NULL DEFAULT 'active'");
    }
};
