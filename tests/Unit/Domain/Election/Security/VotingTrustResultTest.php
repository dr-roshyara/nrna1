<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

class VotingTrustResultTest extends TestCase
{
    public function test_sufficient_evidence_creates_sufficient_result(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            TrustLevel::Attested,
            ['evidence' => 'verified'],
            ['verification' => 'passed']
        );

        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertEquals(TrustLevel::Attested, $result->trustLevel);
    }

    public function test_insufficient_evidence_creates_insufficient_result(): void
    {
        $result = VotingTrustResult::insufficientEvidence(
            'IP limit exceeded',
            TrustLevel::Unverified,
            ['votes_from_ip' => 15],
            ['network' => 'denied']
        );

        $this->assertEquals(TrustEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertEquals('IP limit exceeded', $result->reason);
    }

    public function test_audit_context_is_preserved(): void
    {
        $context = ['network_evidence' => 'hashed_ip'];
        $result = VotingTrustResult::sufficientEvidence(TrustLevel::RegistrarAttested, $context, []);

        $this->assertEquals($context, $result->auditContext);
    }

    public function test_policy_outcome_sequence_is_preserved(): void
    {
        $sequence = ['verification' => 'passed', 'network' => 'allowed'];
        $result = VotingTrustResult::sufficientEvidence(TrustLevel::Attested, [], $sequence);

        $this->assertEquals($sequence, $result->policyOutcomeSequence);
    }

    public function test_insufficient_evidence_with_unverified_trust_level(): void
    {
        $result = VotingTrustResult::insufficientEvidence(
            'verification required',
            TrustLevel::Unverified,
            [],
            ['verification' => 'not_verified']
        );

        $this->assertEquals(TrustEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
    }
}
