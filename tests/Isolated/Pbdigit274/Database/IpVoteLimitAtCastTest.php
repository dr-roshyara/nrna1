<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use App\Services\Voting\IpVoteLimit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-288 items 1–4 (Human PO 2026-10-13): the per-IP vote limit at the vote itself, over the real cast route.
 *
 * 1. The limit (IpVoteLimit) is checked when the vote is cast, not only at start, code entry and verification.
 * 2. The check and the vote run under a lock for this election and network, so votes from one network are taken
 *    one at a time; a vote that cannot get the lock in time is refused safely (no vote) and may be retried.
 * 3. The IP counted is the IP at the vote: the cast records it on the voter's code row (codes.client_ip).
 * 4. IPv6 addresses count with their /64 network.
 *
 * Earlier votes from a network are other voters' completed code rows with that client_ip. At most one earlier vote
 * per address is used, so the separate constitutional limit (trust policy) never decides these cases.
 * Isolated database only; committed data, removed afterwards. The audit service is stubbed (no files).
 */
final class IpVoteLimitAtCastTest extends IsolatedCommittedDatabaseTestCase
{
    private const LIMIT_REACHED = 'The maximum of 1 vote(s) from your network has been reached.';

    private Organisation $org;
    private Election $election;
    private string $postId;
    private string $candidacyId;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        config(['voting.time_in_minutes' => 30, 'app.max_use_clientIP' => '1']);
        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd288-cast-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-288 cast limit '.uniqid(), 'slug' => 'pbd288-cast-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
            'ip_restriction_enabled' => false, 'ip_whitelist' => null,
        ]);
        $this->postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $this->postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-288 post', 'required_number' => 1, 'is_national_wide' => true, 'created_at' => now(), 'updated_at' => now()]);
        $this->candidacyId = (string) Str::uuid();
        DB::table('candidacies')->insert(['id' => $this->candidacyId, 'organisation_id' => $this->org->id, 'post_id' => $this->postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);
    }

    protected function tearDown(): void
    {
        try {
            DB::purge('pbd288_lock_holder');
            $voteIds = DB::table('votes')->where('election_id', $this->election->id)->pluck('id');
            DB::table('results')->whereIn('vote_id', $voteIds)->delete();
            DB::table('votes')->where('election_id', $this->election->id)->delete();
            foreach (['receipt_codes', 'voter_slug_steps', 'voter_slugs', 'codes', 'election_memberships'] as $table) {
                DB::table($table)->where('election_id', $this->election->id)->delete();
            }
            DB::table('candidacies')->where('post_id', $this->postId)->delete();
            DB::table('posts')->where('id', $this->postId)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('elections')->where('id', $this->election->id)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    // ── fixtures ───────────────────────────────────────────────────────────────────────────────────────

    private function user(): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null,
            'organisation_id' => $this->org->id]);
        $this->users[] = $user->id;
        DB::table('user_organisation_roles')->insertOrIgnore(['id' => (string) Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);

        return $user;
    }

    /** An entitled voter whose code was entered from $codeEntryIp, with a voting link at the verification step. */
    private function voter(string $codeEntryIp): array
    {
        $user = $this->user();
        $code = strtoupper(Str::random(8));
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'code1' => (string) random_int(100000, 999999), 'code_to_open_voting_form' => $code,
            'code_to_save_vote' => 'SAVE1234', 'is_code_to_open_voting_form_usable' => 0, 'code_to_open_voting_form_used_at' => now(),
            'is_code_to_save_vote_usable' => 1, 'can_vote_now' => 1, 'has_voted' => false, 'vote_submitted' => 0,
            'has_agreed_to_vote' => 1, 'client_ip' => $codeEntryIp, 'session_name' => 'vote_data_'.$user->id, 'voting_time_in_minutes' => 30,
            'created_at' => now(), 'updated_at' => now()]);
        $slug = Str::random(40);
        DB::table('voter_slugs')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'slug' => $slug, 'is_active' => true, 'has_voted' => false, 'status' => 'active',
            'current_step' => 4, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30)]);

        return ['user' => $user, 'slug' => $slug, 'code' => $code];
    }

    /** A completed vote by another voter, counted under $ip. */
    private function earlierVoteFrom(string $ip): void
    {
        $other = $this->user();
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'user_id' => $other->id, 'code1' => (string) random_int(100000, 999999),
            'has_voted' => true, 'client_ip' => $ip, 'created_at' => now(), 'updated_at' => now()]);
    }

    private function castFrom(array $v, string $ip): TestResponse
    {
        $ballot = ['national_selected_candidates' => [['post_id' => $this->postId, 'no_vote' => false,
            'candidates' => [['candidacy_id' => $this->candidacyId]]]], 'regional_selected_candidates' => [], 'no_vote_posts' => []];

        return $this->actingAs($v['user'])
            ->withServerVariables(['REMOTE_ADDR' => $ip])
            ->withSession(['current_organisation_id' => $this->org->id, 'vote_data_'.$v['user']->id => $ballot])
            ->post(route('slug.vote.store', ['vslug' => $v['slug']]), ['voting_code' => $v['code']]);
    }

    private function counted(TestResponse $response): bool
    {
        return $response->isRedirect(route('vote.verify_to_show')) && session()->has('success');
    }

    private function errorsText(): string
    {
        $errors = session('errors');

        return trim(($errors ? implode(' ', $errors->all()) : '').' '.(string) session('error'));
    }

    private function codeOf(array $v): object
    {
        return DB::table('codes')->where('user_id', $v['user']->id)->where('election_id', $this->election->id)->first();
    }

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    private function assertRefusedWithoutVote(TestResponse $response, array $v, string $reason, string $case): void
    {
        $this->assertFalse($this->counted($response), "{$case}: refused");
        $this->assertStringContainsString($reason, $this->errorsText(), "{$case}: for the right reason");
        $this->assertFalse((bool) $this->codeOf($v)->has_voted, "{$case}: participation not consumed");
    }

    // ── 1: the limit is checked at the vote ────────────────────────────────────────────────────────────────

    public function test_the_limit_is_checked_at_the_vote_and_refuses_with_a_readable_reason(): void
    {
        $voter = $this->voter('203.0.113.10');   // passed the earlier steps while the network was below the limit
        $this->earlierVoteFrom('203.0.113.10');  // then another vote from the network completed

        $this->assertRefusedWithoutVote($this->castFrom($voter, '203.0.113.10'), $voter, self::LIMIT_REACHED, 'limit reached at the vote');
        $this->assertSame(0, $this->votes(), 'no vote persisted');
    }

    public function test_below_the_limit_the_vote_counts(): void
    {
        config(['app.max_use_clientIP' => '2']);
        $voter = $this->voter('203.0.113.11');
        $this->earlierVoteFrom('203.0.113.11');

        $this->assertTrue($this->counted($this->castFrom($voter, '203.0.113.11')), 'one earlier vote is below the limit of 2: '.$this->errorsText());
        $this->assertSame(1, $this->votes());
    }

    // ── 3: the IP at the vote is recorded and counted ──────────────────────────────────────────────────────

    public function test_the_ip_at_the_vote_is_recorded_on_the_code_row(): void
    {
        $voter = $this->voter('203.0.113.20');

        $this->assertTrue($this->counted($this->castFrom($voter, '198.51.100.20')), 'counted: '.$this->errorsText());
        $this->assertSame('198.51.100.20', $this->codeOf($voter)->client_ip, 'the IP at the vote, not the code-entry IP');
    }

    public function test_a_voter_who_changed_network_is_judged_by_the_ip_at_the_vote(): void
    {
        $this->earlierVoteFrom('198.51.100.30');

        $moved = $this->voter('203.0.113.30');   // entered the code elsewhere, votes from the full network
        $this->assertRefusedWithoutVote($this->castFrom($moved, '198.51.100.30'), $moved, self::LIMIT_REACHED, 'judged by the vote IP');

        $left = $this->voter('198.51.100.30');   // entered the code on the full network, votes from another
        $this->assertTrue($this->counted($this->castFrom($left, '203.0.113.31')), 'the code-entry network is not counted: '.$this->errorsText());
    }

    // ── 4: IPv6 counts by /64 ──────────────────────────────────────────────────────────────────────────────

    public function test_ipv6_votes_from_one_64_network_count_together(): void
    {
        $this->earlierVoteFrom('2001:db8:1:2::10');

        $sameNetwork = $this->voter('2001:db8:1:2::99');
        $this->assertRefusedWithoutVote($this->castFrom($sameNetwork, '2001:db8:1:2::99'), $sameNetwork, self::LIMIT_REACHED, 'same /64');

        $otherNetwork = $this->voter('2001:db8:1:3::1');
        $this->assertTrue($this->counted($this->castFrom($otherNetwork, '2001:db8:1:3::1')), 'another /64: '.$this->errorsText());
    }

    // ── 2: one vote at a time per network ──────────────────────────────────────────────────────────────────

    public function test_a_vote_waits_for_another_vote_from_the_same_network_and_gives_up_safely(): void
    {
        config(['voting.ip_lock_timeout_ms' => 300, 'app.max_use_clientIP' => '5']);
        $holder = $this->lockHolder();
        $holder->select('select pg_advisory_lock(hashtext(?), hashtext(?))',
            [IpVoteLimit::LOCK_NAMESPACE, IpVoteLimit::lockKey($this->election, '203.0.113.40')]);

        try {
            $busy = $this->voter('203.0.113.40');
            $this->assertRefusedWithoutVote($this->castFrom($busy, '203.0.113.40'), $busy, 'Please try again', 'the network is busy');
            $this->assertSame(0, $this->votes(), 'no vote persisted');

            $elsewhere = $this->voter('203.0.113.41');
            $this->assertTrue($this->counted($this->castFrom($elsewhere, '203.0.113.41')), 'another network is not held up: '.$this->errorsText());
        } finally {
            $holder->select('select pg_advisory_unlock_all()');
        }

        $this->assertTrue($this->counted($this->castFrom($busy, '203.0.113.40')), 'once free, the retry counts: '.$this->errorsText());
    }

    public function test_the_lock_key_is_per_election_and_per_network(): void
    {
        $other = Election::factory()->forOrganisation($this->org)->real()->make();
        $other->id = (string) Str::uuid();

        $this->assertSame(IpVoteLimit::lockKey($this->election, '2001:db8:1:2::1'), IpVoteLimit::lockKey($this->election, '2001:db8:1:2::ffff'));
        $this->assertNotSame(IpVoteLimit::lockKey($this->election, '203.0.113.1'), IpVoteLimit::lockKey($this->election, '203.0.113.2'));
        $this->assertNotSame(IpVoteLimit::lockKey($this->election, '203.0.113.1'), IpVoteLimit::lockKey($other, '203.0.113.1'));
    }

    // ── unchanged: a whitelisted network is not limited ────────────────────────────────────────────────────

    public function test_a_whitelisted_network_is_not_limited_at_the_vote(): void
    {
        DB::table('elections')->where('id', $this->election->id)->update(['ip_whitelist' => json_encode(['203.0.113.0/24'])]);
        $this->earlierVoteFrom('203.0.113.50');
        $voter = $this->voter('203.0.113.50');

        $this->assertTrue($this->counted($this->castFrom($voter, '203.0.113.50')), 'whitelisted: '.$this->errorsText());
    }

    /** A second connection to the same (isolated) database that holds a lock the way a concurrent vote would. */
    private function lockHolder(): \Illuminate\Database\Connection
    {
        config(['database.connections.pbd288_lock_holder' => config('database.connections.'.config('database.default'))]);

        return DB::connection('pbd288_lock_holder');
    }
}
