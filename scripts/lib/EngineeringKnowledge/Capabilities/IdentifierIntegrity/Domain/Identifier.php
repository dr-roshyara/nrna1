<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

use InvalidArgumentException;

/**
 * An identifier — the assigned or intrinsic name by which a governed artifact is cited.
 *
 * M4 §1.1  identity is per-kind; mode 1 is durable-global, register(ns)-namespaced.
 * M4 §1.3  SEMANTIC identity, never REPRESENTATIONAL — an identifier is not a filename.
 *
 * Pure value object: parses, compares, and nothing else. It knows its series but holds
 * no policy and performs no validation.
 */
final readonly class Identifier
{
    private function __construct(
        private string $value,
        private IdentifierSeries $series,
        private string $ordinal,
    ) {
    }

    public static function fromString(string $value): self
    {
        $value = trim($value);

        if ($value === '') {
            throw new InvalidArgumentException('Identifier must not be empty.');
        }

        // PREFIX-ORDINAL, e.g. R-65 · ES-005 · ADR-T22 · CAP-001 · PMR-10
        if (preg_match('/^([A-Za-z][A-Za-z0-9]*)-([A-Za-z0-9.]+)$/', $value, $m) !== 1) {
            throw new InvalidArgumentException("Malformed identifier: '{$value}'");
        }

        return new self($value, new IdentifierSeries($m[1]), $m[2]);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function series(): IdentifierSeries
    {
        return $this->series;
    }

    public function ordinal(): string
    {
        return $this->ordinal;
    }

    /**
     * Equality is by full value, so the same ordinal in different register(ns)
     * namespaces is NOT equal — the cross-kind case (R-nn Risk vs R-nn Ruling).
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
