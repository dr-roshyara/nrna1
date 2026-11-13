<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-6a provenance (closure design: Candidate "Accept (→ ProposeRevision)"): the revision an acceptance
 * proposes belongs to that candidate only. The application prepares a NEW revision on acceptance; this index refuses
 * any second candidate naming the same revision, whatever path wrote it.
 * PRODUCTION: a Reference Geography migration: not run in production until the G-4 migration-approval control exists.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::statement('CREATE UNIQUE INDEX refgeo_candidates_one_per_revision ON refgeo_candidates (revision_id) WHERE revision_id IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS refgeo_candidates_one_per_revision');
    }
};
