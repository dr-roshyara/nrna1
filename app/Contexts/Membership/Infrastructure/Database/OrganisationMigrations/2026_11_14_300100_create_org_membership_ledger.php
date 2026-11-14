<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ORGANISATION migration set (PBDIGIT-345 task 4; M-5): the Membership ledger in an organisation's own database, AT
 * ITS CURRENT CENTRAL SHAPE (central migrations 2026_10_09_100000, 2026_11_02_180000, 2026_11_03_120000,
 * 2026_11_12_130000 consolidated: a new organisation database has no history to replay).
 *
 * The one difference (F-13): org_memberships.organisation_id references organisation_identity (this database's one
 * organisation), not the central `organisations` table, which does not exist here; the ledger clock references it
 * too. OrganisationMigrationsTest proves parity with the central ledger, constraint by constraint, except that
 * target. RULE: a change to the central ledger needs the same change here (the parity test fails otherwise).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_memberships', function (Blueprint $table) {
            $table->uuid('organisation_id');
            $table->text('id');
            $table->text('person_ref');
            $table->integer('version')->default(0);
            $table->timestampTz('created_at', 6);

            $table->primary(['organisation_id', 'id']);
            $table->unique(['organisation_id', 'person_ref']);
            $table->foreign('organisation_id', 'org_memberships_organisation_identity_fk')->references('id')->on('organisation_identity')->restrictOnDelete();
        });
        DB::statement('ALTER TABLE org_memberships ADD CONSTRAINT org_memberships_version_check CHECK (version >= 0)');

        Schema::create('org_membership_facts', function (Blueprint $table) {
            $table->uuid('organisation_id');
            $table->text('membership_id');
            $table->integer('seq');
            $table->text('fact_id');
            $table->text('kind');
            $table->date('effective_at')->nullable();
            $table->text('initial_term')->nullable();
            $table->date('initial_term_until')->nullable();
            $table->text('target_fact_id')->nullable();
            $table->text('end_kind')->nullable();
            $table->timestampTz('decided_at', 6)->nullable();
            $table->timestampTz('recorded_at', 6);
            $table->jsonb('provenance');
            $table->jsonb('consequences')->nullable();
            $table->smallInteger('g2_version')->nullable();

            $table->primary(['organisation_id', 'membership_id', 'seq']);
            $table->unique(['organisation_id', 'membership_id', 'fact_id']);
            $table->foreign(['organisation_id', 'membership_id'])
                ->references(['organisation_id', 'id'])->on('org_memberships')->restrictOnDelete();
        });
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              ADD CONSTRAINT org_membership_facts_target_fk
                FOREIGN KEY (organisation_id, membership_id, target_fact_id)
                REFERENCES org_membership_facts (organisation_id, membership_id, fact_id),
              ADD CONSTRAINT org_membership_facts_seq_check CHECK (seq >= 1),
              ADD CONSTRAINT org_membership_facts_initial_term_check CHECK (
                initial_term IS NULL
                OR (initial_term IN ('none', 'unlimited') AND initial_term_until IS NULL)
                OR (initial_term = 'until' AND initial_term_until IS NOT NULL)),
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND effective_at IS NOT NULL AND initial_term IS NOT NULL
                    AND target_fact_id IS NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'end' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NOT NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind IN ('annul', 'void') AND effective_at IS NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'correct' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL
                    AND consequences IS NOT NULL AND jsonb_typeof(consequences) = 'array' AND g2_version IS NOT NULL)),
              ADD CONSTRAINT org_membership_facts_g2_version_check CHECK (g2_version IS NULL OR g2_version >= 1),
              ADD CONSTRAINT org_membership_facts_target_not_self_check CHECK (target_fact_id IS NULL OR target_fact_id <> fact_id)
            SQL);

        Schema::create('org_membership_commands', function (Blueprint $table) {
            $table->uuid('organisation_id');
            $table->text('idempotency_key');
            $table->text('fingerprint');
            $table->text('membership_id');
            $table->integer('seq');

            $table->primary(['organisation_id', 'idempotency_key']);
            $table->foreign(['organisation_id', 'membership_id'])
                ->references(['organisation_id', 'id'])->on('org_memberships')->restrictOnDelete();
        });
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_commands
              ADD CONSTRAINT org_membership_commands_fact_fk
                FOREIGN KEY (organisation_id, membership_id, seq)
                REFERENCES org_membership_facts (organisation_id, membership_id, seq)
                ON DELETE RESTRICT
            SQL);

        DB::statement(<<<'SQL'
            CREATE TABLE org_membership_ledger_clocks (
              organisation_id uuid PRIMARY KEY,
              last_recorded_at timestamptz(6) NULL,
              closed_through timestamptz(6) NULL,
              CONSTRAINT org_membership_ledger_clocks_organisation_identity_fk FOREIGN KEY (organisation_id) REFERENCES organisation_identity (id)
            )
            SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('org_membership_ledger_clocks');
        Schema::dropIfExists('org_membership_commands');
        Schema::dropIfExists('org_membership_facts');
        Schema::dropIfExists('org_memberships');
    }
};
