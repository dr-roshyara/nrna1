<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\IdentifierIntegrity\Domain;

use InvalidArgumentException;

/**
 * A numbered section identifier within ONE document — the document-local register.
 *
 * S1 extends CAP-001's register notion (DP-1: unique within its register(ns)) to the
 * register = THE DOCUMENT. Where {@see Identifier} is the assigned name of a governed
 * artifact across a register(ns), this VO is the section number (`4.1`, `0.6.5`) that
 * names a heading within a single governed document — the AMD4/AMD6 `## 4.x` case.
 *
 * Pure value object: parses, compares, and nothing else. It carries the line it
 * appears on so an assessment can state WHERE a defect is, but it holds no policy
 * and performs no validation.
 */
final readonly class DocumentSectionIdentifier
{
    /** @var list<int> the parsed tuple — `4.1` → [4, 1]; used for ordering */
    private array $tuple;

    private function __construct(
        private string $number,
        private int $line,
    ) {
        $this->tuple = array_map(static fn (string $c): int => (int) $c, explode('.', $this->number));
    }

    public static function fromNumberLine(string $number, int $line): self
    {
        $number = trim($number);

        if (preg_match('/^\d+(?:\.\d+)*$/', $number) !== 1) {
            throw new InvalidArgumentException("Not a numeric section identifier: '{$number}'");
        }

        if ($line < 1) {
            throw new InvalidArgumentException('Section identifier lines are 1-based.');
        }

        return new self($number, $line);
    }

    public function number(): string
    {
        return $this->number;
    }

    public function line(): int
    {
        return $this->line;
    }

    /** @return list<int> */
    public function tuple(): array
    {
        return $this->tuple;
    }

    /**
     * Tuple-lexicographic, prefix-shorter-wins: `4` < `4.0` < `4.2` < `4.10` < `5`.
     * Components are integer LEVELS, never fractional values.
     */
    public function compareTo(self $other): int
    {
        $a = $this->tuple;
        $b = $other->tuple;
        $n = min(count($a), count($b));

        for ($i = 0; $i < $n; $i++) {
            if ($a[$i] !== $b[$i]) {
                return $a[$i] <=> $b[$i];
            }
        }

        return count($a) <=> count($b);
    }

    /** Equality is by number string — the collision identity. */
    public function equals(self $other): bool
    {
        return $this->number === $other->number;
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
