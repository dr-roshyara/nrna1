<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Application;

use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\GraphBuilder;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Interpretation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Lcom4;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitEligibility;

/**
 * Application service — drives L3 facts through L4 and L5 for one analysis scope.
 *
 * It emits the THREE EVIDENCE LEVELS Decision 13.7 requires, not only the metric:
 *   1 the unit set, with kind and eligibility — an interface appears here, marked
 *     ineligible, so SEEN-AND-EXCLUDED stays distinguishable from NEVER-SEEN;
 *   2 the node set and edge set, plus every excluded reference with its reason;
 *   3 the final LCOM4 value.
 *
 * Final-metric equality alone is insufficient: compensating errors cancel in a
 * scalar and cannot survive an element-wise set comparison.
 *
 * ⛔ It takes a FactSet. It never sees PHP source — the binding produced that.
 */
final class AnalyseCohesion
{
    private function __construct()
    {
    }

    /**
     * @return list<array{
     *   metric:string, unit:string, unitKind:string, analysed:bool,
     *   value:?int, interpretation:?string,
     *   nodes:list<string>, edges:list<array{0:string,1:string,2:string}>,
     *   excluded:list<array{method:string,target:string,reason:string}>
     * }>
     */
    public static function observe(FactSet $facts): array
    {
        $observations = [];

        foreach ($facts->units as $unit) {
            $analysed = UnitEligibility::isAnalysedUnit($unit->kind);

            if (!$analysed) {
                $observations[] = [
                    'metric' => 'LCOM4',
                    'unit' => $unit->identity->toString(),
                    'unitKind' => $unit->kind->name,
                    'analysed' => false,
                    'value' => null,
                    'interpretation' => null,
                    'nodes' => [],
                    'edges' => [],
                    'excluded' => [],
                ];
                continue;
            }

            $graph = GraphBuilder::build($unit);
            $value = Lcom4::compute($graph);

            $observations[] = [
                'metric' => 'LCOM4',
                'unit' => $unit->identity->toString(),
                'unitKind' => $unit->kind->name,
                'analysed' => true,
                'value' => $value,
                'interpretation' => Interpretation::of($value),
                'nodes' => $graph->nodes(),
                'edges' => $graph->edges(),
                'excluded' => array_map(
                    static fn (array $e): array => [
                        'method' => $e['method'],
                        'target' => $e['target'],
                        'reason' => $e['reason']->name,
                    ],
                    $graph->excludedReferences()
                ),
            ];
        }

        return $observations;
    }
}
