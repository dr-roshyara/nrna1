<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;

/**
 * The outcome of a scheduled resignation command (PBDIGIT-354 S2-4b): Recorded(the declaration as stored now) or
 * Refused(domain or application reason). Not a CommandOutcome: nothing is appended to the ledger (D-204).
 */
final readonly class DeclarationOutcome
{
    private function __construct(
        private ?ScheduledResignation $declaration,
        private ?RefusalReason $domainRefusal,
        private ?ApplicationRefusal $applicationRefusal,
    ) {
    }

    public static function recorded(ScheduledResignation $declaration): self
    {
        return new self($declaration, null, null);
    }

    public static function refusedByDomain(RefusalReason $reason): self
    {
        return new self(null, $reason, null);
    }

    public static function refused(ApplicationRefusal $reason): self
    {
        return new self(null, null, $reason);
    }

    public function isRecorded(): bool
    {
        return $this->declaration !== null;
    }

    public function declaration(): ScheduledResignation
    {
        return $this->declaration ?? throw new \LogicException('The command was refused.');
    }

    public function domainRefusal(): ?RefusalReason
    {
        return $this->domainRefusal;
    }

    public function applicationRefusal(): ?ApplicationRefusal
    {
        return $this->applicationRefusal;
    }
}
