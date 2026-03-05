<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rename user_organization_roles to user_organisation_roles
     * Standardize on British English spelling throughout
     */
    public function up(): void
    {
        // Rename the pivot table from American to British spelling
        if (Schema::hasTable('user_organization_roles')) {
            Schema::rename('user_organization_roles', 'user_organisation_roles');
            echo "✅ Renamed user_organization_roles to user_organisation_roles\n";
        }
    }

    public function down(): void
    {
        // Revert the rename
        if (Schema::hasTable('user_organisation_roles')) {
            Schema::rename('user_organisation_roles', 'user_organization_roles');
        }
    }
};
