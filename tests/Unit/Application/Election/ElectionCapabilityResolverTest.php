<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Application\Election\Services\ElectionCapabilityResolver;
use App\Domain\Election\Enum\ElectionLifecycleState;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ElectionCapabilityResolver evaluation semantics.
 *
 * Invariants under test:
 *   - A grant from policy N must NOT short-circuit evaluation of policy N+1.
 *   - CapabilityDecision::abstain() must be treated identically to a null return.
 *   - A deny from any policy is still immediate and final.
 *   - All policies are recorded in the trace regardless of outcome.
 */
class ElectionCapabilityResolverTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function context(): CapabilityContext
    {
        return new CapabilityContext(
            election: null,
            user: null,
            action: 'cast_vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
        );
    }

    private function policyReturning(
        CapabilityDecision|null $decision,
        CapabilityPolicyLayer $layer,
    ): CapabilityPolicy {
        return new class($decision, $layer) implements CapabilityPolicy {
            public function __construct(
                private readonly ?CapabilityDecision $result,
                private readonly CapabilityPolicyLayer $policyLayer,
            ) {}

            public function evaluate(CapabilityContext $context): ?CapabilityDecision
            {
                return $this->result;
            }

            public function layer(): CapabilityPolicyLayer
            {
                return $this->policyLayer;
            }
        };
    }

    // -----------------------------------------------------------------------
    // S1 — grant must not short-circuit remaining policies
    // -----------------------------------------------------------------------

    public function test_grant_from_early_policy_does_not_prevent_later_deny(): void
    {
        // Policy at layer 3 (Lifecycle) grants — this must not end evaluation.
        // Policy at layer 5 (Authorization) denies — this must still fire.
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(CapabilityDecision::grant(), CapabilityPolicyLayer::Lifecycle),
            $this->policyReturning(
                CapabilityDecision::deny(CapabilityDenialReason::MissingRole, 'voter'),
                CapabilityPolicyLayer::Authorization,
            ),
        ]);

        $decision = $resolver->evaluate($this->context());

        $this->assertTrue($decision->denies(), 'A downstream deny must override an earlier grant');
        $this->assertEquals(CapabilityDenialReason::MissingRole, $decision->reason);
    }

    public function test_authorized_from_early_policy_does_not_prevent_later_deny(): void
    {
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(CapabilityDecision::authorized(), CapabilityPolicyLayer::Lifecycle),
            $this->policyReturning(
                CapabilityDecision::deny(CapabilityDenialReason::UnmetPrecondition),
                CapabilityPolicyLayer::Authorization,
            ),
        ]);

        $decision = $resolver->evaluate($this->context());

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::UnmetPrecondition, $decision->reason);
    }

    public function test_all_policies_appear_in_trace_when_none_deny(): void
    {
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(CapabilityDecision::grant(), CapabilityPolicyLayer::Overlay),
            $this->policyReturning(null, CapabilityPolicyLayer::Lifecycle),
            $this->policyReturning(CapabilityDecision::grant(), CapabilityPolicyLayer::Authorization),
        ]);

        $decision = $resolver->evaluate($this->context());
        $trace    = $resolver->lastTrace();

        $this->assertTrue($decision->allows());
        $this->assertCount(3, $trace->entries, 'All three policies must be recorded in the trace');
    }

    // -----------------------------------------------------------------------
    // S2 — CapabilityDecision::abstain() must behave identically to null
    // -----------------------------------------------------------------------

    public function test_abstain_decision_continues_to_next_policy(): void
    {
        // If abstain() is treated as authorized() the deny at layer 5 is never reached.
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(CapabilityDecision::abstain(), CapabilityPolicyLayer::Overlay),
            $this->policyReturning(
                CapabilityDecision::deny(CapabilityDenialReason::Suspended),
                CapabilityPolicyLayer::Authorization,
            ),
        ]);

        $decision = $resolver->evaluate($this->context());

        $this->assertTrue($decision->denies(), 'abstain() must not short-circuit; later deny must fire');
        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
    }

    public function test_abstain_decision_and_null_return_produce_same_outcome(): void
    {
        $withAbstainDecision = new ElectionCapabilityResolver([
            $this->policyReturning(CapabilityDecision::abstain(), CapabilityPolicyLayer::Overlay),
            $this->policyReturning(
                CapabilityDecision::deny(CapabilityDenialReason::Suspended),
                CapabilityPolicyLayer::Authorization,
            ),
        ]);

        $withNullReturn = new ElectionCapabilityResolver([
            $this->policyReturning(null, CapabilityPolicyLayer::Overlay),
            $this->policyReturning(
                CapabilityDecision::deny(CapabilityDenialReason::Suspended),
                CapabilityPolicyLayer::Authorization,
            ),
        ]);

        $decisionA = $withAbstainDecision->evaluate($this->context());
        $decisionB = $withNullReturn->evaluate($this->context());

        $this->assertEquals($decisionA->denies(), $decisionB->denies());
        $this->assertEquals($decisionA->reason, $decisionB->reason);
    }

    public function test_abstain_decision_is_recorded_in_trace_as_abstained(): void
    {
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(CapabilityDecision::abstain(), CapabilityPolicyLayer::Overlay),
            $this->policyReturning(CapabilityDecision::grant(), CapabilityPolicyLayer::Authorization),
        ]);

        $resolver->evaluate($this->context());
        $trace = $resolver->lastTrace();

        $this->assertCount(2, $trace->entries);
        $this->assertTrue($trace->entries[0]->isAbstained(), 'First entry must be abstained');
        $this->assertTrue($trace->entries[1]->isGranted(), 'Second entry must be granted');
    }

    // -----------------------------------------------------------------------
    // Regression — deny short-circuit must still work after the fix
    // -----------------------------------------------------------------------

    public function test_deny_is_still_immediate_and_skips_remaining_policies(): void
    {
        // Policy 1 (Overlay, layer 1): denies — must stop evaluation immediately.
        // Policy 2 (Authorization, layer 5): would grant — must never be reached.
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(
                CapabilityDecision::deny(CapabilityDenialReason::Suspended),
                CapabilityPolicyLayer::Overlay,
            ),
            $this->policyReturning(CapabilityDecision::grant(), CapabilityPolicyLayer::Authorization),
        ]);

        $decision = $resolver->evaluate($this->context());
        $trace    = $resolver->lastTrace();

        $this->assertTrue($decision->denies());
        $this->assertCount(1, $trace->entries, 'Only the denying policy should appear in the trace');
    }

    public function test_resolver_authorizes_when_all_policies_abstain(): void
    {
        $resolver = new ElectionCapabilityResolver([
            $this->policyReturning(null, CapabilityPolicyLayer::Overlay),
            $this->policyReturning(CapabilityDecision::abstain(), CapabilityPolicyLayer::Lifecycle),
        ]);

        $decision = $resolver->evaluate($this->context());

        $this->assertTrue($decision->allows());
        $this->assertFalse($decision->denies());
    }
}
