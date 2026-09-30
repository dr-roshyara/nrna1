<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\MethodRole;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R1 (constructor/destructor exclusion) — lifecycle normalization at L3.
 *
 * Grant: `KOS-PYTHON-RULE-VALIDATION`, Rule 1, GREEN slice (2026-09-27). Prior reports in
 * this directory established: (a) `GraphBuilder` excluded nodes by matching
 * `methodIdentity` against a literal PHP name list, the only spelling-match in the whole
 * `Domain` layer; (b) this produced a MATERIAL metric divergence for Python, whose
 * lifecycle method is spelled `__init__`, not `__construct`; (c) the correct placement is
 * a new closed-vocabulary `MethodRole` fact on `MethodFacts` (Ordinary | Lifecycle),
 * populated per-language by each adapter, consumed by `GraphBuilder` instead of a name
 * list. This test file is the RED/GREEN proof for that slice. Before the fix: Test A/C/D
 * fail with an "Undefined property: MethodFacts::$methodRole" fatal (the field does not
 * exist yet); Test B fails on the LCOM4 assertion (1, not the semantically correct 2) —
 * the exact reproduction already recorded in `...-R1-constructors.md`.
 */
final class MethodRoleLifecycleNormalizationTest extends TestCase
{
    private const PHP = <<<'PHP'
        <?php
        class A {
            public function __construct() {
                $this->x = 1;
                $this->y = 2;
                $this->z = 3;
            }
            public function f() { return $this->x; }
            public function g() { return $this->y; }
        }
        PHP;

    private const PHP_WITH_DESTRUCT = <<<'PHP'
        <?php
        class A {
            public function __destruct() {
                $this->x = 1;
            }
            public function f() { return $this->x; }
        }
        PHP;

    private const PYTHON = <<<'PY'
        class A:
            def __init__(self):
                self.x = 1
                self.y = 2
                self.z = 3

            def f(self):
                return self.x

            def g(self):
                return self.y
        PY;

    private const PHP_ORDINARY = <<<'PHP'
        <?php
        class A {
            public function helper() {}
            public function f() {
                $this->helper();
            }
        }
        PHP;

    /** Test A — PHP: __construct normalizes to Lifecycle; existing exclusion behaviour unchanged. */
    public function test_a_php_construct_normalizes_to_lifecycle_role_and_stays_excluded(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP);
        $unit = $facts->units[0];

        $byName = [];
        foreach ($unit->methods as $m) {
            $byName[$m->methodIdentity] = $m;
        }

        self::assertSame(MethodRole::Lifecycle, $byName['__construct']->methodRole);
        self::assertSame(MethodRole::Ordinary, $byName['f']->methodRole);
        self::assertSame(MethodRole::Ordinary, $byName['g']->methodRole);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(['f', 'g'], $observed[0]['nodes']);
        self::assertSame([], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value']);
    }

    /** Test A (destructor variant) — __destruct normalizes to Lifecycle identically. */
    public function test_a_php_destruct_normalizes_to_lifecycle_role_and_stays_excluded(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP_WITH_DESTRUCT);
        $unit = $facts->units[0];
        $byName = [];
        foreach ($unit->methods as $m) {
            $byName[$m->methodIdentity] = $m;
        }

