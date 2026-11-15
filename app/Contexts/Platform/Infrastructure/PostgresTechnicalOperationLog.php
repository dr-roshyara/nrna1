<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure;

use App\Contexts\Platform\Application\Ports\TechnicalOperationLog;
use App\Contexts\Platform\Application\TechnicalOperationRecord;
use Illuminate\Database\ConnectionInterface;

/** PBDIGIT-345 task 11: the central operator-channel record (`platform_technical_operations`), written before anything is done. */
final class PostgresTechnicalOperationLog implements TechnicalOperationLog
{
    public function __construct(private readonly ConnectionInterface $central)
    {
    }

    public function attempted(TechnicalOperationRecord $record): void
    {
        $this->central->insert("INSERT INTO platform_technical_operations (id, organisation_id, operation, operator, status, started_at)
            VALUES (?::uuid, ?, ?, ?, 'attempted', ?::timestamptz)",
            [$record->operationId, $record->organisationId, $record->operation, $record->operator, $record->requestedAt->format('Y-m-d H:i:s.uP')]);
    }

    public function concluded(TechnicalOperationRecord $record, string $outcome, ?string $reason, bool $reported): void
    {
        $this->central->update("UPDATE platform_technical_operations SET status = ?, reason = ?, reported = ?, finished_at = clock_timestamp()
            WHERE id = ?::uuid AND status = 'attempted'", [$outcome, $reason, $reported, $record->operationId]);
    }
}
