<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Add composite unique key (id, organisation_id).
     *
     * Required so that election_memberships can declare a composite foreign key:
     *   FOREIGN KEY (election_id, organisation_id) REFERENCES elections(id, organisation_id)
     *
     * MySQL requires the referenced columns to form a key. The existing PRIMARY KEY covers
     * only `id`; this adds the compound unique needed for the FK reference.
     */
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->unique(['id', 'organisation_id'], 'unique_org_election');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropUnique('unique_org_election');
        });
    }
};
