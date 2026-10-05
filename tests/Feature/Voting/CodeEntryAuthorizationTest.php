<?php

namespace Tests\Feature\Voting;

use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Models\Code;
use App\Models\Election;
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
 * PBDIGIT-239 · Phase 5 Round 2A · RED — the CodeController ENTRY AUTHORIZATION BOUNDARY (Stage B call sites).
 *
 * Blueprint docs/publicdigit/reviews/2026-10-05-phase5-round2-forentry-blueprint.md §9. Approved order:
 *
 *   create():  resolve election (real boundary, PBDIGIT-238 R1)
 *              → establish the election's organisation as tenant (decision (a))
 *              → obtain the E2 SecurityVerdict from VotingSecurityVerdictQuery
 *              → VotingAuthorization::forEntry()
 *              → only if allowed: code creation / issuance (e-mail)
 *   store():   resolve election → basic non-domain input validation (voting_code required, size 8)
 *              → obtain the E2 SecurityVerdict → forEntry() → only if allowed: code verification
 *   denial or any \Throwable → refusal (redirect to the dashboard with an error), never HTTP 500.
 *
 * E2-BEFORE-E1 IS INTENTIONAL. VotingSecurityVerdictQuery is the single E2 provider. CodeController obtains the
 * security fact BEFORE calling VotingAuthorization::forEntry(); forEntry() receives the already-established
 * SecurityVerdict and does not manufacture one (E2 is not an internal step of forEntry()). Consequently an E1
 * entitlement denial may still cause an E2 evaluation and its security telemetry.
 *
 * Decision (a) is a TRANSITIONAL DEPENDENCY, not a new invariant: the verdict's density count is tenant-scoped
 * today (VoteController::store() has the same dependency). Stage B preserves it; no explicit election-scoped
 * query is introduced. These tests observe the tenant in force WHEN the security authority is invoked — they do
 * not prove which component (controller or middleware) established it.
 *
 * What is observed: the security authority's invocations (recording double), code creation / verification /
 * issuance (database, notifications), voter progress, and the HTTP outcome. forEntry() itself is not observed
 * directly. Code LOOKUP absence is not asserted.
 *
 * The request IP is made deterministic with withServerVariables(['REMOTE_ADDR' => …]) (as in IpValidationTest).
 * Out of scope here: forCast() / VoteController (Stage C), whitelist, VE-01, can_vote_now, regeneration,
 * code-validity window, middleware, tenancy architecture. Synthetic data only.
 */
class CodeEntryAuthorizationTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private const REQUEST_IP = '10.66.0.1';

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

    /** Records every call and the tenant in force at that moment; answers with $verdict or throws. */
    private function securityDouble(?SecurityVerdict $verdict, bool $throws = false): object
    {
        $double = new class($verdict, $throws) implements VotingSecurityVerdictQuery {
            /** @var list<array{context: SecurityAttemptContext, tenant: mixed}> */
            public array $calls = [];

            public function __construct(private ?SecurityVerdict $verdict, private bool $throws) {}

            public function verdictFor(SecurityAttemptContext $context): SecurityVerdict
            {
                $this->calls[] = [
                    'context' => $context,
                    'tenant'  => \App\Services\TenantContext::get() ?? session('current_organisation_id'),
                ];
                if ($this->throws) {
                    throw new \RuntimeException('security authority unavailable (test double)');
                }

                return $this->verdict;
            }
        };
        $this->app->instance(VotingSecurityVerdictQuery::class, $double);

        return $double;
    }

    private function slugVoterFor(Election $election): array
    {
        $voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        if ($election->type === 'real') {
            ElectionMembership::create([
                'user_id'         => $voter->id,
                'organisation_id' => $this->org->id,
                'election_id'     => $election->id,
                'role'            => 'voter',
                'status'          => 'active',
            ]);
        }

        $slug = VoterSlug::factory()->create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'is_active'       => true,
            'expires_at'      => now()->addHours(2),
        ]);

        return [$voter, $slug];
    }

    private function codeFor(User $voter, Election $election, array $attributes = []): Code
    {
        return Code::factory()->create(array_merge([
            'organisation_id'                    => $this->org->id,
            'user_id'                            => $voter->id,
            'election_id'                        => $election->id,
            'code_to_open_voting_form'           => 'ABCD2345',
            'code_to_open_voting_form_sent_at'   => now(),
            'is_code_to_open_voting_form_usable' => 1,
            'can_vote_now'                       => 0,
            'has_voted'                          => 0,
            'client_ip'                          => '10.0.0.1',
        ], $attributes));
    }

    /** Authenticated voter request from the deterministic REQUEST_IP; session defaults to the fixture organisation. */
    private function asVoter(User $voter, ?array $session = null)
    {
        return $this->actingAs($voter)
            ->withServerVariables(['REMOTE_ADDR' => self::REQUEST_IP])
            ->withSession($session ?? ['current_organisation_id' => $this->org->id]);
    }

    private function codeCount(User $voter): int
    {
        return Code::withoutGlobalScopes()->where('user_id', $voter->id)->count();
    }

    private function stepCount(VoterSlug $slug): int
    {
        return DB::table('voter_slug_steps')->where('voter_slug_id', $slug->id)->count();
    }

    private function assertRefusedAtEntry($response, string $case): void
    {
        $this->assertNotSame(500, $response->status(), "{$case}: never a server error");
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ── create(): allowed → existing flow continues ──────────────────────────

    public function test_create_allowed_evaluates_the_security_authority_once_with_the_attempt_context_and_continues(): void
    {
        $security = $this->securityDouble(SecurityVerdict::Allowed);
        [$voter, $slug] = $this->slugVoterFor($this->election);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $response->assertOk();
        $this->assertSame(1, $this->codeCount($voter), 'allowed: the existing flow creates the code');

        $this->assertCount(1, $security->calls, 'the E2 verdict is obtained exactly once per entry attempt');
        $call = $security->calls[0];
        $this->assertSame($voter->id, $call['context']->userId);
        $this->assertSame($this->election->id, $call['context']->electionId,
            'the security authority is evaluated for the resolved real election');
        $this->assertSame(self::REQUEST_IP, $call['context']->clientIp, 'clientIp = request()->ip(), as in the inline path');
        $this->assertNull($call['context']->deviceFingerprint);
        $this->assertNotSame('', (string) $call['context']->sessionId);
        $this->assertSame($this->election->organisation_id, $call['tenant'],
            'decision (a): when the security authority is invoked, the active tenant is the election\'s organisation');
    }

    public function test_create_active_tenant_at_security_evaluation_is_the_election_organisation_without_a_prior_session_tenant(): void
    {
        $security = $this->securityDouble(SecurityVerdict::Allowed);
        [$voter, $slug] = $this->slugVoterFor($this->election);

        // No tenant in the session at request start. Observed: the tenant in force when the authority is invoked —
        // not which component (controller or middleware) established it.
        $this->asVoter($voter, [])->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertCount(1, $security->calls);
        $this->assertSame($this->election->organisation_id, $security->calls[0]['tenant']);
    }

    // ── create(): denial → no code creation / issuance ───────────────────────

    public function test_create_e2_denied_refuses_without_code_creation_or_issuance(): void
    {
        $this->securityDouble(SecurityVerdict::Denied);
        [$voter, $slug] = $this->slugVoterFor($this->election);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertRefusedAtEntry($response, 'create / SecurityDenied');
        $this->assertSame(0, $this->codeCount($voter), 'no code creation');
        Notification::assertNothingSent();
        $this->assertSame(0, $this->stepCount($slug), 'no voter progress written');
    }

    public function test_create_e2_review_required_is_a_refusal_q2(): void
    {
        $this->securityDouble(SecurityVerdict::ReviewRequired);
        [$voter, $slug] = $this->slugVoterFor($this->election);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertRefusedAtEntry($response, 'create / SecurityReviewRequired');
        $this->assertSame(0, $this->codeCount($voter), 'no code creation');
        Notification::assertNothingSent();
    }

    public function test_create_e1_fresh_entitlement_refuses_a_non_votable_election_even_when_navigation_guards_pass(): void
    {
        // Navigation guards today: ensure.election.voter = cached standing (no lifecycle); vote.eligibility skips the
        // lifecycle when the session flag selected_election_type = demo (F-C2). E1 (VotingEntitlement decide()) is
        // fresh and includes the lifecycle — eligibility comes from VotingEntitlement, not users.can_vote or the slug.
        // E2 is still obtained first (intentional E2-before-E1, see class doc); the double answers Allowed.
        $this->securityDouble(SecurityVerdict::Allowed);
        $closed = ElectionScenarioFactory::votingClosed($this->org);
        [$voter, $slug] = $this->slugVoterFor($closed);

        $response = $this->asVoter($voter, ['current_organisation_id' => $this->org->id, 'selected_election_type' => 'demo'])
            ->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertRefusedAtEntry($response, 'create / ElectionNotVotable');
        $this->assertSame(0, $this->codeCount($voter), 'no code creation for a non-votable election');
        Notification::assertNothingSent();
    }

    public function test_create_fails_closed_when_the_security_authority_throws(): void
    {
        $this->securityDouble(null, throws: true);
        [$voter, $slug] = $this->slugVoterFor($this->election);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertRefusedAtEntry($response, 'create / authority error');
        $this->assertSame(0, $this->codeCount($voter), 'no code creation');
        Notification::assertNothingSent();
    }

    public function test_create_security_authority_is_never_evaluated_for_a_demo_election(): void
    {
        // PBDIGIT-238 R1 real-election boundary comes first: the security authority is evaluated only after a real
        // election context is established. No demo bypass is reintroduced.
        $security = $this->securityDouble(SecurityVerdict::Allowed);
        $demo = ElectionScenarioFactory::votingActive($this->org);
        $demo->forceFill(['type' => 'demo'])->save();
        [$voter, $slug] = $this->slugVoterFor($demo->fresh());

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertSame(403, $response->status());
        $this->assertSame([], $security->calls, 'no security evaluation for a non-real election');
    }

    // ── store(): validation → security → verification ───────────────────────

    public function test_store_rejects_malformed_input_without_a_security_evaluation(): void
    {
        $security = $this->securityDouble(SecurityVerdict::Allowed);
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $this->codeFor($voter, $this->election);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'abc']);

        $response->assertSessionHasErrors('voting_code');
        $this->assertSame([], $security->calls, 'basic input validation precedes the security evaluation (review amendment 2)');
    }

    public function test_store_allowed_continues_to_code_verification(): void
    {
        $security = $this->securityDouble(SecurityVerdict::Allowed);
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $code = $this->codeFor($voter, $this->election);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $response->assertRedirect(route('slug.code.agreement', ['vslug' => $slug->slug]));
        $this->assertTrue((bool) $code->fresh()->can_vote_now, 'allowed: the existing verification flow runs');
        $this->assertCount(1, $security->calls);
        $this->assertSame(self::REQUEST_IP, $security->calls[0]['context']->clientIp);
        $this->assertSame($this->election->organisation_id, $security->calls[0]['tenant']);
    }

    public function test_store_e2_denied_refuses_without_code_verification(): void
    {
        $this->securityDouble(SecurityVerdict::Denied);
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $code = $this->codeFor($voter, $this->election);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $this->assertRefusedAtEntry($response, 'store / SecurityDenied');
        $this->assertFalse((bool) $code->fresh()->can_vote_now, 'no code verification: the code step is not completed');
        $this->assertSame(0, $this->stepCount($slug), 'no voter progress written');
    }

    public function test_store_fails_closed_when_the_security_authority_throws(): void
    {
        $this->securityDouble(null, throws: true);
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $code = $this->codeFor($voter, $this->election);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $this->assertRefusedAtEntry($response, 'store / authority error');
        $this->assertFalse((bool) $code->fresh()->can_vote_now, 'no code verification');
    }

    // ── wiring with the real authority: temporal relocation of an EXISTING rule ──

    public function test_e2_relocation_the_existing_ip_density_rule_now_refuses_at_entry_without_code_creation(): void
    {
        // Proves: the EXISTING Trust → Legitimacy security rule is temporally relocated to entry (review amendment 3)
        // — the same policy and threshold that already refuse at the final cast (elections.max_votes_per_ip 6, halved
        // to 3 for unverified voters; denial when votes from the IP > 3) now also refuse at entry.
        // Does NOT prove the security policy itself is correct — policy equivalence with today's inline path is
        // characterized by VotingSecurityVerdictAdapterTest. Real adapter, no double.
        foreach (range(1, 4) as $i) {
            [$other] = $this->slugVoterFor($this->election);
            $this->codeFor($other, $this->election, [
                'has_voted'                => 1,
                'client_ip'                => self::REQUEST_IP,
                'code_to_open_voting_form' => sprintf('RELOC%03d', $i),
            ]);
        }
        [$voter, $slug] = $this->slugVoterFor($this->election);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertRefusedAtEntry($response, 'create / relocated density rule');
        $this->assertSame(0, $this->codeCount($voter), 'no code creation or issuance');
        Notification::assertNothingSent();
    }
}
