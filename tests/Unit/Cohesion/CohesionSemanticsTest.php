<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\BehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\EdgeRules;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ExclusionReason;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\GraphBuilder;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Lcom4;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodFacts;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\StateAccess;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitEligibility;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitIdentity;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use PHPUnit\Framework\TestCase;

/**
 * L4 COHESION SEMANTICS — Decision 13.3 / 13.5.
 *
 * ⭐ GATE 3 PROOF: this test constructs L3 facts DIRECTLY. No PHP source, no
 * tokens, no AST node, no parser object and no provenance exists anywhere in
 * this process. If any cohesion rule ever needed one, this file could not be
 * written — which is the structural guarantee the accepted architecture calls
 * for, restated as an executable test.
 */
final class CohesionSemanticsTest extends TestCase
{
    private function ref(
        string $target,
        QualifierKind $qualifier,
        TargetUnitRelation $relation = TargetUnitRelation::DenotesAnalysedUnit,
        ReferenceMode $mode = ReferenceMode::Invocation,
        AccessMode $access = AccessMode::Direct,
        Determinability $determinability = Determinability::Determinable,
    ): BehaviourReference {
        return new BehaviourReference($target, $qualifier, $relation, $mode, $access, $determinability);
    }

    // ---- 13.5 unit eligibility -------------------------------------------

    public function test_class_enum_trait_and_anonymous_class_are_analysed_units(): void
    {
        foreach ([UnitKind::ClassUnit, UnitKind::AnonymousClass, UnitKind::EnumUnit, UnitKind::TraitUnit] as $kind) {
            self::assertTrue(UnitEligibility::isAnalysedUnit($kind), $kind->name);
        }
    }

    public function test_interface_is_not_an_analysed_unit(): void
    {
        self::assertFalse(UnitEligibility::isAnalysedUnit(UnitKind::InterfaceUnit));
    }

    // ---- 13.3 the edge rule table ----------------------------------------

    public function test_self_and_static_and_instance_receiver_are_included(): void
    {
        foreach ([QualifierKind::SelfKeyword, QualifierKind::StaticKeyword, QualifierKind::InstanceReceiver] as $q) {
            self::assertTrue(EdgeRules::verdict($this->ref('b', $q))->isIncluded(), $q->name);
        }
    }

    public function test_parent_is_excluded_as_out_of_frame(): void
    {
        $v = EdgeRules::verdict($this->ref('b', QualifierKind::ParentKeyword));
        self::assertFalse($v->isIncluded());
        self::assertSame(ExclusionReason::OutOfFrame, $v->reason());
    }

    public function test_fully_qualified_and_relative_denoting_the_unit_are_included(): void
    {
        foreach ([QualifierKind::FullyQualifiedName, QualifierKind::RelativeName, QualifierKind::UnqualifiedName] as $q) {
            self::assertTrue(EdgeRules::verdict($this->ref('b', $q))->isIncluded(), $q->name);
        }
    }

    /** ⭐ THE BUCKET RULING: determinable-but-other is NOT "not determinable". */
    public function test_a_name_denoting_another_unit_is_excluded_as_not_the_analysed_unit(): void
    {
        $v = EdgeRules::verdict($this->ref('b', QualifierKind::FullyQualifiedName, TargetUnitRelation::DenotesOtherUnit));
        self::assertFalse($v->isIncluded());
        self::assertSame(ExclusionReason::NotTheAnalysedUnit, $v->reason());
        self::assertNotSame(ExclusionReason::NotDeterminable, $v->reason());
    }

    public function test_qualified_unaliased_is_excluded_as_not_the_analysed_unit(): void
    {
        $v = EdgeRules::verdict($this->ref('b', QualifierKind::QualifiedName, TargetUnitRelation::DenotesOtherUnit));
        self::assertSame(ExclusionReason::NotTheAnalysedUnit, $v->reason());
    }

    public function test_a_computed_target_is_excluded_as_not_determinable(): void
    {
        $v = EdgeRules::verdict($this->ref(
            'b', QualifierKind::ComputedTarget, TargetUnitRelation::Undetermined,
            ReferenceMode::Invocation, AccessMode::Direct, Determinability::NotDeterminable
        ));
        self::assertSame(ExclusionReason::NotDeterminable, $v->reason());
    }

    public function test_aliased_is_excluded_as_a_stated_limitation_even_when_it_denotes_the_unit(): void
    {
        $v = EdgeRules::verdict($this->ref('b', QualifierKind::AliasedName, TargetUnitRelation::DenotesAnalysedUnit));
        self::assertFalse($v->isIncluded());
        self::assertSame(ExclusionReason::AliasedSpelling, $v->reason());
    }

