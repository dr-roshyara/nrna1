<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedAtToUserOrganizationRoles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('user_organisation_roles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_organisation_roles', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('permissions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('user_organisation_roles', function (Blueprint $table) {
            if (Schema::hasColumn('user_organisation_roles', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
        });
    }
}
