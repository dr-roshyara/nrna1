<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L4 — turns one analysed unit's L3 facts into the cohesion graph.
 *
 * Nodes  : methods declared in the unit's own body, minus lifecycle methods
 *          (pinned decision: they touch everything and would mask real splits).
 *          Bodyless declarations ARE nodes.
 * Edges  : STATE     — two nodes touching the same property (both access modes count, 13.5)
 *          BEHAVIOUR — an INCLUDEd reference whose target is a node of this unit
 *
 * ⛔ Receives L3 facts only. No source, no tokens, no parser objects, no provenance.
 *    Lifecycle exclusion is decided from `MethodFacts::methodRole` (a closed-vocabulary L3
 *    fact each adapter sets from its own language's convention), never from a method's name.
 *
 * Node identity (OWD-3, 2026-09-27): a node IS a declared method (this docblock's own
 * words, unchanged) — TWO declared methods sharing a name (Python's `@property` +
 * `@x.setter` idiom; impossible for any PHP input, where duplicate method names are a
 * fatal error) are two nodes, not one. Connectivity is keyed by OCCURRENCE, never by bare
 * name; the reported label is disambiguated (`name#0`, `name#1`, ...) only when a
 * collision actually exists — every non-colliding case (every PHP input; the overwhelming
 * majority of Python input) reports exactly the same label as before this change.
 */
final class GraphBuilder
{
    private function __construct()
    {
    }

    public static function build(DeclaredUnit $unit): CohesionGraph
    {
        $eligible = array_values(array_filter(
            $unit->methods,
            static fn (MethodFacts $m): bool => $m->methodRole !== MethodRole::Lifecycle,
        ));

        $nameCounts = [];
        foreach ($eligible as $m) {
            $nameCounts[$m->methodIdentity] = ($nameCounts[$m->methodIdentity] ?? 0) + 1;
        }

        $labels = [];
        $occurrence = [];
        foreach ($eligible as $i => $m) {
            $name = $m->methodIdentity;
            if ($nameCounts[$name] > 1) {
                $n = $occurrence[$name] ?? 0;
                $labels[$i] = "{$name}#{$n}";
                $occurrence[$name] = $n + 1;
            } else {
                $labels[$i] = $name;
            }
        }

        $nodes = array_values($labels);

        $edges = [];
        $excluded = [];
        $propertyOwner = [];

        foreach ($eligible as $i => $method) {
            $label = $labels[$i];

            foreach ($method->stateAccesses as $access) {
                $property = $access->propertyName;
                if (isset($propertyOwner[$property])) {
                    if ($propertyOwner[$property] !== $label) {
                        $edges[] = [$propertyOwner[$property], $label, 'state'];
                    }
                } else {
                    $propertyOwner[$property] = $label;
                }
            }

            foreach ($method->behaviourReferences as $ref) {
                $verdict = EdgeRules::verdict($ref);
                if (!$verdict->isIncluded()) {
                    // D-1: an IndeterminateBehaviourReference carries no target name to
                    // report (INV-L3-5 does not extend to this evidence record — verified
                    // 2026-09-28-KOS-CONTRACT-NEUTRALITY-001-V3-decision-gate-
                    // reconciliation.md — so `target: null` asserts nothing illegal).
                    $excluded[] = [
                        'method' => $label,
                        'target' => $ref instanceof IndeterminateBehaviourReference ? null : $ref->targetMethodName,
                        'reason' => $verdict->reason(),
                    ];
                    continue;
                }

                // A source-level reference names its target by bare name. Unambiguous
                // in every PHP input and the overwhelming majority of Python input
                // (matches exactly one label). When ambiguous (target name collides —
                // Python property idiom only), connect to every matching occurrence
                // rather than silently guessing which one was meant: disambiguating by
                // read/write semantics is a separate, deliberately unresolved question
                // (OWD-3 report, section 6).
                $targets = self::matchingLabels($ref->targetMethodName, $labels);
                if ($targets === []) {
                    // Included by the rule table, but there is no such node here.
                    // Retained as evidence rather than silently dropped.
                    $excluded[] = [
                        'method' => $label,
                        'target' => $ref->targetMethodName,
                        'reason' => ExclusionReason::TargetNotDeclaredHere,
                    ];
                    continue;
                }
                foreach ($targets as $target) {
                    $edges[] = [$label, $target, 'behaviour'];
                }
            }
        }

        return new CohesionGraph($nodes, $edges, $excluded);
    }

    /**
     * @param  list<string> $labels
     * @return list<string>
     */
    private static function matchingLabels(string $name, array $labels): array
    {
        return array_values(array_filter(
            $labels,
            static fn (string $label): bool => $label === $name || str_starts_with($label, $name . '#'),
        ));
    }
}
