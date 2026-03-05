<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Updates platform-wide records to use organisation_id = 0 instead of NULL
     * This provides cleaner validation logic and better performance
     */
    public function up(): void
    {
        // ✅ Update platform-wide elections (currently NULL) to 0
        DB::table('elections')
            ->whereNull('organisation_id')
            ->update(['organisation_id' => 0]);

        // ✅ Update voter slugs that reference platform elections
        DB::table('voter_slugs')
            ->whereIn('election_id', function($query) {
                $query->select('id')->from('elections')->where('organisation_id', 0);
            })
            ->whereNull('organisation_id')
            ->update(['organisation_id' => 0]);

        // ✅ Update demo codes that reference platform elections
        DB::table('demo_codes')
            ->whereIn('election_id', function($query) {
                $query->select('id')->from('elections')->where('organisation_id', 0);
            })
            ->whereNull('organisation_id')
            ->update(['organisation_id' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ⚠️ Rollback changes - convert 0 back to NULL for platform records
        DB::table('elections')
            ->where('organisation_id', 0)
            ->where('type', 'demo')
            ->update(['organisation_id' => null]);

        DB::table('voter_slugs')
            ->where('organisation_id', 0)
            ->update(['organisation_id' => null]);

        DB::table('demo_codes')
            ->where('organisation_id', 0)
            ->update(['organisation_id' => null]);
    }
};
