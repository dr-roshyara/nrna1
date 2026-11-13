<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-345 task 2 (central database): the Platform registry, one row per served organisation (D-193 Part 4).
 * The checks mirror the ServedOrganisation invariants, so a write that skips the domain still cannot store an
 * impossible entry; and ONE DATABASE PER ORGANISATION (D-222) is a unique constraint: no two organisations can ever
 * be routed to the same database. A credential is stored as a reference, never as a secret.
 */
return new class extends Migration {
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE platform_served_organisations (
              id uuid PRIMARY KEY,
              status text NOT NULL CHECK (status IN ('pending', 'provisioning', 'operational')),
              schema_state text NULL CHECK (schema_state IS NULL OR schema_state IN ('current', 'migration-failed')),
              locator_cluster text NULL,
              locator_database text NULL,
              locator_credential_ref text NULL,
              counterparty_evidence_ref text NULL,
              grant_terms_version text NULL,
              grant_accepted_at timestamptz(6) NULL,
              grant_accepted_by text NULL,
              grant_fingerprints jsonb NULL,
              row_version integer NOT NULL DEFAULT 1 CHECK (row_version > 0),
              created_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              updated_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              CHECK ((locator_cluster IS NULL) = (locator_database IS NULL) AND (locator_database IS NULL) = (locator_credential_ref IS NULL)),
              CHECK ((grant_terms_version IS NULL) = (grant_accepted_at IS NULL) AND (grant_accepted_at IS NULL) = (grant_accepted_by IS NULL)
                     AND (grant_accepted_by IS NULL) = (grant_fingerprints IS NULL)),
              CHECK (status <> 'pending' OR (locator_cluster IS NULL AND schema_state IS NULL)),
              CHECK (status = 'pending' OR (counterparty_evidence_ref IS NOT NULL AND grant_terms_version IS NOT NULL)),
              CHECK (status <> 'operational' OR (locator_cluster IS NOT NULL AND schema_state IS NOT NULL))
            )
            SQL);
        DB::statement('CREATE UNIQUE INDEX platform_served_organisations_one_database ON platform_served_organisations (locator_cluster, locator_database) WHERE locator_database IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS platform_served_organisations');
    }
};
