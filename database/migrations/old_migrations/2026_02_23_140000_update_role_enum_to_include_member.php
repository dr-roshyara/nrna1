<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateRoleEnumToIncludeMember extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update the enum to include 'member' and 'staff' roles
        DB::statement("ALTER TABLE user_organisation_roles CHANGE role role ENUM('admin', 'member', 'staff', 'commission', 'voter') NOT NULL DEFAULT 'member'");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revert to original enum
        DB::statement("ALTER TABLE user_organisation_roles CHANGE role role ENUM('admin', 'commission', 'voter') NOT NULL");
    }
}
