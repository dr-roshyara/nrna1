<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Domain\AccessMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\DeclaredUnit;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\Determinability;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\QualifierKind;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\ReferenceMode;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\TargetUnitRelation;
use EngineeringKnowledge\Capabilities\Cohesion\Domain\UnitKind;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * B3 PHP EXTRACTION — source becomes L3 facts. Extraction only: the adapter
 * makes no cohesion decision. Every construct below is one the measured defect
 * surface named (K1–K3, NEW-1…NEW-9) or a rule 13.3/13.5 decided.
 */
final class PhpFactExtractionTest extends TestCase
{
    /** @return list<DeclaredUnit> */
    private function units(string $source): array
    {
        return PhpFactExtractor::extract($source)->units;
    }

    private function unitNamed(string $source, string $identity): DeclaredUnit
    {
        foreach ($this->units($source) as $unit) {
            if ($unit->identity->toString() === $identity) {
                return $unit;
            }
        }
        self::fail(sprintf('No unit "%s" (found: %s)', $identity, implode(', ', array_map(
            static fn (DeclaredUnit $u): string => $u->identity->toString(),
            $this->units($source)
        ))));
    }

    // ---- unit kinds (13.5) ------------------------------------------------

    public function test_class_enum_trait_and_interface_are_all_emitted_with_their_kind(): void
    {
        $src = "<?php class C { function a(){} } enum E { function e(){} } "
             . "trait T { function t(){} } interface I { public function i(); }";
        $kinds = [];
        foreach ($this->units($src) as $unit) {
            $kinds[$unit->identity->toString()] = $unit->kind;
        }
        self::assertSame(UnitKind::ClassUnit, $kinds['C']);
        self::assertSame(UnitKind::EnumUnit, $kinds['E']);
        self::assertSame(UnitKind::TraitUnit, $kinds['T']);
        // INV-8: seen-and-excluded must be distinguishable from unseen.
        self::assertSame(UnitKind::InterfaceUnit, $kinds['I']);
    }

    // ---- anonymous identity (OQ-1 / AMD4) ---------------------------------

    public function test_anonymous_classes_get_deterministic_declaration_path_identities(): void
    {
        $src = "<?php class F { function m(){ \$a = new class { function p(){} }; "
             . "\$b = new class { function q(){} }; } }";
        $ids = array_map(
            static fn (DeclaredUnit $u): string => $u->identity->toString(),
            $this->units($src)
        );
        self::assertContains('F/anon#1', $ids);
        self::assertContains('F/anon#2', $ids);
        self::assertNotContains('(anonymous)', $ids);
    }

    /** NEW-1: `new class implements X` must not be named after a keyword. */
    public function test_an_anonymous_class_with_implements_is_not_named_after_a_keyword(): void
    {
        $src = "<?php class F { function m(){ return new class implements Countable { function p(){} }; } }";
        $ids = array_map(static fn (DeclaredUnit $u): string => $u->identity->toString(), $this->units($src));
        self::assertSame(['F', 'F/anon#1'], $ids);
    }

    // ---- lexical constructs from the measured defect surface ---------------

    /** K3 / NEW-2 / NEW-3: `#[` is an attribute, never a comment. */
    public function test_a_same_line_attribute_does_not_hide_the_class_or_its_methods(): void
    {
        $src = "<?php #[Entity] class P { #[Route] public function a(): void { \$this->x; } "
             . "public function b(){ \$this->y; } }";
        $unit = $this->unitNamed($src, 'P');
        self::assertSame(['a', 'b'], array_map(static fn ($m) => $m->methodIdentity, $unit->methods));
    }

