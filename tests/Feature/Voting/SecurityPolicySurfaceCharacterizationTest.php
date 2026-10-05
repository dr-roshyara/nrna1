<?php

namespace Tests\Feature\Voting;

use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Models\Code;
use App\Models\ElectionMembership;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterStepTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * Q3 — Security Policy Surface: CHARACTERIZATION ONLY (current defects and current behaviour).
 *
 * ┌──────────────────────────────────────────────────────────────────────────────────────────────┐
 * │ CURRENT DEFECT / CHARACTERIZATION ONLY. These tests pin what the repository does TODAY.       │
 * │ They do NOT define the desired security contract. Each is REPLACED (not kept green) by the    │
 * │ 2B story that resolves it. A failing test here means the current behaviour has changed.       │
 * └──────────────────────────────────────────────────────────────────────────────────────────────┘
 *
 * Proposal: docs/publicdigit/reviews/2026-10-05-q3-security-policy-surface.md §16 (decisions §0: Q3-D1, D2, D3, D6)
 *
 *   C1  known defect: a configured whitelist currently causes fail-closed refusal          (→ 2B-1)
 *   C2  the settings fields ip_restriction_* do not change the authority's density decision (→ 2B-2)
 *   C3  MAX_USE_IP_ADDRESS at verifyCode() still refuses when the election disables density  (→ 2B-3, Q3-D7)
 *   C4  MAX_USE_IP_ADDRESS unset → step-4 vote_post_check() refuses the voter                (→ 2B-4)
 *   C5  the start-page density check never sees production evidence (step_1_ip unwritten)    (→ 2B-5)
 *   C6  registered-network binding is inert: users.voting_ip does not exist                  (→ 2B-6, PBDIGIT-234)
 *
 * Test-only setup (explained where used): election policy columns are written with the query builder,
 * because the PBDIGIT-244 model guard refuses security-policy writes on an election that is already
 * voting. Synthetic data only. No production change.
 */
class SecurityPolicySurfaceCharacterizationTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private const REQUEST_IP = '127.0.0.1';   // the test client's IP

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();   // also pins app.max_use_clientIP = 7 (Phase 1 fixture)
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    /** Test-only: write election policy columns past the PBDIGIT-244 model guard (election is voting). */
    private function setElectionColumns(array $columns): void
    {
        DB::table('elections')->where('id', $this->election->id)->update($columns);
        $this->election = $this->election->fresh();
    }

    /** $n other voters whose codes have voted from $ip in this election (tenant-scoped, like production). */
    private function votedFrom(string $ip, int $n): void
    {
        foreach (range(1, $n) as $i) {
            Code::factory()->voted()->create([
                'organisation_id' => $this->org->id,
                'election_id'     => $this->election->id,
                'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
                'client_ip'       => $ip,
                'code1'           => 'CHAR' . substr(md5($ip . $i), 0, 4),
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

    private function assess(User $voter, string $ip)
    {
        return app(VotingSecurityAssessmentQuery::class)->assess(new SecurityAttemptContext(
            userId: $voter->id, electionId: $this->election->id, clientIp: $ip, deviceFingerprint: null, sessionId: 'q3-characterization',
        ));
    }

    private function assertNothingConsumed(Code $code): void
    {
        $this->assertSame(0, DB::table('votes')->where('election_id', $this->election->id)->count(), 'no vote is written');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is not consumed');
    }

    // ── C1 — known defect: configured whitelist → fail-closed refusal ────────

    public function test_c1_characterizes_current_defect_a_configured_whitelist_makes_the_authority_throw(): void
    {
        $this->setElectionColumns(['ip_whitelist' => json_encode(['10.77.0.0/16'])]);

        try {
            $this->assess($this->member(), '10.77.0.5');
            $this->fail('CURRENT DEFECT expected: the authority throws on a non-empty whitelist');
        } catch (\TypeError $e) {
            $this->assertStringContainsString('json_decode', $e->getMessage(), 'json_decode() on the array-cast ip_whitelist');
        }
    }

    public function test_c1_characterizes_current_defect_a_configured_whitelist_makes_the_final_cast_refuse_fail_closed(): void
    {
        $this->setElectionColumns(['ip_whitelist' => json_encode(['10.77.0.0/16'])]);
        $this->captureLogs();
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status(), 'fail closed, never HTTP 500');
        $this->assertNothingConsumed($code);
        $failures = array_filter($this->logged, fn (array $e) => $e['message'] === 'Vote submission failed'
            && str_contains((string) ($e['context']['error'] ?? ''), 'json_decode'));
        $this->assertCount(1, $failures, 'the refusal is the whitelist TypeError caught by the fail-closed exit');
    }

    // ── C2 — settings fields have no effect on the authority ────────────────

    public function test_c2_characterizes_current_behaviour_a_strict_settings_limit_does_not_tighten_the_authority(): void
    {
        $ip = '10.55.0.1';
        $this->votedFrom($ip, 2);   // below the authority's default unverified limit (3)
        $this->setElectionColumns(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 1]);

        $this->assertSame(SecurityVerdict::Allowed, $this->assess($this->member(), $ip)->verdict,
            'ip_restriction_* (settings page) is not read by the authority');
    }

    public function test_c2_characterizes_current_behaviour_a_loose_settings_limit_does_not_relax_the_authority(): void
    {
        $ip = '10.55.0.2';
        $this->votedFrom($ip, 4);   // above the authority's default unverified limit (3)
        $this->setElectionColumns(['ip_restriction_enabled' => false, 'ip_restriction_max_per_ip' => 50]);

        $this->assertSame(SecurityVerdict::Denied, $this->assess($this->member(), $ip)->verdict,
            'the authority applies max_votes_per_ip / network_binding_strategy, not ip_restriction_*');
    }

    // ── C3 — the environment overrides the election's policy at verifyCode() ─

    public function test_c3_characterizes_current_defect_verify_code_refuses_by_env_although_the_election_disables_density(): void
    {
        $this->setElectionColumns(['network_binding_strategy' => 'none']);   // the election's policy: no density rule
        $this->votedFrom(self::REQUEST_IP, 7);                                // = app.max_use_clientIP (7, Phase 1 fixture)
        $voter = $this->member();

        // Precondition: the Security Verdict Authority allows (density disabled by the election's policy).
        $this->assertSame(SecurityVerdict::Allowed, $this->assess($voter, self::REQUEST_IP)->verdict);

        $slug = VoterSlug::factory()->create([
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'is_active' => true, 'expires_at' => now()->addHours(2),
        ]);
        $code = Code::factory()->create([
            'organisation_id' => $this->org->id, 'user_id' => $voter->id, 'election_id' => $this->election->id,
            'code_to_open_voting_form' => 'ABCD2345', 'code_to_open_voting_form_sent_at' => now(),
            'is_code_to_open_voting_form_usable' => 1, 'can_vote_now' => 0, 'has_voted' => 0, 'client_ip' => '10.0.0.1',
        ]);

        $response = $this->actingAs($voter)->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $response->assertSessionHasErrors('voting_code');
        $this->assertFalse((bool) $code->fresh()->can_vote_now, 'verifyCode() refused on MAX_USE_IP_ADDRESS (≥ 7), independent of the election policy');
    }

    // ── C4 — MAX_USE_IP_ADDRESS unset → step 4 refuses ──────────────────────

    private function getStep4(?int $maxUseClientIp)
    {
        config(['app.max_use_clientIP' => $maxUseClientIp]);
        [$voter, $slug, $code] = $this->voterReadyToCast();
        foreach ([1, 2, 3] as $step) {
            (new VoterStepTrackingService())->completeStep($slug, $this->election, $step, ['fixture' => true]);
        }

        $response = $this->actingAs($voter)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                $code->session_name       => $this->ballotSessionData(),
            ])
            ->get(route('slug.vote.verify', ['vslug' => $slug->slug]));

        return [$response, $code];
    }

    public function test_c4_characterizes_current_defect_step_4_refuses_the_voter_when_max_use_ip_address_is_unset(): void
    {
        $this->captureLogs();
        [$response, $code] = $this->getStep4(null);   // MAX_USE_IP_ADDRESS unset → config null → 0 >= null is true

        // The IP rule in vote_post_check() fires for a fresh voter …
        $this->assertCount(1, array_filter($this->logged, fn (array $e) => $e['message'] === 'Security: IP rate limit exceeded (election-scoped)'));
        // … and, as observed 2026-10-05, rendering its refusal fails, so verify()'s catch-all answers with a
        // generic system error and sends the voter to the dashboard (characterized as observed, not as intended).
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHasErrors('system');
        $this->assertNothingConsumed($code);
    }

    public function test_c4_contrast_with_the_env_set_step_4_does_not_refuse_on_the_ip_rule(): void
    {
        $this->captureLogs();
        [$response] = $this->getStep4(7);   // same voter state; only the env differs

        $this->assertCount(0, array_filter($this->logged, fn (array $e) => $e['message'] === 'Security: IP rate limit exceeded (election-scoped)'));
        $response->assertSessionDoesntHaveErrors('system');
    }

    // ── C5 — the start-page density check never sees production evidence ────

    private function startAsNewVoter()
    {
        return $this->actingAs($this->member())->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('elections.start', $this->election->slug));
    }

    public function test_c5_characterizes_current_defect_production_never_writes_step_1_ip_so_the_start_check_does_not_fire(): void
    {
        $this->setElectionColumns(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 1]);
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code)->assertSessionHasNoErrors();   // a real production cast from REQUEST_IP
        $this->assertTrue((bool) $code->fresh()->has_voted, 'precondition: one vote cast from this network');

        $this->assertNull($slug->fresh()->step_1_ip, 'the production voting path did not write step_1_ip');

        $this->startAsNewVoter()->assertSessionMissing('error', 'The maximum of 1 vote(s) from your network has been reached.');
        $this->assertSame(0, DB::table('election_audit_logs')->where('action', 'ip_blocked')->count(), 'no ip_blocked audit');
    }

    public function test_c5_contrast_the_start_check_fires_only_with_manufactured_step_1_ip(): void
    {
        $this->setElectionColumns(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 1]);
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code)->assertSessionHasNoErrors();

        // CHARACTERIZATION FIXTURE ONLY — manufactures the evidence production never writes,
        // to prove the check is reached and is silent in C5 only for lack of evidence.
        DB::table('voter_slugs')->where('id', $slug->id)->update(['step_1_ip' => self::REQUEST_IP]);

        $this->startAsNewVoter()->assertSessionHas('error', 'The maximum of 1 vote(s) from your network has been reached.');
    }

    // ── C6 — registered-network binding is inert ─────────────────────────────

    public function test_c6_characterizes_current_behaviour_users_voting_ip_does_not_exist(): void
    {
        $this->assertFalse(Schema::hasColumn('users', 'voting_ip'), 'PBDIGIT-234: no users.voting_ip column');
        $this->assertNull(User::findOrFail($this->member()->id)->voting_ip, 'the authority reads null registered-IP evidence');
    }

    public function test_c6_characterizes_current_behaviour_binding_checks_pass_from_any_network(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        // Cast from a network unrelated to anything stored for this voter.
        $response = $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.40'])->cast($voter, $slug, $code);

        $response->assertSessionHasNoErrors();   // validate.voting.ip middleware and validateVotingIpWithResponse() pass
        $this->assertTrue((bool) $code->fresh()->has_voted, 'the cast succeeds: binding is inert');
    }
}
