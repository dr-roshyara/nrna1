<?php

namespace Tests\Feature\Characterization\Phase1;

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

    // ── G2: retired (Phase 4 E1 GREEN) — the known gap D3 (HTTP 500 on a security
    //    denial) is fixed; the contract now lives in Tests\Feature\Voting\CastFailClosedTest::test_r1_a_…

    // ── I: vote_post_check result ignored (D1) ───────────────────────────────

    public function test_i_known_gap_the_pre_check_result_is_never_read_by_store(): void
    {
        $source = file_get_contents(base_path('app/Http/Controllers/VoteController.php'));
        preg_match('/public function store\(Request \$request\)(.*?)\n    \}\n/s', $source, $m);
        $store = $m[1] ?? $source;

        $this->assertSame(1, substr_count($store, '$pre_check'),
            'KNOWN GAP D1: $pre_check = vote_post_check(...) is assigned once and never read in store()');
    }

    // ── I (missing-view mechanism): retired (Phase 4 E3 GREEN) — a real cast without a ballot
    //    is now refused by forCast() as BallotMissing before the legacy vote_post_check()
    //    missing-view failure can occur; the contract lives in
    //    Tests\Feature\Voting\CastDecisionEnforcementTest::test_r3_1_…
}
