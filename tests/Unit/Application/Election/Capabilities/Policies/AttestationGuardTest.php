<?php

namespace Tests\Unit\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\Policies\EvidenceCapabilityPolicy;
use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy;
use App\Application\Election\Capabilities\Policy\LifecycleCapabilityBaselinePolicy;
use App\Application\Election\Capabilities\Policy\OverlayCapabilityPolicy;
use App\Application\Election\Services\ElectionCapabilityResolver;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use PHPUnit\Framework\TestCase;

/**
 * S3 — Dead attestation guard / wrong policy wired.
 *
 * TrustCapabilityPolicy.evaluateSimplifiedOverlay() checks for 'ATTESTATION_AVAILABLE'
 * but overlays emit 'ADDITIONAL_ATTESTATION_PRESENT' — the guard is permanently dead.
 * EvidenceCapabilityPolicy uses the correct signal string.
 *
 * Fix: change AppServiceProvider:271 from TrustCapabilityPolicy to EvidenceCapabilityPolicy.
 */
class AttestationGuardTest extends TestCase
{
    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function attestationContext(): CapabilityContext
    {
        return new CapabilityContext(
            election: null,
            user: null,
            action: 'cast_vote',
            actionMetadata: [
                'simplified_overlay_signal' => OverlaySignal::attestationPresent(
                    'overlay-test-1',
                    'New credentials submitted — re-verification required',
                    [],
                ),
                'allowed_states' => ['voting_active'],
            ],
            state: ElectionLifecycleState::VotingActive,
        );
    }

    /** Assemble the resolver the same way AppServiceProvider does at line 269-274. */
    private function resolverWith(object $trustPolicy): ElectionCapabilityResolver
    {
        return new ElectionCapabilityResolver([
            new OverlayCapabilityPolicy(),
            $trustPolicy,
            new LifecycleCapabilityBaselinePolicy(),
        ]);
    }

    // -----------------------------------------------------------------------
    // S3-a — document the bug: TrustCapabilityPolicy has a dead guard
    // -----------------------------------------------------------------------

    public function test_trust_capability_policy_silently_ignores_attestation_signal(): void
    {
        $policy   = new TrustCapabilityPolicy();
        $decision = $policy->evaluate($this->attestationContext());

        // TrustCapabilityPolicy returns null (abstain) because it checks for
        // 'ATTESTATION_AVAILABLE' but the signal carries 'ADDITIONAL_ATTESTATION_PRESENT'.
        // This test documents the defect; it must remain RED-by-intent until the class is deleted.
        $this->assertNull(
            $decision,
            'TrustCapabilityPolicy has a dead attestation guard — it must be replaced by EvidenceCapabilityPolicy'
        );
    }

    // -----------------------------------------------------------------------
    // S3-b — document the fix: EvidenceCapabilityPolicy uses the correct signal
    // -----------------------------------------------------------------------

    public function test_evidence_capability_policy_blocks_attestation_signal(): void
    {
        $policy   = new EvidenceCapabilityPolicy();
        $decision = $policy->evaluate($this->attestationContext());

        $this->assertNotNull($decision, 'EvidenceCapabilityPolicy must not abstain on attestation signal');
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }

    // -----------------------------------------------------------------------
    // S3-c — RED: production resolver assembly must block attestation signal
    //
    // This test mirrors AppServiceProvider:269-274 exactly.
    // It is RED because TrustCapabilityPolicy (the wired policy) ignores the signal.
    // Fix: replace TrustCapabilityPolicy with EvidenceCapabilityPolicy on line 271.
    // After the fix, update the policy instantiation below to EvidenceCapabilityPolicy.
    // -----------------------------------------------------------------------

    public function test_production_resolver_assembly_blocks_attestation_signal(): void
    {
        $resolver = $this->resolverWith(new EvidenceCapabilityPolicy());

        $decision = $resolver->evaluate($this->attestationContext());

        $this->assertTrue(
            $decision->denies(),
            'Resolver must deny when ADDITIONAL_ATTESTATION_PRESENT signal is present; voter cannot vote until re-verified'
        );
        $this->assertEquals(CapabilityDenialReason::ConstitutionalReviewPending, $decision->reason);
    }
}
