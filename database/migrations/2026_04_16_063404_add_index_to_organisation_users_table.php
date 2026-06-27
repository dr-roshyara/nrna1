<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add index for foreign key reference from election_memberships
        Schema::table('organisation_users', function (Blueprint $table) {
            $table->index(['user_id', 'organisation_id'], 'idx_organisation_users_user_org');
        });

        // Fix foreign key constraint (MySQL only - SQLite doesn't support altering FK on existing tables)
        if (DB::getDriverName() === 'mysql') {
            // First try to drop the old malformed one
            try {
                DB::statement('ALTER TABLE election_memberships DROP FOREIGN KEY election_memberships_user_id_organisation_id_foreign');
            } catch (\Exception $e) {
                // Key might not exist, continue
            }

            // Add the correct foreign key
            DB::statement('ALTER TABLE election_memberships ADD CONSTRAINT election_memberships_user_id_organisation_id_foreign FOREIGN KEY (`user_id`, `organisation_id`) REFERENCES `organisation_users` (`user_id`, `organisation_id`) ON DELETE CASCADE');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE election_memberships DROP FOREIGN KEY election_memberships_user_id_organisation_id_foreign');
            } catch (\Exception $e) {
                // Ignore
            }
        }

        Schema::table('organisation_users', function (Blueprint $table) {
            $table->dropIndex('idx_organisation_users_user_org');
        });
    }
};
