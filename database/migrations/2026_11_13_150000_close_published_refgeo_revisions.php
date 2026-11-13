<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-4f (published claims never change; a correction is a new revision, D-228): a revision with a
 * publication is closed. Every save updates the revision row first (optimistic row_version), so refusing any UPDATE
 * or DELETE of that row closes the whole aggregate to every writer. Publication locks the row (G-4f), so a save racing
 * a publication waits for it and is then refused here.
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION refgeo_revision_closed_when_published() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              IF EXISTS (SELECT 1 FROM refgeo_publications WHERE revision_id = OLD.id) THEN
                RAISE EXCEPTION 'refgeo_revisions: revision % is published and closed (REVISION_ALREADY_PUBLISHED)', OLD.id;
              END IF;
              RETURN CASE WHEN TG_OP = 'DELETE' THEN OLD ELSE NEW END;
            END
            $$
            SQL);
        DB::statement('CREATE TRIGGER refgeo_revision_closed_when_published BEFORE UPDATE OR DELETE ON refgeo_revisions FOR EACH ROW EXECUTE FUNCTION refgeo_revision_closed_when_published()');
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS refgeo_revision_closed_when_published ON refgeo_revisions');
    }
};
