<?php

namespace Tests\Feature\Voting;

use App\Models\Candidacy;
use App\Models\Code;
use App\Models\Election;
use App\Models\Post;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * VE-01 — FINAL CAST BALLOT INVARIANT (RED contract; no implementation).
 *
 * PO decisions 2026-10-05:
 *   D1 the server accepts only selections from the voter's assembled ballot (election, post, region),
 *      each candidacy at most once;
 *   D2 posts.required_number is the per-post MAXIMUM (0 … required_number selections allowed);
 *   D3 a malformed ballot fails closed.
 *
 * GIVEN an entitled voter in election E (code step completed, valid code)
 * WHEN the final cast (POST slug.vote.store) receives ballot B from the voter's session
 * THEN B is valid iff
 *   1 B has a valid structure
 *   2 every submitted post belongs to E
 *   3 every submitted candidacy belongs to E
 *   4 every candidacy belongs to its submitted post
 *   5 every regional post belongs to the voter's region
 *   6 no candidacy occurs more than once
 *   7 selected count per post <= posts.required_number
 * and if B is invalid: vote count unchanged · codes.has_voted unchanged · result rows unchanged —
 * and the participation compare-and-set is NOT executed (validation happens BEFORE the CAS).
 *
 * Further PO decisions 2026-10-05: D5 only approved candidacies are electable; N1 abstention together
 * with candidates is invalid; N2 an empty candidate list without abstention means NOT ANSWERED;
 * N3 an omitted post means NOT ANSWERED; N4 the final cast enforces whether the election allows
 * abstention; N5 an abstention outside the voter's ballot is invalid. D6 (which region snapshot decides
 * the regional ballot) is OPEN — these contracts use the existing source, users.region at cast time.
 * An invalid ballot must end in a reasoned refusal (`vote` error), not the generic failure.
 *
 * SESSION DATA IS UNTRUSTED INPUT: the ballot is placed in the session directly, bypassing the
 * ballot-submit step; the voting session may transport ballot state but never establishes ballot
 * validity — the final cast must re-establish the invariant against the server-side ballot.
 * Synthetic data only.
 */
class FinalCastBallotInvariantTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private Post $p1;   // national, E, max 1
    private Post $p2;   // national, E, max 1
    private Post $p3;   // national, E, max 2
    private Post $pr1;  // regional, E, Bayern (voter's region)
    private Post $pr2;  // regional, E, Hessen
    private Post $pb;   // national, election B
    private Candidacy $c1a;
    private Candidacy $c1b;
    private Candidacy $c2;
    private Candidacy $c3a;
    private Candidacy $cr1;
    private Candidacy $cr2;
    private Candidacy $cb;

    /** @var list<string> */
    private array $sql = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();

        $this->p1  = $this->makePost($this->election, true, null, 1);
        $this->p2  = $this->makePost($this->election, true, null, 1);
        $this->p3  = $this->makePost($this->election, true, null, 2);
        $this->pr1 = $this->makePost($this->election, false, 'Bayern', 1);
        $this->pr2 = $this->makePost($this->election, false, 'Hessen', 1);
        $this->pb  = $this->makePost(ElectionScenarioFactory::votingActive($this->org), true, null, 1);

        $this->c1a = $this->makeCandidacy($this->p1);
        $this->c1b = $this->makeCandidacy($this->p1);
        $this->c2  = $this->makeCandidacy($this->p2);
        $this->c3a = $this->makeCandidacy($this->p3);
        $this->cr1 = $this->makeCandidacy($this->pr1);
        $this->cr2 = $this->makeCandidacy($this->pr2);
        $this->cb  = $this->makeCandidacy($this->pb);
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function makePost(Election $election, bool $national, ?string $state, int $max): Post
    {
        return Post::factory()->create([
            'organisation_id'  => $election->organisation_id,
            'election_id'      => $election->id,
            'is_national_wide' => $national,
            'state_name'       => $state,
            'required_number'  => $max,
        ]);
    }

    private function makeCandidacy(Post $post): Candidacy
    {
        return Candidacy::factory()->create([
            'organisation_id' => $post->organisation_id,
            'post_id'         => $post->id,
            'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
            'status'          => 'approved',
        ]);
    }

    private function bayernVoter(): array
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $voter->forceFill(['region' => 'Bayern'])->save();

        return [$voter->fresh(), $slug, $code];
    }

    private function sel(Post $post, array $candidacyIds): array
    {
        return [
            'post_id'         => $post->id,
            'post_name'       => $post->name,
            'required_number' => $post->required_number,
            'no_vote'         => false,
            'candidates'      => array_map(fn ($id) => ['candidacy_id' => $id, 'candidacy_name' => 'x'], $candidacyIds),
        ];
    }

    /** Final cast with ballot B placed straight into the session (no ballot-submit step). */
    private function castWithSessionBallot(User $voter, VoterSlug $slug, Code $code, array $national, array $regional = [])
    {
        $this->sql = [];
        DB::listen(function (QueryExecuted $q) {
            $this->sql[] = $q->sql;
        });

        return $this->actingAs($voter)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                $code->session_name       => [
                    'user_id'                      => $voter->id,
                    'national_selected_candidates' => $national,
                    'regional_selected_candidates' => $regional,
                    'no_vote_posts'                => [],
                    'agree_button'                 => 1,
                ],
            ])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $code->code_to_open_voting_form]);
    }

    private function casExecuted(): bool
    {
        foreach ($this->sql as $sql) {
            if (preg_match('/^update "codes" set "has_voted" = \? where "id" = \? and "has_voted" = \?/', $sql)) {
                return true;
            }
        }

        return false;
    }

    private function assertInvalidBallotRefusedBeforeCas(Code $code, $response, string $rule): void
    {
        $this->assertNotSame(500, $response->status(), "{$rule}: no server error");
        $this->assertSame(0, DB::table('votes')->count(), "{$rule}: vote count unchanged");
        $this->assertFalse((bool) $code->fresh()->has_voted, "{$rule}: codes.has_voted unchanged");
        $this->assertSame(0, DB::table('results')->count(), "{$rule}: result rows unchanged");
        $this->assertFalse($this->casExecuted(), "{$rule}: the participation compare-and-set must not run for an invalid ballot (validation before CAS)");

        // Deterministic, reasoned refusal — not merely "some non-500 response", and not the generic failure.
        $response->assertRedirect();
        $response->assertSessionHasErrors('vote');
        $response->assertSessionDoesntHaveErrors('vote_error');
    }

    // ── Valid ballots (GREEN expected) ───────────────────────────────────────

    public function test_a_valid_ballot_from_the_session_is_cast(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        $response = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->c1a->id])], [$this->sel($this->pr1, [$this->cr1->id])]);

        $response->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count());
        $this->assertTrue((bool) $code->fresh()->has_voted);
        $this->assertTrue($this->casExecuted());
    }

    public function test_d2_fewer_selections_than_the_posts_maximum_are_cast(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        $response = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p3, [$this->c3a->id])]);

        $response->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count());
    }

    // ── Regional persistence (characterization, PO 2026-10-05) ──────────────
    // Settles the review concern that saveValidatedVote() loses regional answers: the stored
    // ballot has no national/regional section — every answer is a candidate_NN column carrying
    // its post_id, and result rows are created per post.

    public function test_characterization_national_and_regional_answers_are_both_persisted(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        $response = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->c1a->id])], [$this->sel($this->pr1, [$this->cr1->id])]);

        $response->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count());
        $this->assertSame(1, DB::table('results')->where('post_id', $this->p1->id)->where('candidacy_id', $this->c1a->id)->count(), 'national answer persisted');
        $this->assertSame(1, DB::table('results')->where('post_id', $this->pr1->id)->where('candidacy_id', $this->cr1->id)->count(), 'regional answer persisted');
        $this->assertSame(2, DB::table('results')->count(), 'exactly the two validated answers');
    }

    public function test_characterization_a_regional_only_ballot_is_persisted(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        $response = $this->castWithSessionBallot($voter, $slug, $code, [], [$this->sel($this->pr1, [$this->cr1->id])]);

        $response->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count());
        $this->assertSame(1, DB::table('results')->where('post_id', $this->pr1->id)->where('candidacy_id', $this->cr1->id)->count(), 'regional answer persisted');
        $this->assertSame(1, DB::table('results')->count(), 'exactly the one validated answer');
    }

    // ── Invariant rules 2–7 (RED expected today) ────────────────────────────

    public function test_rule_2_a_post_of_another_election_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->pb, [$this->cb->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 2');
    }

    public function test_rule_3_a_candidacy_of_another_election_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->cb->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 3');
    }

    public function test_rule_4_a_candidacy_of_another_post_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->c2->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 4');
    }

    public function test_rule_5_a_regional_post_outside_the_voters_region_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [], [$this->sel($this->pr2, [$this->cr2->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 5');
    }

    public function test_rule_6_a_candidacy_selected_twice_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->c1a->id, $this->c1a->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 6');
    }

    public function test_rule_7_more_selections_than_the_posts_maximum_are_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->c1a->id, $this->c1b->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 7');
    }

    // ── Rule 1 / D3: malformed structures fail closed ───────────────────────

    public function test_rule_1_a_candidate_entry_without_candidacy_id_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [['post_id' => $this->p1->id, 'no_vote' => false, 'candidates' => [['candidacy_name' => 'x']]]]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 1 (missing candidacy_id)');
    }

    public function test_rule_1_a_scalar_candidate_entry_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [['post_id' => $this->p1->id, 'no_vote' => false, 'candidates' => ['not-an-object']]]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 1 (scalar candidate)');
    }

    public function test_rule_1_a_selection_without_post_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [['no_vote' => false, 'candidates' => [['candidacy_id' => $this->c1a->id]]]]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 1 (missing post)');
    }

    public function test_rule_1_a_nonexistent_candidacy_is_refused_before_cas(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, ['0198a1b2-0000-7000-8000-00000000dead'])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'rule 1/3 (nonexistent candidacy)');
    }

    // ── D5: only approved candidacies are electable ─────────────────────────

    public function test_d5_a_pending_candidacy_is_refused_before_cas(): void
    {
        $pending = $this->makeCandidacy($this->p1);
        $pending->forceFill(['status' => 'pending'])->save();
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$pending->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'D5 (pending candidacy)');
    }

    public function test_d5_a_rejected_candidacy_is_refused_before_cas(): void
    {
        $rejected = $this->makeCandidacy($this->p1);
        $rejected->forceFill(['status' => 'rejected'])->save();
        [$voter, $slug, $code] = $this->bayernVoter();
        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$rejected->id])]);
        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'D5 (rejected candidacy)');
    }

    // ── Abstention / not answered (N1–N5) ───────────────────────────────────

    private function abstain(Post $post): array
    {
        return ['post_id' => $post->id, 'post_name' => $post->name, 'required_number' => $post->required_number, 'no_vote' => true, 'candidates' => []];
    }

    private function allowAbstention(bool $allowed): void
    {
        $this->election->forceFill(['no_vote_option_enabled' => $allowed])->save();
    }

    public function test_an_explicit_abstention_is_cast_as_an_abstention_when_the_election_allows_it(): void
    {
        $this->allowAbstention(true);
        [$voter, $slug, $code] = $this->bayernVoter();

        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->abstain($this->p1), $this->sel($this->p2, [$this->c2->id])]);

        $r->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count());
        $this->assertSame(1, DB::table('results')->where('post_id', $this->p1->id)->whereNull('candidacy_id')->count(), 'one abstention row for p1');
    }

    public function test_n1_abstention_together_with_candidates_is_refused_before_cas(): void
    {
        $this->allowAbstention(true);
        [$voter, $slug, $code] = $this->bayernVoter();
        $selection = $this->sel($this->p1, [$this->c1a->id]);
        $selection['no_vote'] = true;

        $r = $this->castWithSessionBallot($voter, $slug, $code, [$selection]);

        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'N1 (abstention + candidates)');
    }

    public function test_n2_an_empty_candidate_list_without_abstention_is_not_answered_not_an_abstention(): void
    {
        $this->allowAbstention(true);
        [$voter, $slug, $code] = $this->bayernVoter();
        $empty = ['post_id' => $this->p1->id, 'no_vote' => false, 'candidates' => []];

        $r = $this->castWithSessionBallot($voter, $slug, $code, [$empty, $this->sel($this->p2, [$this->c2->id])]);

        $r->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count(), 'the ballot is cast (0 selections on p1 is allowed, D2)');
        $this->assertSame(0, DB::table('results')->where('post_id', $this->p1->id)->count(), 'p1 is NOT ANSWERED: no result row, in particular no abstention row');
    }

    public function test_n3_an_omitted_post_is_not_answered(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p2, [$this->c2->id])]);

        $r->assertSessionHasNoErrors();
        $this->assertSame(1, DB::table('votes')->count());
        $this->assertSame(0, DB::table('results')->where('post_id', $this->p1->id)->count(), 'p1 omitted: no result row');
    }

    public function test_n4_an_abstention_is_refused_before_cas_when_the_election_does_not_allow_it(): void
    {
        $this->allowAbstention(false);
        [$voter, $slug, $code] = $this->bayernVoter();

        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->abstain($this->p1)]);

        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'N4 (abstention not allowed)');
    }

    public function test_n5_an_abstention_for_a_post_outside_the_voters_ballot_is_refused_before_cas(): void
    {
        $this->allowAbstention(true);
        [$voter, $slug, $code] = $this->bayernVoter();

        $r = $this->castWithSessionBallot($voter, $slug, $code, [$this->sel($this->p1, [$this->c1a->id])], [$this->abstain($this->pr2)]);

        $this->assertInvalidBallotRefusedBeforeCas($code, $r, 'N5 (abstention outside the ballot)');
    }
}
