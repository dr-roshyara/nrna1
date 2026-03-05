<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Removes user_id column entirely from demo_votes table.
     *
     * ARCHITECTURAL DECISION: Anonymous voting means NO connection between vote and voter.
     * Therefore, user_id must not exist in the table at all.
     *
     * This maintains vote anonymity and enforces it at the schema level.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            // This migration may run on fresh installs where the FK was never created
            $foreign_keys = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME='demo_votes' AND COLUMN_NAME='user_id' AND REFERENCED_TABLE_NAME='users'");

            if (!empty($foreign_keys)) {
                $table->dropForeign(['user_id']);
            }

            // Completely remove user_id column - anonymous votes have no voter identifier
            if (Schema::hasColumn('demo_votes', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Restores the user_id column if this migration is rolled back.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Restore user_id column
            $table->unsignedBigInteger('user_id')->after('id');

            // Restore the foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
