<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Application;

/**
 * PBDIGIT-345 task 11 (E-4; ISO-8, ISO-11): what one technical operation on the operator channel IS. Identifiers only:
 * never business content. `operator` is a RECORDED reference to who ran it (no operator identity exists in release 1;
 * authority is the organisation's joining grant, D-166 (1)).
 */
final readonly class TechnicalOperationRecord
{
    public function __construct(
        public string $operationId,
        public string $organisationId,
        public string $operation,
        public string $operator,
        public \DateTimeImmutable $requestedAt,
    ) {
    }
}
