<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The value of a SUCCESSION claim (v3.11 §3; closure SuccessorSet; D-228): its kind and ≥ 1 successor identities.
 * Canonical encoding "kind:uuid,uuid" (successors sorted, de-duplicated), stable for digests and comparison.
 */
final readonly class SuccessionEvent
{
    /** @var list<ReferenceUnitId> */
    public array $successors;

    /** @param list<ReferenceUnitId> $successors */
    public function __construct(public SuccessionKind $kind, array $successors)
    {
        $byValue = [];
        foreach ($successors as $s) {
            $byValue[$s->value()] = $s;
        }
        if ($byValue === []) {
            throw new \InvalidArgumentException('A succession event names at least one successor (G-5).');
        }
        ksort($byValue);
        $this->successors = array_values($byValue);
    }

    public static function parse(string $encoded): self
    {
        $parts = explode(':', $encoded, 2);
        $kind = SuccessionKind::tryFrom($parts[0]);
        if ($kind === null || !isset($parts[1]) || $parts[1] === '') {
            throw new \InvalidArgumentException('A succession value is "kind:successor,…" with a known kind.');
        }

        return new self($kind, array_map(static fn (string $id): ReferenceUnitId => ReferenceUnitId::fromString($id), explode(',', $parts[1])));
    }

    public function encoded(): string
    {
        return $this->kind->value . ':' . implode(',', array_map(static fn (ReferenceUnitId $s): string => $s->value(), $this->successors));
    }
}
