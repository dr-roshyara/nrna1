<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddElectionIdToCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds election_id to codes table to support multi-election voting.
     * This allows the same user to verify and vote in multiple elections.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Check if election_id column already exists
            if (!Schema::hasColumn('codes', 'election_id')) {
                // Add election_id column (nullable initially for backward compatibility)
                $table->unsignedBigInteger('election_id')
                      ->nullable()
                      ->after('user_id')
                      ->comment('Reference to elections table - scopes verification codes per election');
            }
        });

        // Add foreign key constraint (if it doesn't exist)
        try {
            Schema::table('codes', function (Blueprint $table) {
                $table->foreign('election_id')
                      ->references('id')
                      ->on('elections')
                      ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist
        }

        // Add indexes (if they don't exist)
        try {
            Schema::table('codes', function (Blueprint $table) {
                $table->index(['election_id', 'user_id']);
                $table->index(['election_id', 'can_vote_now']);
            });
        } catch (\Exception $e) {
            // Indexes might already exist
        }

        // Default existing codes to first election
        DB::table('codes')
            ->whereNull('election_id')
            ->update(['election_id' => 1]);

        // Add unique constraint (if it doesn't exist)
        try {
            Schema::table('codes', function (Blueprint $table) {
                $table->unique(['user_id', 'election_id']);
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            // Drop indexes - use Laravel's convention for index names
            $table->dropIndex(['election_id', 'user_id']);
            $table->dropIndex(['election_id', 'can_vote_now']);
            
            // Drop unique constraint
            $table->dropUnique(['user_id', 'election_id']);
            
            // Drop foreign key first
            $table->dropForeign(['election_id']);
            
            // Drop column
            $table->dropColumn('election_id');
        });
    }
}