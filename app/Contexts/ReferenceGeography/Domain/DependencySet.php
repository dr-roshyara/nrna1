<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The dependency predicates of one validation, with the position p₀ they were read at (v3.11 §5.1, §6).
 * The publication step (G-4) asks, under its lock, whether any claim published after p₀ invalidates one.
 * Claims at or before p₀ were already known and never make the revision stale.
 */
final readonly class DependencySet
{
    /** @param list<Dependency> $dependencies */
    public function __construct(public int $p0, public array $dependencies)
    {
        if ($p0 < 0) {
            throw new \InvalidArgumentException('p₀ is a position, zero or more.');
        }
    }

    /** @param iterable<PublishedClaim> $publishedSinceP0 */
    public function firstInvalidating(iterable $publishedSinceP0): ?PublishedClaim
    {
        foreach ($publishedSinceP0 as $published) {
            if ($published->position <= $this->p0) {
                continue;
            }
            foreach ($this->dependencies as $dependency) {
                if ($dependency->isInvalidatedBy($published)) {
                    return $published;
                }
            }
        }

        return null;
    }

    /** @return list<string> sorted canonical descriptions, for the content-version digest */
    public function describe(): array
    {
        $out = array_map(static fn (Dependency $d): string => $d->describe(), $this->dependencies);
        sort($out);

        return $out;
    }
}
