<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\VotingTrustResult;
use PHPUnit\Framework\TestCase;

class TrustEvaluationEnvelopeTest extends TestCase
{
    /**
     * D.R.2 Migration Note:
     * Overlay influence ontology intentionally replaced by observational constitutional semantics.
     * Old: OverlayInfluenceContext (encoded overlay authority implication)
     * New: ConstitutionalObservationContext (pure observation, resolver-exclusive interpretation)
     */
    public function test_envelope_bundles_result_and_overlay_observations(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            \App\Domain\Election\Security\TrustLevel::Attested,
            [],
            []
        );
        // Constitutional observations: empty set (no overlay signals)
        $observations = ConstitutionalObservationContext::empty();
        $snapshot = new ConstitutionalTrustSnapshot(
            true,
            \App\Domain\Election\Security\TrustLevel::Attested,
            'single_code',
            false,
            false,
            true,
            'none',
            true,
            null,
            null,
            '',
            [],
        );

        $envelope = new TrustEvaluationEnvelope($result, $observations, $snapshot);

        $this->assertSame($result, $envelope->result);
        $this->assertSame($observations, $envelope->overlayObservations);
        $this->assertSame($snapshot, $envelope->snapshot);
    }

    /**
     * D.R.2 Constitutional Principle:
     * TrustEvaluationEnvelope is purely observational/transportational.
     * It carries evidence to the resolver, never derives authority.
     */
    public function test_envelope_has_no_authority_deriving_methods(): void
    {
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::sufficientEvidence(
                \App\Domain\Election\Security\TrustLevel::Attested,
                [],
                []
            ),
            ConstitutionalObservationContext::empty(),
            new ConstitutionalTrustSnapshot(true, \App\Domain\Election\Security\TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );

        $this->assertFalse(method_exists($envelope, 'canVote'));
        $this->assertFalse(method_exists($envelope, 'isAuthorized'));
        $this->assertFalse(method_exists($envelope, 'authorize'));
        $this->assertFalse(method_exists($envelope, 'grant'));
        $this->assertFalse(method_exists($envelope, 'deny'));
        $this->assertFalse(method_exists($envelope, 'recalculate'));
    }

    /**
     * Envelope immutability: evidence bundling is read-only.
     * Constitutional invariant: observations cannot be mutated in transit.
     */
    public function test_envelope_is_readonly(): void
    {
        $envelope = new TrustEvaluationEnvelope(
            VotingTrustResult::sufficientEvidence(
                \App\Domain\Election\Security\TrustLevel::Attested,
                [],
                []
            ),
            ConstitutionalObservationContext::empty(),
            new ConstitutionalTrustSnapshot(true, \App\Domain\Election\Security\TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []),
        );

        $this->expectException(\Error::class);
        $envelope->result = VotingTrustResult::insufficientEvidence('test', \App\Domain\Election\Security\TrustLevel::Unverified, [], []);
    }

    /**
     * Envelope evidence accessibility: result, observations, snapshot remain independent.
     * This preserves evidence plurality semantics (not collapsed into scalar influence).
     */
    public function test_envelope_result_and_observations_accessible_independently(): void
    {
        $result = VotingTrustResult::sufficientEvidence(
            \App\Domain\Election\Security\TrustLevel::Attested,
            ['key' => 'value'],
            ['policy' => 'passed']
        );
        $observations = ConstitutionalObservationContext::empty();
        $snapshot = new ConstitutionalTrustSnapshot(true, \App\Domain\Election\Security\TrustLevel::Attested, 'single_code', false, false, true, 'none', true, null, null, '', []);

        $envelope = new TrustEvaluationEnvelope($result, $observations, $snapshot);

        // Result: resolver's verdict
        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $envelope->result->evaluationState);
        $this->assertEquals('value', $envelope->result->auditContext['key']);

        // Observations: evidence signals (empty in this case = no observational signals)
        // Constitutional point: observations form a SET, not a scalar reduction
        $this->assertNotNull($envelope->overlayObservations);
        $this->assertTrue($envelope->overlayObservations->isEmpty());
    }
}
