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
 * PBDIGIT-288 item 5 (plan step 3): every per-IP vote limit refusal is visible to the election's admins as one
 * `ip_blocked` audit entry — at start, code entry, verification and the vote — and the entry records the limit that
 * actually applied and where it came from (before: only start wrote one, and its `max` was the election setting even
 * when that setting was off).
 *
 * Entry metadata, exactly: reason (limit_exceeded | network_busy), step (start | code_entry | verification | vote),
 * max (the limit applied), limit_source (election | env | default). No IP in the metadata.
 *
 * Isolated database only; committed data, removed afterwards. The audit service records entries in memory.
 */
final class IpRefusalAuditTest extends IsolatedCommittedDatabaseTestCase
{
    private const IP = '127.0.0.1';

    private Organisation $org;
    private Election $election;
    private string $postId;
    private string $candidacyId;
    private object $audit;

    /** @var list<string> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        config(['voting.time_in_minutes' => 30, 'app.max_use_clientIP' => null]);
        $this->audit = new class extends ElectionAuditService {
            public array $entries = [];

            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
                $this->entries[] = ['event' => $event, 'user_id' => $user?->id, 'category' => $category, 'metadata' => $metadata];
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        };
        $this->app->instance(ElectionAuditService::class, $this->audit);

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd288-audit-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-288 audit '.uniqid(), 'slug' => 'pbd288-audit-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
            'ip_restriction_enabled' => false, 'ip_whitelist' => null,   // ip_restriction_max_per_ip keeps its default (4), unused while disabled
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
            DB::purge('pbd288_audit_lock_holder');
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

    private function earlierVotesFrom(string $ip, int $n): void
    {
        for ($i = 0; $i < $n; $i++) {
            $other = $this->user();
            DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
                'election_id' => $this->election->id, 'user_id' => $other->id, 'code1' => (string) random_int(100000, 999999),
                'has_voted' => true, 'client_ip' => $ip, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function as(User $user): static
    {
        return $this->actingAs($user)->withSession(['current_organisation_id' => $this->org->id]);
    }

    /** start → code entry page; returns [link, code]. */
    private function throughCodeEntry(User $user): array
    {
        $this->as($user)->post(route('elections.start', $this->election->slug));
        $slug = DB::table('voter_slugs')->where('user_id', $user->id)->whereNull('deleted_at')->value('slug');
        $this->assertNotNull($slug, 'a link was issued at start');
        $this->as($user)->get(route('slug.code.create', ['vslug' => $slug]))->assertOk();
        $code = DB::table('codes')->where('user_id', $user->id)->where('election_id', $this->election->id)->value('code_to_open_voting_form');

        return [$slug, $code];
    }

    private function throughBallot(User $user, string $slug, string $code): void
    {
        $v = ['vslug' => $slug];
        $this->as($user)->post(route('slug.code.store', $v), ['voting_code' => $code])->assertRedirect(route('slug.code.agreement', $v));
        $this->as($user)->post(route('slug.code.agreement.submit', $v), ['agreement' => 1])->assertRedirect(route('slug.vote.create', $v));
        $this->as($user)->post(route('slug.vote.submit', $v), [
            'user_id' => $user->id, 'agree_button' => 1, 'regional_selected_candidates' => [], 'no_vote_posts' => [],
            'national_selected_candidates' => [['post_id' => $this->postId, 'no_vote' => false, 'candidates' => [['candidacy_id' => $this->candidacyId]]]],
        ])->assertRedirect(route('slug.vote.verify', $v));
    }

