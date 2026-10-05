<?php

namespace Tests\Feature\Voting;

use App\Models\Candidacy;
use App\Models\Code;
use App\Models\Election;
use App\Models\Post;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Voting-engine verification gate · T1 — ballot integrity (VE-01). CHARACTERIZATION, NOT A FIX.
 *
 * Business invariant (derived from the ballot the server itself assembles — BallotAssemblyService):
 *   a voter's ballot consists of the election's national posts plus the regional posts whose
 *   state_name equals the voter's region; each post offers that post's candidacies.
 *   GIVEN an entitled voter casting in election E
 *   WHEN the submitted ballot selects anything outside that assembled ballot
 *        (another election's candidacy or post, a candidacy of another post, a regional post of
 *         another region, the same candidacy twice, a non-existent id, a malformed structure)
 *   THEN the ballot is rejected: no vote, no participation consumed, no result rows,
 *        and the published tally is unaffected.
 *
 * Every test drives the real chain: POST slug.vote.submit (first_submission) → session ballot →
 * POST slug.vote.store (final cast: forCast, CAS, save_vote) → results → result.index tally.
 * A RED result means the current implementation does not satisfy the invariant. Synthetic data only.
 */
class BallotIntegrityCharacterizationTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    private Post $p1;      // national, election E, required 1
    private Post $p2;      // national, election E, required 1
    private Post $pr1;     // regional, election E, state Bayern (the voter's region)
    private Post $pr2;     // regional, election E, state Hessen (another region)
    private Post $pb;      // national, election B
    private Candidacy $c1a;
    private Candidacy $c1b;
    private Candidacy $c2;
    private Candidacy $cr1;
    private Candidacy $cr2;
    private Candidacy $cb;
    private Election $electionB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();

        $this->p1  = $this->makePost($this->election, true, null);
        $this->p2  = $this->makePost($this->election, true, null);
        $this->pr1 = $this->makePost($this->election, false, 'Bayern');
        $this->pr2 = $this->makePost($this->election, false, 'Hessen');

        $this->electionB = ElectionScenarioFactory::votingActive($this->org);
        $this->pb = $this->makePost($this->electionB, true, null);

        $this->c1a = $this->candidacy($this->p1);
        $this->c1b = $this->candidacy($this->p1);
        $this->c2  = $this->candidacy($this->p2);
        $this->cr1 = $this->candidacy($this->pr1);
        $this->cr2 = $this->candidacy($this->pr2);
        $this->cb  = $this->candidacy($this->pb);
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    // ── Fixtures ─────────────────────────────────────────────────────────────

    private function makePost(Election $election, bool $national, ?string $state): Post
    {
        return Post::factory()->create([
            'organisation_id'  => $election->organisation_id,
            'election_id'      => $election->id,
            'is_national_wide' => $national,
            'state_name'       => $state,
            'required_number'  => 1,
        ]);
    }

    private function candidacy(Post $post): Candidacy
    {
        return Candidacy::factory()->create([
            'organisation_id' => $post->organisation_id,
            'post_id'         => $post->id,
            'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
            'status'          => 'approved',
        ]);
    }

    private int $voterCount = 0;

    /**
     * A voter in region Bayern, code step completed, agreement given. Each voter's code carries its
     * own client_ip (one voter per network) so the Trust → Legitimacy IP-density rule — which counts
     * consumed codes per IP — does not interfere when one test casts several ballots.
     */
    private function bayernVoter(): array
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $voter->forceFill(['region' => 'Bayern'])->save();
        $code->forceFill(['client_ip' => '10.20.0.' . (++$this->voterCount)])->save();

        return [$voter->fresh(), $slug, $code->fresh()];
    }

    private function selection(Post $post, array $candidacyIds): array
    {
        return [
            'post_id'         => $post->id,
            'post_name'       => $post->name,
            'required_number' => $post->required_number,
            'no_vote'         => false,
            'candidates'      => array_map(fn ($id) => ['candidacy_id' => $id, 'candidacy_name' => 'x'], $candidacyIds),
        ];
    }

    /** POST the ballot (step 3), then the final cast (step 5) — the real chain. */
    private function submitAndCast(User $voter, VoterSlug $slug, Code $code, array $national, array $regional = []): array
    {
        $submit = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.submit', ['vslug' => $slug->slug]), [
                'user_id'                      => $voter->id,
                'national_selected_candidates' => $national,
                'regional_selected_candidates' => $regional,
                'no_vote_posts'                => [],
                'agree_button'                 => 1,
            ]);

        $ballotInSession = session()->has($code->fresh()->session_name ?? ('vote_data_' . $voter->id));

        $cast = $this->actingAs($voter)
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), [
                'voting_code' => $code->code_to_open_voting_form,
            ]);

        return [$submit, $cast, $ballotInSession];
    }

    private function votes(Election $election): int
    {
        return DB::table('votes')->where('election_id', $election->id)->count();
    }

    private function resultRows(): int
    {
        return DB::table('results')->count();
    }

    /** The invariant for every invalid ballot. */
    private function assertRejectedWithoutEffect(Code $code, $cast, string $case): void
    {
        $this->assertNotSame(500, $cast->status(), "{$case}: no server error");
        $this->assertSame(0, $this->votes($this->election), "{$case}: no vote is committed");
        $this->assertSame(0, $this->votes($this->electionB), "{$case}: no vote in the other election");
        $this->assertFalse((bool) $code->fresh()->has_voted, "{$case}: participation is not consumed");
        $this->assertSame(0, $this->resultRows(), "{$case}: no result row is persisted");
    }

    /** Published tally of election E: candidacy_id => vote_count, as shown by result.index. */
    private function publishedTally(User $viewer): array
    {
        $this->election->forceFill(['results_published' => true])->save();

        $page = $this->actingAs($viewer)->get(route('result.index', ['election' => $this->election->slug]))->viewData('page');

        $tally = [];
        foreach ($page['props']['final_result']['posts'] as $post) {
            foreach ($post['candidates'] as $candidate) {
                $tally[$candidate['candidacy_id']] = $candidate['vote_count'];
            }
        }

        return $tally;
    }

    // ── T1-A: valid ballot (positive baseline) ───────────────────────────────

    public function test_t1_a_a_valid_ballot_is_cast_and_counted_exactly_as_selected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [$submit, $cast, $inSession] = $this->submitAndCast($voter, $slug, $code,
            [$this->selection($this->p1, [$this->c1a->id]), $this->selection($this->p2, [$this->c2->id])],
            [$this->selection($this->pr1, [$this->cr1->id])]);

        $this->assertTrue($inSession, 'precondition: the ballot reached the session');
        $cast->assertSessionHasNoErrors();
        $this->assertSame(1, $this->votes($this->election), 'one vote');
        $this->assertTrue((bool) $code->fresh()->has_voted, 'participation consumed');

        $tally = $this->publishedTally($voter);
        $this->assertSame(1, $tally[$this->c1a->id] ?? null);
        $this->assertSame(1, $tally[$this->c2->id] ?? null);
        $this->assertSame(1, $tally[$this->cr1->id] ?? null);
        $this->assertSame(0, $tally[$this->c1b->id] ?? null);
    }

    // ── T1-B … T1-H: selections outside the voter's ballot ──────────────────

    public function test_t1_b_a_candidacy_of_another_election_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($this->p1, [$this->cb->id])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-B');
    }

    public function test_t1_c_a_candidacy_of_another_post_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($this->p1, [$this->c2->id])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-C');
    }

    public function test_t1_d_a_regional_post_of_another_region_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code,
            [$this->selection($this->p1, [$this->c1a->id])],
            [$this->selection($this->pr2, [$this->cr2->id])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-D');
    }

    public function test_t1_e_the_same_candidacy_selected_twice_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($this->p1, [$this->c1a->id, $this->c1a->id])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-E');
    }

    public function test_t1_f_a_nonexistent_candidacy_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($this->p1, ['0198a1b2-0000-7000-8000-00000000dead'])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-F');
    }

    public function test_t1_g_a_nonexistent_post_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        $selection = $this->selection($this->p1, [$this->c1a->id]);
        $selection['post_id'] = '0198a1b2-0000-7000-8000-0000000beef0';

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$selection]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-G');
    }

    public function test_t1_h_a_post_of_another_election_is_rejected(): void
    {
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($this->pb, [$this->cb->id])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-H');
    }

    // ── T1-I: malformed ballot structures (each with a fresh voter) ─────────

    public function test_t1_i_malformed_ballot_structures_fail_closed(): void
    {
        $cases = [
            'candidates scalar'        => [['post_id' => $this->p1->id, 'no_vote' => false, 'candidates' => 'oops']],
            'candidate without id'     => [['post_id' => $this->p1->id, 'no_vote' => false, 'candidates' => [['candidacy_name' => 'x']]]],
            'selection without post'   => [['no_vote' => false, 'candidates' => [['candidacy_id' => $this->c1a->id]]]],
            'candidate entry scalar'   => [['post_id' => $this->p1->id, 'no_vote' => false, 'candidates' => ['not-an-object']]],
            'nested malformed'         => [['post_id' => ['nested' => true], 'no_vote' => false, 'candidates' => [['candidacy_id' => ['x' => 1]]]]],
        ];

        // Every case is observed before asserting, so the report shows each variant's outcome.
        $observed = [];
        foreach ($cases as $case => $national) {
            $votesBefore = $this->votes($this->election);
            $resultsBefore = $this->resultRows();
            [$voter, $slug, $code] = $this->bayernVoter();

            [$submit, $cast] = $this->submitAndCast($voter, $slug, $code, $national);

            $observed[$case] = [
                'submit_status' => $submit->status(),
                'cast_status'   => $cast->status(),
                'vote_added'    => $this->votes($this->election) - $votesBefore,
                'consumed'      => (bool) $code->fresh()->has_voted,
                'result_rows'   => $this->resultRows() - $resultsBefore,
            ];
        }

        $violations = array_filter($observed, fn ($o) => $o['cast_status'] === 500 || $o['vote_added'] !== 0 || $o['consumed'] || $o['result_rows'] !== 0);

        $this->assertSame([], $violations, 'T1-I: malformed ballots must fail closed (no 500, no vote, no consumption, no rows). Observed: ' . json_encode($observed));
    }

    // ── T1-J: selection count vs the post's required number ─────────────────

    public function test_t1_j_selecting_more_candidacies_than_the_posts_required_number_is_rejected(): void
    {
        // Rule under test: post.required_number (UI: "Select exactly N" / "Max N") — whether the
        // server must enforce it per post is a DESIGN QUESTION (server uses an election-level constraint).
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($this->p1, [$this->c1a->id, $this->c1b->id])]);

        $this->assertRejectedWithoutEffect($code, $cast, 'T1-J');
    }

    public function test_t1_j2_selecting_fewer_candidacies_than_the_posts_maximum_is_accepted(): void
    {
        // PO decision 2026-10-05: posts.required_number is the per-post MAXIMUM — fewer is allowed.
        $p3 = $this->makePost($this->election, true, null);
        $p3->forceFill(['required_number' => 2])->save();
        $c3a = $this->candidacy($p3);
        $this->candidacy($p3);
        [$voter, $slug, $code] = $this->bayernVoter();

        [, $cast] = $this->submitAndCast($voter, $slug, $code, [$this->selection($p3->fresh(), [$c3a->id])]);

        $cast->assertSessionHasNoErrors();
        $this->assertSame(1, $this->votes($this->election), 'one vote');
        $this->assertTrue((bool) $code->fresh()->has_voted, 'participation consumed');
    }

    // ── T1-L: can an invalid ballot change the published result? ────────────

    public function test_t1_l_an_invalid_ballot_cannot_change_the_published_result(): void
    {
        // One fresh voter per invalid variant; the published tally of election E is read after
        // each cast and the change attributed to that variant. No invalid ballot may change it.
        $variants = [
            'T1-B other election candidacy under p1' => [[$this->selection($this->p1, [$this->cb->id])], []],
            'T1-C other post candidacy under p1'     => [[$this->selection($this->p1, [$this->c2->id])], []],
            'T1-D other region regional post'        => [[], [$this->selection($this->pr2, [$this->cr2->id])]],
            'T1-E same candidacy twice'              => [[$this->selection($this->p1, [$this->c1a->id, $this->c1a->id])], []],
            'T1-H other election post'               => [[$this->selection($this->pb, [$this->cb->id])], []],
            'T1-J over-selection in p1'              => [[$this->selection($this->p1, [$this->c1a->id, $this->c1b->id])], []],
        ];

        [$viewer] = $this->bayernVoter();
        $changes = [];
        $castEvidence = [];
        foreach ($variants as $variant => [$national, $regional]) {
            $before = $this->publishedTally($viewer);
            $votesBefore = $this->votes($this->election);
            $rowsBefore = DB::table('results')->pluck('id')->all();
            [$voter, $slug, $code] = $this->bayernVoter();
            [, $cast] = $this->submitAndCast($voter, $slug, $code, $national, $regional);
            $after = $this->publishedTally($viewer);
            $newRows = DB::table('results')->whereNotIn('id', $rowsBefore)->get(['election_id', 'post_id', 'candidacy_id'])
                ->map(fn ($r) => [
                    'election' => $r->election_id === $this->election->id ? 'E' : 'other',
                    'post'     => $r->post_id === $this->p1->id ? 'p1' : ($r->post_id === $this->pr2->id ? 'pr2' : ($r->post_id === $this->pb->id ? 'pb' : 'other')),
                    'candidacy'=> $r->candidacy_id === $this->c1a->id ? 'c1a' : ($r->candidacy_id === $this->c1b->id ? 'c1b' : ($r->candidacy_id === $this->c2->id ? 'c2'
                        : ($r->candidacy_id === $this->cr2->id ? 'cr2' : ($r->candidacy_id === $this->cb->id ? 'cb' : (string) $r->candidacy_id)))),
                ])->all();
            $castEvidence[$variant] = ['cast_status' => $cast->status(), 'vote_added' => $this->votes($this->election) - $votesBefore, 'consumed' => (bool) $code->fresh()->has_voted, 'new_result_rows' => $newRows];

            $delta = [];
            foreach ($after as $candidacyId => $count) {
                if ($count !== ($before[$candidacyId] ?? 0)) {
                    $delta[$candidacyId === $this->c1a->id ? 'c1a' : ($candidacyId === $this->c1b->id ? 'c1b'
                        : ($candidacyId === $this->c2->id ? 'c2' : ($candidacyId === $this->cr2->id ? 'cr2' : $candidacyId)))]
                        = $count - ($before[$candidacyId] ?? 0);
                }
            }
            $changes[$variant] = $delta;
        }

        $this->assertSame([], array_filter($changes), 'T1-L: no invalid ballot may change the published tally. Observed changes per variant: ' . json_encode($changes)
            . ' | Cast evidence per variant: ' . json_encode($castEvidence));
    }
}
