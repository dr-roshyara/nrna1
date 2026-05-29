<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\OverlayAggregator;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use PHPUnit\Framework\TestCase;

class OverlayAggregatorConstitutionalTest extends TestCase
{
    private function makeCtx(): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: null,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'hash', registeredIpHash: 'hash', whitelist: null,
                maxVotesPerIp: 6, votesFromThisIp: 1, restrictionEnabled: true, bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(
                fingerprintHash: 'fp', registeredFingerprintHash: 'fp',
                matchType: FingerprintMatchType::ExactMatch, captureMethod: 'canvas', volatility: 'stable',
            ),
            attestation: new VerificationAttestationRecord(
                required: false, attested: false, registrarId: null, attestationTimestamp: null,
                protocol: 'none', networkEvidenceHash: null, deviceEvidenceHash: null,
                revoked: false, validityScope: TrustValidityScope::ElectionScoped,
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                sessionId: 's1', ipHashAtStart: 'hash', ipHashCurrent: 'hash',
                deviceChanged: false, continuityState: 'continuous',
            ),
        );
    }

    public function test_aggregate_returns_constitutional_observation_context(): void
    {
        // The coordinator's aggregate() method must return ConstitutionalObservationContext
        // (not OverlayObservationSummary), preserving all evidence without ranking
        $coordinator = new OverlayAggregator([]);
        $ctx = $this->makeCtx();

        $result = $coordinator->aggregate($ctx);

        self::assertInstanceOf(ConstitutionalObservationContext::class, $result);
    }

    public function test_aggregate_does_not_rank_or_weight_signals(): void
    {
        // The returned context must NOT have ranking methods
        // No highestConcern, lowestWeight, or similar methods allowed
        $coordinator = new OverlayAggregator([]);
        $ctx = $this->makeCtx();

        $result = $coordinator->aggregate($ctx);

        // Verify context is passive storage, not ranking engine
        self::assertTrue(method_exists($result, 'count'));
        self::assertTrue(method_exists($result, 'all'));
        self::assertTrue(method_exists($result, 'isEmpty'));
        self::assertFalse(method_exists($result, 'highestConcern'));
        self::assertFalse(method_exists($result, 'lowestWeight'));
        self::assertFalse(method_exists($result, 'strongestSignal'));
    }

    public function test_empty_overlay_list_returns_empty_context(): void
    {
        $coordinator = new OverlayAggregator([]);
        $ctx = $this->makeCtx();

        $result = $coordinator->aggregate($ctx);

        self::assertInstanceOf(ConstitutionalObservationContext::class, $result);
        self::assertTrue($result->isEmpty());
        self::assertSame(0, $result->count());
    }

    public function test_context_is_passive_collection_not_ranking_engine(): void
    {
        // Article 4 Refinement: ConstitutionalObservationContext is purely a passive collection
        // It has NO filtering, grouping, categorization, or reasoning methods
        $coordinator = new OverlayAggregator([]);
        $ctx = $this->makeCtx();

        $result = $coordinator->aggregate($ctx);

        // Only these methods are allowed
        $allowedMethods = ['count', 'all', 'isEmpty', 'empty', '__construct'];
        $reflection = new \ReflectionClass($result);
        $allMethods = array_map(fn($m) => $m->name, $reflection->getMethods());

        foreach ($allMethods as $method) {
            // Skip magic/internal methods
            if (str_starts_with($method, '__')) {
                continue;
            }
            self::assertContains($method, $allowedMethods, "Unexpected method: $method (violates R4 passive container doctrine)");
        }
    }
}
