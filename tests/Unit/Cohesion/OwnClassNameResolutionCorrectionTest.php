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
 * R3 (`own_class_name_resolution`) — GREEN slice (`KOS-PYTHON-RULE-VALIDATION`,
 * 2026-09-27). Characterization + classification D already recorded in
 * `...-R3-own-class-name-resolution.md`. This is the RED/GREEN proof for the
 * authorized, minimally-scoped fix: the Python adapter now recognises a call whose
 * receiver is the analysed class's own bare name (-> `UnqualifiedName`) or a
 * module-level alias for it (-> `AliasedName`), exactly mirroring PHP's
 * `classifyQualifier()`/`relationTo()` for these two kinds. Deliberately NOT extended:
 * bare-name calls to any OTHER class (out of scope -- no general cross-unit name
 * resolution is introduced), and non-call attribute reads via a bare class name (the
 * pinned decision names `OwnClass::m()`, a call form, not a property form).
 */
final class OwnClassNameResolutionCorrectionTest extends TestCase
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

    private const PYTHON_OTHER_CLASS_BARE_NAME = <<<'PY'
        class Other:
            def b(self):
                pass

        class Fq:
            def a(self):
                Other.b(self)

            def b(self):
                pass
        PY;

    /** Test A: bare own-class-name call, corrected -- matches PHP field-by-field and at the metric. */
    public function test_a_python_bare_own_class_name_call_now_resolves_and_is_included(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_BARE_OWN_NAME);
        $ref = $facts->units[0]->methods[0]->behaviourReferences[0];

        self::assertSame(QualifierKind::UnqualifiedName, $ref->qualifierKind);
        self::assertSame(TargetUnitRelation::DenotesAnalysedUnit, $ref->targetUnitRelation);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([['a', 'b', 'behaviour']], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value']);

        // Cross-language parity, field by field, not only at the metric.
        $phpRef = PhpFactExtractor::extract(self::PHP_BARE_OWN_NAME)->units[0]->methods[0]->behaviourReferences[0];
        self::assertSame($phpRef->qualifierKind, $ref->qualifierKind);
        self::assertSame($phpRef->targetUnitRelation, $ref->targetUnitRelation);
        $phpObserved = AnalyseCohesion::observe(PhpFactExtractor::extract(self::PHP_BARE_OWN_NAME));
        self::assertSame($phpObserved[0]['value'], $observed[0]['value']);
    }

    /**
     * Test B: aliased own-class-name call -- now produces a REAL fact (not invisible),
     * correctly classified `AliasedName`, and correctly EXCLUDED at L4 with reason
     * `AliasedSpelling` -- the same "stated limitation, excluded by kind even where it
     * denotes the analysed unit" PHP already applies. Evidence-precision improvement,
     * not a metric change (both before and after: no edge, same LCOM4) -- exactly
     * parallel in shape to the earlier frozen inheritance evidence-precision correction.
     */
    public function test_b_python_aliased_own_class_name_call_now_produces_evidence_and_is_excluded_as_aliased(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_ALIASED_OWN_NAME);
        $methodA = $facts->units[0]->methods[0];

        self::assertNotSame([], $methodA->behaviourReferences, 'must now produce a real fact, not silence');
        self::assertSame(QualifierKind::AliasedName, $methodA->behaviourReferences[0]->qualifierKind);
        self::assertSame(TargetUnitRelation::DenotesAnalysedUnit, $methodA->behaviourReferences[0]->targetUnitRelation);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame([], $observed[0]['edges'], 'aliased spellings remain excluded by kind, same as PHP');
        self::assertSame('AliasedSpelling', $observed[0]['excluded'][0]['reason']);
    }

    /** Test C (falsification): a bare name denoting a DIFFERENT class must remain unrecognised -- scope stays bounded. */
    public function test_c_bare_name_denoting_a_different_class_remains_unrecognised(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_OTHER_CLASS_BARE_NAME);
        $units = [];
        foreach ($facts->units as $u) {
            $units[$u->identity->toString()] = $u;
        }
        $methodA = $units['Fq']->methods[0];
        self::assertSame('a', $methodA->methodIdentity);
        self::assertSame([], $methodA->behaviourReferences, 'Other.b(self) must stay unrecognised -- no general cross-unit resolution was introduced');
    }

    /** Test D (regression guard): self/cls receivers are unaffected by this change. */
    public function test_d_self_and_cls_receivers_are_unaffected(): void
    {
        $python = "class A:\n    def f(self):\n        self.helper()\n\n    def helper(self):\n        pass\n";
        $observed = AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
        self::assertSame([['f', 'helper', 'behaviour']], $observed[0]['edges']);
    }
}
