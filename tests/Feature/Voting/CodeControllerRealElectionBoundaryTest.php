<?php

namespace Tests\Feature\Voting;

use App\Models\Code;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterStepTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * PBDIGIT-238 R1 — RED contract: the real-election boundary of CodeController.
 *
 * Invariants (blueprint §7, docs/publicdigit/reviews/2026-10-05-codecontroller-refactoring-blueprint-v1.md):
 *  B1  A demo election on any of the four real code routes is refused fail closed (HTTP 403) —
 *      no Code created or modified, no voter progress written (step records) and no VoterSlug
 *      lifecycle field changed (current_step, status, is_active, can_vote_now, expires_at).
 *  B2  Real election: a participation record with has_voted = true is rejected on every code route
 *      and stays consumed (no new code, no agreement). The demo variant is covered by B1: the boundary
 *      refuses a demo election before any Code access, so getOrCreateCode() is never reached.
 *  B3  The plaintext verification code does not occur in the captured log entries — checked over each
 *      entry's message AND its JSON-encoded context (CastFixtures::logsMentioning()), for code creation,
 *      correct verification, wrong verification and both regeneration paths.
 *
 * Asserted on database / log state, not only on HTTP. Synthetic data only.
 *
 * Fixture note: the demo voter intentionally has NO ElectionMembership — this test proves the real-route
 * boundary of CodeController, not membership authorization (ensure.election.voter lets demo elections
 * through today, which is exactly how they reach the controller). Do not "clean up" by adding one.
 * PO decisions 2026-10-05: demo on real routes → refused (403); logging scope = plaintext code values.
 */
class CodeControllerRealElectionBoundaryTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();   // real election in $this->election
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── fixtures ────────────────────────────────────────────────────────────

    private function demoElection(): Election
    {
        $demo = ElectionScenarioFactory::votingActive($this->org);
        $demo->forceFill(['type' => 'demo'])->save();

        return $demo->fresh();
    }

    /** A voter with an active slug for $election; voter membership for real elections only (see class note). */
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

    private function asVoter(User $voter)
    {
        return $this->actingAs($voter)->withSession(['current_organisation_id' => $this->org->id]);
    }

    /** Snapshot of everything B1 forbids changing: the voter's codes and the slug's progress. */
    private function stateOf(User $voter, VoterSlug $slug): array
    {
        $stepCount = DB::table('voter_slug_steps')->where('voter_slug_id', $slug->id)->count();
        if (Schema::hasTable('demo_voter_slug_steps')) {
            $stepCount += DB::table('demo_voter_slug_steps')->where('voter_slug_id', $slug->id)->count();
        }

        return [
            'codes'        => Code::withoutGlobalScopes()->where('user_id', $voter->id)->orderBy('id')
                ->get()->map(fn (Code $c) => $c->only([
                    'id', 'code_to_open_voting_form', 'code_to_open_voting_form_sent_at', 'can_vote_now',
                    'has_voted', 'vote_submitted', 'has_agreed_to_vote', 'is_code_to_open_voting_form_usable',
                ]))->all(),
            'slug'         => $slug->fresh()->only(['current_step', 'status', 'is_active', 'can_vote_now', 'expires_at']),
            'steps'        => $stepCount,
        ];
    }

    private function assertRefusedWithoutEffect($response, array $before, User $voter, VoterSlug $slug, string $route): void
    {
        $this->assertSame(403, $response->status(), "{$route}: a demo election must be refused fail closed (403) on the real code route");
        $this->assertSame($before, $this->stateOf($voter, $slug), "{$route}: no Code created / modified and no voter progress written");
    }

    // ── B1: demo election on the four real code routes ───────────────────────

    public function test_b1_demo_election_is_refused_on_code_create_without_effect(): void
    {
        [$voter, $slug] = $this->slugVoterFor($this->demoElection());
        $before = $this->stateOf($voter, $slug);

        $response = $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $this->assertRefusedWithoutEffect($response, $before, $voter, $slug, 'slug.code.create');
    }

    public function test_b1_demo_election_is_refused_on_code_store_without_effect(): void
    {
        $demo = $this->demoElection();
        [$voter, $slug] = $this->slugVoterFor($demo);
        $this->codeFor($voter, $demo);
        $before = $this->stateOf($voter, $slug);

        $response = $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'ABCD2345']);

        $this->assertRefusedWithoutEffect($response, $before, $voter, $slug, 'slug.code.store');
    }

    public function test_b1_demo_election_is_refused_on_agreement_page_without_effect(): void
    {
        $demo = $this->demoElection();
        [$voter, $slug] = $this->slugVoterFor($demo);
        $this->codeFor($voter, $demo, ['can_vote_now' => 1, 'has_agreed_to_vote' => 1]);
        (new VoterStepTrackingService())->completeStep($slug, $demo, 1, ['fixture' => true]);
        $before = $this->stateOf($voter, $slug);

        $response = $this->asVoter($voter)->get(route('slug.code.agreement', ['vslug' => $slug->slug]));

        $this->assertRefusedWithoutEffect($response, $before, $voter, $slug, 'slug.code.agreement');
    }

    public function test_b1_demo_election_is_refused_on_agreement_submit_without_effect(): void
    {
        $demo = $this->demoElection();
        [$voter, $slug] = $this->slugVoterFor($demo);
        $this->codeFor($voter, $demo, ['can_vote_now' => 1, 'has_agreed_to_vote' => 0]);
        (new VoterStepTrackingService())->completeStep($slug, $demo, 1, ['fixture' => true]);
        $before = $this->stateOf($voter, $slug);

        $response = $this->asVoter($voter)->post(route('slug.code.agreement.submit', ['vslug' => $slug->slug]), ['agreement' => 1]);

        $this->assertRefusedWithoutEffect($response, $before, $voter, $slug, 'slug.code.agreement.submit');
    }

    // ── B2: consumed participation is never reset ────────────────────────────

    public function test_b2_a_consumed_real_participation_record_is_rejected_on_every_route_and_stays_consumed(): void
    {
        // Expected responses = the current rejection semantics pinned by the R0 characterization (r0_04–r0_06).
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $code = $this->codeFor($voter, $this->election, ['has_voted' => 1, 'can_vote_now' => 1, 'code_to_open_voting_form' => 'VOTED234']);
        (new VoterStepTrackingService())->completeStep($slug, $this->election, 1, ['fixture' => true]);

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]))
            ->assertRedirect(route('dashboard'));
        $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'VOTED234'])
            ->assertSessionHasErrors('voting_code');
        $this->asVoter($voter)->get(route('slug.code.agreement', ['vslug' => $slug->slug]))
            ->assertRedirect(route('dashboard'));
        $this->asVoter($voter)->post(route('slug.code.agreement.submit', ['vslug' => $slug->slug]), ['agreement' => 1])
            ->assertRedirect(route('dashboard'));

        $after = $code->fresh();
        $this->assertTrue((bool) $after->has_voted, 'has_voted stays true');
        $this->assertSame('VOTED234', $after->code_to_open_voting_form, 'no new code issued');
        $this->assertFalse((bool) $after->has_agreed_to_vote, 'no agreement recorded after participation');
    }

    // ── B3: no plaintext code in logs ────────────────────────────────────────

    private function assertCodeNotLogged(string $code, string $operation): void
    {
        $hits = $this->logsMentioning($code);
        // logsMentioning() searches each captured entry's message plus its JSON-encoded context.
        $this->assertSame([], array_map(fn ($e) => $e['message'], $hits),
            "{$operation}: the plaintext verification code must not occur in a captured log entry (message or context)");
    }

    public function test_b3_code_creation_does_not_log_the_code(): void
    {
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $this->captureLogs();

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]))->assertOk();

        $code = Code::withoutGlobalScopes()->where('user_id', $voter->id)->firstOrFail();
        $this->assertCodeNotLogged($code->code_to_open_voting_form, 'code creation');
    }

    public function test_b3_a_correct_verification_does_not_log_the_code(): void
    {
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $this->codeFor($voter, $this->election, ['code_to_open_voting_form' => 'RIGHT234']);
        $this->captureLogs();

        $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'RIGHT234'])
            ->assertSessionHasNoErrors();

        $this->assertCodeNotLogged('RIGHT234', 'correct verification');
    }

    public function test_b3_a_wrong_verification_logs_neither_the_expected_nor_the_submitted_code(): void
    {
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $this->codeFor($voter, $this->election, ['code_to_open_voting_form' => 'RIGHT234']);
        $this->captureLogs();

        $this->asVoter($voter)->post(route('slug.code.store', ['vslug' => $slug->slug]), ['voting_code' => 'WRONG567'])
            ->assertSessionHasErrors('voting_code');

        $this->assertCodeNotLogged('RIGHT234', 'wrong verification (expected value)');
        $this->assertCodeNotLogged('WRONG567', 'wrong verification (submitted value)');
    }

    public function test_b3_regeneration_of_an_expired_unverified_code_does_not_log_the_new_code(): void
    {
        // getOrCreateCode() resend path (expired, unused, not voted).
        config(['voting.time_in_minutes' => 30]);
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $this->codeFor($voter, $this->election, [
            'code_to_open_voting_form'         => 'STALE234',
            'code_to_open_voting_form_sent_at' => now()->subMinutes(45),
            'can_vote_now'                     => 0,   // unverified → getOrCreateCode() resend path
            'has_voted'                        => 0,
        ]);
        $this->captureLogs();

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]))->assertOk();

        $newCode = Code::withoutGlobalScopes()->where('user_id', $voter->id)->firstOrFail()->code_to_open_voting_form;
        $this->assertIsString($newCode);
        $this->assertNotSame('STALE234', $newCode, 'precondition: a new code value was issued');
        $this->assertCodeNotLogged($newCode, 'regeneration (resend path)');
    }

    public function test_b3_regeneration_of_a_stale_verified_code_does_not_log_the_new_code(): void
    {
        // create() regeneration path (window elapsed; can_vote_now preserved — PBDIGIT-231 unchanged here).
        config(['voting.time_in_minutes' => 30]);
        [$voter, $slug] = $this->slugVoterFor($this->election);
        $this->codeFor($voter, $this->election, [
            'code_to_open_voting_form'         => 'STALE234',
            'code_to_open_voting_form_sent_at' => now()->subMinutes(45),
            'can_vote_now'                     => 1,   // verified → create() regeneration path (PBDIGIT-231 behaviour)
            'has_voted'                        => 0,
        ]);
        $this->captureLogs();

        $this->asVoter($voter)->get(route('slug.code.create', ['vslug' => $slug->slug]));

        $after = Code::withoutGlobalScopes()->where('user_id', $voter->id)->firstOrFail();
        $newCode = $after->code_to_open_voting_form;
        $this->assertIsString($newCode);
        $this->assertNotSame('STALE234', $newCode, 'precondition: a new code value was issued');
        $this->assertTrue((bool) $after->can_vote_now, 'precondition: this is the create() path that preserves can_vote_now (PBDIGIT-231, unchanged)');
        $this->assertCodeNotLogged($newCode, 'regeneration (create path)');
    }
}