        self::assertSame(MethodRole::Lifecycle, $byName['__destruct']->methodRole);
        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(['f'], $observed[0]['nodes']);
    }

    /**
     * Test B — Python: __init__ normalizes to Lifecycle. THE CORRECTED CASE.
     * RED before the fix: LCOM4 = 1 (the material divergence recorded in
     * `...-R1-constructors.md`, reproduced independently against the unmodified
     * implementation). GREEN after: LCOM4 = 2, matching PHP exactly.
     */
    public function test_b_python_init_normalizes_to_lifecycle_role_and_is_excluded(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON);
        $unit = $facts->units[0];
        $byName = [];
        foreach ($unit->methods as $m) {
            $byName[$m->methodIdentity] = $m;
        }

        self::assertSame(MethodRole::Lifecycle, $byName['__init__']->methodRole);
        self::assertSame(MethodRole::Ordinary, $byName['f']->methodRole);
        self::assertSame(MethodRole::Ordinary, $byName['g']->methodRole);

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(['f', 'g'], $observed[0]['nodes'], '__init__ must not appear as a node');
        self::assertSame([], $observed[0]['edges'], 'no __init__->f or __init__->g state edge');
        self::assertSame(2, $observed[0]['value'], 'must match the semantically correct PHP value, not the pre-fix 1');
    }

    /** Test C — ordinary methods are unaffected: role Ordinary, existing behaviour edge intact. */
    public function test_c_ordinary_methods_remain_ordinary_and_unaffected(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP_ORDINARY);
        $unit = $facts->units[0];
        foreach ($unit->methods as $m) {
            self::assertSame(MethodRole::Ordinary, $m->methodRole, $m->methodIdentity);
        }

        $observed = AnalyseCohesion::observe($facts);
        self::assertSame(['helper', 'f'], $observed[0]['nodes']);
        self::assertSame([['f', 'helper', 'behaviour']], $observed[0]['edges']);
    }

    /** Test D — cross-language canonical parity: PHP and Python converge at L3, before L4/L5. */
    public function test_d_php_and_python_l3_lifecycle_role_converges_before_the_graph_and_metric_do(): void
    {
        $phpFacts = PhpFactExtractor::extract(self::PHP);
        $pythonFacts = (new PythonSemanticFactProvider())->extract(self::PYTHON);

        $phpByName = [];
        foreach ($phpFacts->units[0]->methods as $m) {
            $phpByName[$m->methodIdentity] = $m;
        }
        $pythonByName = [];
        foreach ($pythonFacts->units[0]->methods as $m) {
            $pythonByName[$m->methodIdentity] = $m;
        }

        // L3: same role, independent of spelling.
        self::assertSame(MethodRole::Lifecycle, $phpByName['__construct']->methodRole);
        self::assertSame(MethodRole::Lifecycle, $pythonByName['__init__']->methodRole);
        self::assertSame($phpByName['f']->methodRole, $pythonByName['f']->methodRole);
        self::assertSame($phpByName['g']->methodRole, $pythonByName['g']->methodRole);

        // L4: same graph shape (node sets differ only by the language-specific lifecycle
        // spelling, which is absent from both once excluded).
        $phpObserved = AnalyseCohesion::observe($phpFacts);
        $pythonObserved = AnalyseCohesion::observe($pythonFacts);
        self::assertSame($phpObserved[0]['nodes'], $pythonObserved[0]['nodes']);
        self::assertSame($phpObserved[0]['edges'], $pythonObserved[0]['edges']);

        // L5: same metric.
        self::assertSame($phpObserved[0]['value'], $pythonObserved[0]['value']);
    }

    /**
     * Falsification (Phase 5): the rule must recognise each adapter's exact lifecycle
     * spelling, not arbitrary string similarity. A same-ish-looking but different name
     * (PHP's OTHER magic methods; a non-dunder name that merely contains "construct"/
     * "init") must remain Ordinary.
     */
    public function test_e_falsification_lookalike_names_remain_ordinary(): void
    {
        $php = <<<'PHP'
            <?php
            class A {
                public function construct() {}
                public function __toString() { return ''; }
                public function __wakeup() {}
            }
            PHP;
        foreach (PhpFactExtractor::extract($php)->units[0]->methods as $m) {
            self::assertSame(MethodRole::Ordinary, $m->methodRole, "PHP {$m->methodIdentity} must not be Lifecycle");
        }

        $python = <<<'PY'
            class A:
                def init(self):
                    pass

                def __repr__(self):
                    return ''

                def __str__(self):
                    return ''
            PY;
        foreach ((new PythonSemanticFactProvider())->extract($python)->units[0]->methods as $m) {
            self::assertSame(MethodRole::Ordinary, $m->methodRole, "Python {$m->methodIdentity} must not be Lifecycle");
        }
    }
}
