<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The identity of a membership period: the FactId of the Admit that opened it.
 *
 * Contract Theorem 1: this is a view of FactId, not a second identity system.
 * It is never generated and never stored separately.
 */
final readonly class PeriodId
{
    private function __construct(private FactId $admit)
    {
    }

    public static function ofAdmit(FactId $admit): self
    {
        return new self($admit);
    }

    public static function fromString(string $value): self
    {
        return new self(FactId::fromString($value));
    }

    public function admitFactId(): FactId
    {
        return $this->admit;
    }

    public function value(): string
    {
        return $this->admit->value();
    }

    public function equals(self $other): bool
    {
        return $this->admit->equals($other->admit);
    }
}
