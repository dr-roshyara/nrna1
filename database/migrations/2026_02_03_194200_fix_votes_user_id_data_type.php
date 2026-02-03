<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixVotesUserIdDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * Fixes data type inconsistency in votes table.
     * Changes user_id from string to unsignedBigInteger to match codes table.
     * Adds foreign key constraint to users table.
     *
     * This ensures consistency across all voting tables:
     * - codes.user_id = unsignedBigInteger
     * - votes.user_id = unsignedBigInteger (after this migration)
     * - voter_registrations.user_id = unsignedBigInteger
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            // Convert string user_id to unsignedBigInteger
            // This matches the data type used in codes table
            $table->unsignedBigInteger('user_id')->change();

            // Add foreign key constraint
            // Uses cascade on delete to maintain referential integrity
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['user_id']);

            // Convert back to string
            $table->string('user_id')->change();
        });
    }
}
