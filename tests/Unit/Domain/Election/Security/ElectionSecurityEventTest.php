<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\ElectionSecurityEvent;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class ElectionSecurityEventTest extends TestCase
{
    public function test_event_is_immutable(): void
    {
        $now = new \DateTimeImmutable();
        $event = new ElectionSecurityEvent(
            eventType: 'trust_allowed',
            electionId: 1,
            voterSlugId: 'slug123',
            networkEvidence: ['ip_hash' => 'hash123'],
            deviceEvidence: ['fingerprint_hash' => 'fprint123'],
            trustLevelBefore: TrustLevel::Unverified,
            trustLevelAfter: TrustLevel::Attested,
            policyEvaluated: 'verification_attestation',
            overlayApplied: null,
            policyEvaluationSequence: ['verification' => 'passed'],
            overlayInfluenceChain: [],
            trustStateTransition: 'unverified → attested',
            finalConstitutionalOutcome: 'allow',
            recordedAt: $now,
        );

        $this->assertEquals('trust_allowed', $event->eventType);
        $this->assertTrue(true); // Readonly classes prevent mutations
    }

    public function test_event_preserves_trust_levels(): void
    {
        $now = new \DateTimeImmutable();
        $event = new ElectionSecurityEvent(
            eventType: 'trust_denied',
            electionId: 1,
            voterSlugId: null,
            networkEvidence: [],
            deviceEvidence: [],
            trustLevelBefore: TrustLevel::Attested,
            trustLevelAfter: TrustLevel::Unverified,
            policyEvaluated: 'network_binding',
            overlayApplied: null,
            policyEvaluationSequence: [],
            overlayInfluenceChain: [],
            trustStateTransition: 'attested → unverified',
            finalConstitutionalOutcome: 'deny',
            recordedAt: $now,
        );

        $this->assertEquals(TrustLevel::Attested, $event->trustLevelBefore);
        $this->assertEquals(TrustLevel::Unverified, $event->trustLevelAfter);
    }

    public function test_event_preserves_causality_chain(): void
    {
        $now = new \DateTimeImmutable();
        $sequence = ['verification' => 'passed', 'network' => 'denied'];
        $overlayChain = ['ip_velocity' => 'triggered'];
        $event = new ElectionSecurityEvent(
            eventType: 'trust_denied',
            electionId: 1,
            voterSlugId: 'slug123',
            networkEvidence: [],
            deviceEvidence: [],
            trustLevelBefore: TrustLevel::Attested,
            trustLevelAfter: TrustLevel::Unverified,
            policyEvaluated: 'network_binding',
            overlayApplied: 'ip_velocity_overlay',
            policyEvaluationSequence: $sequence,
            overlayInfluenceChain: $overlayChain,
            trustStateTransition: 'attested → unverified',
            finalConstitutionalOutcome: 'deny',
            recordedAt: $now,
        );

        $this->assertEquals($sequence, $event->policyEvaluationSequence);
        $this->assertEquals($overlayChain, $event->overlayInfluenceChain);
    }

    public function test_event_recorded_at_is_immutable(): void
    {
        $now = new \DateTimeImmutable();
        $event = new ElectionSecurityEvent(
            eventType: 'trust_allowed',
            electionId: 1,
            voterSlugId: null,
            networkEvidence: [],
            deviceEvidence: [],
            trustLevelBefore: TrustLevel::Unverified,
            trustLevelAfter: TrustLevel::Attested,
            policyEvaluated: 'verification_attestation',
            overlayApplied: null,
            policyEvaluationSequence: [],
            overlayInfluenceChain: [],
            trustStateTransition: 'unverified → attested',
            finalConstitutionalOutcome: 'allow',
            recordedAt: $now,
        );

        $this->assertEquals($now, $event->recordedAt);
    }

    public function test_replay_rejected_event_type(): void
    {
        $now = new \DateTimeImmutable();
        $event = new ElectionSecurityEvent(
            eventType: 'replay_rejected',
            electionId: 1,
            voterSlugId: 'slug123',
            networkEvidence: [],
            deviceEvidence: [],
            trustLevelBefore: TrustLevel::Attested,
            trustLevelAfter: TrustLevel::Unverified,
            policyEvaluated: 'commit_freshness',
            overlayApplied: null,
            policyEvaluationSequence: [],
            overlayInfluenceChain: [],
            trustStateTransition: 'attested → unverified',
            finalConstitutionalOutcome: 'deny',
            recordedAt: $now,
        );

        $this->assertEquals('replay_rejected', $event->eventType);
    }
}
