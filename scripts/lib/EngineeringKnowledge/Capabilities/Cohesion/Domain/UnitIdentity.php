<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

use InvalidArgumentException;

/**
 * L3 — a designation STABLE and UNIQUE within the analysis scope (INV-L3-2).
 *
 * OQ-1, ratified (AMD4): deterministic DECLARATION-PATH identity. A named unit
 * is its declared name; an anonymous unit is its enclosing path plus a 1-based
 * ordinal among the anonymous declarations of the same enclosing unit, in
 * source order — e.g. "Factory/anon#1/anon#1".
 *
 * Ratified cost, stated by the act: identity is INDEPENDENT of line/column
 * formatting, and SOURCE-ORDER CHANGES MAY CHANGE IDENTITY and therefore
 * invalidate affected declared evidence.
 *
 * The runtime label "(anonymous)" is forbidden: it collides within one file.
 */
final readonly class UnitIdentity
{
    public function __construct(public string $path)
    {
        if ($path === '') {
            throw new InvalidArgumentException('A unit identity is never empty (INV-L3-2).');
        }
        if (str_contains($path, '(anonymous)')) {
            throw new InvalidArgumentException(
                'The runtime label "(anonymous)" is not an identity: it collides within one file (AMD4).'
            );
        }
    }

    public static function named(string $declaredName): self
    {
        return new self($declaredName);
    }

    /** @param positive-int $ordinal */
    public function anonymousChild(int $ordinal): self
    {
        return new self($this->path . '/anon#' . $ordinal);
    }

    public function toString(): string
    {
        return $this->path;
    }
}
