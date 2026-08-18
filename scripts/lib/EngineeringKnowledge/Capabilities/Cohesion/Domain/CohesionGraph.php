<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L4 output / L5 input — the cohesion graph for one analysed unit.
 *
 * Decision 13.7 requires conformance evidence at NODE SET + EDGE SET + final
 * metric: final-metric equality alone is insufficient, because compensating
 * errors cancel. So the graph is exposed as evidence, never only counted.
 * Excluded references are retained WITH THEIR REASON — seen-and-excluded must
 * stay distinguishable from never-seen.
 */
final readonly class CohesionGraph
{
    /**
     * @param list<string>                                            $nodes
     * @param list<array{0:string,1:string,2:string}>                 $edges  [from, to, kind]
     * @param list<array{method:string,target:string,reason:ExclusionReason}> $excluded
     */
    public function __construct(
        private array $nodes,
        private array $edges,
        private array $excluded = [],
    ) {
    }

    /** @return list<string> */
    public function nodes(): array
    {
        return $this->nodes;
    }

    /** @return list<array{0:string,1:string,2:string}> */
    public function edges(): array
    {
        return $this->edges;
    }

    /** @return list<array{method:string,target:string,reason:ExclusionReason}> */
    public function excludedReferences(): array
    {
        return $this->excluded;
    }
}
