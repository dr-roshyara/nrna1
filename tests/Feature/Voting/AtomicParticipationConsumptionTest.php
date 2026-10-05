<?php

namespace Tests\Feature\Voting;

use App\Models\DemoCode;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Phase 4 · E4 · R4 / R5 / R6 — atomic participation consumption (PBDIGIT-230).
 *
 * Plan: docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md §2.2 E4, §4 R4–R6, §5, §7
 * ADR_20261005_0950 §3.2 / §3.4, I1, I6, I7, I10. Decision record §4 (CAS evidence wording).
 *
 * The final cast of a REAL election consumes participation by ONE conditional update —
 *   UPDATE codes SET has_voted = true WHERE id = :code AND has_voted = false
 * — executed BEFORE the vote insert, inside the cast transaction. Exactly one affected row
 * lets the cast continue; zero rows means participation was already consumed: the cast is
 * refused, no vote is inserted. The update writes has_voted only (no new voter-side
 * timestamp, §7).
 *
 * What these tests prove: the production ordering, the shape of the conditional update
 * (keyed to one code, guarded by has_voted = false) and the 0-rows refusal, using an
 * INTERLEAVED writer inside the same transaction. They do NOT reproduce a real parallel
 * race (single-process PHPUnit); the guarantee under true concurrency rests on PostgreSQL
 * row locking for a single-row conditional UPDATE — reasoned, not tested.
 *
 * Synthetic data only.
 */
class AtomicParticipationConsumptionTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

    /** @var list<array{sql:string,bindings:array}> */
    private array $queries = [];

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

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function recordQueries(): void
    {
        DB::listen(function (QueryExecuted $query) {
            $this->queries[] = ['sql' => $query->sql, 'bindings' => $query->bindings];
        });
    }

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    /**
     * Indexes of conditional-consumption updates on $table: an UPDATE whose WHERE clause
     * guards on has_voted (the compare-and-set). Today's unconditional markUserAsVoted()
     * update (WHERE "id" = ? only) does not match.
     */
    private function conditionalConsumptions(string $table = 'codes'): array
    {
        $found = [];
        foreach ($this->queries as $i => $q) {
            if (preg_match('/^update "' . $table . '" set (.+) where (.+)$/s', $q['sql'], $m)
                && str_contains($m[2], '"has_voted" = ?')) {
                $found[] = $i;
            }
        }

        return $found;
    }

    private function firstVoteInsert(): ?int
    {
        foreach ($this->queries as $i => $q) {
            if (str_starts_with($q['sql'], 'insert into "votes"')) {
                return $i;
            }
        }

        return null;
    }

    // ── R4-1: consumption precedes the insert, exactly once ─────────────────

    public function test_r4_1_a_valid_real_cast_consumes_participation_once_and_before_the_vote_insert(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->recordQueries();

        $response = $this->cast($voter, $slug, $code);

        $response->assertSessionHasNoErrors();
        $this->assertSame(1, $this->votes(), 'the vote is written');
        $this->assertTrue((bool) $code->fresh()->has_voted, 'participation is consumed');

        $consumptions = $this->conditionalConsumptions();
        $insert = $this->firstVoteInsert();

        $this->assertCount(1, $consumptions, 'exactly one conditional has_voted false → true update per committed cast');
        $this->assertNotNull($insert, 'precondition: the vote insert was observed');
        $this->assertLessThan($insert, $consumptions[0], 'participation is consumed BEFORE the vote is inserted (I6)');
    }

    // ── R4-2: already consumed → zero rows → refused, no vote ───────────────

    public function test_r4_2_participation_consumed_between_the_fact_read_and_the_cast_is_refused_without_a_vote(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        // Interleaved writer: right after store() has read the code's facts (fresh select by
        // id, has_voted = false), the same row is consumed — as a competing cast would. The
        // conditional update must then affect zero rows. Same connection and transaction:
        // an interleaving, not a parallel race.
        $consumed = false;
        DB::listen(function (QueryExecuted $query) use ($code, &$consumed) {
            if (! $consumed
                && preg_match('/^select \* from "codes" where "id" = \? limit 1$/', $query->sql)
                && in_array($code->id, $query->bindings, true)) {
                $consumed = true;
                DB::table('codes')->where('id', $code->id)->update(['has_voted' => true]);
            }
        });

        $response = $this->cast($voter, $slug, $code);

        $this->assertTrue($consumed, 'precondition: the interleaved consumption happened after the fact read');
        $this->assertNotSame(500, $response->status());
        $response->assertRedirect();
        $response->assertSessionHasErrors('vote');
        $this->assertSame(0, $this->votes(), 'no vote is inserted when the conditional update affects zero rows');
    }

    // ── R4-3: the update is keyed to this one code and guarded by has_voted = false ──

    public function test_r4_3_the_consumption_update_targets_this_code_only_and_requires_has_voted_false(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->recordQueries();

        $this->cast($voter, $slug, $code);

        $consumptions = $this->conditionalConsumptions();
        $this->assertCount(1, $consumptions, 'one conditional consumption');

        $q = $this->queries[$consumptions[0]];
        preg_match('/ where (.+)$/s', $q['sql'], $where);
        $this->assertStringContainsString('"id" = ?', $where[1], 'keyed to one code row — at most one row can change');
        $this->assertContains($code->id, $q['bindings'], 'keyed to THIS voter\'s code');
        $this->assertTrue(
            in_array(false, $q['bindings'], true) || in_array(0, $q['bindings'], true),
            'guarded by has_voted = false — an already consumed code changes zero rows'
        );
    }

    // ── R4-4: a failure AFTER a successful consumption rolls the consumption back ──

    public function test_r4_4_a_failure_after_a_successful_consumption_rolls_back_has_voted_and_the_vote(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->recordQueries();

        // Malformed ballot session (no regional selections): save_vote() throws after its
        // first vote insert (Phase 1 B2 setup) — i.e. after the consumption succeeded.
        $response = $this->actingAs($voter)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                $code->session_name       => ['national_selected_candidates' => [], 'no_vote_posts' => []],
            ])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $code->code_to_open_voting_form]);

        // Precondition: the consumption ran and succeeded (the cast only continues to the
        // vote insert on exactly one affected row), and the failure came after it.
        $consumptions = $this->conditionalConsumptions();
        $insert = $this->firstVoteInsert();
        $this->assertCount(1, $consumptions, 'the conditional consumption ran');
        $this->assertNotNull($insert, 'the cast continued to the vote insert — the consumption affected one row');
        $this->assertLessThan($insert, $consumptions[0], 'the failure happened after the consumption');

        // The whole cast transaction is rolled back (E1 failCastClosed()).
        $this->assertNotSame(500, $response->status());
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is back to false — the consumption was rolled back');
        $this->assertSame(0, $this->votes(), 'no vote remains');
    }

    // ── R5: no new correlation through the consumption ──────────────────────

    public function test_r5_the_consumption_update_writes_has_voted_only(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->recordQueries();

        $this->cast($voter, $slug, $code);

        $consumptions = $this->conditionalConsumptions();
        $this->assertCount(1, $consumptions, 'one conditional consumption');

        preg_match('/^update "codes" set (.+) where /s', $this->queries[$consumptions[0]]['sql'], $set);
        $this->assertSame('"has_voted" = ?', trim($set[1]),
            'the compare-and-set writes has_voted only — no new voter-side timestamp (plan §7, interim rule point 2)');
    }

    public function test_r5_the_vote_row_holds_no_value_of_the_voter_or_the_code(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $this->cast($voter, $slug, $code);

        $vote = (array) DB::table('votes')->where('election_id', $this->election->id)->first();
        $this->assertNotEmpty($vote, 'precondition: the vote exists');

        foreach ([$voter->id, $code->id, $slug->id, $code->code_to_open_voting_form] as $voterSide) {
            $this->assertNotContains($voterSide, array_values($vote), 'the ballot row carries no voter-side identifier (I10)');
        }
    }

    // ── R6: demo stays outside the real-election CAS contract (guard) ───────

    public function test_r6_a_demo_cast_through_store_issues_no_conditional_consumption(): void
    {
        // A demo election in the voting phase (the plain demo factory state is "setup not
        // started", which the vote.eligibility middleware refuses before store()).
        $demo = tap(ElectionScenarioFactory::votingActive($this->org))->update(['type' => 'demo'])->fresh();
        $voter = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);
        $slug = VoterSlug::factory()->create([
            'user_id' => $voter->id, 'organisation_id' => $this->org->id, 'election_id' => $demo->id,
            'is_active' => true, 'expires_at' => now()->addHours(2),
        ]);
        $code = DemoCode::factory()->create([
            'organisation_id' => $this->org->id, 'user_id' => $voter->id, 'election_id' => $demo->id,
            'code_to_open_voting_form' => 'DEMO1234', 'can_vote_now' => 1, 'has_agreed_to_vote' => 1,
            'session_name' => 'vote_data_' . $voter->id,
        ]);
        $this->recordQueries();
        $this->captureLogs();

        $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $this->org->id, $code->session_name => $this->ballotSessionData()])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => 'DEMO1234']);

        $this->assertNotEmpty($this->logsMentioning('Vote final submission started'), 'precondition: the demo request reached store()');
        $this->assertSame([], $this->conditionalConsumptions('codes'), 'no conditional consumption on codes for a demo election');
        $this->assertSame([], $this->conditionalConsumptions('demo_codes'), 'no conditional consumption on demo_codes (Q4)');
    }
}
