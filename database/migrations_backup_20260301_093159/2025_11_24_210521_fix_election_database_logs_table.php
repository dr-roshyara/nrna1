<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // If the table exists, we'll just log that it's already there
        if (Schema::hasTable('election_database_logs')) {
            \Log::info('election_database_logs table already exists, skipping migration');
        }
    }

    public function down()
    {
        // Nothing to rollback
    }
};