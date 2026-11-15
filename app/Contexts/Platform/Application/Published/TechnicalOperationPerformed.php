<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application\Published;

/**
 * PUBLISHED by Platform (PBDIGIT-345 task 11; ISO-8: "reported to the organisation"): a technical operation was
 * performed on the organisation's own database through the operator channel. Delivered from THAT organisation's outbox,
 * inside its context; the consuming context (Organisation, slice 3) shows it to the organisation. Identifiers only.
 */
final readonly class TechnicalOperationPerformed
{
    public function __construct(
        public string $operationId,
        public string $organisationId,
        public string $operation,
        public string $operator,
        public string $outcome,
        public ?string $reason,
        public string $requestedAt,
    ) {
    }
}
