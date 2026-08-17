<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Event;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\TerminalStatePlaceholder;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Recorded fact: the halted-recovery period expired without successful recovery —
 * THE one genuinely new recording obligation of EM-GOV-063: the expiry, the failure
 * of recovery, and the resulting state, together. The resulting state renders as
 * ELECTION DISCONTINUED (EM-GOV-069). @immutable
 */
final readonly class RecoveryPeriodExpired implements DomainEvent, NonCanonicalEventName
{
    public function __construct(
        public ElectionId $electionId,
        public PeriodKind $kind,
        public RecordedInstant $expiredAt,
        public bool $recoveryFailed,
        public TerminalStatePlaceholder $resultingState,
    ) {
    }
}
