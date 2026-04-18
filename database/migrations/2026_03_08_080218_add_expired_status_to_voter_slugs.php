<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extend the status enum to include 'expired' value for voter slugs
     * that have surpassed their expiration time
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            Schema::table('voter_slugs', function (Blueprint $table) {
                $table->enum('status', ['active', 'voted', 'expired', 'abstained'])
                    ->default('active')
                    ->change();
            });
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE voter_slugs ADD COLUMN status_new VARCHAR(50)");
            DB::statement("UPDATE voter_slugs SET status_new = status::text");
            DB::statement("ALTER TABLE voter_slugs DROP COLUMN status");
            DB::statement("ALTER TABLE voter_slugs RENAME COLUMN status_new TO status");
            DB::statement("ALTER TABLE voter_slugs ADD CONSTRAINT voter_slugs_status_check CHECK (status IN ('active', 'voted', 'expired', 'abstained'))");
            DB::statement("ALTER TABLE voter_slugs ALTER COLUMN status SET DEFAULT 'active'");
        }

        // Add status column to demo_voter_slugs if it doesn't exist
        if (Schema::hasTable('demo_voter_slugs') && !Schema::hasColumn('demo_voter_slugs', 'status')) {
            if (DB::getDriverName() === 'mysql') {
                Schema::table('demo_voter_slugs', function (Blueprint $table) {
                    $table->enum('status', ['active', 'voted', 'expired', 'abstained'])
                        ->default('active')
                        ->after('is_active');
                });
            } elseif (DB::getDriverName() === 'pgsql') {
                DB::statement("ALTER TABLE demo_voter_slugs ADD COLUMN status VARCHAR(50) DEFAULT 'active'");
                DB::statement("ALTER TABLE demo_voter_slugs ADD CONSTRAINT demo_voter_slugs_status_check CHECK (status IN ('active', 'voted', 'expired', 'abstained'))");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            Schema::table('voter_slugs', function (Blueprint $table) {
                $table->enum('status', ['active', 'voted'])
                    ->default('active')
                    ->change();
            });
        } elseif (DB::getDriverName() === 'pgsql') {
            DB::statement("UPDATE voter_slugs SET status = 'active' WHERE status IN ('expired', 'abstained')");
            DB::statement("ALTER TABLE voter_slugs DROP CONSTRAINT voter_slugs_status_check");
            DB::statement("ALTER TABLE voter_slugs ADD COLUMN status_new VARCHAR(50)");
            DB::statement("UPDATE voter_slugs SET status_new = status");
            DB::statement("ALTER TABLE voter_slugs DROP COLUMN status");
            DB::statement("ALTER TABLE voter_slugs RENAME COLUMN status_new TO status");
            DB::statement("ALTER TABLE voter_slugs ADD CONSTRAINT voter_slugs_status_check CHECK (status IN ('active', 'voted'))");
            DB::statement("ALTER TABLE voter_slugs ALTER COLUMN status SET DEFAULT 'active'");
        }

        // Drop status column from demo_voter_slugs if we added it
        if (Schema::hasTable('demo_voter_slugs') && Schema::hasColumn('demo_voter_slugs', 'status')) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("ALTER TABLE demo_voter_slugs DROP CONSTRAINT IF EXISTS demo_voter_slugs_status_check");
            }
            Schema::table('demo_voter_slugs', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
