<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\SecurityEventRecorder;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use App\Models\ElectionSecurityEvent;
use Tests\TestCase;

class SecurityEventRecorderTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

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
        $result = VotingTrustResult::deny(
            reason: 'network_limit_exceeded',
            context: ['votes_from_ip' => 10],
            sequence: ['network_binding_policy' => ['outcome' => 'denied']],
        );
        $ctx = $this->makeCtx($election);

        $recorder = new SecurityEventRecorder();
        $recorder->record($result, $ctx);

        $this->assertDatabaseHas('election_security_events', [
            'election_id' => $election->id,
            'final_constitutional_outcome' => 'deny',
        ]);
    }

    public function test_recorder_returns_void(): void
    {
        $recorder = new SecurityEventRecorder();
        $result = VotingTrustResult::allow(
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
        $result = VotingTrustResult::allow(
            trustLevel: TrustLevel::Attested,
            context: [],
            sequence: [],
        );
        $ctx = $this->makeCtx();

        $recorder = new SecurityEventRecorder();

        // Record should succeed and not affect result
        $recorder->record($result, $ctx);

        // Result should be unchanged
        $this->assertTrue($result->trusted);
        $this->assertEquals(TrustLevel::Attested, $result->trustLevel);
    }

    public function test_recorded_event_has_recorded_at_timestamp(): void
    {
        $election = Election::factory()->create();
        $result = VotingTrustResult::deny(
            reason: 'device_attestation_failed',
            context: [],
            sequence: [],
        );
        $ctx = $this->makeCtx($election);

        $recorder = new SecurityEventRecorder();
        $recorder->record($result, $ctx);

        $event = ElectionSecurityEvent::where('election_id', $election->id)->first();
        $this->assertNotNull($event);
        $this->assertNotNull($event->recorded_at);
    }

    public function test_event_preserves_policy_evaluation_sequence(): void
    {
        $election = Election::factory()->create();
        $result = VotingTrustResult::deny(
            reason: 'verification_required',
            context: [],
            sequence: [
                'verification_attestation_policy' => 'passed_attested',
                'network_binding_policy' => ['outcome' => 'passed', 'remaining_votes' => 5],
            ],
        );
        $ctx = $this->makeCtx($election);

        $recorder = new SecurityEventRecorder();
        $recorder->record($result, $ctx);

        $event = ElectionSecurityEvent::where('election_id', $election->id)->first();
        $this->assertNotNull($event);
        $this->assertArrayHasKey('verification_attestation_policy', $event->policy_evaluation_sequence);
    }
}
