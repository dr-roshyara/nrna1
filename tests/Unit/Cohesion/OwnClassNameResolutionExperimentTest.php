<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R3 — `own_class_name_resolution` (`KOS-PYTHON-RULE-VALIDATION`, 2026-09-27). Recovered
 * meaning (from `PhpFactExtractor::classifyQualifier()`/`relationTo()` and
 * `EdgeRules::verdict()`, not from the pinned text alone): PHP resolves FIVE
 * name-spelling kinds (`UnqualifiedName`, `FullyQualifiedName`, `RelativeName`,
 * `QualifiedName`, `AliasedName`) to a fully-qualified identity and compares it against
 * the analysed unit's own name; three of the five are honoured for inclusion when they
 * denote the unit (`UnqualifiedName`, `FullyQualifiedName`, `RelativeName` — existing,
 * accepted tests), two are excluded BY KIND regardless of denotation (`QualifiedName`,
 * `AliasedName` — existing, accepted tests, the pinned "known limitation").
 *
 * HISTORY, preserved rather than erased: this file originally characterized (RED-A) a
 * real Python adapter gap — `_receiver_qualifier()` recognised exactly `self`/`cls`, so
 * a bare own-class-name call (`Fq.b(self)`) or an alias for it produced NO FACT OF ANY
 * KIND, giving a materially wrong LCOM4 (3, not PHP's correct 2). Classified D (adapter
 * limitation, no `Domain` change needed) in `...-R3-own-class-name-resolution.md`,
 * authorized, and corrected via `_own_class_name_qualifier()` (grant
 * G-KOS-PYTHON-RULE-VALIDATION-R3-OWN-CLASS-NAME). The two tests below now assert the
 * CORRECTED behaviour; the full RED→GREEN proof lives in
 * `OwnClassNameResolutionCorrectionTest.php`.
 */
final class OwnClassNameResolutionExperimentTest extends TestCase
{
    private const PHP_BARE_OWN_NAME = '<?php class Fq { function a(){ Fq::b(); } function b(){} function lonely(){ $this->z; } }';

    private const PYTHON_BARE_OWN_NAME = <<<'PY'
        class Fq:
            def a(self):
                Fq.b(self)

            def b(self):
                pass

            def lonely(self):
                return self.z
        PY;

    private const PYTHON_ALIASED_OWN_NAME = <<<'PY'
        class Fq:
            def a(self):
                Alias.b(self)

            def b(self):
                pass

        Alias = Fq
        PY;

    /** PHP: the bare own-name call is a genuine `UnqualifiedName` that denotes the analysed unit, and is included. */
    public function test_php_bare_own_class_name_call_is_resolved_and_included(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP_BARE_OWN_NAME);
        $ref = $facts->units[0]->methods[0]->behaviourReferences[0];

        self::assertSame(QualifierKind::UnqualifiedName, $ref->qualifierKind);
        self::assertSame(TargetUnitRelation::DenotesAnalysedUnit, $ref->targetUnitRelation);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([['a', 'b', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value']);
    }

    /**
     * CORRECTED: was RED-A (LCOM4 = 3, the material divergence — the `Fq.b(self)` call
     * was invisible, not merely excluded-with-a-reason). Now resolves as `UnqualifiedName`
     * / `DenotesAnalysedUnit`, included, matching PHP's correct LCOM4 = 2.
     */
    public function test_python_bare_own_class_name_call_now_resolves_and_is_included(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_BARE_OWN_NAME);
        $methodA = $facts->units[0]->methods[0];

        self::assertSame('a', $methodA->methodIdentity);
        self::assertSame(QualifierKind::UnqualifiedName, $methodA->behaviourReferences[0]->qualifierKind);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([['a', 'b', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value'], 'now matches PHP exactly, not the pre-fix 3');
    }

    /**
     * CORRECTED: was "also invisible, same root cause." Now produces a real fact,
     * classified `AliasedName`, and is excluded at L4 with reason `AliasedSpelling` —
     * the same stated limitation PHP applies, for the right reason now, not by silence.
     */
    public function test_python_aliased_own_class_name_call_now_produces_evidence_and_is_excluded_by_kind(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_ALIASED_OWN_NAME);
        $methodA = $facts->units[0]->methods[0];

        self::assertSame(QualifierKind::AliasedName, $methodA->behaviourReferences[0]->qualifierKind);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([], $observed[0]['edges']);
        self::assertSame('AliasedSpelling', $observed[0]['excluded'][0]['reason']);
    }
}
