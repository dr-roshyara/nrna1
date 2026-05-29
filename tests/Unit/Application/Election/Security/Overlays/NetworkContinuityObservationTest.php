<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Application\Election\Security\Overlays\NetworkContinuityObservation;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use Tests\TestCase;

/**
 * GROUP 1 — Network Continuity Evidence Tests
 *
 * Focus: Participation network continuity semantics
 * NOT: IP validation or security scoring
 *
 * Constitutional relationship:
 * Participation initiated from network X should continue from same network.
 * Network change is evidence that may warrant enhanced scrutiny,
 * but is itself purely observational (no procedural consequence).
 */
class NetworkContinuityObservationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeClock(): \App\Domain\Shared\Clock\ClockInterface
    {
        return new class implements \App\Domain\Shared\Clock\ClockInterface {
            public function now(): \DateTimeImmutable
            {
                return new \DateTimeImmutable();
            }
        };
    }

    private function makeOverlay(): NetworkContinuityObservation
    {
        return new NetworkContinuityObservation();
    }

    private function makeContext(
        ?string $registeredNetworkHash = null,
        ?string $currentNetworkHash = null,
        string $bindingStrategy = 'ip_count',
        ?Election $election = null
    ): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: $currentNetworkHash ?? 'net_current_123',
                registeredIpHash: $registeredNetworkHash,
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 0,
                restrictionEnabled: $bindingStrategy !== 'none',
                bindingStrategy: $bindingStrategy,
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                's1',
                $registeredNetworkHash ?? 'net_current_123',
                $currentNetworkHash ?? 'net_current_123',
                false,
                'continuous'
            ),
        );
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 1.1-1.3: Network Continuity Semantic Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_network_continuity_matched_emits_context_stable(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(
            registeredNetworkHash: 'net_abc123',
            currentNetworkHash: 'net_abc123',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('network_continuity', $signal->overlayIdentifier);
    }

    public function test_network_continuity_mismatched_emits_evidence_inconsistent(): void
    {
        // Semantic pressure point: This will be split into NETWORK_CONTINUITY_INSUFFICIENT in D.R.4+
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(
            registeredNetworkHash: 'net_abc123',
            currentNetworkHash: 'net_def456',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
        $this->assertEquals('network_continuity', $signal->overlayIdentifier);
    }

    public function test_network_continuity_no_constraint_emits_context_stable(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(
            registeredNetworkHash: null,
            currentNetworkHash: 'net_current_123',
            bindingStrategy: 'ip_count',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 1.4-1.5: Binding Strategy Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_network_continuity_respects_binding_strategy_none(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['network_binding_strategy' => 'none']);
        $ctx = $this->makeContext(
            registeredNetworkHash: 'net_abc123',
            currentNetworkHash: 'net_different_456',
            bindingStrategy: 'none',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('network_continuity', $signal->overlayIdentifier);
    }

    public function test_network_continuity_respects_binding_strategy_strict(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['network_binding_strategy' => 'ip_strict']);
        $ctx = $this->makeContext(
            registeredNetworkHash: 'net_abc123',
            currentNetworkHash: 'net_different_456',
            bindingStrategy: 'ip_strict',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 1.6-1.7: Evidence Context Preservation
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_network_continuity_preserves_evidence_context(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(
            registeredNetworkHash: 'net_abc123',
            currentNetworkHash: 'net_def456',
            bindingStrategy: 'ip_count',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertArrayHasKey('registeredNetworkHash', $signal->evidenceContext);
        $this->assertArrayHasKey('currentNetworkHash', $signal->evidenceContext);
        $this->assertArrayHasKey('bindingStrategy', $signal->evidenceContext);
        $this->assertEquals('net_abc123', $signal->evidenceContext['registeredNetworkHash']);
        $this->assertEquals('net_def456', $signal->evidenceContext['currentNetworkHash']);
    }

    public function test_network_continuity_constitutional_basis_documented(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(
            registeredNetworkHash: 'net_abc123',
            currentNetworkHash: 'net_abc123',
            election: $election
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertNotEmpty($signal->constitutionalBasis);
        $this->assertStringContainsString('continuity', strtolower($signal->constitutionalBasis));
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 1.8: Identifier Registry
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_network_continuity_identifier_registered(): void
    {
        $overlay = $this->makeOverlay();

        $this->assertEquals('network_continuity', $overlay->identifier());
    }
}
