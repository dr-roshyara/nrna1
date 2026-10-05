<?php

namespace Tests\Feature\Characterization\Phase1;

use App\Models\Code;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Phase 1 — groups 1D FINAL DENIAL (G) and 1E IGNORED PRE-CHECK (I)
 * (roadmap Phase 1D/1E, SR §8 D1–D3, §11a G, I).
 *
 * Does the final vote fail closed? Is any state consumed after a denial? Is the slug
 * lock released? Synthetic data only; production behaviour is observed, not changed.
 */
class FinalDenialCharacterizationTest extends TestCase
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

    private function lockIsFree(string $slugId): bool
    {
        $probe = Cache::lock("voter_slug_transition:{$slugId}", 10);
        $free = $probe->get();
        if ($free) {
            $probe->release();
        }

        return $free;
    }

    // ── G1: wrong code ───────────────────────────────────────────────────────

    public function test_g1_a_wrong_code_is_refused_without_consuming_state_and_releases_the_lock(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->cast($voter, $slug, $code, 'WRONG999');

        $response->assertSessionHasErrors('voting_code');
        $this->assertSame(0, $this->votes(), 'no vote');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted not consumed');
        $this->assertTrue($this->lockIsFree($slug->id), 'slug lock released');
        $this->assertSame(0, DB::table('receipt_codes')->where('election_id', $this->election->id)->count(), 'no receipt');
    }

    // ── G2: security (trust / IP-density) denial ─────────────────────────────

    public function test_g2_known_gap_the_security_denial_branch_ends_in_an_uncaught_error(): void
    {
        // Four voted codes from the same IP: the trust gate's IP-density limit for an
        // "Unverified" voter is intdiv(max_votes_per_ip = 6, 2) = 3 → 4 > 3 → denied.
        foreach (range(1, 4) as $i) {
            Code::factory()->voted()->create([
                'organisation_id' => $this->org->id,
                'election_id'     => $this->election->id,
                'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
                'client_ip'       => '127.0.0.1',
                'code1'           => 'IPCODE' . $i,
            ]);
        }
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->cast($voter, $slug, $code);

        $this->assertSame(0, $this->votes(), 'no vote is written (fails closed in effect)');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted not consumed');
        $this->assertSame(500, $response->status(),
            'KNOWN GAP D3: the denial branch passes a UUID user id to trackSovereigntyDivergence(?int) → TypeError → HTTP 500 instead of a denial message');
        $this->assertTrue($this->lockIsFree($slug->id),
            'the trust gate (store :1522) runs BEFORE the slug lock is taken (:1644) — the lock is not involved on this path '
            . '(the TypeError is raised before DB::rollBack(); the open transaction ends with the request)');
    }

    // ── I: vote_post_check result ignored (D1) ───────────────────────────────

    public function test_i_known_gap_the_pre_check_result_is_never_read_by_store(): void
    {
        $source = file_get_contents(base_path('app/Http/Controllers/VoteController.php'));
        preg_match('/public function store\(Request \$request\)(.*?)\n    \}\n/s', $source, $m);
        $store = $m[1] ?? $source;

        $this->assertSame(1, substr_count($store, '$pre_check'),
            'KNOWN GAP D1: $pre_check = vote_post_check(...) is assigned once and never read in store()');
    }

    public function test_i_a_failing_pre_check_condition_still_blocks_through_a_missing_view_exception(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->captureLogs();

        // No ballot in the session → vote_post_check's "vote missing" branch renders
        // view('components.error_message'), which does not exist → exception → generic error.
        $response = $this->actingAs($voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $code->code_to_open_voting_form]);

        $this->assertSame(0, $this->votes(), 'no vote');
        $this->assertFalse((bool) $code->fresh()->has_voted);
        $this->assertNotEmpty($this->logsMentioning('View [components.error_message] not found'),
            'KNOWN GAP D1/D10: the pre-check denies only by crashing on a missing view, not by its returned result');
        $response->assertSessionHasErrors();
    }
}
