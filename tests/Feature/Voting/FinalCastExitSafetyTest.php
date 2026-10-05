<?php

namespace Tests\Feature\Voting;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Models\Code;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * PBDIGIT-243 — RED: final-cast exits that return without failCastClosed() (F-V9).
 *
 * Plan: docs/plans/20261005-2139-final-cast-exit-safety-red-plan.md
 * ADR_20261005_0950 §3.4 (fail-closed), I7.
 *
 * Each exit has its own invariant (PO 2026-10-05):
 *   Exit A  fresh membership denied — after BEGIN, before the lock:
 *           transaction back at the caller's level · lock not taken (another holder keeps it) · nothing consumed
 *   Exit B  validateVotingIpWithResponse() denies — after BEGIN and the lock, before the CAS:
 *           transaction back at the caller's level · this path's lock released · nothing consumed
 * Every test first proves its exit was the one taken (its own response).
 *
 * Exit B is latent (no users.voting_ip column — PBDIGIT-234). Test-only double: the authenticated
 * user instance carries an in-memory voting_ip different from the request IP; nothing is saved.
 *
 * Synthetic data only (Phase 1 cast fixtures). No production change.
 */
class FinalCastExitSafetyTest extends TestCase
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

    private function lockKey(VoterSlug $slug): string
    {
        return "voter_slug_transition:{$slug->id}";
    }

    /** True when nobody holds the slug lock (probe acquires and immediately releases). */
    private function lockIsFree(VoterSlug $slug): bool
    {
        $probe = Cache::lock($this->lockKey($slug), 10);
        $free = $probe->get();
        if ($free) {
            $probe->release();
        }

        return $free;
    }

    private function assertNothingConsumed(Code $code): void
    {
        $this->assertSame(0, DB::table('votes')->where('election_id', $this->election->id)->count(), 'no vote is written');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is not consumed');
    }

    // ── Exit A: fresh membership denied (after BEGIN, before the lock) ───────

    public function test_exit_a_a_fresh_membership_denial_leaves_no_open_transaction_and_does_not_touch_the_lock(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        // The route middleware's cached standing says "allowed" …
        app(VotingEntitlementQuery::class)->standingOf($voter->id, $this->election->id);
        // … then the membership ends without model events, so only the fresh check in store() sees it.
        DB::table('election_memberships')
            ->where('user_id', $voter->id)->where('election_id', $this->election->id)
            ->update(['status' => 'inactive']);

        $other = Cache::lock($this->lockKey($slug), 10);
        $this->assertTrue($other->get(), 'precondition: another holder owns the slug lock');

        $baseline = DB::transactionLevel();
        $response = $this->cast($voter, $slug, $code);

        // Precondition: Exit A was taken (its own redirect and message).
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error', 'You are not assigned to vote in this election.');

        $this->assertNotSame(500, $response->status());
        $this->assertSame($baseline, DB::transactionLevel(), 'Exit A: the cast transaction is rolled back — no leaked transaction level');
        $this->assertFalse($this->lockIsFree($slug), 'Exit A: the lock was never taken here — another holder keeps it');
        $this->assertNothingConsumed($code);

        $other->release();
    }

    // ── Exit B: IP-binding response (after BEGIN and the lock, before the CAS) ──

    /** Drive Exit B and prove it was the exit taken. Returns [code, slug, transaction baseline]. */
    private function castThroughExitB(): array
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        // Test-only double: an in-memory registered IP that differs from the request IP (127.0.0.1).
        $voter->voting_ip = '10.9.9.9';

        $baseline = DB::transactionLevel();
        $response = $this->cast($voter, $slug, $code);

        // Precondition: Exit B was taken (the IP-mismatch denial page).
        $page = $response->viewData('page');
        $this->assertSame('Vote/VoteDenied', $page['component'] ?? null, 'precondition: Exit B (IP-binding denial) was taken');
        $this->assertSame('IP Address Mismatch', $page['props']['denial_type'] ?? null);
        $this->assertNotSame(500, $response->status());
        $this->assertNothingConsumed($code);

        return [$code, $slug, $baseline];
    }

    public function test_exit_b_an_ip_binding_denial_rolls_back_the_cast_transaction(): void
    {
        [, , $baseline] = $this->castThroughExitB();

        $this->assertSame($baseline, DB::transactionLevel(), 'Exit B: the cast transaction is rolled back — no leaked transaction level');
    }

    public function test_exit_b_an_ip_binding_denial_releases_the_lock_this_path_acquired(): void
    {
        [, $slug] = $this->castThroughExitB();

        $this->assertTrue($this->lockIsFree($slug), 'Exit B: the lock this path acquired is released');
    }
}
