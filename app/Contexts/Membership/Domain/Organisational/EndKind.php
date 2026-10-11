<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The kind of an End: a closed set (D-3 = E1, PO decision 2026-10-11). `legacy` is reserved for attested
 * migration history (enforced by Member::decideEnd). Non-payment is a ground for exclusion, not a kind;
 * term expiry is derived, not an End. No other value can exist in the domain.
 * Resolution never reads it (contract §18).
 */
final readonly class EndKind
{
    public const RESIGNATION = 'resignation';
    public const EXCLUSION = 'exclusion';
    public const DEATH = 'death';
    public const LEGACY = 'legacy';

    private const RECOGNISED = [self::RESIGNATION, self::EXCLUSION, self::DEATH, self::LEGACY];

    private function __construct(private string $value)
    {
        if (!in_array($value, self::RECOGNISED, true)) {
            throw new \InvalidArgumentException("EndKind '$value' is not a recognised ending act.");
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    /** null for an unrecognised value; the command boundary turns it into a refusal. */
    public static function tryFromString(string $value): ?self
    {
        return in_array($value, self::RECOGNISED, true) ? new self($value) : null;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isLegacy(): bool
    {
        return $this->value === self::LEGACY;
    }
}
