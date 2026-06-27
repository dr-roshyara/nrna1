<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds platform_role column for platform staff (platform_admin).
     * Keeps is_super_admin boolean for platform owner (super_admin).
     *
     * Role hierarchy:
     * - is_super_admin=true → super_admin (platform owner, can manage admins)
     * - platform_role='platform_admin' → platform_admin (platform staff, can approve elections)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('platform_role')->nullable()->after('is_super_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('platform_role');
        });
    }
};
