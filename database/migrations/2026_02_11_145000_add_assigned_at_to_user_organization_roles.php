<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_organization_roles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_organization_roles', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('permissions');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_organization_roles', function (Blueprint $table) {
            if (Schema::hasColumn('user_organization_roles', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
        });
    }
};
