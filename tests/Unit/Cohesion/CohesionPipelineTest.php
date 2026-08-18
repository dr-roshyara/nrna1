<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Php\PhpFactExtractor;
use PHPUnit\Framework\TestCase;

/** End-to-end: PHP source → L3 → L4 → L5, with the three evidence levels. */
final class CohesionPipelineTest extends TestCase
{
    /** @return list<array<string,mixed>> */
    private function observe(string $source): array
    {
        return AnalyseCohesion::observe(PhpFactExtractor::extract($source));
    }

    public function test_an_interface_is_reported_as_seen_and_not_analysed(): void
    {
        $observed = $this->observe('<?php interface I { public function a(); public function b(); }');
        self::assertCount(1, $observed);
        self::assertSame('InterfaceUnit', $observed[0]['unitKind']);
        self::assertFalse($observed[0]['analysed']);
        self::assertNull($observed[0]['value']);
    }

    public function test_an_enum_is_analysed_as_a_unit(): void
    {
        $src = '<?php enum E { case X; function a(){ return self::b(); } function b(){ return 1; } }';
        $observed = $this->observe($src);
        self::assertSame('EnumUnit', $observed[0]['unitKind']);
        self::assertTrue($observed[0]['analysed']);
        self::assertSame(1, $observed[0]['value']);
    }

    public function test_a_trait_is_analysed_as_a_unit(): void
    {
        $src = '<?php trait T { function a(){ $this->p; } function b(){ $this->q; } }';
        self::assertSame(2, $this->observe($src)[0]['value']);
    }

    /** 13.5 — nullsafe is a behavioural edge; both prior implementations were blind to it. */
    public function test_a_nullsafe_call_creates_a_behavioural_edge(): void
    {
        $src = '<?php class N { function a(){ $this?->b(); } function b(){} function lonely(){ $this->z; } }';
        $observed = $this->observe($src)[0];
        self::assertSame([['a', 'b', 'behaviour']], $observed['edges']);
        self::assertSame(2, $observed['value']);
    }

    /** 13.3 — fully-qualified own-class reference INCLUDES. */
    public function test_a_fully_qualified_own_reference_creates_an_edge(): void
    {
        $src = '<?php namespace App; class Fq { function a(){ \App\Fq::b(); } function b(){} function lonely(){ $this->z; } }';
        self::assertSame(2, $this->observe($src)[0]['value']);
    }

    /** 13.3 bucket ruling — reported as NotTheAnalysedUnit, never NotDeterminable. */
    public function test_a_qualified_unaliased_reference_is_excluded_with_the_right_reason(): void
    {
        $src = '<?php class Fq { function a(){ Sub\Fq::b(); } function b(){} }';
        $observed = $this->observe($src)[0];
        // Two methods, and the reference did NOT connect them: it names another unit.
        self::assertSame(2, $observed['value']);
        self::assertSame('NotTheAnalysedUnit', $observed['excluded'][0]['reason']);
    }

    public function test_a_computed_target_is_excluded_as_not_determinable(): void
    {
        $src = '<?php class C { function a(){ $x::b(); } function b(){} }';
        self::assertSame('NotDeterminable', $this->observe($src)[0]['excluded'][0]['reason']);
    }

    /** ⭐ 13.7 — the graph is emitted as evidence, not only the number. */
    public function test_the_observation_carries_the_node_set_and_edge_set(): void
    {
        $src = '<?php class G { function a(){ $this->p; } function b(){ $this->p; } }';
        $observed = $this->observe($src)[0];
        self::assertSame(['a', 'b'], $observed['nodes']);
        self::assertSame([['a', 'b', 'state']], $observed['edges']);
        self::assertSame(1, $observed['value']);
    }

    public function test_anonymous_units_are_analysed_with_stable_identities(): void
    {
        $src = '<?php class F { function m(){ return new class { function p(){ $this->x; } function q(){ $this->y; } }; } }';
        $units = array_column($this->observe($src), 'unit');
        self::assertSame(['F', 'F/anon#1'], $units);
    }
}
