<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

use InvalidArgumentException;

/**
 * A register(ns) — M4's identity NAMESPACE UNIT.
 *
 * ⚠️ Named `IdentifierSeries`, never `Register`: M6 R-M6-8 records that `register`
 * carries THREE senses (doc · ns · art), and M6 §7.5 warns that naming with the
 * corpus's contested word "would deepen the drift".
 *
 * Pure value object. Holds no behaviour beyond identity (PA: "Value Objects remain pure").
 */
final readonly class IdentifierSeries
{
    private string $prefix;

    public function __construct(string $prefix)
    {
        $prefix = trim($prefix);

        if ($prefix === '' || preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $prefix) !== 1) {
            throw new InvalidArgumentException("Invalid identifier series prefix: '{$prefix}'");
        }

        $this->prefix = $prefix;
    }

    public function prefix(): string
    {
        return $this->prefix;
    }

    public function equals(self $other): bool
    {
        return $this->prefix === $other->prefix;
    }

    public function __toString(): string
    {
        return $this->prefix;
    }
}
