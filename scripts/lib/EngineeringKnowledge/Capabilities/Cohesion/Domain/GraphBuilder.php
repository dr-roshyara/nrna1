<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L4 — turns one analysed unit's L3 facts into the cohesion graph.
 *
 * Nodes  : methods declared in the unit's own body, minus constructors/destructors
 *          (pinned decision: they touch everything and would mask real splits).
 *          Bodyless declarations ARE nodes.
 * Edges  : STATE     — two nodes touching the same property (both access modes count, 13.5)
 *          BEHAVIOUR — an INCLUDEd reference whose target is a node of this unit
 *
 * ⛔ Receives L3 facts only. No source, no tokens, no parser objects, no provenance.
 */
final class GraphBuilder
{
    private const EXCLUDED_METHODS = ['__construct', '__destruct'];

    private function __construct()
    {
    }

    public static function build(DeclaredUnit $unit): CohesionGraph
    {
        $nodes = [];
        foreach ($unit->methods as $method) {
            if (!in_array($method->methodIdentity, self::EXCLUDED_METHODS, true)) {
                $nodes[] = $method->methodIdentity;
            }
        }

        $edges = [];
        $excluded = [];
        $propertyOwner = [];

        foreach ($unit->methods as $method) {
            if (in_array($method->methodIdentity, self::EXCLUDED_METHODS, true)) {
                continue;
            }
            $name = $method->methodIdentity;

            foreach ($method->stateAccesses as $access) {
                $property = $access->propertyName;
                if (isset($propertyOwner[$property])) {
                    if ($propertyOwner[$property] !== $name) {
                        $edges[] = [$propertyOwner[$property], $name, 'state'];
                    }
                } else {
                    $propertyOwner[$property] = $name;
                }
            }

            foreach ($method->behaviourReferences as $ref) {
                $verdict = EdgeRules::verdict($ref);
                if (!$verdict->isIncluded()) {
                    $excluded[] = [
                        'method' => $name,
                        'target' => $ref->targetMethodName,
                        'reason' => $verdict->reason(),
                    ];
                    continue;
                }
                if (!in_array($ref->targetMethodName, $nodes, true)) {
                    // Included by the rule table, but there is no such node here.
                    // Retained as evidence rather than silently dropped.
                    $excluded[] = [
                        'method' => $name,
                        'target' => $ref->targetMethodName,
                        'reason' => ExclusionReason::TargetNotDeclaredHere,
                    ];
                    continue;
                }
                $edges[] = [$name, $ref->targetMethodName, 'behaviour'];
            }
        }

        return new CohesionGraph($nodes, $edges, $excluded);
    }
}