    /**
     * K1 / K2 / NEW-4 — and the language's actual truth, which the earlier
     * regex scanner got wrong in BOTH directions.
     *
     * In a HEREDOC, `$this->b()` interpolates the PROPERTY READ `$this->b`; the
     * trailing `()` is literal text (T_ENCAPSED_AND_WHITESPACE), NOT parentheses.
     * So it is a StateAccess, never an invocation — which is exactly why the
     * AST reference reported 2 and the scanner fabricated an edge and reported 1.
     *
     * In a NOWDOC nothing interpolates at all, so no fact exists.
     */
    public function test_a_heredoc_yields_a_property_read_not_an_invocation(): void
    {
        $src = "<?php class H { function a(){ \$s = <<<TXT\n  \$this->b()\n  TXT; } function b(){} }";
        $method = $this->unitNamed($src, 'H')->methods[0];
        self::assertSame([], $method->behaviourReferences);
        self::assertSame('b', $method->stateAccesses[0]->propertyName);
    }

    public function test_a_nowdoc_yields_no_facts_at_all(): void
    {
        $src = "<?php class NW { function a(){ \$s = <<<'RAW'\n  \$this->b()\n  RAW; } function b(){} }";
        $method = $this->unitNamed($src, 'NW')->methods[0];
        self::assertSame([], $method->behaviourReferences);
        self::assertSame([], $method->stateAccesses);
    }

    /** NEW-6: interpolated code IS code. */
    public function test_interpolation_yields_real_facts(): void
    {
        $src = "<?php class I2 { function a(){ \$s = \"v: {\$this->b()} \$this->p\"; } function b(){} }";
        $unit = $this->unitNamed($src, 'I2');
        self::assertSame('b', $unit->methods[0]->behaviourReferences[0]->targetMethodName);
        self::assertSame('p', $unit->methods[0]->stateAccesses[0]->propertyName);
    }

    /** NEW-7: post-`?>` text is not code. */
    public function test_inline_html_after_the_close_tag_fabricates_no_unit(): void
    {
        $src = "<?php class R { function a(){} } ?>\n<p>class Ghost { function p(){} }</p>";
        self::assertSame(['R'], array_map(static fn ($u) => $u->identity->toString(), $this->units($src)));
    }

    /** NEW-8: `$class` is a variable, never a declaration. */
    public function test_a_variable_named_class_fabricates_no_unit(): void
    {
        $src = "<?php class V { function a(\$class){ return \$class instanceof self; } function b(){} }";
        self::assertSame(['V'], array_map(static fn ($u) => $u->identity->toString(), $this->units($src)));
    }

    /** NEW-9: non-ASCII identifiers are legal PHP. */
    public function test_non_ascii_identifiers_are_preserved(): void
    {
        $src = "<?php class \u{00DC}nique { function \u{00FC}ber(){ \$this->gr\u{00F6}sse; } }";
        $unit = $this->unitNamed($src, "\u{00DC}nique");
        self::assertSame("\u{00FC}ber", $unit->methods[0]->methodIdentity);
        self::assertSame("gr\u{00F6}sse", $unit->methods[0]->stateAccesses[0]->propertyName);
    }

    // ---- 13.3 name kinds ---------------------------------------------------

    #[DataProvider('nameKinds')]
    public function test_qualifier_kind_and_relation_are_preserved(
        string $source,
        QualifierKind $kind,
        TargetUnitRelation $relation
    ): void {
        $unit = $this->units($source)[0];
        $ref = $unit->methods[0]->behaviourReferences[0];
        self::assertSame($kind, $ref->qualifierKind);
        self::assertSame($relation, $ref->targetUnitRelation);
    }

