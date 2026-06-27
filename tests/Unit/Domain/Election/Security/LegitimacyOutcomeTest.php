<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Application\Election\Security\ConstitutionalLegitimacyDecision;
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

/**
 * LegitimacyOutcomeTest
 *
 * Verifies the sovereign legitimacy outcomes:
 * - Four discrete enum values (Allowed, Denied, Deferred, Investigate)
 * - Correct mapping from TrustEvaluationState via the exclusive resolver
 * - No behavioral methods (pure enum)
 *
 * CONSTITUTIONAL LAW (DD.3a):
 * LegitimacyOutcome derivation from TrustEvaluationState is structurally
 * enforced through ConstitutionalLegitimacyDecision — no fromTrustState()
 * method exists on the enum. Tests verify the resolver's mapping, not
 * a removed enum method.
 */
class LegitimacyOutcomeTest extends TestCase
{
    private ConstitutionalLegitimacyDecision $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new ConstitutionalLegitimacyDecision();
    }

    public function test_has_four_outcomes(): void
    {
        $cases = LegitimacyOutcome::cases();
        $this->assertCount(4, $cases);
    }

    private function makeVotingTrustResult(TrustEvaluationState $state): VotingTrustResult
    {
        return new VotingTrustResult(
            evaluationState: $state,
            reason: 'test',
            trustLevel: TrustLevel::Unverified,
            auditContext: [],
            policyOutcomeSequence: [],
        );
    }

    public function test_allowed_maps_from_sufficient_evidence(): void
    {
        $outcome = $this->resolver->decide(
            $this->makeVotingTrustResult(TrustEvaluationState::SUFFICIENT_EVIDENCE)
        );
        $this->assertSame(LegitimacyOutcome::Allowed, $outcome);
        $this->assertSame('allowed', $outcome->value);
    }

    public function test_denied_maps_from_insufficient_evidence(): void
    {
        $outcome = $this->resolver->decide(
            $this->makeVotingTrustResult(TrustEvaluationState::INSUFFICIENT_EVIDENCE)
        );
        $this->assertSame(LegitimacyOutcome::Denied, $outcome);
        $this->assertSame('denied', $outcome->value);
    }

    public function test_deferred_maps_from_review_required(): void
    {
        $outcome = $this->resolver->decide(
            $this->makeVotingTrustResult(TrustEvaluationState::REVIEW_REQUIRED)
        );
        $this->assertSame(LegitimacyOutcome::Deferred, $outcome);
        $this->assertSame('deferred', $outcome->value);
    }

    public function test_investigate_maps_from_inconclusive(): void
    {
        $outcome = $this->resolver->decide(
            $this->makeVotingTrustResult(TrustEvaluationState::INCONCLUSIVE)
        );
        $this->assertSame(LegitimacyOutcome::Investigate, $outcome);
        $this->assertSame('investigate', $outcome->value);
    }

    public function test_all_trust_states_map_exhaustively(): void
    {
        $trustStates = TrustEvaluationState::cases();
        foreach ($trustStates as $state) {
            $outcome = $this->resolver->decide(
                $this->makeVotingTrustResult($state)
            );
            $this->assertInstanceOf(LegitimacyOutcome::class, $outcome);
        }
    }
}
