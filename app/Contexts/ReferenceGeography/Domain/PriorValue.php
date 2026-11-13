<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * What a claim replaced at some time (v3.11 §5.1): a VALUE, NONE (a prior withdrawal was in force) or ABSENT
 * (no claim had ever covered that time in that slot). Kept apart, because each means something different.
 */
final readonly class PriorValue
{
    private const NONE = 'NONE';
    private const ABSENT = 'ABSENT';

    private function __construct(private string $kind, private ?string $value)
    {
    }

    public static function of(string $value): self
    {
        return new self('VALUE', $value);
    }

    public static function none(): self
    {
        return new self(self::NONE, null);
    }

    public static function absent(): self
    {
        return new self(self::ABSENT, null);
    }

    public function isValue(string $value): bool
    {
        return $this->kind === 'VALUE' && $this->value === $value;
    }

    public function isNone(): bool
    {
        return $this->kind === self::NONE;
    }

    public function isAbsent(): bool
    {
        return $this->kind === self::ABSENT;
    }

    public function equals(self $other): bool
    {
        return $this->kind === $other->kind && $this->value === $other->value;
    }

    /** The value itself, or NONE / ABSENT. */
    public function describe(): string
    {
        return $this->value ?? $this->kind;
    }
}
