<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-4d (standalone completion §4: refgeo_revisions, refgeo_revision_versions, refgeo_assessments,
 * refgeo_approvals, refgeo_dependency_predicates; G-4 interpretation §3): the Revision aggregate, in the CENTRAL
 * database, outside the knowledge stream (v3.11 §15: invisible until a publication references them).
 *
 * - refgeo_revisions: identity, state, row_version (optimistic concurrency of the aggregate).
 * - Append-only (history that only grows): refgeo_revision_versions (each submitted content version, its frozen
 *   digest and assessor), refgeo_approvals, refgeo_revision_persons (preparers, excluded), refgeo_judgment_flags.
 * - Replaced on each save (the version being prepared): refgeo_revision_claims, refgeo_revision_evidence,
 *   refgeo_assessments, refgeo_revision_validations, refgeo_dependency_predicates.
 * - A submitted version's claims are ALSO frozen in refgeo_claims (G-4a, immutable).
 * Nothing here is ever trusted on load: the repository rebuilds only through Revision::fromSnapshot.
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_revisions (
              id uuid PRIMARY KEY,
              state text NOT NULL CHECK (state IN ('draft', 'submitted', 'stale', 'withdrawn')),
              row_version integer NOT NULL CHECK (row_version > 0),
              updated_at timestamptz(6) NOT NULL DEFAULT clock_timestamp()
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_revision_versions (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              content_version integer NOT NULL CHECK (content_version > 0),
              digest char(64) NOT NULL CHECK (digest ~ '^[0-9a-f]{64}$'),
              assessor char(32) NOT NULL CHECK (assessor ~ '^[0-9a-f]{32}$'),
              PRIMARY KEY (revision_id, content_version)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_revision_claims (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              claim_id uuid NOT NULL,
              seq integer NOT NULL DEFAULT 2147483647,
              slot_key text NOT NULL,
              value text NULL,
              valid_from date NOT NULL,
              valid_to date NULL,
              basis_kind text NOT NULL,
              basis_reference text NOT NULL,
              PRIMARY KEY (revision_id, claim_id)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_revision_evidence (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              evidence_id text NOT NULL,
              seq integer NOT NULL,
              fingerprint char(64) NOT NULL,
              issuer text NULL,
              issued_on date NULL,
              mandates jsonb NOT NULL DEFAULT '[]',
              precedence_rules jsonb NOT NULL DEFAULT '[]',
              PRIMARY KEY (revision_id, evidence_id)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_assessments (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              seq integer NOT NULL,
              kind text NOT NULL,
              outcome text NOT NULL,
              by_person char(32) NOT NULL,
              PRIMARY KEY (revision_id, seq)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_approvals (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              seq integer NOT NULL,
              content_version integer NOT NULL CHECK (content_version > 0),
              roster_version bigint NOT NULL CHECK (roster_version > 0),
              by_person char(32) NOT NULL,
              PRIMARY KEY (revision_id, seq)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_revision_persons (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              role text NOT NULL CHECK (role IN ('preparer', 'excluded')),
              seq integer NOT NULL,
              person char(32) NOT NULL,
              PRIMARY KEY (revision_id, role, person)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_judgment_flags (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              seq integer NOT NULL,
              by_person char(32) NOT NULL,
              reason text NOT NULL,
              PRIMARY KEY (revision_id, seq)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_revision_validations (
              revision_id uuid PRIMARY KEY REFERENCES refgeo_revisions (id),
              p0 bigint NOT NULL CHECK (p0 >= 0),
              structural boolean NOT NULL,
              violations jsonb NOT NULL,
              before_images jsonb NOT NULL,
              disputed jsonb NOT NULL
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE refgeo_dependency_predicates (
              revision_id uuid NOT NULL REFERENCES refgeo_revisions (id),
              seq integer NOT NULL,
              form text NOT NULL,
              slot_key text NULL,
              kind text NULL,
              fixed jsonb NULL,
              value text NULL,
              subject uuid NULL,
              valid_from date NULL,
              valid_to date NULL,
              PRIMARY KEY (revision_id, seq)
            )
            SQL);
        foreach (['refgeo_revision_versions', 'refgeo_approvals', 'refgeo_revision_persons', 'refgeo_judgment_flags'] as $table) {
            DB::statement("CREATE TRIGGER {$table}_append_only BEFORE UPDATE OR DELETE ON {$table} FOR EACH ROW EXECUTE FUNCTION refgeo_refuse_change()");
        }
        // the atlas reader's family query (G-4d): published claims of one kind with one value
        DB::statement('CREATE INDEX refgeo_claims_kind_value ON refgeo_claims (kind, value)');
    }

    public function down(): void
    {
        foreach (['refgeo_dependency_predicates', 'refgeo_revision_validations', 'refgeo_judgment_flags', 'refgeo_revision_persons', 'refgeo_approvals',
            'refgeo_assessments', 'refgeo_revision_evidence', 'refgeo_revision_claims', 'refgeo_revision_versions', 'refgeo_revisions'] as $table) {
            DB::statement("DROP TABLE IF EXISTS {$table}");
        }
        DB::statement('DROP INDEX IF EXISTS refgeo_claims_kind_value');
    }
};
