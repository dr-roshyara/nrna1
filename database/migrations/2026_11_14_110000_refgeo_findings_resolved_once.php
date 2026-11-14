<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-6c-1 audit (the Principal review of the integrity pattern, 2026-11-14):
 * - a finding is resolved ONCE (design revision 3 §8.1): one resolving notice, confirmation OR closure, per finding.
 *   The two separate indexes (one confirmation, one closure) allowed a finding to be both confirmed and closed; one
 *   index over both kinds replaces them. (A third resolution, a published correction, is a revision row: the ledger
 *   refuses a correction link to a publication without an open finding, G-6c-5.)
 * - the notice-kind value CHECK gets a stable, explicit name (it was auto-named).
 * PRODUCTION: a Reference Geography migration: not run in production until the G-4 migration-approval control exists.
 */
return new class extends Migration {
    public function up(): void
    {
        // Migration safety (Principal review 2026-11-14): the earlier separate indexes allowed one confirmation AND one
        // closure for the same finding. Such history is never rewritten: stop, name the findings, change nothing.
        $twice = array_map(static fn ($r): int => (int) $r->concerns_position, DB::select(
            "SELECT concerns_position FROM refgeo_publications WHERE notice_kind IN ('confirmation', 'closure')
             GROUP BY concerns_position HAVING count(*) > 1 ORDER BY concerns_position"));
        if ($twice !== []) {
            throw new \RuntimeException('Reference Geography: finding(s) resolved more than once: ' . implode(', ', $twice)
                . '. A finding is resolved once (design revision 3 §8.1). Governance history is not rewritten: report these '
                . 'positions to the Principal for a business resolution before migrating.');
        }

        DB::statement('DROP INDEX IF EXISTS refgeo_publications_one_confirmation');
        DB::statement('DROP INDEX IF EXISTS refgeo_publications_one_closure');
        DB::statement("CREATE UNIQUE INDEX refgeo_publications_one_resolution ON refgeo_publications (concerns_position) WHERE notice_kind IN ('confirmation', 'closure')");
        DB::statement('ALTER TABLE refgeo_publications RENAME CONSTRAINT refgeo_publications_notice_kind_check TO refgeo_publications_notice_kind_known');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE refgeo_publications RENAME CONSTRAINT refgeo_publications_notice_kind_known TO refgeo_publications_notice_kind_check');
        DB::statement('DROP INDEX IF EXISTS refgeo_publications_one_resolution');
        DB::statement("CREATE UNIQUE INDEX refgeo_publications_one_confirmation ON refgeo_publications (concerns_position) WHERE notice_kind = 'confirmation'");
        DB::statement("CREATE UNIQUE INDEX refgeo_publications_one_closure ON refgeo_publications (concerns_position) WHERE notice_kind = 'closure'");
    }
};
