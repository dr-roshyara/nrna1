<?php

namespace Tests\Feature\Characterization\Phase1;

use App\Models\ElectionMembership;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Phase 1 — group 1, VOTING INTEGRITY (roadmap §5 Phase 1, SR §11a A, B, B2, C, D, H).
 *
 * CHARACTERIZATION: these tests pin CURRENT behaviour. A test named `known_gap_*`
 * asserts today's defective behaviour so that a later, separately authorised fix
 * turns it into the target assertion. Synthetic data, testing database only.
 * No production code is changed by Phase 1.
 */
class VotingIntegrityCharacterizationTest extends TestCase
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

    private function consumedCodes(): int
    {
        return DB::table('codes')->where('election_id', $this->election->id)->where('has_voted', true)->count();
    }

    // ── C: source of truth ───────────────────────────────────────────────────

    public function test_c_a_cast_sets_the_code_as_participation_record_and_the_voter_slug_projection(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $this->cast($voter, $slug, $code);

        $this->assertSame(1, $this->votes());
        $this->assertTrue((bool) $code->fresh()->has_voted, 'codes.has_voted = authoritative participation record');
        $slugRow = VoterSlug::withoutGlobalScopes()->find($slug->id);
        $this->assertTrue((bool) $slugRow->has_voted, 'voter_slugs.has_voted projection written');
        $this->assertSame('voted', $slugRow->status);
    }

    // ── D: projection defect (D9) ────────────────────────────────────────────

    public function test_d_known_gap_a_cast_does_not_write_election_memberships_has_voted(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $this->cast($voter, $slug, $code);

        $membership = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $this->election->id)->where('user_id', $voter->id)->first();

        $this->assertTrue((bool) $code->fresh()->has_voted, 'precondition: the voter has voted');
        $this->assertFalse((bool) $membership->has_voted, 'KNOWN GAP D9: election_memberships.has_voted stays false after a real vote');
        $this->assertNull($membership->voted_at, 'KNOWN GAP D9: election_memberships.voted_at stays null');
    }

    // ── A: duplicate votes and concurrency ───────────────────────────────────

    public function test_a_a_second_sequential_cast_by_the_same_voter_writes_no_second_vote(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $this->cast($voter, $slug, $code);
        $this->cast($voter, $slug, $code->fresh());

        $this->assertSame(1, $this->votes(), 'sequential re-submission is refused (has_voted re-read)');
        $this->assertSame(1, $this->consumedCodes());
    }

    public function test_a_a_held_slug_lock_refuses_a_concurrent_cast_on_the_same_slug(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $held = Cache::lock("voter_slug_transition:{$slug->id}", 10);
        $this->assertTrue($held->get(), 'precondition: simulate an in-flight cast holding the slug lock');

        $this->cast($voter, $slug, $code);

        $held->release();
        $this->assertSame(0, $this->votes(), 'the cache lock refuses the second request on the same slug');
        $this->assertFalse((bool) $code->fresh()->has_voted);
    }

    public function test_a_one_voter_slug_per_voter_and_election_makes_the_slug_lock_effectively_per_voter(): void
    {
        // voter_slugs has unique(election_id, user_id): a voter cannot hold a second slug for
        // the same election, so the cache lock key voter_slug_transition:{slug} is in effect
        // one lock per voter and election. (Hypothesis "lock is per slug, not per voter" refuted.)
        [$voter] = $this->voterReadyToCast();

        $this->expectException(\Illuminate\Database\UniqueConstraintViolationException::class);
        $this->secondSlugFor($voter);
    }

    public function test_a_structural_the_vote_path_takes_no_database_row_lock(): void
    {
        foreach (['app/Http/Controllers/VoteController.php', 'app/Http/Controllers/CodeController.php'] as $file) {
            $source = file_get_contents(base_path($file));
            $this->assertDoesNotMatchRegularExpression('/lockForUpdate|sharedLock|FOR UPDATE/i', $source,
                "KNOWN GAP (D4): {$file} takes no database row lock; double-vote protection is cache lock + re-read");
        }
    }

    // ── B2: a failed cast rolls back ─────────────────────────────────────────

    public function test_b2_a_failure_while_writing_the_vote_rolls_back_everything(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        // Throwing double: the vote write fails after its first insert (replaces the former
        // malformed-session trigger, removed by VE-01 ballot normalisation).
        $this->failTheVoteWriteAfterItsInsert();

        $this->actingAs($voter)
            ->withSession([
                'current_organisation_id' => $this->org->id,
                $code->session_name       => $this->wellFormedEmptyBallot(),
            ])
            ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $code->code_to_open_voting_form]);

        $this->assertSame(0, $this->votes(), 'no vote committed');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted not committed');
    }

    // ── H: aggregate invariant (never per code) ──────────────────────────────

    public function test_h_committed_votes_equal_consumed_codes_after_successful_and_failed_casts(): void
    {
        [$v1, $s1, $c1] = $this->voterReadyToCast('AAAA1111');
        [$v2, $s2, $c2] = $this->voterReadyToCast('BBBB2222');
        [$v3, $s3, $c3] = $this->voterReadyToCast('CCCC3333');

        $this->cast($v1, $s1, $c1);
        $this->cast($v2, $s2, $c2, 'WRONG999');   // refused: wrong code
        $this->cast($v3, $s3, $c3);

        $this->assertSame(2, $this->votes());
        $this->assertSame($this->votes(), $this->consumedCodes(), 'per election: count(votes) = count(codes WHERE has_voted)');
    }
}
