<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\BehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodFacts;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitIdentity;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use PHPUnit\Framework\TestCase;

/**
 * Closes the last open cell in the L3->L4/L5 dependency matrix (2026-09-27 report):
 * `DeclaredUnit::declaredName`. Isolates it from `UnitIdentity`, which is a genuinely
 * separate field (path-based, INV-L3-2, independent of declaredName by construction --
 * an anonymous unit has an identity via ordinal path but no declared name at all,
 * `declaredName` being nullable for exactly that reason).
 *
 * `identity` is held CONSTANT ('A') across both runs; only `declaredName` varies
 * ('A' vs a deliberately divergent 'SomethingElse'). Everything else -- methods, state
 * accesses, behaviour references -- is held identical.
 */
final class DeclaredNameCharacterizationTest extends TestCase
{
    private function unitWith(?string $declaredName): DeclaredUnit
    {
        return new DeclaredUnit(
            UnitKind::ClassUnit,
            new UnitIdentity('A'),
            $declaredName,
            [
                new MethodFacts('f', true, [], [
                    new BehaviourReference('g', QualifierKind::InstanceReceiver, TargetUnitRelation::DenotesAnalysedUnit, ReferenceMode::Invocation, AccessMode::Direct, Determinability::Determinable),
                ]),
                new MethodFacts('g', true, [], []),
            ],
        );
    }

    public function test_declaredName_affects_neither_analysis_nor_reporting(): void
    {
        $matching = AnalyseCohesion::observe(new FactSet([$this->unitWith('A')]));
        $divergent = AnalyseCohesion::observe(new FactSet([$this->unitWith('SomethingElse')]));
        $null = AnalyseCohesion::observe(new FactSet([$this->unitWith(null)]));

        // Reporting: the observed 'unit' label comes from identity, NOT declaredName --
        // all three runs report the SAME label despite three different declaredName values.
        self::assertSame('A', $matching[0]['unit']);
        self::assertSame('A', $divergent[0]['unit']);
        self::assertSame('A', $null[0]['unit']);

        // Analysis: graph/metric are byte-identical across all three.
        self::assertSame($matching, $divergent);
        self::assertSame($matching, $null);
    }

    /**
     * OWD-11 (2026-09-28): `declaredName` is not simply a redundant mirror of
     * `identity` at the POPULATION level, even though the test above proves it has no
     * observable effect at the CONSUMPTION level. For an anonymous class, PHP's real
     * adapter sets `declaredName = null` (the class genuinely has no name) while
     * `identity` is always a synthesized path (`Named/anon#1`) -- `identity` alone
     * cannot distinguish "named" from "anonymous"; `declaredName` still preserves that
     * distinction, unused by any current consumer. Confirmed by direct execution, not
     * assumed from reading `PhpFactExtractor` alone.
     */
    public function test_declaredName_preserves_named_vs_anonymous_information_identity_alone_discards(): void
    {
        $php = '<?php class Named { function m(){ $a = new class { function p(){} }; } }';
        $units = PhpFactExtractor::extract($php)->units;

        self::assertSame('Named', $units[0]->identity->toString());
        self::assertSame('Named', $units[0]->declaredName);

        self::assertSame('Named/anon#1', $units[1]->identity->toString());
        self::assertNull($units[1]->declaredName, 'the anonymous class has no name; identity alone cannot express that');
    }
}
