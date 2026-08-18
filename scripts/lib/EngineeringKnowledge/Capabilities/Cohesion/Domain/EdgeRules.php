<?php

declare(strict_types=1);

namespace EngineeringKnowledge\Capabilities\Cohesion\Domain;

/**
 * L4 — Decision 13.3's stratified rule, executable. First match decides.
 *
 * ⛔ LANGUAGE-NEUTRAL IN SEMANTIC RESPONSIBILITY. This class receives only
 * closed-vocabulary L3 facts: no source text, no spelling, no token, no AST
 * node, no parser object, no provenance. It is implemented in PHP and knows
 * nothing about PHP — the bounded exception is a TOPOLOGY exception only.
 *
 * The behavioural-dependency clause governs what an edge IS; the qualifier
 * kind, preserved by the binding, governs what the contract INTERPRETS.
 */
final class EdgeRules
{
    private function __construct()
    {
    }

    public static function verdict(BehaviourReference $ref): EdgeVerdict
    {
        // 1 — a first-class callable REFERENCES a behaviour rather than invoking it.
        if ($ref->referenceMode === ReferenceMode::CallableReference) {
            return EdgeVerdict::exclude(ExclusionReason::CallableNotInvocation);
        }

        // 2 — the target cannot be determined from this scope. NOT the same claim as row 8/9.
        if ($ref->determinability === Determinability::NotDeterminable
            || $ref->qualifierKind === QualifierKind::ComputedTarget) {
            return EdgeVerdict::exclude(ExclusionReason::NotDeterminable);
        }

        // 3 — parent:: uses inherited behaviour, which is not a node here.
        if ($ref->qualifierKind === QualifierKind::ParentKeyword) {
            return EdgeVerdict::exclude(ExclusionReason::OutOfFrame);
        }

        // 4 — aliased spellings are not resolved. A stated LIMITATION, excluded BY KIND
        //     even where the alias does denote the analysed unit.
        if ($ref->qualifierKind === QualifierKind::AliasedName) {
            return EdgeVerdict::exclude(ExclusionReason::AliasedSpelling);
        }

        // 5/6 — self, static and the instance receiver are self-referential by construction.
        if ($ref->qualifierKind === QualifierKind::SelfKeyword
            || $ref->qualifierKind === QualifierKind::StaticKeyword
            || $ref->qualifierKind === QualifierKind::InstanceReceiver) {
            return EdgeVerdict::include();
        }

        // 7/8 — written names: included only where they DENOTE the analysed unit.
        if ($ref->qualifierKind === QualifierKind::UnqualifiedName
            || $ref->qualifierKind === QualifierKind::FullyQualifiedName
            || $ref->qualifierKind === QualifierKind::RelativeName) {
            return $ref->targetUnitRelation === TargetUnitRelation::DenotesAnalysedUnit
                ? EdgeVerdict::include()
                : EdgeVerdict::exclude(ExclusionReason::NotTheAnalysedUnit);
        }

        // 9 — a qualified unaliased name is DETERMINABLE and is simply not this unit.
        //     ⛔ It must NOT be classified as "not determinable" (the bucket ruling).
        return EdgeVerdict::exclude(ExclusionReason::NotTheAnalysedUnit);
    }
}