    /** @return array<string,array{0:string,1:QualifierKind,2:TargetUnitRelation}> */
    public static function nameKinds(): array
    {
        $wrap = static fn (string $call, string $prefix = ''): string =>
            "<?php {$prefix} class Fq { function a(){ {$call} } function b(){} }";

        return [
            'unqualified own name' => [$wrap('Fq::b();'), QualifierKind::UnqualifiedName, TargetUnitRelation::DenotesAnalysedUnit],
            'self'                 => [$wrap('self::b();'), QualifierKind::SelfKeyword, TargetUnitRelation::DenotesAnalysedUnit],
            'static'               => [$wrap('static::b();'), QualifierKind::StaticKeyword, TargetUnitRelation::DenotesAnalysedUnit],
            'parent'               => [$wrap('parent::b();'), QualifierKind::ParentKeyword, TargetUnitRelation::Undetermined],
            'fully-qualified own'  => [$wrap('\Fq::b();'), QualifierKind::FullyQualifiedName, TargetUnitRelation::DenotesAnalysedUnit],
            'fully-qualified other'=> [$wrap('\Vendor\Fq::b();'), QualifierKind::FullyQualifiedName, TargetUnitRelation::DenotesOtherUnit],
            'relative'             => [$wrap('namespace\Fq::b();'), QualifierKind::RelativeName, TargetUnitRelation::DenotesAnalysedUnit],
            'qualified unaliased'  => [$wrap('Sub\Fq::b();'), QualifierKind::QualifiedName, TargetUnitRelation::DenotesOtherUnit],
            'aliased (denotes the own unit)' => [$wrap('Ali::b();', 'namespace App; use App\Fq as Ali;'), QualifierKind::AliasedName, TargetUnitRelation::DenotesAnalysedUnit],
            'aliased (denotes another unit)' => [$wrap('Ali::b();', 'use App\Fq as Ali;'), QualifierKind::AliasedName, TargetUnitRelation::DenotesOtherUnit],
            'computed'             => [$wrap('$c::b();'), QualifierKind::ComputedTarget, TargetUnitRelation::Undetermined],
        ];
    }

    public function test_fully_qualified_own_name_inside_a_namespace_denotes_the_analysed_unit(): void
    {
        $src = "<?php namespace App; class Fq { function a(){ \App\Fq::b(); } function b(){} }";
        $ref = $this->units($src)[0]->methods[0]->behaviourReferences[0];
        self::assertSame(QualifierKind::FullyQualifiedName, $ref->qualifierKind);
        self::assertSame(TargetUnitRelation::DenotesAnalysedUnit, $ref->targetUnitRelation);
    }

    public function test_a_computed_target_is_marked_not_determinable(): void
    {
        $src = "<?php class C { function a(){ \$c::b(); } function b(){} }";
        $ref = $this->units($src)[0]->methods[0]->behaviourReferences[0];
        self::assertSame(Determinability::NotDeterminable, $ref->determinability);
    }

    // ---- nullsafe + first-class callables ----------------------------------

    /** 13.5: nullsafe must become a fact — both implementations were blind to it. */
    public function test_nullsafe_call_and_property_are_extracted_with_access_mode(): void
    {
        $src = "<?php class N { function a(){ \$this?->b(); \$x = \$this?->p; } function b(){} }";
        $method = $this->units($src)[0]->methods[0];
        self::assertSame(AccessMode::Nullsafe, $method->behaviourReferences[0]->accessMode);
        self::assertSame('b', $method->behaviourReferences[0]->targetMethodName);
        self::assertSame(AccessMode::Nullsafe, $method->stateAccesses[0]->accessMode);
        self::assertSame('p', $method->stateAccesses[0]->propertyName);
    }

    public function test_first_class_callables_are_marked_as_references_not_invocations(): void
    {
        $src = "<?php class FC { function a(){ \$f = \$this->b(...); \$g = self::b(...); } function b(){} }";
        foreach ($this->units($src)[0]->methods[0]->behaviourReferences as $ref) {
            self::assertSame(ReferenceMode::CallableReference, $ref->referenceMode);
        }
    }

    public function test_a_bodyless_method_is_declared_without_a_body(): void
    {
        $src = "<?php abstract class A { abstract public function a(); public function b(){} }";
        $unit = $this->unitNamed($src, 'A');
        self::assertFalse($unit->methods[0]->hasBody);
        self::assertTrue($unit->methods[1]->hasBody);
    }

    public function test_nested_functions_and_closures_do_not_become_methods(): void
    {
        $src = "<?php class Cl { function a(){ \$f = function(){ return 1; }; } }";
        self::assertSame(['a'], array_map(static fn ($m) => $m->methodIdentity, $this->unitNamed($src, 'Cl')->methods));
    }
}
