<?php

namespace Tests\Unit\Application\Election\Capabilities;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\EvaluationEnvelope;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationResult;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use App\Domain\Election\Security\Simplified\EvaluationReasonCode;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\Simplified\EvidenceSnapshot;
use PHPUnit\Framework\TestCase;

class CapabilityContextTrustFieldTest extends TestCase
{
    public function test_trust_field_is_nullable_default_null(): void
    {
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'view_election',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
        );

        $this->assertNull($context->trust);
    }

    public function test_trust_field_accepts_evaluation_envelope(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                classification: EvidenceClassification::Attested,
                reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
                auditContext: [],
                policyOutcomeSequence: [],
            ),
            new ConstitutionalObservationContext([
                OverlaySignal::contextStable('test_overlay', 'Normal operation', []),
            ]),
            new EvidenceSnapshot(
                evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                classification: EvidenceClassification::Attested,
                attestationValid: true,
                continuityPreserved: true,
                activeOverlay: null,
                denialReason: '',
                evidenceProvenance: [],
            ),
        );

        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $this->assertSame($envelope, $context->trust);
    }

    public function test_non_voting_context_has_null_trust(): void
    {
        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'view_results',
            actionMetadata: [],
            state: ElectionLifecycleState::ResultsPublished,
        );

        $this->assertNull($context->trust);
    }

    public function test_voting_context_carries_full_envelope(): void
    {
        $envelope = new EvaluationEnvelope(
            new EvidenceEvaluationResult(
                evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                classification: EvidenceClassification::Attested,
                reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
                auditContext: ['key' => 'value'],
                policyOutcomeSequence: [],
            ),
            new ConstitutionalObservationContext([
                OverlaySignal::contextStable('test_overlay', 'Normal operation', []),
            ]),
            new EvidenceSnapshot(
                evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                classification: EvidenceClassification::Attested,
                attestationValid: true,
                continuityPreserved: true,
                activeOverlay: null,
                denialReason: '',
                evidenceProvenance: [],
            ),
        );

        $context = new CapabilityContext(
            election: null,
            user: null,
            action: 'vote',
            actionMetadata: [],
            state: ElectionLifecycleState::VotingActive,
            trust: $envelope,
        );

        $this->assertNotNull($context->trust);
        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $context->trust->result->evaluationState);
        $this->assertEquals('value', $context->trust->result->auditContext['key']);
    }
}
