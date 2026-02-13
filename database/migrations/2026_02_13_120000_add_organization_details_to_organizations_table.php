<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationDetailsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Add email field for official communications
            if (!Schema::hasColumn('organizations', 'email')) {
                $table->string('email')->after('name')->nullable();
            }
            
            // Add address as JSON (street, city, zip, country)
            if (!Schema::hasColumn('organizations', 'address')) {
                $table->json('address')->after('email')->nullable();
            }
            
            // Add representative information as JSON
            if (!Schema::hasColumn('organizations', 'representative')) {
                $table->json('representative')->after('address')->nullable();
            }
            
            // Track who created the organization
            if (!Schema::hasColumn('organizations', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('representative')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('organizations', 'representative')) {
                $table->dropColumn('representative');
            }
            if (Schema::hasColumn('organizations', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('organizations', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
}
