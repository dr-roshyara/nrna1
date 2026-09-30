<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\BehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\GraphBuilder;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Lcom4;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodFacts;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitIdentity;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use PHPUnit\Framework\TestCase;

/**
 * Completes the empirically-derived L3->L4/L5 consumption matrix (minimal-semantic-contract
 * report, 2026-09-27). `AccessMode`'s no-op status already has a dedicated confirming test
 * in the accepted suite (`test_nullsafe_access_does_not_change_the_verdict`,
 * `test_a_nullsafe_property_read_still_creates_a_state_edge`). `hasBody` and `factId` did
 * not -- their "no consumer" status rested only on a repository-wide read-absence grep.
 * These two tests upgrade that to the same standard: vary the field across its full value
 * range, hold everything else constant, and show the observable graph/metric is identical
 * either way. Confirmatory only -- no production code changed, no new behaviour asserted.
 */
final class UnconsumedFieldCharacterizationTest extends TestCase
{
    public function test_hasBody_does_not_affect_graph_or_metric(): void
    {
        $build = static fn (bool $hasBody) => GraphBuilder::build(new DeclaredUnit(
            UnitKind::ClassUnit,
            new UnitIdentity('A'),
            'A',
            [
                new MethodFacts('f', $hasBody, [], [
                    new BehaviourReference('g', QualifierKind::InstanceReceiver, TargetUnitRelation::DenotesAnalysedUnit, ReferenceMode::Invocation, AccessMode::Direct, Determinability::Determinable),
                ]),
                new MethodFacts('g', $hasBody, [], []),
            ],
        ));

        $withBody = $build(true);
        $withoutBody = $build(false);

        self::assertSame($withBody->nodes(), $withoutBody->nodes());
        self::assertSame($withBody->edges(), $withoutBody->edges());
        self::assertSame($withBody->excludedReferences(), $withoutBody->excludedReferences());
        self::assertSame(Lcom4::compute($withBody), Lcom4::compute($withoutBody));
    }

    public function test_factId_does_not_affect_graph_or_metric(): void
    {
        $build = static fn (?string $factId) => GraphBuilder::build(new DeclaredUnit(
            UnitKind::ClassUnit,
            new UnitIdentity('A'),
            'A',
            [
                new MethodFacts('f', true, [], [
                    new BehaviourReference('g', QualifierKind::InstanceReceiver, TargetUnitRelation::DenotesAnalysedUnit, ReferenceMode::Invocation, AccessMode::Direct, Determinability::Determinable, $factId),
                ]),
                new MethodFacts('g', true, [], []),
            ],
        ));

        $withFactId = $build('some-fact-id-0001');
        $withoutFactId = $build(null);

        self::assertSame($withFactId->nodes(), $withoutFactId->nodes());
        self::assertSame($withFactId->edges(), $withoutFactId->edges());
        self::assertSame($withFactId->excludedReferences(), $withoutFactId->excludedReferences());
        self::assertSame(Lcom4::compute($withFactId), Lcom4::compute($withoutFactId));
    }
}
