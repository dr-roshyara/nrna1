<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\Attributes\DataProvider;
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

    public function test_unverified_at_exactly_the_limit_is_refused(): void
    {
        // PBDIGIT-286 D7 (`>=`): votesFromThisIp counts completed votes, not the current voter's. Once L completed
        // votes exist, the next voter is refused (until P6 this asserted false and a network completed L + 1).
        $evidence = new NetworkTrustEvidence(
            currentIpHash: 'hash123',
            registeredIpHash: null,
            whitelist: null,
            maxVotesPerIp: 10,
            votesFromThisIp: 5,  // exactly at limit for unverified
            restrictionEnabled: true,
            bindingStrategy: 'ip_count',
        );

        $this->assertTrue($evidence->exceedsLimit(TrustLevel::Unverified));
    }

    /**
     * PBDIGIT-286 AC-11 / D7 / D4: one below, at and one above the effective limit L, for N = 1, 6 and 50 and both
     * reachable trust levels. L = N (Attested), max(1, ⌊N/2⌋) (Unverified).
     *
     * @return iterable<string, array{int, TrustLevel, int}>
     */
    public static function boundaries(): iterable
    {
        foreach ([1, 6, 50] as $n) {
            yield "N=$n Attested" => [$n, TrustLevel::Attested, $n];
            yield "N=$n Unverified" => [$n, TrustLevel::Unverified, max(1, intdiv($n, 2))];
        }
    }

    #[DataProvider('boundaries')]
    public function test_the_next_voter_is_refused_once_l_completed_votes_exist(int $n, TrustLevel $level, int $limit): void
    {
        $evidence = fn (int $votes) => new NetworkTrustEvidence(
            currentIpHash: 'hash123', registeredIpHash: null, whitelist: null,
            maxVotesPerIp: $n, votesFromThisIp: $votes,
            restrictionEnabled: true, bindingStrategy: 'ip_count',
        );

        $this->assertFalse($evidence($limit - 1)->exceedsLimit($level), 'one below L: admitted');
        $this->assertTrue($evidence($limit)->exceedsLimit($level), 'at L: refused');
        $this->assertTrue($evidence($limit + 1)->exceedsLimit($level), 'one above L: refused');
        $this->assertSame(0, $evidence($limit)->remainingVotes($level), 'at L nothing remains, consistent with the refusal');
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
