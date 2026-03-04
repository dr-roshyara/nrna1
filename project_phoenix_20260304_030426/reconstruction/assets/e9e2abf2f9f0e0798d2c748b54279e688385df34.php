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
     * Adds organisation_id column to voter_slug_steps table to support
     * BelongsToTenant global scope and multi-tenancy isolation.
     */
    public function up(): void
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            // Add organisation_id column after election_id for logical grouping
            if (!Schema::hasColumn('voter_slug_steps', 'organisation_id')) {
                $table->unsignedBigInteger('organisation_id')
                    ->after('election_id')
                    ->nullable();

                // Add foreign key constraint
                $table->foreign('organisation_id')
                    ->references('id')
                    ->on('organisations')
                    ->onDelete('cascade');

                // Add index for tenant filtering
                $table->index('organisation_id');
            }
        });

        // Populate existing records with organisation_id from related voter_slug
        if (DB::table('voter_slug_steps')->whereNull('organisation_id')->exists()) {
            DB::statement('
                UPDATE voter_slug_steps vss
                JOIN voter_slugs vs ON vss.voter_slug_id = vs.id
                SET vss.organisation_id = vs.organisation_id
                WHERE vss.organisation_id IS NULL
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasForeignKey('voter_slug_steps', 'voter_slug_steps_organisation_id_foreign')) {
                $table->dropForeign('voter_slug_steps_organisation_id_foreign');
            }

            // Drop index if it exists
            if (Schema::hasIndex('voter_slug_steps', 'voter_slug_steps_organisation_id_index')) {
                $table->dropIndex('voter_slug_steps_organisation_id_index');
            }

            // Drop column if it exists
            if (Schema::hasColumn('voter_slug_steps', 'organisation_id')) {
                $table->dropColumn('organisation_id');
            }
        });
    }
};
