<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\Ports;

use App\Contexts\Membership\Application\Organisational\Appended;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;

final readonly class AppendResult
{
    private function __construct(private AppendOutcome $outcome, private ?Appended $appended, private ?RefusalReason $refusal = null)
    {
    }

    /**
     * D-240 (S2-4d-Q5): a domain rule checked at record time, under the append's locks, refused it (a pending scheduled
     * resignation takes effect first). Nothing was written; never retried.
     */
    public static function refusedByDomain(RefusalReason $reason): self
    {
        return new self(AppendOutcome::RefusedByDomain, null, $reason);
    }

    /** The domain reason of a RefusedByDomain outcome, else null. */
    public function refusal(): ?RefusalReason
    {
        return $this->refusal;
    }

    public static function success(Appended $appended): self
    {
        return new self(AppendOutcome::Appended, $appended);
    }

    public static function retried(Appended $original): self
    {
        return new self(AppendOutcome::AlreadyApplied, $original);
    }

    public static function failed(AppendOutcome $outcome): self
    {
        if (in_array($outcome, [AppendOutcome::Appended, AppendOutcome::AlreadyApplied, AppendOutcome::RefusedByDomain], true)) {
            throw new \InvalidArgumentException('Use success() / retried() / refusedByDomain().');
        }

        return new self($outcome, null);
    }

    public function outcome(): AppendOutcome
    {
        return $this->outcome;
    }

    /** The recorded fact: the new one (Appended) or the original one (AlreadyApplied). */
    public function appended(): Appended
    {
        return $this->appended ?? throw new \LogicException('Nothing was appended: ' . $this->outcome->value);
    }
}
