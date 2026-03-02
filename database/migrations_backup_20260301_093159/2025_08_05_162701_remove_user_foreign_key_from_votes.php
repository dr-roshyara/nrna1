<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Only proceed if votes table and user_id column exist
        if (!Schema::hasTable('votes') || !Schema::hasColumn('votes', 'user_id')) {
            return;
        }

        Schema::table('votes', function (Blueprint $table) {
            // MySQL-specific FK dropping
            if (DB::connection()->getDriverName() === 'mysql') {
                try {
                    DB::statement('ALTER TABLE votes DROP FOREIGN KEY votes_user_id_foreign');
                } catch (\Throwable $e) {
                    // Ignore if FK doesn't exist
                }

                try {
                    DB::statement('ALTER TABLE votes DROP INDEX votes_user_id_foreign');
                } catch (\Throwable $e) {
                    // Ignore if not there
                }
            }

            // Drop user_id column if it exists (SQLite will handle this automatically)
            if (Schema::hasColumn('votes', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'user_id')) {
                $table->string('user_id')->nullable(); // Safe to allow null for rollback
                // You can re-add FK if needed:
                // $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            }
        });
    }
};
