<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The outcome of a decision: Accepted(fact, basedOnVersion) or Refused(reason).
 *
 * basedOnVersion is the Member version the fact was decided against. The fact
 * may only be appended at that version (Slice 1a, R-1a-1 / R-1a-2), so a stale
 * decision cannot be reused.
 */
final readonly class Decision
{
    /** @param list<TrimRevival> $revivedTrims */
    private function __construct(private ?Fact $fact, private ?int $basedOnVersion, private ?RefusalReason $refusal, private array $revivedTrims = [])
    {
    }

    /** @param list<TrimRevival> $revivedTrims earlier trims this fact makes apply again (CFC+; TrimRevived data) */
    public static function accepted(Fact $fact, int $basedOnVersion, array $revivedTrims = []): self
    {
        return new self($fact, $basedOnVersion, null, $revivedTrims);
    }

    /** @return list<TrimRevival> empty for a refusal */
    public function revivedTrims(): array
    {
        return $this->revivedTrims;
    }

    public static function refused(RefusalReason $reason): self
    {
        return new self(null, null, $reason);
    }

    public function isAccepted(): bool
    {
        return $this->refusal === null;
    }

    public function fact(): Fact
    {
        return $this->fact ?? throw new \LogicException('A refused decision has no fact: ' . $this->refusal?->value);
    }

    public function basedOnVersion(): int
    {
        return $this->basedOnVersion ?? throw new \LogicException('A refused decision has no version.');
    }

    public function refusal(): ?RefusalReason
    {
        return $this->refusal;
    }
}
