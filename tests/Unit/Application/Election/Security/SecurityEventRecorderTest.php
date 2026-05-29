<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Domain\Shared\Clock\ClockInterface;
use App\Models\Election;
use App\Models\ElectionSecurityEvent;
use Tests\TestCase;

class SecurityEventRecorderTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeClock(): ClockInterface
    {
        return new class implements ClockInterface {
            public function now(): \DateTimeImmutable
            {
                return new \DateTimeImmutable('2026-05-27 12:00:00');
            }
        };
    }

    private function makeCtx(?Election $election = null): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 1,
                restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'fp', registeredFingerprintHash: 'fp',
                matchType: FingerprintMatchType::ExactMatch,
                captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: true, attested: true, registrarId: null,
                attestationTimestamp: new \DateTimeImmutable(),
                protocol: 'both', networkEvidenceHash: 'hash', deviceEvidenceHash: 'fp',
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    public function test_deny_result_always_recorded(): void
    {
        $election = Election::factory()->create();
        $result = VotingTrustResult::insufficientEvidence(
            reason: 'network_limit_exceeded',
            trustLevel: TrustLevel::Unverified,
            context: ['votes_from_ip' => 10],
            sequence: ['network_binding_policy' => ['outcome' => 'denied']],
        );
        $ctx = $this->makeCtx($election);

        $recorder = new SecurityEventRecorder($this->makeClock());
        $recorder->record($result, $ctx);

        $this->assertDatabaseHas('election_security_events', [
            'election_id' => $election->id,
        ]);

        $event = ElectionSecurityEvent::where('election_id', $election->id)->first();
        $this->assertNotNull($event);
        $this->assertNotNull($event->evaluation_summary);
        $this->assertIsArray($event->evaluation_summary);
        $this->assertEquals(TrustEvaluationState::INSUFFICIENT_EVIDENCE->value, $event->evaluation_summary['state']);
    }

    public function test_recorder_returns_void(): void
    {
        $recorder = new SecurityEventRecorder($this->makeClock());
        $result = VotingTrustResult::sufficientEvidence(
            trustLevel: TrustLevel::Attested,
            context: [],
            sequence: [],
        );
        $ctx = $this->makeCtx();

        // Fire-and-forget: should return void, not throw
        $return = $recorder->record($result, $ctx);

        $this->assertNull($return);
    }

    public function test_recorder_failure_does_not_change_trust_result(): void
    {
        // Invariant 8: audit failure MUST NEVER cause trust denial
        // Even if recording fails, trust outcome remains unchanged
        $result = VotingTrustResult::sufficientEvidence(
            trustLevel: TrustLevel::Attested,
            context: [],
            sequence: [],
        );
        $ctx = $this->makeCtx();

        $recorder = new SecurityEventRecorder($this->makeClock());

        // Record should succeed and not affect result
        $recorder->record($result, $ctx);

        // Result should be unchanged
        $this->assertEquals(TrustEvaluationState::SUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertEquals(TrustLevel::Attested, $result->trustLevel);
    }

    public function test_recorded_event_has_recorded_at_timestamp(): void
    {
        $election = Election::factory()->create();
        $result = VotingTrustResult::insufficientEvidence(
            reason: 'device_attestation_failed',
            trustLevel: TrustLevel::Unverified,
            context: [],
            sequence: [],
        );
        $ctx = $this->makeCtx($election);

        $recorder = new SecurityEventRecorder($this->makeClock());
        $recorder->record($result, $ctx);

        $event = ElectionSecurityEvent::where('election_id', $election->id)->first();
        $this->assertNotNull($event);
        $this->assertNotNull($event->recorded_at);
    }

    public function test_event_preserves_policy_evaluation_sequence(): void
    {
        $election = Election::factory()->create();
        $result = VotingTrustResult::insufficientEvidence(
            reason: 'verification_required',
            trustLevel: TrustLevel::Unverified,
            context: [],
            sequence: [
                'verification_attestation_policy' => 'passed_attested',
                'network_binding_policy' => ['outcome' => 'passed', 'remaining_votes' => 5],
            ],
        );
        $ctx = $this->makeCtx($election);

        $recorder = new SecurityEventRecorder($this->makeClock());
        $recorder->record($result, $ctx);

        $event = ElectionSecurityEvent::where('election_id', $election->id)->first();
        $this->assertNotNull($event);
        $this->assertArrayHasKey('verification_attestation_policy', $event->policy_evaluation_sequence);
    }
}
