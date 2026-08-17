<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionLevelCancellation;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the Committee-restoration period expired while Inoperative;
 * "cannot restore" is evidenced solely by that expiry (EM-GOV-065) and the election
 * is cancelled — an ELECTION-LEVEL consequence, an Election Rule, never a service
 * decision (EM-GOV-058). @immutable
 */
final readonly class ElectionCancelledOnRestorationExpiry implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public RecordedInstant $expiredAt,
        public ElectionLevelCancellation $cancellation,
    ) {
    }
}
