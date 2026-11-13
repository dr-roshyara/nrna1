<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-6a (central database): Reference Geography candidates, submitted missing or changed places
 * (v3.11 §16; D-31). Operational records, outside the knowledge stream (§15): a candidate never becomes a fact; only a
 * separately governed revision does. The checks mirror the Candidate lifecycle. Persons are platform references only
 * (D-225); an organisation submitter is its opaque id.
 * PRODUCTION: a Reference Geography migration. The G-4 condition holds: not run in production until a technically
 * enforced migration-approval control exists.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_candidates (
              id uuid PRIMARY KEY,
              state text NOT NULL CHECK (state IN ('submitted', 'under-verification', 'accepted', 'linked', 'rejected')),
              submitter_kind text NOT NULL CHECK (submitter_kind IN ('organisation', 'steward')),
              submitter_ref text NOT NULL,
              summary text NOT NULL CHECK (btrim(summary) <> ''),
              subject_unit uuid NULL,
              evidence jsonb NOT NULL DEFAULT '[]'::jsonb,
              recorded_by text NOT NULL,
              verification_by text NULL,
              decided_by text NULL,
              revision_id uuid NULL,
              linked_to uuid NULL REFERENCES refgeo_candidates (id),
              reason text NULL,
              row_version integer NOT NULL DEFAULT 1 CHECK (row_version > 0),
              recorded_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              updated_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              CHECK ((state IN ('accepted', 'linked', 'rejected')) = (decided_by IS NOT NULL)),
              CHECK ((state = 'accepted') = (revision_id IS NOT NULL)),
              CHECK ((state = 'linked') = (linked_to IS NOT NULL)),
              CHECK ((state = 'rejected') = (reason IS NOT NULL)),
              CHECK (state <> 'accepted' OR verification_by IS NOT NULL),
              CHECK (linked_to IS NULL OR linked_to <> id)
            )
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS refgeo_candidates');
    }
};
