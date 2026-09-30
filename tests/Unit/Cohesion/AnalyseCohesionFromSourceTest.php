<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Application\Ports\SemanticFactProvider;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\FactSet;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitIdentity;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodFacts;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\BehaviourReference;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Architecture Gate, Slice 1 (2026-09-27-KOS-architecture-gate.md, §9/§19).
 *
 * Proves TWO distinct things, not one: (1) the PHP path behaves identically once routed
 * through the port (behaviour preservation), and (2) AnalyseCohesion::observeFromSource()
 * depends only on the SemanticFactProvider contract — not on PhpFactExtractor specifically
 * (genuine substitutability, via a fake that never touches PHP extraction at all).
 */
final class AnalyseCohesionFromSourceTest extends TestCase
{
    public function test_php_path_through_the_port_matches_the_existing_direct_pipeline(): void
    {
        $source = '<?php class A { public function f() { $this->g(); } public function g() {} }';

        $viaPort = AnalyseCohesion::observeFromSource(new PhpSemanticFactProvider(), $source);
        $direct = AnalyseCohesion::observe(PhpFactExtractor::extract($source));

        self::assertSame($direct, $viaPort);
    }

    public function test_a_fake_provider_with_no_php_extraction_proves_genuine_substitutability(): void
    {
        $fake = new class implements SemanticFactProvider {
            public function extract(string $source): FactSet
            {
                // Deliberately ignores $source entirely -- if this test can only pass by
                // secretly calling PhpFactExtractor, that would defeat its purpose.
                return new FactSet([
                    new DeclaredUnit(UnitKind::ClassUnit, new UnitIdentity('Fake'), 'Fake', [
                        new MethodFacts('f', true, [], [
                            new BehaviourReference(
                                'g',
                                QualifierKind::InstanceReceiver,
                                TargetUnitRelation::DenotesAnalysedUnit,
                                ReferenceMode::Invocation,
                                AccessMode::Direct,
                                Determinability::Determinable,
                            ),
                        ]),
                        new MethodFacts('g', true, [], []),
                    ]),
                ]);
            }
        };

        $observed = AnalyseCohesion::observeFromSource($fake, 'this string is never inspected');

        self::assertCount(1, $observed);
        self::assertSame('Fake', $observed[0]['unit']);
        self::assertSame(1, $observed[0]['value']);
        self::assertSame([['f', 'g', 'behaviour']], $observed[0]['edges']);
    }

    /**
     * Characterizes CURRENT behaviour (PhpFactExtractor::extract() never throws; malformed
     * or non-PHP input yields an empty FactSet) -- confirmed by direct execution before this
     * test was written (Architecture Gate §4's open question). Pins it so the port's
     * contract is not left ambiguous by assumption.
     */
    public function test_malformed_or_non_php_input_yields_an_empty_observation_without_throwing(): void
    {
        $provider = new PhpSemanticFactProvider();

        foreach (['', '<?php', 'not php at all', '<?php class {'] as $malformed) {
            self::assertSame([], AnalyseCohesion::observeFromSource($provider, $malformed));
        }
    }
}
