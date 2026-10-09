<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Domain\Organisational\RefusalReason;

/** Appended(…) or Refused(domain or application reason). */
final readonly class CommandOutcome
{
    private function __construct(
        private ?Appended $appended,
        private ?RefusalReason $domainRefusal,
        private ?ApplicationRefusal $applicationRefusal,
    ) {
    }

    public static function ofAppended(Appended $appended): self
    {
        return new self($appended, null, null);
    }

    public static function refusedByDomain(RefusalReason $reason): self
    {
        return new self(null, $reason, null);
    }

    public static function refused(ApplicationRefusal $reason): self
    {
        return new self(null, null, $reason);
    }

    public function isAppended(): bool
    {
        return $this->appended !== null;
    }

    public function appended(): Appended
    {
        return $this->appended ?? throw new \LogicException('The command was refused.');
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
