<?php

namespace Tests\Feature\Characterization\Phase1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Phase 1 — group 1A, BALLOT ANONYMITY (roadmap Phase 1A, SR §8a, §11a K1 K6 K7/J K8 K9).
 *
 * Question: does a deterministic or practical voter → ballot path exist today?
 * Invariant under test: the system may know that a voter voted, never which ballot.
 *
 * ⚠ SYNTHETIC DATA ONLY (roadmap P7). Every voter, code and vote here is created by
 * the test in the testing database. These linkage checks must never be run against
 * the development database or any real election. No production code is changed.
 *
 * `known_gap_*` tests assert today's (anonymity-breaking) behaviour; the Phase 2 fix,
 * when authorised, turns each into the target assertion.
 */
class BallotAnonymityCharacterizationTest extends TestCase
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
        Carbon::setTestNow();
        $this->tearDownCast();
        parent::tearDown();
    }

    private function votesOfElection()
    {
        return DB::table('votes')->where('election_id', $this->election->id)->get(['id', 'vote_hash', 'cast_at', 'created_at']);
    }

    /** Three synthetic voters cast one after another; returns [[voter, slug, code], …] in cast order. */
    private function threeVotersCast(): array
    {
        $cast = [];
        foreach (['AAAA1111', 'BBBB2222', 'CCCC3333'] as $i => $code1) {
            Carbon::setTestNow(now()->startOfMinute()->addMinutes(5 * $i));   // distinct wall-clock times
            [$voter, $slug, $code] = $this->voterReadyToCast($code1);
            $this->cast($voter, $slug, $code);
            $cast[] = [$voter, $slug, $code->fresh()];
        }
        Carbon::setTestNow();

        $this->assertSame(3, $this->votesOfElection()->count(), 'precondition: three votes cast');

        return $cast;
    }

    // ── K1 / A5: the vote_hash is computable from voter-side data + configuration ──

    public function test_k1_known_gap_each_voters_ballot_is_found_by_recomputing_vote_hash_from_voter_side_data(): void
    {
        $cast  = $this->threeVotersCast();
        $votes = $this->votesOfElection();
        $found = [];

        foreach ($cast as [$voter, $slug, $code]) {
            // Inputs available on the voter side or in configuration only:
            $this->assertNotNull($code->code_to_open_voting_form, 'the plain-text code value is still stored after voting');
            // codes has no vote_completed_at column (markUserAsVoted's 'vote_completed_at' is dropped:
            // not fillable, no column); the stored voter-side completion time is code_to_save_vote_used_at.
            $this->assertNotNull($code->code_to_save_vote_used_at, 'the voter-side completion time is stored');
            $t = Carbon::parse($code->code_to_save_vote_used_at)->timestamp;

            $matches = [];
            foreach ([0, -1, 1] as $delta) {   // ±1 second search window
                $candidate = hash('sha256', $code->id . $this->election->id . $code->code_to_open_voting_form . ($t + $delta) . config('app.vote_salt', ''));
                foreach ($votes as $vote) {
                    if (hash_equals($vote->vote_hash, $candidate)) {
                        $matches[] = ['vote' => $vote->id, 'delta' => $delta];
                    }
                }
            }

            $this->assertCount(1, $matches, 'KNOWN GAP A5: exactly one ballot is derived for this voter from code.id, election.id, code value, completion second and vote_salt');
            $this->assertSame(0, $matches[0]['delta'], 'the voter-side completion second equals the hashed cast second (no search needed in this run)');
            $found[$voter->id] = $matches[0]['vote'];
        }

        $this->assertCount(3, array_unique($found), 'KNOWN GAP A5: three voters map to three distinct ballots — a complete voter → ballot mapping');
    }

    // ── K6 / A2: timestamp correlation ───────────────────────────────────────

    public function test_k6_known_gap_voter_side_completion_time_matches_exactly_one_ballot_time(): void
    {
        $cast  = $this->threeVotersCast();
        $votes = $this->votesOfElection();

        foreach ($cast as [$voter, $slug, $code]) {
            $completed = Carbon::parse($code->code_to_save_vote_used_at)->timestamp;
            $sameSecond = $votes->filter(fn ($v) => abs(Carbon::parse($v->cast_at)->timestamp - $completed) <= 1);

            $this->assertCount(1, $sameSecond,
                'KNOWN GAP A2: codes.code_to_save_vote_used_at (voter side) joins to exactly one votes.cast_at — no hash or IP needed');

            $updated = Carbon::parse($code->updated_at)->timestamp;
            $this->assertCount(1, $votes->filter(fn ($v) => abs(Carbon::parse($v->created_at)->timestamp - $updated) <= 1),
                'KNOWN GAP A2: codes.updated_at joins to exactly one votes.created_at as well');
        }
    }

    // ── K7 / J / A4: vote identifiers carry creation time and order ──────────

    public function test_k7_known_gap_vote_ids_sort_in_cast_order_and_encode_the_creation_time(): void
    {
        $before = microtime(true);
        $cast   = $this->threeVotersCast();
        $after  = microtime(true);

        $ids = DB::table('votes')->where('election_id', $this->election->id)->orderBy('cast_at')->pluck('id')->all();
        $sorted = $ids;
        sort($sorted, SORT_STRING);
        $this->assertSame($sorted, $ids, 'KNOWN GAP A4: lexical id order equals cast order (time-ordered UUIDs)');

        foreach ($ids as $id) {
            // The first 12 hex digits carry the creation time in both id formats:
            // - UUIDv7 (HasUuids since Laravel 12; version digit 7): unix milliseconds.
            // - Str::orderedUuid (Laravel 11; Ramsey CombGenerator + TimestampFirstCombCodec):
            //   unix_seconds * 100000 + 10-µs fraction.
            $hex     = str_replace('-', '', $id);
            $divisor = $hex[12] === '7' ? 1000 : 100000;
            $decoded = hexdec(substr($hex, 0, 12)) / $divisor;
            $this->assertGreaterThanOrEqual($before - 1, $decoded, 'KNOWN GAP A4: creation time recovered from the vote id');
            $this->assertLessThanOrEqual($after + 1, $decoded, 'KNOWN GAP A4: creation time recovered from the vote id');
        }
    }

    // ── K8 / A1: logs carry correlating values ───────────────────────────────

    public function test_k8_known_gap_the_cast_logs_the_vote_id_together_with_ip_and_slug(): void
    {
        $this->captureLogs();
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code);
        $voteId = DB::table('votes')->where('election_id', $this->election->id)->value('id');

        $voteIdWithIp = array_filter($this->logsMentioning($voteId), fn ($e) => array_key_exists('ip', $e['context']));
        $this->assertNotEmpty($voteIdWithIp, 'KNOWN GAP A1: a log entry carries the vote id together with the client IP');

        $voteIdWithSlug = array_filter($this->logsMentioning($voteId), fn ($e) => ($e['context']['voter_slug_id'] ?? null) === $slug->id);
        $this->assertNotEmpty($voteIdWithSlug, 'KNOWN GAP: a log entry carries the vote id together with the voter slug id (slug → user)');

        $this->assertEmpty(array_filter($this->logged, fn ($e) => $e['message'] === 'private_key'),
            'PBDIGIT-241: the receipt secret is no longer written to the log (was a known gap)');

        $this->assertNotEmpty($this->logsMentioning($voter->id), 'the same request also logs the voter\'s user id');
    }

    // ── K9: database identifiers ─────────────────────────────────────────────

    public function test_k9_known_gap_the_voters_own_slug_step_record_stores_the_vote_id(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code);
        $voteId = DB::table('votes')->where('election_id', $this->election->id)->value('id');

        $step5 = DB::table('voter_slug_steps')->where('voter_slug_id', $slug->id)->where('step', 5)->first();

        $this->assertNotNull($step5, 'step 5 is recorded for the voter\'s slug');
        $this->assertStringContainsString($voteId, (string) $step5->step_data,
            'KNOWN GAP (stored link): voter_slug_steps.step_data of the voter\'s slug stores the vote id — voter_slugs.user_id → vote');
        $this->assertSame($voter->id, DB::table('voter_slugs')->where('id', $slug->id)->value('user_id'));
    }

    public function test_k9_the_vote_row_itself_holds_no_voter_or_code_reference(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code);

        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('votes');
        foreach (['user_id', 'code_id', 'voter_id', 'membership_id', 'voter_slug_id'] as $forbidden) {
            $this->assertNotContains($forbidden, $columns, "votes has no {$forbidden} column");
        }
        $row = (array) DB::table('votes')->where('election_id', $this->election->id)->first();
        $this->assertStringNotContainsString($voter->id, json_encode($row), 'the vote row contains no voter id');
        $this->assertStringNotContainsString($code->id, json_encode($row), 'the vote row contains no code id in clear');
    }
}
