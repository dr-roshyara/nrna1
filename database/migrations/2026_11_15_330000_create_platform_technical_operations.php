<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-345 task 11 (E-4; ISO-8: every technical operation recorded; D-193 gateway rule: recorded FIRST): the
 * central record of operations on the operator channel, refusals included. Identifiers and outcomes only, never
 * business content. organisation_id is not a foreign key: an operation for an unknown organisation is recorded too.
 * CENTRAL migration. PRODUCTION: not before the migration-approval control exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE platform_technical_operations (
              id uuid PRIMARY KEY,
              organisation_id text NOT NULL,
              operation text NOT NULL,
              operator text NOT NULL,
              status text NOT NULL CHECK (status IN ('attempted', 'succeeded', 'failed', 'refused')),
              reason text NULL,
              reported boolean NOT NULL DEFAULT false,
              started_at timestamptz(6) NOT NULL,
              finished_at timestamptz(6) NULL,
              CHECK ((status = 'attempted') = (finished_at IS NULL))
            )
            SQL);
        DB::statement('CREATE INDEX platform_technical_operations_organisation ON platform_technical_operations (organisation_id, started_at)');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS platform_technical_operations');
    }
};
