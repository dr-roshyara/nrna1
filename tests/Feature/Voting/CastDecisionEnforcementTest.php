<?php

namespace Tests\Feature\Voting;

use App\Models\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * Phase 4 · E3 · R3 — VoteController::store enforces forCast() (PBDIGIT-233, PBDIGIT-219).
 *
 * Plan: docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md §2.2 E3, §4 R3
 * Decision record: P4-D1 = B (no second IP-density check), P4-D2 = A (CodeMissing,
 * BallotMissing), P4-D3 = A (missing Code → CodeMissing, resolves PBDIGIT-219), E2-D1…D4.
 *
 * The final cast refuses with a reason — never through a crash, a missing view or a
 * generic error — and writes nothing:
 *   R3-1  no ballot in the session          → BallotMissing   (today: missing-view exception)
 *   R3-2  code step not completed           → CodeStepIncomplete (today: not checked)
 *   R3-3  the voter has no Code             → CodeMissing     (PBDIGIT-219; today: generic error)
 *   R3-4  wrong submitted code              → CodeMismatch, existing field and message
 *   R3-5  GUARD (unchanged behaviour, not an E3 contract): an already-used code is still
 *         refused as already voted
 *   R3-7  GUARD (source-level fitness test, not behavioural evidence): store() decides
 *         through VotingAuthorization::forCast() and no longer calls vote_post_check()
 * (R3-6 — MAX_USE_IP_ADDRESS — is outside E3 by PO decision.)
 *
 * Entitlement / lifecycle refusals are made by the route middleware before store()
 * (ensure.election.voter, voting.active) and are covered by the existing suites and the
 * E2 unit contract; they are not repeated here. Synthetic data only.
 */
class CastDecisionEnforcementTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    private function assertNothingConsumed(?Code $code): void
    {
        $this->assertSame(0, $this->votes(), 'no vote is written');
        if ($code !== null) {
            $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is not consumed');
        }
    }

    /** A reasoned refusal: a redirect carrying a `vote` error, never the generic `vote_error`. */
    private function assertReasonedRefusal($response): void
    {
        $this->assertNotSame(500, $response->status());
        $response->assertRedirect();
        $response->assertSessionHasErrors('vote');
        $response->assertSessionDoesntHaveErrors('vote_error');
    }

    // ── R3-1 BallotMissing ───────────────────────────────────────────────────

    public function test_r3_1_a_missing_ballot_is_refused_with_a_reason_not_a_missing_view(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->captureLogs();

        // No ballot selections in the session.
        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $code->code_to_open_voting_form]);

        $this->assertReasonedRefusal($response);
        $this->assertNothingConsumed($code);
        $this->assertSame([], $this->logsMentioning('View [components.error_message] not found'),
            'the refusal does not run through the missing view (PBDIGIT-233 D10)');
    }

    // ── R3-2 CodeStepIncomplete ──────────────────────────────────────────────

    public function test_r3_2_a_code_whose_code_step_is_not_completed_is_refused(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $code->forceFill(['can_vote_now' => 0])->save(); // read as a fact only; semantics unchanged

        $response = $this->cast($voter, $slug, $code);

        $this->assertReasonedRefusal($response);
        $this->assertNothingConsumed($code);
    }

    // ── R3-3 CodeMissing (PBDIGIT-219) ───────────────────────────────────────

    public function test_r3_3_a_voter_without_a_code_is_refused_with_a_reason(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $sessionName = $code->session_name;
        $submitted = $code->code_to_open_voting_form;
        // No Code row at all (PBDIGIT-219). forceDelete: Code uses SoftDeletes and store()
        // loads it withoutGlobalScopes(), so a soft-deleted code would still be found.
        Code::withoutGlobalScopes()->whereKey($code->id)->forceDelete();

        $response = $this->actingAs($voter)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                $sessionName              => $this->ballotSessionData(),
            ])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $submitted]);

        $this->assertReasonedRefusal($response);
        $this->assertNothingConsumed(null);
    }

    // ── R3-4 CodeMismatch (guard: field and message unchanged) ──────────────

    public function test_r3_4_a_wrong_code_keeps_its_existing_field_and_message(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->cast($voter, $slug, $code, 'WRONG999');

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasErrors(['voting_code' => 'Submitted code is false. Please check your email and try again.']);
        $this->assertNothingConsumed($code);
    }

    // ── R3-5 GUARD — already voted: refusal unchanged (not an E3 contract) ───

    public function test_r3_5_an_already_used_code_is_refused_as_already_voted(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $code->forceFill(['has_voted' => true])->save();

        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertRedirect();
        $response->assertSessionHasErrors('vote');
        $this->assertSame(0, $this->votes(), 'no vote is written');
    }

    // ── R3-7 GUARD — source-level fitness test: one decision ────────────────

    public function test_r3_7_store_decides_through_for_cast_and_no_longer_calls_vote_post_check(): void
    {
        $source = file_get_contents(base_path('app/Http/Controllers/VoteController.php'));
        preg_match('/public function store\(Request \$request\)(.*?)\n    \}\n/s', $source, $m);
        $store = $m[1] ?? '';

        $this->assertNotSame('', $store, 'store() located');
        $this->assertStringContainsString('->forCast(', $store, 'store() asks VotingAuthorization::forCast()');
        $this->assertStringNotContainsString('vote_post_check(', $store, 'store() no longer calls vote_post_check() (PBDIGIT-233 D1)');
    }
}
