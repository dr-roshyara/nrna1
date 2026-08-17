<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

use App\Contexts\Election\Domain\DomainEvent;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * One protocol entry: a recorded business fact OR a recorded refusal, always
 * carrying its history kind (F-PROTO-1 property 7) and optionally bound to a
 * voting opportunity (property 4). The protocol records; it does not decide
 * (EM-VOC-008), and no entry may exist for a phase that never occurred
 * (EM-GOV-005, negative half). @immutable
 */
final readonly class ProtocolEntry
{
    private function __construct(
        public HistoryKind $kind,
        public ?DomainEvent $event,
        public ?RefusalRecord $refusal,
        public RecordedInstant $recordedAt,
        public ?string $opportunityReference,
    ) {
    }

    public static function event(
        HistoryKind $kind,
        DomainEvent $event,
        RecordedInstant $recordedAt,
        ?string $opportunityReference = null,
    ): self {
        return new self($kind, $event, null, $recordedAt, $opportunityReference);
    }

    /** F-PROTO-1 property 6: a refusal needs no state-transition success to be recorded. */
    public static function refusal(
        HistoryKind $kind,
        RefusalRecord $refusal,
        RecordedInstant $recordedAt,
        ?string $opportunityReference = null,
    ): self {
        return new self($kind, null, $refusal, $recordedAt, $opportunityReference);
    }

    public function isRefusal(): bool
    {
        return $this->refusal !== null;
    }
}
