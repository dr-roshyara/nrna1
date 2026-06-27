<?php

namespace Tests\Unit\Application\Election\VoterVerification;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\VoterVerification\Policies\VoterVerificationPolicy;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\VoterVerification\VerificationSession;
use PHPUnit\Framework\TestCase;

/**
 * VoterVerificationPolicy Test
 *
 * VoterVerification is an evidence attestation subdomain.
 * The officer is evidence attestation authority, NOT voting authority.
 * This policy evaluates whether officer evidence capture is complete.
 */
class VoterVerificationPolicyTest extends TestCase
{
    private VoterVerificationPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new VoterVerificationPolicy();
    }

    private function makeContext(?VerificationSession $session = null): CapabilityContext
    {
        return new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: $session
                ? ['verification_session' => $session]
                : [],
            state: ElectionLifecycleState::VotingActive,
        );
    }

    public function test_policy_layers_at_preconditions(): void
    {
        $this->assertEquals(CapabilityPolicyLayer::Preconditions, $this->policy->layer());
    }

    /**
     * When verification is not required, the policy abstains.
     * Verification requirement is a constitutional matter, not a capability gate.
     */
    public function test_abstains_when_not_required(): void
    {
        $session = new VerificationSession(
            sessionId: 'sess_001',
            isRequired: false,
            isComplete: false,
            officerId: null,
            completedAt: null,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertNull($decision);
    }

    /**
     * When verification is required and complete, the policy abstains.
     * The precondition is met — let other policies (trust, lifecycle) decide.
     */
    public function test_abstains_when_required_and_complete(): void
    {
        $session = new VerificationSession(
            sessionId: 'sess_001',
            isRequired: true,
            isComplete: true,
            officerId: 'off_001',
            completedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertNull($decision);
    }

    /**
     * When verification is required and incomplete, the policy prohibits.
     * The precondition is not met — voting cannot proceed.
     */
    public function test_denies_when_required_and_incomplete(): void
    {
        $session = new VerificationSession(
            sessionId: 'sess_001',
            isRequired: true,
            isComplete: false,
            officerId: null,
            completedAt: null,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::UnmetPrecondition, $decision->reason);
    }

    /**
     * CRITICAL INVARIANT: This policy must NEVER authorize participation.
     * It checks preconditions only. Authority is derived by the Resolver.
     */
    public function test_does_not_authorize_participation(): void
    {
        // The policy only returns null (abstain) or prohibited — never authorized
        $notRequired = new VerificationSession(
            sessionId: 'sess_001',
            isRequired: false,
            isComplete: false,
            officerId: null,
            completedAt: null,
        );

        $requiredComplete = new VerificationSession(
            sessionId: 'sess_001',
            isRequired: true,
            isComplete: true,
            officerId: 'off_001',
            completedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
        );

        $this->assertNull($this->policy->evaluate($this->makeContext($notRequired)));
        $this->assertNull($this->policy->evaluate($this->makeContext($requiredComplete)));

        // Verify no test case returns authorized()
        $incomplete = new VerificationSession(
            sessionId: 'sess_001',
            isRequired: true,
            isComplete: false,
            officerId: null,
            completedAt: null,
        );

        $denied = $this->policy->evaluate($this->makeContext($incomplete));
        $this->assertTrue($denied->denies());
    }

    /**
     * When no verification session data exists, the policy abstains.
     * Non-voting actions have no verification data.
     */
    public function test_abstains_when_no_session_data(): void
    {
        $context = $this->makeContext(null);

        $decision = $this->policy->evaluate($context);

        $this->assertNull($decision);
    }
}
