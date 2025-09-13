<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatabaseColumnsToElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->string('database_name')->nullable()->after('status');
            $table->string('database_host')->nullable()->after('database_name');
            $table->string('database_port')->nullable()->after('database_host');
            $table->string('database_username')->nullable()->after('database_port');
            $table->text('database_password')->nullable()->after('database_username');
            $table->string('database_connection_name')->nullable()->after('database_password');
            $table->timestamp('database_created_at')->nullable()->after('database_connection_name');
            
            // Add indexes for performance
            $table->index('database_name');
            $table->index('database_connection_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex(['database_name']);
            $table->dropIndex(['database_connection_name']);
            $table->dropColumn([
                'database_name',
                'database_host', 
                'database_port',
                'database_username',
                'database_password',
                'database_connection_name',
                'database_created_at'
            ]);
        });
    }
    
}
