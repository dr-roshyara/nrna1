<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityTrace;
use App\Application\Election\Capabilities\CapabilityTraceEntry;
use PHPUnit\Framework\TestCase;

class CapabilityTraceTest extends TestCase
{
    public function test_empty_trace(): void
    {
        $trace = CapabilityTrace::empty();

        $this->assertEquals(0, $trace->count());
        $this->assertFalse($trace->hasDenials());
        $this->assertEmpty($trace->denials());
    }

    public function test_add_entry_to_trace(): void
    {
        $trace = CapabilityTrace::empty();
        $entry = CapabilityTraceEntry::granted('Policy1');

        $newTrace = $trace->add($entry);

        $this->assertEquals(1, $newTrace->count());
        $this->assertEmpty($newTrace->denials());
    }

    public function test_trace_is_immutable(): void
    {
        $trace1 = CapabilityTrace::empty();
        $entry = CapabilityTraceEntry::granted('Policy1');
        $trace2 = $trace1->add($entry);

        $this->assertNotSame($trace1, $trace2);
        $this->assertEquals(0, $trace1->count());
        $this->assertEquals(1, $trace2->count());
    }

    public function test_add_multiple_entries(): void
    {
        $trace = CapabilityTrace::empty();
        $entry1 = CapabilityTraceEntry::granted('Policy1');
        $entry2 = CapabilityTraceEntry::abstained('Policy2');
        $entry3 = CapabilityTraceEntry::denied('Policy3', CapabilityDenialReason::Suspended);

        $trace = $trace->add($entry1)->add($entry2)->add($entry3);

        $this->assertEquals(3, $trace->count());
    }

    public function test_has_denials(): void
    {
        $trace = CapabilityTrace::empty()
            ->add(CapabilityTraceEntry::granted('Policy1'))
            ->add(CapabilityTraceEntry::abstained('Policy2'));

        $this->assertFalse($trace->hasDenials());

        $traceWithDenial = $trace->add(
            CapabilityTraceEntry::denied('Policy3', CapabilityDenialReason::Suspended)
        );

        $this->assertTrue($traceWithDenial->hasDenials());
    }

    public function test_denials_returns_only_denied_entries(): void
    {
        $trace = CapabilityTrace::empty()
            ->add(CapabilityTraceEntry::granted('Policy1'))
            ->add(CapabilityTraceEntry::denied('Policy2', CapabilityDenialReason::MissingRole))
            ->add(CapabilityTraceEntry::abstained('Policy3'))
            ->add(CapabilityTraceEntry::denied('Policy4', CapabilityDenialReason::InvalidLifecycle));

        $denials = $trace->denials();

        $this->assertCount(2, $denials);
        $this->assertTrue($denials[0]->isDenied());
        $this->assertTrue($denials[1]->isDenied());
    }

    public function test_first_denial(): void
    {
        $trace = CapabilityTrace::empty()
            ->add(CapabilityTraceEntry::granted('Policy1'))
            ->add(CapabilityTraceEntry::denied('Policy2', CapabilityDenialReason::Suspended))
            ->add(CapabilityTraceEntry::denied('Policy3', CapabilityDenialReason::MissingRole));

        $first = $trace->firstDenial();

        $this->assertNotNull($first);
        $this->assertEquals('Policy2', $first->policyName);
        $this->assertEquals(CapabilityDenialReason::Suspended, $first->reason);
    }

    public function test_first_denial_returns_null_when_no_denials(): void
    {
        $trace = CapabilityTrace::empty()
            ->add(CapabilityTraceEntry::granted('Policy1'))
            ->add(CapabilityTraceEntry::abstained('Policy2'));

        $this->assertNull($trace->firstDenial());
    }

    public function test_policy_names(): void
    {
        $trace = CapabilityTrace::empty()
            ->add(CapabilityTraceEntry::granted('Policy1'))
            ->add(CapabilityTraceEntry::abstained('Policy2'))
            ->add(CapabilityTraceEntry::denied('Policy3', CapabilityDenialReason::Suspended));

        $names = $trace->policyNames();

        $this->assertEquals(['Policy1', 'Policy2', 'Policy3'], $names);
    }

    public function test_empty_trace_policy_names(): void
    {
        $trace = CapabilityTrace::empty();

        $this->assertEmpty($trace->policyNames());
    }
}
