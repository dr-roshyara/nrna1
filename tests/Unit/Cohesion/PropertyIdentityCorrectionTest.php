<?php

declare(strict_types=1);

namespace Tests\Unit\Cohesion;

use EngineeringKnowledge\Capabilities\Cohesion\Application\AnalyseCohesion;
use EngineeringKnowledge\Capabilities\Cohesion\Infrastructure\Python\PythonSemanticFactProvider;
use PHPUnit\Framework\TestCase;

/**
 * OWD-3 -- GREEN slice (2026-09-27). Model A resolved directly from the existing
 * contract's own words (`GraphBuilder`/`Lcom4` docblocks, `expected.json`'s citation --
 * all say "node = declared method"). The correction: node connectivity is keyed by
 * OCCURRENCE, not bare name, disambiguating the reported label only when two or more
 * declared (non-lifecycle) methods actually share a name -- exactly the Python
 * `@property`/`@x.setter` idiom PHP cannot produce (confirmed, OWD-2: duplicate method
 * names are a PHP fatal error). RED/GREEN proof for the predictions table in
 * `...-OWD3-identity-model-resolution.md`, section 4.
 */
final class PropertyIdentityCorrectionTest extends TestCase
{
    private function observe(string $python): array
    {
        return AnalyseCohesion::observe((new PythonSemanticFactProvider())->extract($python));
    }

    /** Fixture 3: disjoint state, now correctly TWO isolated components, not the pre-fix 1. */
    public function test_fixture_3_disjoint_state_now_reports_two_components(): void
    {
        $observed = $this->observe(<<<'PY'
            class A:
                @property
                def value(self):
                    return self._a

                @value.setter
                def value(self, v):
                    self._b = v
            PY);

        self::assertSame([], $observed[0]['edges']);
        self::assertSame(2, $observed[0]['value'], 'was 1 pre-fix (OWD-2) -- two genuinely unrelated bodies must not collapse');
        self::assertSame(['value#0', 'value#1'], $observed[0]['nodes'], 'disambiguated only because they collide');
    }

    /** Fixture 6: two empty same-named nodes + unrelated, now correctly THREE components, not the pre-fix 2. */
    public function test_fixture_6_two_empty_nodes_plus_unrelated_now_reports_three_components(): void
    {
        $observed = $this->observe(<<<'PY'
            class A:
                @property
                def value(self):
                    return 1

                @value.setter
                def value(self, v):
                    pass

                def unrelated(self):
                    return self._z
            PY);

        self::assertSame([], $observed[0]['edges']);
        self::assertSame(3, $observed[0]['value'], 'was 2 pre-fix (OWD-2)');
        self::assertSame(['value#0', 'value#1', 'unrelated'], $observed[0]['nodes'], 'unrelated is not disambiguated -- no collision');
    }

    /** Fixture 2: both touch the same backing field -- LCOM4 stays 1, now via a REAL edge, not an accidental collapse. */
    public function test_fixture_2_shared_backing_field_now_produces_a_real_edge(): void
    {
        $observed = $this->observe(<<<'PY'
            class A:
                @property
                def value(self):
                    return self._value

                @value.setter
                def value(self, v):
                    self._value = v
            PY);

        self::assertSame([['value#0', 'value#1', 'state']], $observed[0]['edges'], 'was [] pre-fix (OWD-2) -- an honest edge now exists');
        self::assertSame(1, $observed[0]['value'], 'same number as pre-fix, now for the true reason');
    }

    /** Fixture 7: a third method sharing the field now produces TWO edges, not the pre-fix one (the setter's touch was silently absorbed). */
    public function test_fixture_7_third_method_sharing_the_field_now_produces_two_edges(): void
    {
        $observed = $this->observe(<<<'PY'
            class A:
                @property
                def value(self):
                    return self._value

                @value.setter
                def value(self, v):
                    self._value = v

                def other(self):
                    return self._value
            PY);

        self::assertCount(2, $observed[0]['edges'], 'was 1 pre-fix (OWD-2) -- the setters own claim was silently suppressed');
        self::assertSame(
            [['value#0', 'value#1', 'state'], ['value#0', 'other', 'state']],
            $observed[0]['edges']
        );
        self::assertSame(1, $observed[0]['value']);
    }

    /**
     * CORRECTED (combined OWD-4/OWD-7 fix, 2026-09-28): "value" was ambiguous at the
     * time OWD-3 was written (deliberately unresolved which occurrence a read vs.
     * write should target, per OWD-3 section 6) -- that question is now resolved.
     * A read of `self.value` connects ONLY to the getter occurrence; disjoint backing
     * fields here (_a/_b) confirm this is real target resolution, not a coincidence
     * of shared state (that case is covered separately by the fixture-2/7-style tests
     * above).
     */
    public function test_falsification_ambiguous_behaviour_reference_connects_to_every_matching_occurrence(): void
    {
        $observed = $this->observe(<<<'PY'
            class A:
                @property
                def value(self):
                    return self._a

                @value.setter
                def value(self, v):
                    self._b = v

                def reads_it(self):
                    return self.value
            PY);

        self::assertSame([['reads_it', 'value#0', 'behaviour']], $observed[0]['edges'], 'CORRECTED: only the getter, not both occurrences');
    }

    /** Regression guard: an ordinary, non-colliding property (getter only) is completely unaffected. */
    public function test_regression_ordinary_property_unaffected(): void
    {
        $observed = $this->observe("class A:\n    @property\n    def value(self):\n        return self._value\n");
        self::assertSame(['value'], $observed[0]['nodes'], 'no "#0" suffix when there is no collision');
        self::assertSame(1, $observed[0]['value']);
    }
}
