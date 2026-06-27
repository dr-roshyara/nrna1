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
        $connection = DB::connection()->getDriverName();

        if ($connection === 'pgsql') {
            // PostgreSQL: Update CHECK constraint
            DB::statement("ALTER TABLE election_memberships DROP CONSTRAINT election_memberships_role_check");
            DB::statement("ALTER TABLE election_memberships ADD CONSTRAINT election_memberships_role_check CHECK (role IN ('voter', 'candidate', 'observer', 'admin', 'committee'))");
        } elseif ($connection === 'mysql') {
            // MySQL: Modify the enum
            Schema::table('election_memberships', function (Blueprint $table) {
                $table->enum('role', ['voter', 'candidate', 'observer', 'admin', 'committee'])->change();
            });
        } else {
            // SQLite: No action needed
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = DB::connection()->getDriverName();

        if ($connection === 'pgsql') {
            DB::statement("ALTER TABLE election_memberships DROP CONSTRAINT election_memberships_role_check");
            DB::statement("ALTER TABLE election_memberships ADD CONSTRAINT election_memberships_role_check CHECK (role IN ('voter', 'candidate', 'observer', 'admin'))");
        } elseif ($connection === 'mysql') {
            Schema::table('election_memberships', function (Blueprint $table) {
                $table->enum('role', ['voter', 'candidate', 'observer', 'admin'])->change();
            });
        }
    }
};
