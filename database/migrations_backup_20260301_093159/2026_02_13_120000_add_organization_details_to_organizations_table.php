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
        Schema::table('organisations', function (Blueprint $table) {
            // Add email field for official communications
            if (!Schema::hasColumn('organisations', 'email')) {
                $table->string('email')->after('name')->nullable();
            }
            
            // Add address as JSON (street, city, zip, country)
            if (!Schema::hasColumn('organisations', 'address')) {
                $table->json('address')->after('email')->nullable();
            }
            
            // Add representative information as JSON
            if (!Schema::hasColumn('organisations', 'representative')) {
                $table->json('representative')->after('address')->nullable();
            }
            
            // Track who created the organisation
            if (!Schema::hasColumn('organisations', 'created_by')) {
                $table->foreignId('created_by')->nullable()->after('representative')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('organisations', function (Blueprint $table) {
            if (Schema::hasColumn('organisations', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }
            if (Schema::hasColumn('organisations', 'representative')) {
                $table->dropColumn('representative');
            }
            if (Schema::hasColumn('organisations', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('organisations', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
}
