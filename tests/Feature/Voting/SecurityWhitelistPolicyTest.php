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
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * PBDIGIT-246 · 2B-1 — RED: the election whitelist inside the Security Verdict Authority.
 *
 * Decision Q3-D5 (PO 2026-10-05): raw CIDR. ip_whitelist holds raw IPv4 CIDR ranges / exact IPs; the
 * authority matches the request's raw IP inside the authority and only the fact "whitelisted" enters
 * the evidence — the raw IP never appears in the envelope.
 *
 * Contract: whitelisted → the density rule does not deny · not whitelisted → density unchanged ·
 * a non-empty list never throws · empty list unchanged · malformed entry matches nothing (PO-confirmed).
 * Scope: IPv4 only (PO 2026-10-05); IPv6 ranges are outside PBDIGIT-246.
 *
 * Plan: docs/plans/20261005-2333-pbdigit-246-whitelist-red-plan.md
 * Fixtures: ip_count, max_votes_per_ip 6 → unverified limit 3; "above the limit" = 4 voted codes.
 * Test-only setup: the whitelist is written with the query builder (PBDIGIT-244 model guard refuses
 * security-policy writes on an election that is already voting). Synthetic data only.
 */
class SecurityWhitelistPolicyTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private const REQUEST_IP = '127.0.0.1';   // the test client's IP
    private const ABOVE_LIMIT = 4;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();
        DB::table('elections')->where('id', $this->election->id)
            ->update(['network_binding_strategy' => 'ip_count', 'max_votes_per_ip' => 6]);
        $this->election = $this->election->fresh();
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function whitelist(?array $entries): void
    {
        DB::table('elections')->where('id', $this->election->id)
            ->update(['ip_whitelist' => $entries === null ? null : json_encode($entries)]);
        $this->election = $this->election->fresh();
    }

    private function votedFrom(string $ip, int $n): void
    {
        foreach (range(1, $n) as $i) {
            Code::factory()->voted()->create([
                'organisation_id' => $this->org->id,
                'election_id'     => $this->election->id,
                'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
                'client_ip'       => $ip,
                'code1'           => 'WL' . substr(md5($ip . $i), 0, 6),
            ]);
        }
    }

    private function member(): User
    {
        $voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        ElectionMembership::create([
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'role' => 'voter', 'status' => 'active',
        ]);

        return $voter;
    }

    private function assess(string $ip): SecurityAssessment
    {
        return app(VotingSecurityAssessmentQuery::class)->assess(new SecurityAttemptContext(
            userId: $this->member()->id, electionId: $this->election->id, clientIp: $ip,
            deviceFingerprint: null, sessionId: 'pbdigit-246',
        ));
    }

    // ── Authority contract ───────────────────────────────────────────────────

    public function test_w1_an_ip_inside_a_whitelisted_cidr_is_not_denied_by_the_density_rule(): void
    {
        $this->whitelist(['10.88.0.0/16']);
        $this->votedFrom('10.88.1.7', self::ABOVE_LIMIT);

        $this->assertSame(SecurityVerdict::Allowed, $this->assess('10.88.1.7')->verdict);
    }

    public function test_w2_an_ip_equal_to_a_whitelisted_exact_entry_is_not_denied_by_the_density_rule(): void
    {
        $this->whitelist(['10.88.1.7']);
        $this->votedFrom('10.88.1.7', self::ABOVE_LIMIT);

        $this->assertSame(SecurityVerdict::Allowed, $this->assess('10.88.1.7')->verdict);
    }

    public function test_w3_an_ip_outside_a_non_empty_whitelist_is_still_denied_above_the_limit(): void
    {
        $this->whitelist(['10.88.0.0/16']);
        $this->votedFrom('10.99.0.5', self::ABOVE_LIMIT);

        $this->assertSame(SecurityVerdict::Denied, $this->assess('10.99.0.5')->verdict);
    }

    public function test_w4_an_ip_outside_a_non_empty_whitelist_is_allowed_below_the_limit(): void
    {
        $this->whitelist(['10.88.0.0/16']);
        $this->votedFrom('10.99.0.6', 1);

        $this->assertSame(SecurityVerdict::Allowed, $this->assess('10.99.0.6')->verdict);
    }

    public function test_w5_a_malformed_entry_matches_nothing_and_does_not_throw(): void
    {
        // Contract point confirmed by the PO (2026-10-05): a malformed entry is not a wildcard and not an error.
        $this->whitelist(['not-an-ip']);
        $this->votedFrom('10.99.0.7', self::ABOVE_LIMIT);

        $this->assertSame(SecurityVerdict::Denied, $this->assess('10.99.0.7')->verdict);
    }

    // ── Privacy (Q3-D5: the raw IP stays inside the authority) ───────────────

    public function test_w6_a_whitelisted_assessment_carries_no_raw_request_ip_in_its_envelope(): void
    {
        $this->whitelist(['10.88.0.0/16']);

        $envelope = serialize($this->assess('10.88.1.9')->envelope);

        $this->assertStringNotContainsString('10.88.1.9', $envelope, 'only the fact "whitelisted" enters the evidence');
    }

    public function test_w6b_control_without_a_whitelist_the_envelope_carries_no_raw_request_ip(): void
    {
        $this->whitelist(null);

        $envelope = serialize($this->assess('10.88.1.10')->envelope);

        $this->assertStringNotContainsString('10.88.1.10', $envelope);
    }

    // ── End to end ───────────────────────────────────────────────────────────

    public function test_w7_a_whitelisted_network_above_the_limit_can_cast(): void
    {
        $this->whitelist([self::REQUEST_IP . '/32']);
        $this->votedFrom(self::REQUEST_IP, self::ABOVE_LIMIT);
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $code->fresh()->has_voted, 'the whitelisted network is exempt from the density rule');
    }

    public function test_w8_a_whitelisted_network_above_the_limit_can_enter_the_code_step(): void
    {
        $this->whitelist([self::REQUEST_IP . '/32']);
        $this->votedFrom(self::REQUEST_IP, self::ABOVE_LIMIT);
        $voter = $this->member();
        $slug = VoterSlug::factory()->create([
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'is_active' => true, 'expires_at' => now()->addHours(2),
        ]);

        $response = $this->actingAs($voter)->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $response->assertOk();
        $this->assertSame(1, Code::withoutGlobalScopes()->where('user_id', $voter->id)->where('election_id', $this->election->id)->count(),
            'forEntry() allows the whitelisted network and a code is issued');
    }

    // ── Control: empty whitelist unchanged ───────────────────────────────────

    public function test_w9_control_without_a_whitelist_the_density_rule_denies_above_the_limit(): void
    {
        $this->whitelist(null);
        $this->votedFrom('10.99.0.8', self::ABOVE_LIMIT);

        $this->assertSame(SecurityVerdict::Denied, $this->assess('10.99.0.8')->verdict);
    }
}