    /** A voter at the verification step whose code was entered from $ip (direct fixture, as in IpVoteLimitAtCastTest). */
    private function voterAtVerification(string $ip): array
    {
        $user = $this->user();
        $code = strtoupper(Str::random(8));
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'code1' => (string) random_int(100000, 999999), 'code_to_open_voting_form' => $code,
            'code_to_save_vote' => 'SAVE1234', 'is_code_to_open_voting_form_usable' => 0, 'code_to_open_voting_form_used_at' => now(),
            'is_code_to_save_vote_usable' => 1, 'can_vote_now' => 1, 'has_voted' => false, 'vote_submitted' => 0,
            'has_agreed_to_vote' => 1, 'client_ip' => $ip, 'session_name' => 'vote_data_'.$user->id, 'voting_time_in_minutes' => 30,
            'created_at' => now(), 'updated_at' => now()]);
        $slug = Str::random(40);
        DB::table('voter_slugs')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'slug' => $slug, 'is_active' => true, 'has_voted' => false, 'status' => 'active',
            'current_step' => 4, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30)]);

        return ['user' => $user, 'slug' => $slug, 'code' => $code];
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

    /** @return list<array> the ip_blocked entries */
    private function ipBlocked(): array
    {
        return array_values(array_filter($this->audit->entries, fn ($e) => $e['event'] === 'ip_blocked'));
    }

    private function assertOneEntry(User $user, string $reason, string $step, int $max, string $source): void
    {
        $entries = $this->ipBlocked();
        $this->assertCount(1, $entries, "exactly one ip_blocked entry for the {$step} refusal");
        $this->assertSame($user->id, $entries[0]['user_id']);
        $this->assertSame('voters', $entries[0]['category']);
        $this->assertSame(['reason' => $reason, 'step' => $step, 'max' => $max, 'limit_source' => $source], $entries[0]['metadata']);
    }

    // ── each refusal site writes one entry ─────────────────────────────────────────────────────────────────

    public function test_a_refusal_at_start_records_the_limit_that_applied_not_the_disabled_election_setting(): void
    {
        $this->earlierVotesFrom(self::IP, 10);
        $voter = $this->user();

        $this->as($voter)->post(route('elections.start', $this->election->slug));

        $this->assertOneEntry($voter, 'limit_exceeded', 'start', 10, 'default');
    }

    public function test_a_refusal_at_code_entry_is_audited(): void
    {
        config(['app.max_use_clientIP' => '1']);
        $voter = $this->user();
        [$slug, $code] = $this->throughCodeEntry($voter);
        $this->earlierVotesFrom(self::IP, 1);

        $this->as($voter)->post(route('slug.code.store', ['vslug' => $slug]), ['voting_code' => $code]);

        $this->assertOneEntry($voter, 'limit_exceeded', 'code_entry', 1, 'env');
    }

    public function test_a_refusal_at_verification_is_audited(): void
    {
        config(['app.max_use_clientIP' => '1']);
        $voter = $this->user();
        [$slug, $code] = $this->throughCodeEntry($voter);
        $this->throughBallot($voter, $slug, $code);
        $this->earlierVotesFrom(self::IP, 1);

        $this->as($voter)->get(route('slug.vote.verify', ['vslug' => $slug]));

        $this->assertOneEntry($voter, 'limit_exceeded', 'verification', 1, 'env');
    }

    public function test_a_refusal_at_the_vote_is_audited_with_the_election_setting_as_source(): void
    {
        DB::table('elections')->where('id', $this->election->id)->update(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 1]);
        $voter = $this->voterAtVerification('203.0.113.60');
        $this->earlierVotesFrom('203.0.113.60', 1);

        $this->castFrom($voter, '203.0.113.60');

        $this->assertOneEntry($voter['user'], 'limit_exceeded', 'vote', 1, 'election');
        $this->assertSame(0, DB::table('votes')->where('election_id', $this->election->id)->count(), 'no vote');
    }

    public function test_a_busy_network_at_the_vote_is_audited_as_network_busy(): void
    {
        config(['voting.ip_lock_timeout_ms' => 300, 'app.max_use_clientIP' => '5']);
        config(['database.connections.pbd288_audit_lock_holder' => config('database.connections.'.config('database.default'))]);
        $holder = DB::connection('pbd288_audit_lock_holder');
        $holder->select('select pg_advisory_lock(hashtext(?), hashtext(?))',
            [IpVoteLimit::LOCK_NAMESPACE, IpVoteLimit::lockKey($this->election, '203.0.113.61')]);

        try {
            $voter = $this->voterAtVerification('203.0.113.61');
            $this->castFrom($voter, '203.0.113.61');
        } finally {
            $holder->select('select pg_advisory_unlock_all()');
        }

        $this->assertOneEntry($voter['user'], 'network_busy', 'vote', 5, 'env');
    }

    // ── no entry without a refusal ─────────────────────────────────────────────────────────────────────────

    public function test_a_vote_below_the_limit_writes_no_ip_blocked_entry(): void
    {
        $voter = $this->voterAtVerification('203.0.113.62');

        $this->castFrom($voter, '203.0.113.62');

        $this->assertSame([], $this->ipBlocked());
    }
}
