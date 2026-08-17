<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;
use InvalidArgumentException;

/**
 * A recorded REFUSAL: the requested act, its reason, its instant — recordable
 * before, or independently of, any state-transition success (F-PROTO-1 property 6;
 * EM-GOV-005: requests, evaluations, refusals AND their reasons are material
 * events). A refusal is never a termination (P-2H). @immutable
 */
final readonly class RefusalRecord
{
    public function __construct(
        public string $requestedAct,
        public string $reason,
        public RecordedInstant $refusedAt,
    ) {
        if (trim($requestedAct) === '' || trim($reason) === '') {
            throw new InvalidArgumentException('A refusal is recorded with its act and its reason (EM-GOV-005).');
        }
    }
}
