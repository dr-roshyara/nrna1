<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-6c-1 (design revision 3 §11, §17; accepted 2026-11-14): D-90 governance notices in the stream.
 * - refgeo_publications gains the notice fields, additively; CHECKs fix each row's shape; a trigger checks the KIND of
 *   the row a notice or correction points at; one confirmation and one closure per finding. The G-4 triggers
 *   (gap-free positions, clock, immutability) are untouched and apply to notices as to revisions.
 * - refgeo_publication_persons: who took part in a second-person publication (every approver of the published
 *   version, the excluded persons; confirmers from G-6c-4). Append-only and immutable. A superset of what candidate
 *   selection needs (§5.1, §17): the counted approver itself is derived by replaying mayPublish, never stored.
 * PRODUCTION: a Reference Geography migration: not run in production until the G-4 migration-approval control exists.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::statement(<<<'SQL'
            ALTER TABLE refgeo_publications
              ADD COLUMN notice_kind text NULL CHECK (notice_kind IS NULL OR notice_kind IN ('finding', 'confirmation', 'closure')),
              ADD COLUMN concerns_position bigint NULL REFERENCES refgeo_publications (position),
              ADD COLUMN merge_basis jsonb NULL,
              ADD COLUMN ended_by text NULL,
              ADD COLUMN confirmed_by text NULL,
              ADD COLUMN confirmed_digest text NULL,
              ADD COLUMN corrects_position bigint NULL REFERENCES refgeo_publications (position),
              ADD CONSTRAINT refgeo_publications_notice_iff_kind CHECK ((kind = 'notice') = (notice_kind IS NOT NULL)),
              ADD CONSTRAINT refgeo_publications_notice_shape CHECK (notice_kind IS NULL OR (corrects_position IS NULL
                  AND concerns_position IS NOT NULL AND concerns_position < position)),
              ADD CONSTRAINT refgeo_publications_finding_shape CHECK (notice_kind IS DISTINCT FROM 'finding' OR (merge_basis IS NOT NULL
                  AND confirmed_by IS NULL AND confirmed_digest IS NULL AND ended_by IS NULL)),
              ADD CONSTRAINT refgeo_publications_confirmation_shape CHECK (notice_kind IS DISTINCT FROM 'confirmation' OR (confirmed_by IS NOT NULL
                  AND confirmed_digest IS NOT NULL AND merge_basis IS NULL AND ended_by IS NULL)),
              ADD CONSTRAINT refgeo_publications_closure_shape CHECK (notice_kind IS DISTINCT FROM 'closure' OR (ended_by IS NOT NULL
                  AND merge_basis IS NULL AND confirmed_by IS NULL AND confirmed_digest IS NULL)),
              ADD CONSTRAINT refgeo_publications_revision_shape CHECK (kind <> 'revision' OR (concerns_position IS NULL AND merge_basis IS NULL
                  AND ended_by IS NULL AND confirmed_by IS NULL AND confirmed_digest IS NULL)),
              ADD CONSTRAINT refgeo_publications_corrects_backwards CHECK (corrects_position IS NULL OR corrects_position < position)
            SQL);
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_notice_references() RETURNS trigger LANGUAGE plpgsql AS $$
            DECLARE target refgeo_publications%ROWTYPE;
            BEGIN
              IF NEW.concerns_position IS NOT NULL THEN
                SELECT * INTO target FROM refgeo_publications WHERE position = NEW.concerns_position;
                IF NEW.notice_kind = 'finding' AND target.kind <> 'revision' THEN
                  RAISE EXCEPTION 'refgeo_publications: a finding concerns a revision publication (position %)', NEW.concerns_position;
                END IF;
                IF NEW.notice_kind IN ('confirmation', 'closure') AND target.notice_kind IS DISTINCT FROM 'finding' THEN
                  RAISE EXCEPTION 'refgeo_publications: a % concerns a finding (position %)', NEW.notice_kind, NEW.concerns_position;
                END IF;
              END IF;
              IF NEW.corrects_position IS NOT NULL THEN
                SELECT * INTO target FROM refgeo_publications WHERE position = NEW.corrects_position;
                IF target.kind <> 'revision' THEN
                  RAISE EXCEPTION 'refgeo_publications: a correction corrects a revision publication (position %)', NEW.corrects_position;
                END IF;
              END IF;
              RETURN NEW;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER refgeo_notice_references BEFORE INSERT ON refgeo_publications FOR EACH ROW EXECUTE FUNCTION refgeo_notice_references()');
        DB::statement("CREATE UNIQUE INDEX refgeo_publications_one_confirmation ON refgeo_publications (concerns_position) WHERE notice_kind = 'confirmation'");
        DB::statement("CREATE UNIQUE INDEX refgeo_publications_one_closure ON refgeo_publications (concerns_position) WHERE notice_kind = 'closure'");
        DB::statement('CREATE INDEX refgeo_publications_concerns ON refgeo_publications (concerns_position) WHERE concerns_position IS NOT NULL');
        DB::statement('CREATE INDEX refgeo_publications_corrects ON refgeo_publications (corrects_position) WHERE corrects_position IS NOT NULL');

        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_publication_persons (
              position bigint NOT NULL REFERENCES refgeo_publications (position),
              person char(32) NOT NULL,
              role text NOT NULL CHECK (role IN ('approver', 'excluded', 'confirmer')),
              PRIMARY KEY (position, person, role)
            )
            SQL);
        DB::statement('CREATE INDEX refgeo_publication_persons_person ON refgeo_publication_persons (person)');
        DB::statement('CREATE TRIGGER refgeo_publication_persons_immutable BEFORE UPDATE OR DELETE ON refgeo_publication_persons FOR EACH ROW EXECUTE FUNCTION refgeo_refuse_change()');

        $this->backfillPublicationPersons();
    }

    /**
     * Idempotent: indexes the second-person publications written before G-6c (every approver of the published content
     * version; the revision's excluded persons). Publications are closed, so these sets cannot have changed since.
     */
    public function backfillPublicationPersons(): void
    {
        DB::statement(<<<'SQL'
            INSERT INTO refgeo_publication_persons (position, person, role)
            SELECT DISTINCT p.position, a.by_person, 'approver'
              FROM refgeo_publications p JOIN refgeo_approvals a ON a.revision_id = p.revision_id AND a.content_version = p.content_version
             WHERE p.kind = 'revision' AND p.governance_rule = 'second-person-approved'
            UNION
            SELECT DISTINCT p.position, rp.person, 'excluded'
              FROM refgeo_publications p JOIN refgeo_revision_persons rp ON rp.revision_id = p.revision_id AND rp.role = 'excluded'
             WHERE p.kind = 'revision' AND p.governance_rule = 'second-person-approved'
            ON CONFLICT DO NOTHING
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS refgeo_publication_persons');
        DB::statement('DROP TRIGGER IF EXISTS refgeo_notice_references ON refgeo_publications');
        DB::statement('DROP FUNCTION IF EXISTS refgeo_notice_references()');
        DB::statement('ALTER TABLE refgeo_publications DROP COLUMN IF EXISTS notice_kind, DROP COLUMN IF EXISTS concerns_position,
            DROP COLUMN IF EXISTS merge_basis, DROP COLUMN IF EXISTS ended_by, DROP COLUMN IF EXISTS confirmed_by,
            DROP COLUMN IF EXISTS confirmed_digest, DROP COLUMN IF EXISTS corrects_position');
    }
};
