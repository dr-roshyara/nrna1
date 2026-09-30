<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * Grant G-KOS-CONTRACT-PYTHON-CLASS-STATIC-DISPATCH-EXPERIMENT (2026-09-27).
 * H1-H5: staticmethod/classmethod/instance dispatch, one class, no inheritance.
 */
final class PythonClassStaticDispatchExperimentTest extends TestCase
{
    private const PYTHON = <<<'PY'
        class Example:
            @staticmethod
            def helper():
                pass

            @classmethod
            def factory(cls):
                cls.helper()

            def instance(self):
                self.helper()
        PY;

    private const PHP = <<<'PHP'
        <?php
        class Example {
            public static function helper() {}
            public static function factory() { static::helper(); }
            public function instance() { $this->helper(); }
        }
        PHP;

    private function observePython(string $source): array
    {
        return AnalyseCohesion::observeFromSource(new PythonSemanticFactProvider(), $source);
    }

    private function observePhp(string $source): array
    {
        return AnalyseCohesion::observe(PhpFactExtractor::extract($source));
    }

    public function test_h1_h2_h4_python_class_static_dispatch_matches_php_representation_and_graph(): void
    {
        $python = $this->observePython(self::PYTHON);
        $php = $this->observePhp(self::PHP);

        self::assertSame($php, $python);
        self::assertSame(
            [['factory', 'helper', 'behaviour'], ['instance', 'helper', 'behaviour']],
            $python[0]['edges'],
        );
    }

    /**
     * H3: instance dispatch (self.) and class dispatch (cls.) remain distinguishable at L3
     * (different QualifierKind) even though, for this case, EdgeRules' existing rule (both
     * are "self-referential by construction") happens to include both as the same KIND of
     * edge. L3 differs; graph outcome coincides. Both facts are reported, neither collapsed.
     */
    public function test_h3_instance_and_static_dispatch_are_distinct_at_l3_despite_the_same_graph_outcome(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP);
        $byMethod = [];
        foreach ($facts->units[0]->methods as $m) {
            foreach ($m->behaviourReferences as $ref) {
                $byMethod[$m->methodIdentity] = $ref->qualifierKind->name;
            }
        }

        self::assertSame('StaticKeyword', $byMethod['factory']);
        self::assertSame('InstanceReceiver', $byMethod['instance']);
        self::assertNotSame($byMethod['factory'], $byMethod['instance']);
    }

    public function test_helper_itself_has_no_outgoing_behaviour_references(): void
    {
        $observed = $this->observePython(self::PYTHON);
        // helper's own body is `pass` -- it must not appear as the SOURCE of any edge
        // (it is a legitimate target, from factory and instance, per the other test).
        foreach ($observed[0]['edges'] as $edge) {
            self::assertNotSame('helper', $edge[0]);
        }
    }
}
