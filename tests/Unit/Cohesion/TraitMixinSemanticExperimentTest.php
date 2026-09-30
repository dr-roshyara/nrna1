<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * R2 — `trait_methods`. Characterization only (`KOS-PYTHON-RULE-VALIDATION`, 2026-09-27).
 * No production code touched in this file's own history. Question: does the pinned
 * decision "trait methods NOT resolved -- only methods declared in the class body are
 * analyzed" name a semantic property Python lacks, or is it the SAME "unit analysed in
 * isolation" principle already governing `inherited_methods` (frozen, untouched), merely
 * instantiated for PHP's second composition keyword? No new vocabulary is assumed; this
 * file only measures.
 */
final class TraitMixinSemanticExperimentTest extends TestCase
{
    private const PHP_TRAIT = <<<'PHP'
        <?php
        trait SomeBehavior { public function traitMethod() { return $this->hidden; } }
        class TraitUserExample {
            use SomeBehavior;
            private $own;
            public function ownMethod() { return $this->own; }
        }
        PHP;

    private const PYTHON_SINGLE_MIXIN = <<<'PY'
        class Mixin:
            def helper(self):
                pass

        class A(Mixin):
            def f(self):
                self.helper()
        PY;

    private const PYTHON_MULTIPLE_MIXINS = <<<'PY'
        class MixinOne:
            def helper_one(self):
                pass

        class MixinTwo:
            def helper_two(self):
                pass

        class A(MixinOne, MixinTwo):
            def f(self):
                self.helper_one()
                self.helper_two()
        PY;

    /**
     * PHP characterization: the golden fixture, verified at L3, not only L5. The trait's
     * own method is declared in the TRAIT's own lexical body -- a separate analysed unit
     * (`TraitUnit`) -- never copied into `TraitUserExample`'s own `methods` list. No
     * PHP-level trait-flattening is performed anywhere in this pipeline.
     */
    public function test_php_trait_method_is_not_part_of_the_using_class_own_method_set(): void
    {
        $facts = PhpFactExtractor::extract(self::PHP_TRAIT);
        $units = [];
        foreach ($facts->units as $u) {
            $units[$u->identity->toString()] = $u;
        }

        self::assertSame(UnitKind::TraitUnit, $units['SomeBehavior']->kind);
        self::assertSame(['traitMethod'], array_map(fn ($m) => $m->methodIdentity, $units['SomeBehavior']->methods));

        self::assertSame(UnitKind::ClassUnit, $units['TraitUserExample']->kind);
        self::assertSame(['ownMethod'], array_map(fn ($m) => $m->methodIdentity, $units['TraitUserExample']->methods));

        $observed = AnalyseCohesion::observe($facts);
        $traitUserIndex = array_search('TraitUserExample', array_column($observed, 'unit'), true);
        self::assertSame(1, $observed[$traitUserIndex]['value'], 'matches the pinned golden value for trait-user.php');
    }

    /**
     * Python single-mixin analogue: the SAME shape, produced by an entirely different
     * language mechanism (Python multiple inheritance / MRO, not a compile-time trait
     * flattening). `A`'s own method set (this adapter's `class_node.body`, deliberately
     * NOT inheritance-aware -- unchanged since the frozen inheritance branch) contains
     * only `f`; `helper` lives only on `Mixin` and is invisible to `A`'s own extraction.
     */
    public function test_python_single_mixin_produces_the_same_shape_as_the_php_trait_case(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_SINGLE_MIXIN);
        $units = [];
        foreach ($facts->units as $u) {
            $units[$u->identity->toString()] = $u;
        }

        self::assertSame(['helper'], array_map(fn ($m) => $m->methodIdentity, $units['Mixin']->methods));
        self::assertSame(['f'], array_map(fn ($m) => $m->methodIdentity, $units['A']->methods));

        $observed = AnalyseCohesion::observe($facts);
        $aIndex = array_search('A', array_column($observed, 'unit'), true);
        self::assertSame([], $observed[$aIndex]['edges'], 'no edge to a method A does not itself declare');
        self::assertSame('TargetNotDeclaredHere', $observed[$aIndex]['excluded'][0]['reason']);
        self::assertSame(1, $observed[$aIndex]['value'], 'same LCOM4 shape as the PHP trait golden fixture (single lone node)');
    }

    /** Python multiple mixins: confirmed the extractor never inspects `bases` at all -- unaffected by how many there are. */
    public function test_python_multiple_mixins_do_not_change_the_result(): void
    {
        $facts = (new PythonSemanticFactProvider())->extract(self::PYTHON_MULTIPLE_MIXINS);
        $units = [];
        foreach ($facts->units as $u) {
            $units[$u->identity->toString()] = $u;
        }

        self::assertSame(['f'], array_map(fn ($m) => $m->methodIdentity, $units['A']->methods));

        $observed = AnalyseCohesion::observe($facts);
        $aIndex = array_search('A', array_column($observed, 'unit'), true);
        self::assertSame([], $observed[$aIndex]['edges']);
        self::assertCount(2, $observed[$aIndex]['excluded'], 'both external calls excluded, independently, same reason');
        foreach ($observed[$aIndex]['excluded'] as $exclusion) {
            self::assertSame('TargetNotDeclaredHere', $exclusion['reason']);
        }
        self::assertSame(1, $observed[$aIndex]['value']);
    }
}
