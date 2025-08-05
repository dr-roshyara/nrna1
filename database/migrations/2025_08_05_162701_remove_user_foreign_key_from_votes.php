<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Drop foreign key if exists (try-catch is safest for MySQL)
            try {
                DB::statement('ALTER TABLE votes DROP FOREIGN KEY votes_user_id_foreign');
            } catch (\Throwable $e) {
                // Ignore if FK doesn't exist
            }

            // Drop index if exists
            try {
                DB::statement('ALTER TABLE votes DROP INDEX votes_user_id_foreign');
            } catch (\Throwable $e) {
                // Ignore if not there
            }

            // Drop user_id column if it exists
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
