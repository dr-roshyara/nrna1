<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L5 — LCOM4 (Hitz & Montazeri): the number of connected components in the
 * method graph. NO NEW METRIC is introduced.
 *
 * Receives only (node set, edge set). It sees no unit kind, no qualifier, no
 * source — the least interesting layer, deliberately: the difficulty was never
 * in the metric.
 */
final class Lcom4
{
    private function __construct()
    {
    }

    public static function compute(CohesionGraph $graph): int
    {
        $nodes = $graph->nodes();
        if ($nodes === []) {
            return 0;
        }

        $parent = array_combine($nodes, $nodes);

        $find = static function (string $node) use (&$parent, &$find): string {
            while ($parent[$node] !== $node) {
                $parent[$node] = $parent[$parent[$node]];
                $node = $parent[$node];
            }

            return $node;
        };

        foreach ($graph->edges() as [$from, $to]) {
            $rootFrom = $find($from);
            $rootTo = $find($to);
            if ($rootFrom !== $rootTo) {
                $parent[$rootFrom] = $rootTo;
            }
        }

        return count(array_unique(array_map($find, $nodes)));
    }
}
