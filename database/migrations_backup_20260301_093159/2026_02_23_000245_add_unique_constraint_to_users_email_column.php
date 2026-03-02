<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToUsersEmailColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add unique constraint to email column (if it doesn't already exist)
            // This prevents duplicate emails from being created in the database
            // NOTE: This constraint already exists in the database

            // Check if the constraint already exists
            $indexes = DB::select(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                 WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'email' AND CONSTRAINT_NAME = 'users_email_unique'"
            );

            // Only add if it doesn't exist
            if (empty($indexes)) {
                $table->unique('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the unique constraint on email (if it exists)
            // Check if the constraint exists before dropping
            $indexes = DB::select(
                "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                 WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'email' AND CONSTRAINT_NAME = 'users_email_unique'"
            );

            if (!empty($indexes)) {
                $table->dropUnique(['email']);
            }
        });
    }
}
