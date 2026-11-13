<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-4a (v3.11 §6, §8, §15; standalone completion §4; G-4 interpretation B-2, B-3, B-4, B-8): the Reference
 * Geography knowledge stream and roster, in the CENTRAL database, with database-level guards.
 *
 * - refgeo_sequence: ONE row. The publication lock is `SELECT … FOR UPDATE` on it (G-4e). It holds the last position,
 *   the last roster version (§8 "its own gap-free counter, in the same row"), closed_through_time (W) and the last
 *   recorded_at. Its counters move ONLY through the insert triggers below; W only forward, and never into the future.
 * - refgeo_publications: insert-only; position = last + 1, which advances the row; recorded_at ≥ the previous one
 *   and > W (never adjusted: a write that fails it is refused); (revision, content version) at most once.
 * - refgeo_claims: the claims of one submitted content version; immutable; none added once that version is published.
 * - refgeo_roster_entries: append-only; roster_version = last + 1, which advances the row; a notice (merge id, kind)
 *   once.
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 * Functions use CREATE OR REPLACE: migrate:fresh drops tables but not functions (the G-0 lesson).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_sequence (
              id smallint PRIMARY KEY CHECK (id = 1),
              last_position bigint NOT NULL DEFAULT 0 CHECK (last_position >= 0),
              last_roster_version bigint NOT NULL DEFAULT 0 CHECK (last_roster_version >= 0),
              closed_through_time timestamptz(6) NULL,
              last_recorded_at timestamptz(6) NULL
            )
            SQL);
        DB::statement('INSERT INTO refgeo_sequence (id) VALUES (1)');

        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_publications (
              position bigint PRIMARY KEY CHECK (position > 0),
              kind text NOT NULL CHECK (kind IN ('revision', 'notice')),
              revision_id uuid NULL,
              content_version integer NULL CHECK (content_version IS NULL OR content_version > 0),
              roster_version bigint NOT NULL CHECK (roster_version >= 0),
              governance_rule text NOT NULL,
              independence text NULL,
              recorded_at timestamptz(6) NOT NULL,
              UNIQUE (revision_id, content_version),
              CHECK ((kind = 'revision') = (revision_id IS NOT NULL AND content_version IS NOT NULL))
            )
            SQL);

        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_claims (
              claim_id uuid NOT NULL,
              revision_id uuid NOT NULL,
              content_version integer NOT NULL CHECK (content_version > 0),
              kind text NOT NULL,
              slot_key text NOT NULL,
              value text NULL,
              valid_from date NOT NULL,
              valid_to date NULL CHECK (valid_to IS NULL OR valid_to > valid_from),
              basis_kind text NOT NULL,
              basis_reference text NOT NULL,
              PRIMARY KEY (revision_id, content_version, claim_id)
            )
            SQL);
        DB::statement('CREATE INDEX refgeo_claims_slot ON refgeo_claims (slot_key)');

        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_roster_entries (
              roster_version bigint PRIMARY KEY CHECK (roster_version > 0),
              kind text NOT NULL CHECK (kind IN ('steward-appointed', 'steward-ended', 'matter-reviewer-appointed', 'persons-merged', 'person-merge-reversed', 'conflict-declared')),
              subject char(32) NULL CHECK (subject IS NULL OR subject ~ '^[0-9a-f]{32}$'),
              by_person char(32) NULL CHECK (by_person IS NULL OR by_person ~ '^[0-9a-f]{32}$'),
              matter uuid NULL,
              merge_id text NULL,
              merged jsonb NULL,
              interest boolean NULL,
              recorded_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              UNIQUE (merge_id, kind)
            )
            SQL);

        // --- guards -------------------------------------------------------------------------------------------
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_refuse_change() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              RAISE EXCEPTION '%: rows are never updated or deleted (v3.11 §6, §15)', TG_TABLE_NAME;
            END
            $$
            SQL);
        foreach (['refgeo_publications', 'refgeo_claims', 'refgeo_roster_entries'] as $table) {
            DB::statement("CREATE TRIGGER {$table}_immutable BEFORE UPDATE OR DELETE ON {$table} FOR EACH ROW EXECUTE FUNCTION refgeo_refuse_change()");
        }

        // The sequence row: never deleted; its counters and last reading move only inside the insert triggers
        // (trigger depth > 1); W only forward.
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_sequence_guard() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              IF TG_OP = 'DELETE' THEN
                RAISE EXCEPTION 'refgeo_sequence: the row is never deleted';
              END IF;
              IF pg_trigger_depth() < 2 AND (NEW.last_position <> OLD.last_position
                  OR NEW.last_roster_version <> OLD.last_roster_version
                  OR NEW.last_recorded_at IS DISTINCT FROM OLD.last_recorded_at) THEN
                RAISE EXCEPTION 'refgeo_sequence: counters move only by appending a publication or a roster entry';
              END IF;
              IF OLD.closed_through_time IS NOT NULL
                 AND (NEW.closed_through_time IS NULL OR NEW.closed_through_time < OLD.closed_through_time) THEN
                RAISE EXCEPTION 'refgeo_sequence: closed_through_time never moves back';
              END IF;
              IF NEW.closed_through_time IS DISTINCT FROM OLD.closed_through_time AND NEW.closed_through_time > clock_timestamp() THEN
                RAISE EXCEPTION 'refgeo_sequence: closed_through_time is never in the future (BOUNDARY_NOT_CLOSED)';
              END IF;
              RETURN NEW;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER refgeo_sequence_guard BEFORE UPDATE OR DELETE ON refgeo_sequence FOR EACH ROW EXECUTE FUNCTION refgeo_sequence_guard()');

        // A publication: at the next position, recorded_at ≥ the previous reading and > W; the insert advances the row.
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_publication_append() RETURNS trigger LANGUAGE plpgsql AS $$
            DECLARE s refgeo_sequence%ROWTYPE;
            BEGIN
              SELECT * INTO s FROM refgeo_sequence WHERE id = 1 FOR UPDATE;
              IF NEW.position <> s.last_position + 1 THEN
                RAISE EXCEPTION 'refgeo_publications: position % is not the next position %', NEW.position, s.last_position + 1;
              END IF;
              IF s.last_recorded_at IS NOT NULL AND NEW.recorded_at < s.last_recorded_at THEN
                RAISE EXCEPTION 'refgeo_publications: recorded_at below the previous reading (CLOCK_NOT_PAST_BOUNDARY)';
              END IF;
              IF s.closed_through_time IS NOT NULL AND NEW.recorded_at <= s.closed_through_time THEN
                RAISE EXCEPTION 'refgeo_publications: recorded_at at or below closed_through_time (CLOCK_NOT_PAST_BOUNDARY)';
              END IF;
              UPDATE refgeo_sequence SET last_position = NEW.position, last_recorded_at = NEW.recorded_at WHERE id = 1;
              RETURN NEW;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER refgeo_publication_append BEFORE INSERT ON refgeo_publications FOR EACH ROW EXECUTE FUNCTION refgeo_publication_append()');

        // A roster entry: at the next roster version; the insert advances the row.
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_roster_append() RETURNS trigger LANGUAGE plpgsql AS $$
            DECLARE s refgeo_sequence%ROWTYPE;
            BEGIN
              SELECT * INTO s FROM refgeo_sequence WHERE id = 1 FOR UPDATE;
              IF NEW.roster_version <> s.last_roster_version + 1 THEN
                RAISE EXCEPTION 'refgeo_roster_entries: version % is not the next version %', NEW.roster_version, s.last_roster_version + 1;
              END IF;
              UPDATE refgeo_sequence SET last_roster_version = NEW.roster_version WHERE id = 1;
              RETURN NEW;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER refgeo_roster_append BEFORE INSERT ON refgeo_roster_entries FOR EACH ROW EXECUTE FUNCTION refgeo_roster_append()');

        // Claims: none added to a content version that is already published (B-2).
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_claims_not_published() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              IF EXISTS (SELECT 1 FROM refgeo_publications WHERE revision_id = NEW.revision_id AND content_version = NEW.content_version) THEN
                RAISE EXCEPTION 'refgeo_claims: content version % of % is published; its claims cannot change', NEW.content_version, NEW.revision_id;
              END IF;
              RETURN NEW;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER refgeo_claims_not_published BEFORE INSERT ON refgeo_claims FOR EACH ROW EXECUTE FUNCTION refgeo_claims_not_published()');
    }

    public function down(): void
    {
        foreach (['refgeo_claims', 'refgeo_publications', 'refgeo_roster_entries', 'refgeo_sequence'] as $table) {
            DB::statement("DROP TABLE IF EXISTS {$table}");
        }
    }
};