    public function test_a_callable_reference_is_excluded_whatever_the_qualifier(): void
    {
        $v = EdgeRules::verdict($this->ref(
            'b', QualifierKind::SelfKeyword, TargetUnitRelation::DenotesAnalysedUnit, ReferenceMode::CallableReference
        ));
        self::assertSame(ExclusionReason::CallableNotInvocation, $v->reason());
    }

    /** 13.5: nullsafe is a behavioural edge — it must not change the verdict. */
    public function test_nullsafe_access_does_not_change_the_verdict(): void
    {
        $direct = EdgeRules::verdict($this->ref('b', QualifierKind::InstanceReceiver));
        $nullsafe = EdgeRules::verdict($this->ref(
            'b', QualifierKind::InstanceReceiver, TargetUnitRelation::DenotesAnalysedUnit,
            ReferenceMode::Invocation, AccessMode::Nullsafe
        ));
        self::assertTrue($direct->isIncluded());
        self::assertTrue($nullsafe->isIncluded());
    }

    // ---- graph construction + L5 -----------------------------------------

    private function unit(MethodFacts ...$methods): DeclaredUnit
    {
        return new DeclaredUnit(UnitKind::ClassUnit, new UnitIdentity('X'), 'X', $methods);
    }

    public function test_two_methods_sharing_a_property_form_one_component(): void
    {
        $u = $this->unit(
            new MethodFacts('a', true, [new StateAccess('p', AccessMode::Direct)], []),
            new MethodFacts('b', true, [new StateAccess('p', AccessMode::Direct)], []),
        );
        self::assertSame(1, Lcom4::compute(GraphBuilder::build($u)));
    }

    public function test_disconnected_methods_form_two_components(): void
    {
        $u = $this->unit(
            new MethodFacts('a', true, [new StateAccess('p', AccessMode::Direct)], []),
            new MethodFacts('b', true, [new StateAccess('q', AccessMode::Direct)], []),
        );
        self::assertSame(2, Lcom4::compute(GraphBuilder::build($u)));
    }

    public function test_a_nullsafe_property_read_still_creates_a_state_edge(): void
    {
        $u = $this->unit(
            new MethodFacts('a', true, [new StateAccess('p', AccessMode::Nullsafe)], []),
            new MethodFacts('b', true, [new StateAccess('p', AccessMode::Direct)], []),
        );
        self::assertSame(1, Lcom4::compute(GraphBuilder::build($u)));
    }

    public function test_constructors_are_excluded_from_the_node_set(): void
    {
        $u = $this->unit(
            new MethodFacts('__construct', true, [new StateAccess('p', AccessMode::Direct)], []),
            new MethodFacts('a', true, [new StateAccess('p', AccessMode::Direct)], []),
            new MethodFacts('b', true, [new StateAccess('q', AccessMode::Direct)], []),
        );
        $graph = GraphBuilder::build($u);
        self::assertSame(['a', 'b'], $graph->nodes());
        self::assertSame(2, Lcom4::compute($graph));
    }

    public function test_an_excluded_reference_creates_no_edge(): void
    {
        $u = $this->unit(
            new MethodFacts('a', true, [], [$this->ref('b', QualifierKind::ParentKeyword)]),
            new MethodFacts('b', true, [], []),
        );
        self::assertSame(2, Lcom4::compute(GraphBuilder::build($u)));
    }

    public function test_a_reference_to_a_method_not_declared_here_is_retained_but_creates_no_edge(): void
    {
        $u = $this->unit(
            new MethodFacts('a', true, [], [$this->ref('elsewhere', QualifierKind::InstanceReceiver)]),
            new MethodFacts('b', true, [], []),
        );
        $graph = GraphBuilder::build($u);
        self::assertSame(2, Lcom4::compute($graph));
        self::assertSame(
            [ExclusionReason::TargetNotDeclaredHere],
            array_map(static fn (array $e): ExclusionReason => $e['reason'], $graph->excludedReferences())
        );
    }

    /** ⭐ 13.7: the graph is evidence, not only the number. */
    public function test_the_graph_exposes_its_node_set_and_edge_set(): void
    {
        $u = $this->unit(
            new MethodFacts('a', true, [], [$this->ref('b', QualifierKind::SelfKeyword)]),
            new MethodFacts('b', true, [], []),
        );
        $graph = GraphBuilder::build($u);
        self::assertSame(['a', 'b'], $graph->nodes());
        self::assertSame([['a', 'b', 'behaviour']], $graph->edges());
    }

    public function test_a_unit_with_no_analysable_methods_has_value_zero(): void
    {
        self::assertSame(0, Lcom4::compute(GraphBuilder::build($this->unit())));
    }
}
