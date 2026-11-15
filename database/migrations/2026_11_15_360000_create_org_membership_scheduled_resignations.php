<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-354 S2-4b (D-201, D-204; M-TDD-01 §5.2): scheduled resignation declarations, held OUTSIDE the ledger.
 *
 * up():
 *   - org_membership_scheduled_resignations: one row per declaration, keyed (organisation, membership, declaration).
 *     It is not a ledger fact: C1 / C2 never read it (MI-4). The declared fields (period, dates, provenance = the
 *     authority answer, decided_at, recorded_at) are written once; state moves once, from `scheduled` to `withdrawn`
 *     (withdrawn_on, withdrawn_by), `executed` (executed_fact_id: the End it recorded) or `superseded`
 *     (superseded_because); changed_at is when.
 *   - the database keeps the history: one `scheduled` declaration per period (partial unique index
 *     ..._one_pending); dates and each state's fields CHECKed; a trigger refuses changing a declared field, changing a
 *     final declaration, and any DELETE.
 *   - org_membership_declaration_commands: the idempotency keys of the schedule / withdraw commands (this store's own,
 *     separate from org_membership_commands), never changed or deleted.
 *   - an index for finding due declarations (S2-4c).
 *   The trigger function is CREATE OR REPLACE: `migrate:fresh` drops tables, not functions, so a second run must not
 *   collide with the function left behind (found by the legacy Membership unit suite, S2-4b).
 *   Every key includes organisation_id and refers to the organisation's own membership rows, so the same SQL serves
 *   the central and the organisation set (no foreign key to `organisations` / `organisation_identity`).
 * down(): refuses while any declaration exists; otherwise drops both tables and the trigger function.
 *
 * PARITY (developer_guide/membership/23): the organisation set carries the same SQL in
 * app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_15_300700_create_org_membership_scheduled_resignations.php.
 *
 * RELEASE GATE: as the S2-1 migration. Not on any persistent database before the migration-approval control exists;
 * code and migration are deployed together. Until then the throwaway test cluster only.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE org_membership_scheduled_resignations (
              organisation_id uuid NOT NULL,
              membership_id text NOT NULL,
              declaration_id text NOT NULL,
              period_fact_id text NOT NULL,
              declaration_date date NOT NULL,
              effective_date date NOT NULL,
              provenance jsonb NOT NULL,
              decided_at timestamptz(6) NULL,
              recorded_at timestamptz(6) NOT NULL,
              state text NOT NULL,
              withdrawn_on date NULL,
              withdrawn_by jsonb NULL,
              executed_fact_id text NULL,
              superseded_because text NULL,
              changed_at timestamptz(6) NULL,
              CONSTRAINT org_membership_scheduled_resignations_pkey PRIMARY KEY (organisation_id, membership_id, declaration_id),
              CONSTRAINT org_membership_scheduled_resignations_membership_fk FOREIGN KEY (organisation_id, membership_id)
                REFERENCES org_memberships (organisation_id, id),
              CONSTRAINT org_membership_scheduled_resignations_period_fk FOREIGN KEY (organisation_id, membership_id, period_fact_id)
                REFERENCES org_membership_facts (organisation_id, membership_id, fact_id),
              CONSTRAINT org_membership_scheduled_resignations_executed_fk FOREIGN KEY (organisation_id, membership_id, executed_fact_id)
                REFERENCES org_membership_facts (organisation_id, membership_id, fact_id),
              CONSTRAINT org_membership_scheduled_resignations_dates_check CHECK (effective_date > declaration_date),
              CONSTRAINT org_membership_scheduled_resignations_state_check CHECK (
                (state = 'scheduled' AND withdrawn_on IS NULL AND withdrawn_by IS NULL AND executed_fact_id IS NULL
                    AND superseded_because IS NULL AND changed_at IS NULL)
                OR (state = 'withdrawn' AND withdrawn_on IS NOT NULL AND withdrawn_by IS NOT NULL AND withdrawn_on < effective_date
                    AND executed_fact_id IS NULL AND superseded_because IS NULL AND changed_at IS NOT NULL)
                OR (state = 'executed' AND executed_fact_id IS NOT NULL AND withdrawn_on IS NULL AND withdrawn_by IS NULL
                    AND superseded_because IS NULL AND changed_at IS NOT NULL)
                OR (state = 'superseded' AND superseded_because IS NOT NULL AND btrim(superseded_because) <> ''
                    AND withdrawn_on IS NULL AND withdrawn_by IS NULL AND executed_fact_id IS NULL AND changed_at IS NOT NULL))
            )
            SQL);
        DB::statement("CREATE UNIQUE INDEX org_membership_scheduled_resignations_one_pending ON org_membership_scheduled_resignations (organisation_id, membership_id, period_fact_id) WHERE state = 'scheduled'");
        DB::statement("CREATE INDEX org_membership_scheduled_resignations_due ON org_membership_scheduled_resignations (organisation_id, effective_date) WHERE state = 'scheduled'");
        DB::statement(<<<'SQL'
            CREATE TABLE org_membership_declaration_commands (
              organisation_id uuid NOT NULL,
              idempotency_key text NOT NULL,
              fingerprint text NOT NULL,
              membership_id text NOT NULL,
              declaration_id text NOT NULL,
              recorded_at timestamptz(6) NOT NULL,
              CONSTRAINT org_membership_declaration_commands_pkey PRIMARY KEY (organisation_id, idempotency_key),
              CONSTRAINT org_membership_declaration_commands_declaration_fk FOREIGN KEY (organisation_id, membership_id, declaration_id)
                REFERENCES org_membership_scheduled_resignations (organisation_id, membership_id, declaration_id)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION org_membership_scheduled_resignation_guard() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              IF TG_OP = 'DELETE' THEN
                RAISE EXCEPTION 'SCHEDULED_RESIGNATION_HISTORY: % rows are never deleted', TG_TABLE_NAME;
              END IF;
              IF TG_TABLE_NAME = 'org_membership_declaration_commands' THEN
                RAISE EXCEPTION 'SCHEDULED_RESIGNATION_HISTORY: % rows are never changed', TG_TABLE_NAME;
              END IF;
              IF OLD.state <> 'scheduled' THEN
                RAISE EXCEPTION 'SCHEDULED_RESIGNATION_FINAL: a % declaration never changes', OLD.state;
              END IF;
              IF (NEW.organisation_id, NEW.membership_id, NEW.declaration_id, NEW.period_fact_id, NEW.declaration_date, NEW.effective_date,
                  NEW.provenance, NEW.decided_at, NEW.recorded_at)
                 IS DISTINCT FROM (OLD.organisation_id, OLD.membership_id, OLD.declaration_id, OLD.period_fact_id, OLD.declaration_date,
                  OLD.effective_date, OLD.provenance, OLD.decided_at, OLD.recorded_at) THEN
                RAISE EXCEPTION 'SCHEDULED_RESIGNATION_DECLARED_FIELDS: the declaration itself is never changed';
              END IF;
              RETURN NEW;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER org_membership_scheduled_resignations_guard BEFORE UPDATE OR DELETE ON org_membership_scheduled_resignations FOR EACH ROW EXECUTE FUNCTION org_membership_scheduled_resignation_guard()');
        DB::statement('CREATE TRIGGER org_membership_declaration_commands_guard BEFORE UPDATE OR DELETE ON org_membership_declaration_commands FOR EACH ROW EXECUTE FUNCTION org_membership_scheduled_resignation_guard()');
    }

    public function down(): void
    {
        $rows = (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_scheduled_resignations')->n;
        if ($rows > 0) {
            throw new RuntimeException("S2-4b down() refused: {$rows} scheduled resignation declaration(s) exist; dropping them would destroy history. Nothing was changed.");
        }
        DB::statement('DROP TABLE org_membership_declaration_commands');
        DB::statement('DROP TABLE org_membership_scheduled_resignations');
        DB::statement('DROP FUNCTION org_membership_scheduled_resignation_guard()');
    }
};
