<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class NetworkTrustEvidenceTest extends TestCase
{
    public function test_not_whitelisted_when_no_whitelist(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: null,
            maxVotesPerIp: 5,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertFalse($evidence->isWhitelisted());
    }

    public function test_not_whitelisted_when_empty_whitelist(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: [],
            maxVotesPerIp: 5,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertFalse($evidence->isWhitelisted());
    }

    public function test_whitelisted_when_current_ip_in_whitelist(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash_office',
            registeredIpHash: 'hash123',
            whitelist: ['hash_office', 'hash_home'],
            maxVotesPerIp: 5,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'whitelist_only',
        );

        $this->assertTrue($evidence->isWhitelisted());
    }

    public function test_exceeds_limit_unverified_trust_level(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: null,
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 6,  // max for unverified is 5 (10/2)
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($evidence->exceedsLimit(TrustLevel::Unverified));
    }

    public function test_not_exceeds_limit_unverified_trust_level(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: null,
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 5,  // exactly at limit for unverified
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertFalse($evidence->exceedsLimit(TrustLevel::Unverified));
    }

    public function test_exceeds_limit_attested_trust_level(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 11,  // max for attested is 10
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($evidence->exceedsLimit(TrustLevel::Attested));
    }

    public function test_exceeds_limit_continuity_verified_trust_level(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 16,  // max for continuity_verified is 15 (10 * 1.5)
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($evidence->exceedsLimit(TrustLevel::ContinuityVerified));
    }

    public function test_exceeds_limit_registrar_attested_trust_level(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 21,  // max for registrar_attested is 20 (10 * 2)
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($evidence->exceedsLimit(TrustLevel::RegistrarAttested));
    }

    public function test_satisfies_network_attestation_when_registered_ip_matches(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: null,
            maxVotesPerIp: 5,
            votesFromThisIp: 2,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($evidence->satisfiesNetworkAttestation());
    }

    public function test_satisfies_network_attestation_when_restriction_disabled(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash_other',
            whitelist: null,
            maxVotesPerIp: 5,
            votesFromThisIp: 2,
            restrictionEnabled: false,
            bindingStrategy: 'none',
        );

        $this->assertTrue($evidence->satisfiesNetworkAttestation());
    }

    public function test_remaining_votes_calculates_correctly(): void
    {
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: 'hash123',
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 3,
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        // Attested: max is 10, used 3, remaining is 7
        $this->assertEquals(7, $evidence->remainingVotes(TrustLevel::Attested));

        // Unverified: max is 5, used 3, remaining is 2
        $this->assertEquals(2, $evidence->remainingVotes(TrustLevel::Unverified));
    }
}
