<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Contexts\Elections\Infrastructure\Security\TrustLegitimacySecurityVerdict;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * PBDIGIT-286 P6, D6 (decision record §3.2; ADR-C §3.2): System B counts completed votes with System A's grouping,
 * through IpVoteLimit::votesFrom: an IPv4 address with both of its spellings, an IPv6 address with its /64, only this
 * election, without the tenant scope. With D7 (`>=`) the next voter is refused once L completed votes exist.
 *
 * Observed through the decision (SecurityVerdict), the behaviour that matters. A first draft read the count from
 * the security event; that failed for a test reason (allowed events are sampled and use their own connection).
 *
 * Isolated database only (G-ISO for P6):
 * . ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/SystemBNetworkCountTest.php
 */
final class SystemBNetworkCountTest extends IsolatedDatabaseTestCase
{
    private Organisation $org;

    private Election $election;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();

        TenantContext::clear();
        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd286-p6-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = $this->election();
        $this->officer = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null]);
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    private function election(): Election
    {
        return Election::factory()->forOrganisation($this->org)->real()->create([
            'name' => 'PBDIGIT-286 P6 '.uniqid(), 'slug' => 'pbd286-p6-election-'.uniqid(),
            'network_binding_strategy' => 'ip_count', 'max_votes_per_ip' => 6,
        ]);
    }

    private function voter(?Election $election = null): User
    {
        $election ??= $this->election;
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null,
            'organisation_id' => $this->org->id]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $election->id, 'role' => 'voter', 'status' => 'active']);

        return $user;
    }

    /** A code of another voter, from $ip; completed unless $voted is false. */
    private function codeFrom(string $ip, ?Election $election = null, bool $voted = true, ?User $owner = null): void
    {
        $election ??= $this->election;
        $owner ??= $this->voter($election);
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
            'election_id' => $election->id, 'user_id' => $owner->id, 'code1' => (string) random_int(100000, 999999),
            'has_voted' => $voted, 'client_ip' => $ip, 'created_at' => now(), 'updated_at' => now()]);
    }

    private function attested(User $voter): void
    {
        DB::table('voter_verifications')->insert(['id' => (string) Str::uuid(), 'election_id' => $this->election->id,
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'verified_by' => $this->officer->id,
            'verified_at' => now(), 'status' => 'active', 'method' => 'officer_attestation',
            'created_at' => now(), 'updated_at' => now()]);
    }

    private function assess(User $voter, string $ip): SecurityVerdict
    {
        return $this->app->make(TrustLegitimacySecurityVerdict::class)->assess(new SecurityAttemptContext(
            userId: (string) $voter->id, electionId: (string) $this->election->id, clientIp: $ip,
            deviceFingerprint: null, sessionId: 'pbd286-p6-'.uniqid(),
        ))->verdict;
    }

    /**
     * N = 2, so an Unverified voter's L is max(1, ⌊2/2⌋) = 1: ONE vote that counts refuses the voter, none admits them.
     * The decision is the observation; the security event is not, because allowed events are sampled (~10%) and
     * written on their own connection (SecurityEventRecorder).
     */
    private function limitOfOneVote(): void
    {
        DB::table('elections')->where('id', $this->election->id)->update(['max_votes_per_ip' => 2]);
        $this->election->refresh();
    }

    public function test_ipv6_votes_count_together_within_the_64(): void
    {
        $this->limitOfOneVote();
        $voter = $this->voter();

        $this->codeFrom('2001:db8:1:3::10'); // another /64
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, '2001:db8:1:2::99'), 'another /64 does not count');

        $this->codeFrom('2001:db8:1:2:ffff:ffff:ffff:1'); // the same /64, a different address
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($voter, '2001:db8:1:2::99'), 'the same /64 counts');
    }

    public function test_ipv4_counts_per_address_with_both_spellings(): void
    {
        $this->limitOfOneVote();
        $voter = $this->voter();

        $this->codeFrom('203.0.113.6'); // IPv4 has no network grouping: another address
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, '203.0.113.5'), 'another IPv4 address does not count');

        $this->codeFrom('::ffff:203.0.113.5'); // the same address, IPv4-mapped spelling
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($voter, '203.0.113.5'), 'the mapped spelling counts');
    }

    public function test_the_count_does_not_depend_on_the_tenant_context(): void
    {
        // ADR-C §3.2: no ambient tenant context, or a different one, must not under-count.
        $this->limitOfOneVote();
        $this->codeFrom('203.0.113.5');
        $voter = $this->voter();

        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($voter, '203.0.113.5'), 'no tenant context: counted');

        TenantContext::set((string) Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd286-p6-other-'.uniqid()])->id);
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($voter, '203.0.113.5'), 'another tenant context: counted');
    }

    public function test_an_unreadable_ip_counts_only_by_exact_value(): void
    {
        // IpVoteLimit::votesFrom: a value that isn't an IP address is matched exactly (no grouping).
        $this->limitOfOneVote();
        $voter = $this->voter();

        $this->codeFrom('other-unreadable');
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, 'not-an-ip'), 'another unreadable value does not count');

        $this->codeFrom('not-an-ip');
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($voter, 'not-an-ip'), 'the same unreadable value counts');
    }

    public function test_votes_of_another_election_and_unfinished_codes_do_not_count(): void
    {
        $this->limitOfOneVote();
        $other = $this->election();
        $this->codeFrom('203.0.113.5', $other);
        $voter = $this->voter();
        $this->codeFrom('203.0.113.5', voted: false, owner: $voter); // the current voter's own, not yet voted

        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, '203.0.113.5'));
    }

    public function test_the_limit_applies_to_the_grouped_count_per_trust_level(): void
    {
        // N = 6: L = 3 Unverified, 6 Attested; refused once L completed votes exist in the /64 (D6 + D7).
        $unverified = $this->voter();
        $verified = $this->voter();
        $this->attested($verified);

        $this->assertSame(SecurityVerdict::Allowed, $this->assess($unverified, '2001:db8:1:2::99'), 'zero votes');

        foreach (['2001:db8:1:2::1', '2001:db8:1:2::2'] as $ip) {
            $this->codeFrom($ip);
        }
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($unverified, '2001:db8:1:2::99'), 'Unverified, one below L = 3');

        $this->codeFrom('2001:db8:1:2::3');
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($unverified, '2001:db8:1:2::99'), 'Unverified, at L = 3');
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($verified, '2001:db8:1:2::99'), 'Attested, 3 votes');

        foreach (['2001:db8:1:2::4', '2001:db8:1:2::5'] as $ip) {
            $this->codeFrom($ip);
        }
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($verified, '2001:db8:1:2::99'), 'Attested, one below L = 6');

        $this->codeFrom('2001:db8:1:2::6');
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($verified, '2001:db8:1:2::99'), 'Attested, at L = 6');
        $this->codeFrom('2001:db8:1:2::7');
        $this->assertNotSame(SecurityVerdict::Allowed, $this->assess($verified, '2001:db8:1:2::99'), 'Attested, one above L');
    }
}
