<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Application\Election\Security\ConstitutionalLegitimacyDecision;
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

/**
 * D.0.3a — Constitutional Primary Enforcement Tests
 *
 * Verifies that the constitutional enforcement gate correctly maps
 * trust evaluation states to legitimacy outcomes and enforces them.
 *
 * CONSTITUTIONAL LAW:
 * - LegitimacyOutcome is derived EXCLUSIVELY by ConstitutionalLegitimacyDecision
 * - Non-Allowed outcomes MUST block vote submission for real elections
 * - Demo elections are exempted to preserve testability
 */
class D0_3a_ConstitutionalEnforcementTest extends TestCase
{
    private ConstitutionalLegitimacyDecision $decision;

    protected function setUp(): void
    {
        parent::setUp();
        $this->decision = new ConstitutionalLegitimacyDecision();
    }

    // =========================================================================
    // ConstitutionalLegitimacyDecision — Outcome Mapping
    // =========================================================================

    public function test_sufficient_evidence_maps_to_allowed(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            TrustLevel::Attested,
            ['policy' => 'clear'],
            ['verification' => 'clear'],
        );

        $outcome = $this->decision->decide($result);

        $this->assertSame(LegitimacyOutcome::Allowed, $outcome);
    }

    public function test_insufficient_evidence_maps_to_denied(): void
    {
        $result = VotingTrustResult::insufficientEvidence(
            'Attestation revoked',
            TrustLevel::Unverified,
            ['concern' => 'attestation_revoked'],
            ['verification' => 'attestation_revoked'],
        );

        $outcome = $this->decision->decide($result);

        $this->assertSame(LegitimacyOutcome::Denied, $outcome);
    }

    public function test_review_required_maps_to_deferred(): void
    {
        $result = VotingTrustResult::reviewRequired(
            'Evidence ambiguous',
            ['concern' => 'incomplete_attestation'],
            ['verification' => 'review_required'],
        );

        $outcome = $this->decision->decide($result);

        $this->assertSame(LegitimacyOutcome::Deferred, $outcome);
    }

    public function test_inconclusive_maps_to_investigate(): void
    {
        $result = VotingTrustResult::inconclusive(
            'Evidence quality insufficient',
            ['concern' => 'evidence_too_old'],
            ['verification' => 'inconclusive'],
        );

        $outcome = $this->decision->decide($result);

        $this->assertSame(LegitimacyOutcome::Investigate, $outcome);
    }

    // =========================================================================
    // Enforcement Gate Condition — D.0.3a Logic
    // =========================================================================

    /**
     * The enforcement condition is:
     *   $constitutionalOutcome !== LegitimacyOutcome::Allowed
     *     && $election->type !== 'demo'
     *
     * This test verifies the outcome side of the condition.
     */
    public function test_allowed_outcome_does_not_trigger_enforcement(): void
    {
        $allowed = $this->decision->decide(
            VotingTrustResult::sufficientEvidence(TrustLevel::Attested, [], []),
        );

        $this->assertFalse(
            $allowed !== LegitimacyOutcome::Allowed,
            'Allowed outcome must NOT trigger enforcement block',
        );
    }

    public function test_denied_outcome_triggers_enforcement(): void
    {
        $denied = $this->decision->decide(
            VotingTrustResult::insufficientEvidence('test', TrustLevel::Unverified, [], []),
        );

        $this->assertTrue(
            $denied !== LegitimacyOutcome::Allowed,
            'Denied outcome MUST trigger enforcement block',
        );
    }

    public function test_deferred_outcome_triggers_enforcement(): void
    {
        $deferred = $this->decision->decide(
            VotingTrustResult::reviewRequired('test', [], []),
        );

        $this->assertTrue(
            $deferred !== LegitimacyOutcome::Allowed,
            'Deferred outcome MUST trigger enforcement block',
        );
    }

    public function test_investigate_outcome_triggers_enforcement(): void
    {
        $investigate = $this->decision->decide(
            VotingTrustResult::inconclusive('test', [], []),
        );

        $this->assertTrue(
            $investigate !== LegitimacyOutcome::Allowed,
            'Investigate outcome MUST trigger enforcement block',
        );
    }

    // =========================================================================
    // Deterministic Replay — Same Input, Same Outcome
    // =========================================================================

    public function test_identical_trust_results_produce_identical_outcomes(): void
    {
        $input1 = VotingTrustResult::insufficientEvidence(
            'Network limit exceeded',
            TrustLevel::Unverified,
            ['votes_from_ip' => 10, 'max_allowed' => 5],
            ['network' => 'denied'],
        );

        $input2 = VotingTrustResult::insufficientEvidence(
            'Network limit exceeded',
            TrustLevel::Unverified,
            ['votes_from_ip' => 10, 'max_allowed' => 5],
            ['network' => 'denied'],
        );

        $outcome1 = $this->decision->decide($input1);
        $outcome2 = $this->decision->decide($input2);

        $this->assertSame($outcome1, $outcome2,
            'D.0.3a F6 violation: identical trust results produced different outcomes',
        );
    }
}
