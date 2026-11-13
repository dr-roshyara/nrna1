<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-6b (central database): objections, an organisation's challenge to a published fact (strategic
 * baseline S1.1; D-84; v3.11 §16). Operational records, outside the knowledge stream (§15): the target claim is never
 * changed here; corrections and disputes act through the revision each one proposes (one revision per objection).
 * The checks mirror the Objection lifecycle. Persons are platform references only (D-225); the organisation is its id.
 * PRODUCTION: a Reference Geography migration: not run in production until the G-4 migration-approval control exists.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_objections (
              id uuid PRIMARY KEY,
              state text NOT NULL CHECK (state IN ('submitted', 'assessed', 'corrected', 'disputed', 'rejected')),
              organisation_id uuid NOT NULL,
              target_claim uuid NOT NULL,
              statement text NOT NULL CHECK (btrim(statement) <> ''),
              evidence jsonb NOT NULL CHECK (jsonb_typeof(evidence) = 'array' AND jsonb_array_length(evidence) > 0),
              recorded_by text NOT NULL,
              authentic boolean NULL,
              mandate boolean NULL,
              applicable boolean NULL,
              assessed_by text NULL,
              decided_by text NULL,
              revision_id uuid NULL,
              reason text NULL,
              row_version integer NOT NULL DEFAULT 1 CHECK (row_version > 0),
              recorded_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              updated_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              CHECK ((state <> 'submitted') = (assessed_by IS NOT NULL AND authentic IS NOT NULL AND mandate IS NOT NULL AND applicable IS NOT NULL)),
              CHECK ((state IN ('corrected', 'disputed', 'rejected')) = (decided_by IS NOT NULL)),
              CHECK ((state IN ('corrected', 'disputed')) = (revision_id IS NOT NULL)),
              CHECK ((state = 'rejected') = (reason IS NOT NULL)),
              CHECK (state NOT IN ('corrected', 'disputed') OR (authentic AND mandate AND applicable)),
              CHECK (state <> 'rejected' OR NOT (authentic AND mandate AND applicable)),
              CHECK (assessed_by IS NULL OR assessed_by <> recorded_by),
              CHECK (decided_by IS NULL OR decided_by <> recorded_by)
            )
            SQL);
        DB::statement('CREATE INDEX refgeo_objections_target ON refgeo_objections (target_claim)');
        DB::statement('CREATE UNIQUE INDEX refgeo_objections_one_per_revision ON refgeo_objections (revision_id) WHERE revision_id IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS refgeo_objections');
    }
};
