<?php

namespace Tests\Feature\Voting;

use App\Contexts\Elections\Application\DTO\SecurityAssessment;
use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Models\Code;
use App\Models\ElectionMembership;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-247 — RED: IP Binding at the code step (forEntry) and in the Security Verdict Authority.
 *
 * Plan: docs/plans/20261006-2125-pbdigit-247-ip-binding-plan.md. PO decisions 2026-10-06:
 *   B1 captured AND matching, fail closed · B3 exact address (IPv4 / IPv6, canonicalised) ·
 *   Q1 the whitelist does NOT bypass binding · Q2 a specific voter message, never revealing the captured IP.
 * Binding is evaluated only by the Security Verdict Authority (Q3-D2); these tests use the REAL authority.
 * Binding off ⇒ the authority's outcome is unchanged (the adapter oracle stays as it is).
 *
 *   B-E1 captured + same IP → allowed, code issued
 *   B-E2 captured + different IP → refused, no code, nothing sent, specific message without the captured IP
 *   B-E3 nothing captured → refused (fail closed)
 *   B-E4 binding off + different IP → allowed (unchanged)
 *   B-E5 IPv6 written two ways → allowed
 *   B-E6 a capture in another election does not satisfy this election
 *   B-E7 after re-capture: allowed from the new IP, refused from the old one
 *   B-A1 authority: mismatch → Denied; binding off → Allowed
 *   B-A2 the whitelist does not bypass binding (Q1)
 *   B-A3 the assessment envelope carries neither the request IP nor the captured IP
 * Synthetic data only.
 */
class IpBindingEntryTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;
    use IpBindingFixtures;

    private const VOTING_IP = '10.66.0.1';
    private const OTHER_IP  = '10.77.0.9';

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();   // real, voting active; Notification::fake()
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── fixtures ────────────────────────────────────────────────────────────

    private function member(): User
    {
        $voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        ElectionMembership::create([
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'role' => 'voter', 'status' => 'active',
        ]);

        return $voter;
    }

    private function slugFor(User $voter): VoterSlug
    {
        return VoterSlug::factory()->create([
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'is_active' => true, 'expires_at' => now()->addHours(2),
        ]);
    }

    private function enterFrom(User $voter, VoterSlug $slug, string $ip)
    {
        return $this->actingAs($voter)
            ->withServerVariables(['REMOTE_ADDR' => $ip])
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $slug->slug]));
    }

    private function codes(User $voter): int
    {
        return Code::withoutGlobalScopes()->where('user_id', $voter->id)->where('election_id', $this->election->id)->count();
    }

    private function assertRefusedForBinding($response, User $voter, string $capturedIp): void
    {
        $this->assertNotSame(500, $response->status(), 'never a server error');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', self::BINDING_MESSAGE);
        $this->assertStringNotContainsString($capturedIp, (string) session('error'), 'the captured IP is never revealed');
        $this->assertSame(0, $this->codes($voter), 'no code is created');
        Notification::assertNothingSent();
    }

    private function assess(User $voter, string $ip): SecurityAssessment
    {
        return app(VotingSecurityAssessmentQuery::class)->assess(new SecurityAttemptContext(
            userId: $voter->id, electionId: $this->election->id, clientIp: $ip,
            deviceFingerprint: null, sessionId: 'pbdigit-247',
        ));
    }

    // ── code step (forEntry) ────────────────────────────────────────────────

    public function test_b_e1_a_captured_voter_on_the_captured_ip_enters_and_gets_a_code(): void
    {
        $this->bindingOn();
        $voter = $this->member();
        $this->capturedIp($voter, self::VOTING_IP);

        $this->enterFrom($voter, $this->slugFor($voter), self::VOTING_IP)->assertOk();

        $this->assertSame(1, $this->codes($voter));
    }

    public function test_b_e2_a_captured_voter_on_a_different_ip_is_refused_with_the_binding_message(): void
    {
        $this->bindingOn();
        $voter = $this->member();
        $this->capturedIp($voter, self::OTHER_IP);

        $response = $this->enterFrom($voter, $this->slugFor($voter), self::VOTING_IP);

        $this->assertRefusedForBinding($response, $voter, self::OTHER_IP);
    }

    public function test_b_e3_a_voter_without_a_captured_ip_is_refused_fail_closed(): void
    {
        $this->bindingOn();
        $voter = $this->member();

        $response = $this->enterFrom($voter, $this->slugFor($voter), self::VOTING_IP);

        $this->assertRefusedForBinding($response, $voter, self::VOTING_IP);
    }

    public function test_b_e4_with_binding_off_a_different_ip_is_still_allowed(): void
    {
        $this->bindingOn(false);
        $voter = $this->member();
        $this->capturedIp($voter, self::OTHER_IP);

        $this->enterFrom($voter, $this->slugFor($voter), self::VOTING_IP)->assertOk();

        $this->assertSame(1, $this->codes($voter), 'binding off: unchanged behaviour');
    }

    public function test_b_e5_an_ipv6_address_written_two_ways_is_the_same_address(): void
    {
        $this->bindingOn();
        $voter = $this->member();
        $this->capturedIp($voter, '2001:db8::1');

        $this->enterFrom($voter, $this->slugFor($voter), '2001:0db8:0000:0000:0000:0000:0000:0001')->assertOk();

        $this->assertSame(1, $this->codes($voter), 'exact address after canonicalisation');
    }

    public function test_b_e6_a_capture_in_another_election_does_not_satisfy_this_election(): void
    {
        $this->bindingOn();
        $voter = $this->member();
        $other = ElectionScenarioFactory::setupAdministration($this->org);
        $this->capturedIp($voter, self::VOTING_IP, $other->id);

        $response = $this->enterFrom($voter, $this->slugFor($voter), self::VOTING_IP);

        $this->assertRefusedForBinding($response, $voter, self::VOTING_IP);
    }

    public function test_b_e7_after_a_recapture_the_new_ip_is_allowed_and_the_old_ip_refused(): void
    {
        $this->bindingOn();
        $voter = $this->member();
        $this->capturedIp($voter, self::OTHER_IP);
        $this->capturedIp($voter, self::VOTING_IP);   // officer re-capture replaces the row (PBDIGIT-222 I6)

        $this->assertSame(SecurityVerdict::IpBindingDenied, $this->assess($voter, self::OTHER_IP)->verdict, 'the old IP no longer binds');
        $this->enterFrom($voter, $this->slugFor($voter), self::VOTING_IP)->assertOk();
        $this->assertSame(1, $this->codes($voter));
    }

    // ── Security Verdict Authority ──────────────────────────────────────────

    public function test_b_a1_the_authority_denies_a_mismatch_and_is_unchanged_with_binding_off(): void
    {
        $voter = $this->member();
        $this->capturedIp($voter, self::OTHER_IP);

        $this->bindingOn();
        $this->assertSame(SecurityVerdict::IpBindingDenied, $this->assess($voter, self::VOTING_IP)->verdict,
            'a denial, reported as the IP-Binding reason (E2-D4 narrow supersession, PO 2026-10-06)');
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, self::OTHER_IP)->verdict);

        $this->bindingOn(false);
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, self::VOTING_IP)->verdict, 'binding off: unchanged');
    }

    public function test_b_a2_the_whitelist_does_not_bypass_binding(): void
    {
        $this->bindingOn();
        DB::table('elections')->where('id', $this->election->id)->update(['ip_whitelist' => json_encode(['10.66.0.0/16'])]);
        $voter = $this->member();
        $this->capturedIp($voter, self::OTHER_IP);

        $this->assertSame(SecurityVerdict::IpBindingDenied, $this->assess($voter, self::VOTING_IP)->verdict,
            'Q1: a whitelisted network still has to be the voter\'s captured IP');
    }

    public function test_b_a3_the_assessment_envelope_carries_neither_the_request_ip_nor_the_captured_ip(): void
    {
        $this->bindingOn();
        $voter = $this->member();
        $this->capturedIp($voter, self::OTHER_IP);

        $envelope = serialize($this->assess($voter, self::VOTING_IP)->envelope);

        $this->assertStringNotContainsString(self::VOTING_IP, $envelope);
        $this->assertStringNotContainsString(self::OTHER_IP, $envelope);
    }
}
