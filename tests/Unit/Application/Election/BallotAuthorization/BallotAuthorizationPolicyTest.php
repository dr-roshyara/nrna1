<?php

namespace Tests\Unit\Application\Election\BallotAuthorization;

use App\Application\Election\BallotAuthorization\Policies\BallotAuthorizationPolicy;
use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\BallotAuthorization\AuthorizationCode;
use App\Domain\Election\Security\BallotAuthorization\BallotSession;
use PHPUnit\Framework\TestCase;

/**
 * BallotAuthorizationPolicy Test
 *
 * BallotAuthorization is an independent sovereignty dimension:
 * it governs code/token workflow independently of trust evaluation.
 * Trust may be sufficient but authorization may fail — orthogonal concerns.
 */
class BallotAuthorizationPolicyTest extends TestCase
{
    private BallotAuthorizationPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new BallotAuthorizationPolicy();
    }

    private function makeContext(?BallotSession $session = null): CapabilityContext
    {
        return new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: $session
                ? ['ballot_session' => $session]
                : [],
            state: ElectionLifecycleState::VotingActive,
        );
    }

    public function test_policy_layers_at_preconditions(): void
    {
        $this->assertEquals(CapabilityPolicyLayer::Preconditions, $this->policy->layer());
    }

    /**
     * Single code protocol: valid unconsumed code → authorized.
     */
    public function test_single_code_allows_valid_unconsumed(): void
    {
        $session = new BallotSession(
            sessionId: 'sess_001',
            protocol: 'single_code',
            codes: [new AuthorizationCode('CODE1', false, null)],
            viewCompleted: false,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertTrue($decision->allows());
    }

    /**
     * Single code protocol: consumed code → prohibited.
     */
    public function test_single_code_denies_consumed(): void
    {
        $session = new BallotSession(
            sessionId: 'sess_001',
            protocol: 'single_code',
            codes: [new AuthorizationCode('CODE1', true, new \DateTimeImmutable('2026-05-27 12:00:00'))],
            viewCompleted: false,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::UnmetPrecondition, $decision->reason);
    }

    /**
     * Dual code protocol: requires both tokens (view + commit) available.
     */
    public function test_dual_code_requires_both_tokens(): void
    {
        // Only one code available, view not completed
        $session = new BallotSession(
            sessionId: 'sess_001',
            protocol: 'dual_code',
            codes: [
                new AuthorizationCode('VIEW_CODE', false, null),
                // commit code missing
            ],
            viewCompleted: false,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::UnmetPrecondition, $decision->reason);
    }

    /**
     * Dual code protocol: requires view step completed before commit.
     */
    public function test_dual_code_requires_view_before_commit(): void
    {
        // Both codes available but view not completed
        $session = new BallotSession(
            sessionId: 'sess_001',
            protocol: 'dual_code',
            codes: [
                new AuthorizationCode('VIEW_CODE', false, null),
                new AuthorizationCode('COMMIT_CODE', false, null),
            ],
            viewCompleted: false,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::UnmetPrecondition, $decision->reason);
    }

    /**
     * No ballot session data → abstain (non-voting action).
     */
    public function test_abstains_when_no_session(): void
    {
        $context = $this->makeContext(null);

        $decision = $this->policy->evaluate($context);

        $this->assertNull($decision);
    }

    /**
     * BallotAuthorization is independent of trust state.
     * The policy evaluates code protocol, not trust evidence.
     */
    public function test_independent_of_trust_state(): void
    {
        // Session data only — no trust context needed
        $session = new BallotSession(
            sessionId: 'sess_001',
            protocol: 'single_code',
            codes: [new AuthorizationCode('CODE1', false, null)],
            viewCompleted: false,
        );

        $context = $this->makeContext($session);
        $decision = $this->policy->evaluate($context);

        // Decision is based solely on ballot session, not trust state
        $this->assertTrue($decision->allows());
    }

    /**
     * Dual code with view completed and both codes available → authorized.
     */
    public function test_dual_code_allows_when_view_completed(): void
    {
        $session = new BallotSession(
            sessionId: 'sess_001',
            protocol: 'dual_code',
            codes: [
                new AuthorizationCode('VIEW_CODE', true, new \DateTimeImmutable('2026-05-27 12:00:00')),
                new AuthorizationCode('COMMIT_CODE', false, null),
            ],
            viewCompleted: true,
        );

        $decision = $this->policy->evaluate($this->makeContext($session));

        $this->assertTrue($decision->allows());
    }
}
