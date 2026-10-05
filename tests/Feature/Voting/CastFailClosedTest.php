<?php

namespace Tests\Feature\Voting;

use App\Models\Code;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * Phase 4 · E1 · R1 — the final cast fails closed (PBDIGIT-232).
 *
 * Plan: docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md §2.0, §4 R1 (rev 3)
 * Decision record: docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md
 * ADR_20261005_0950 §3.4 (fail-closed), I7.
 *
 * Transaction and lock ownership rule (§2.0):
 *   "Roll back only when a transaction is active, and release the cache lock only
 *    when this execution path actually acquired the lock."
 *
 * Four contracts:
 *   R1-A  denial BEFORE the lock, another holder owns it → reasoned refusal, holder keeps it
 *   R1-B  refused acquisition → the existing holder keeps its lock            (ownership guard)
 *   R1-C  denial AFTER acquisition → this path releases its own lock          (ownership guard)
 *   R1-D  an \Error after acquisition → rollback, generic failure, lock released
 * Every exit: no vote, `has_voted` unchanged, DB transaction level back at the test's
 * own baseline, never HTTP 500.
 *
 * The IP-binding exit is NOT covered: it is unreachable under the current schema
 * (no `users.voting_ip` column) — P4-D4 retired, PBDIGIT-234.
 *
 * Synthetic data only (Phase 1 cast fixtures).
 */
class CastFailClosedTest extends TestCase
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

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
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

    /** Four voted codes from the cast IP: the trust gate's IP-density limit denies (Phase 1 G2 setup). */
    private function exceedIpDensity(): void
    {
        foreach (range(1, 4) as $i) {
            Code::factory()->voted()->create([
                'organisation_id' => $this->org->id,
                'election_id'     => $this->election->id,
                'user_id'         => User::factory()->create(['organisation_id' => $this->org->id])->id,
                'client_ip'       => '127.0.0.1',
                'code1'           => 'IPCODE' . $i,
            ]);
        }
    }

    private function assertNothingConsumed(Code $code): void
    {
        $this->assertSame(0, $this->votes(), 'no vote is written');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is not consumed');
    }

    // ── R1-A: denial before the lock must not touch a lock it does not own ──

    public function test_r1_a_a_security_denial_before_the_lock_is_a_reasoned_refusal_and_leaves_another_holders_lock_held(): void
    {
        $this->exceedIpDensity();
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $other = Cache::lock($this->lockKey($slug), 10);
        $this->assertTrue($other->get(), 'precondition: another holder owns the slug lock');

        $baseline = DB::transactionLevel();
        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status(), 'a security denial is a reasoned refusal, never HTTP 500 (PBDIGIT-232)');
        $response->assertRedirect();
        $response->assertSessionHasErrors('vote');
        $this->assertNothingConsumed($code);
        $this->assertSame($baseline, DB::transactionLevel(), 'the cast transaction is rolled back — no leaked transaction level');
        $this->assertFalse($this->lockIsFree($slug), "the other holder's lock is still held — the denied path did not release it");
        $this->assertTrue($other->release(), 'the lock is still owned by the other holder');
    }

    // ── R1-B: refused acquisition — the existing holder keeps the lock ──────

    public function test_r1_b_a_refused_lock_acquisition_leaves_the_existing_holders_lock_held(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $other = Cache::lock($this->lockKey($slug), 10);
        $this->assertTrue($other->get(), 'precondition: another holder owns the slug lock');

        $baseline = DB::transactionLevel();
        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertNothingConsumed($code);
        $this->assertSame($baseline, DB::transactionLevel(), 'the cast transaction is rolled back');
        $this->assertFalse($this->lockIsFree($slug), "the existing holder's lock is still held");
        $this->assertTrue($other->release(), 'the lock is still owned by the existing holder');
    }

    // ── R1-C: denial after acquisition — this path releases its own lock ────

    public function test_r1_c_a_denial_after_lock_acquisition_releases_this_paths_own_lock(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $baseline = DB::transactionLevel();
        $response = $this->cast($voter, $slug, $code, 'WRONG999');

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasErrors('voting_code');
        $this->assertNothingConsumed($code);
        $this->assertSame($baseline, DB::transactionLevel(), 'the cast transaction is rolled back');
        $this->assertTrue($this->lockIsFree($slug), 'the lock this path acquired is released');
    }

    // ── R1-D: an \Error (not an \Exception) after acquisition ───────────────

    public function test_r1_d_an_error_after_lock_acquisition_rolls_back_fails_generically_and_releases_the_lock(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        // The notification dispatcher (resolved after the vote insert, inside the cast
        // transaction) raises an \Error — not an \Exception. Test double of an external
        // dependency only; no production seam.
        $this->app->instance(ChannelManager::class, new class {
            public function send($notifiables, $notification): void
            {
                throw new \Error('injected failure after lock acquisition');
            }

            public function sendNow($notifiables, $notification, ?array $channels = null): void
            {
                throw new \Error('injected failure after lock acquisition');
            }
        });

        $baseline = DB::transactionLevel();
        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status(), 'an \Error is caught fail-closed, never HTTP 500');
        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertNothingConsumed($code);
        $this->assertSame(0, DB::table('receipt_codes')->where('election_id', $this->election->id)->count(), 'no receipt survives the rollback');
        $this->assertSame($baseline, DB::transactionLevel(), 'the cast transaction is rolled back');
        $this->assertTrue($this->lockIsFree($slug), 'the lock this path acquired is released');
    }
}
