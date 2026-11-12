<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Domain;

/**
 * Durable schema state of an organisation's database, separate from the platform status (D-178 §13.6;
 * D-193 §3.2). `MigrationFailed` blocks routing until repaired. Before the first migration result is recorded
 * there is no schema state at all (the aggregate holds null), which is likewise never routable.
 */
enum SchemaState: string
{
    case Current = 'current';
    case MigrationFailed = 'migration-failed';
}
