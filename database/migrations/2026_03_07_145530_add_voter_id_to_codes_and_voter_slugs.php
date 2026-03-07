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
        // Add voter_id to codes table
        if (Schema::hasTable('codes') && !Schema::hasColumn('codes', 'voter_id')) {
            Schema::table('codes', function (Blueprint $table) {
                $table->uuid('voter_id')->nullable()->after('election_id');
                $table->foreign('voter_id')->references('id')->on('voters')->onDelete('set null');
                $table->index('voter_id');
            });
        }

        // Add voter_id to voter_slugs table
        if (Schema::hasTable('voter_slugs') && !Schema::hasColumn('voter_slugs', 'voter_id')) {
            Schema::table('voter_slugs', function (Blueprint $table) {
                $table->uuid('voter_id')->nullable()->after('election_id');
                $table->foreign('voter_id')->references('id')->on('voters')->onDelete('set null');
                $table->index('voter_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop voter_id from voter_slugs table
        if (Schema::hasTable('voter_slugs') && Schema::hasColumn('voter_slugs', 'voter_id')) {
            Schema::table('voter_slugs', function (Blueprint $table) {
                $table->dropForeignIdFor('voters');
                $table->dropIndex(['voter_id']);
                $table->dropColumn('voter_id');
            });
        }

        // Drop voter_id from codes table
        if (Schema::hasTable('codes') && Schema::hasColumn('codes', 'voter_id')) {
            Schema::table('codes', function (Blueprint $table) {
                $table->dropForeignIdFor('voters');
                $table->dropIndex(['voter_id']);
                $table->dropColumn('voter_id');
            });
        }
    }
};
