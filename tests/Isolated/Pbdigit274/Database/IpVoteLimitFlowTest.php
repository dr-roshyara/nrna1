<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-278 item 8: the per-IP vote limit, end to end over the real routes and middleware.
 *
 * Counting (unchanged intent): completed votes from this IP in this election (codes.client_ip with has_voted).
 * Limit: the election's own restriction when enabled, else MAX_USE_IP_ADDRESS, else 10. It is applied the same
 * way at start (elections.start), code entry (slug.code.store) and verification (slug.vote.verify), and a refusal
 * gives the voter a readable reason; it never issues or extends a link and never persists a vote.
 *
 * Earlier votes from the same IP are other voters' completed code rows with the test client's IP (127.0.0.1).
 * Isolated database only; committed data, removed afterwards. The audit service is stubbed (no files).
 */
final class IpVoteLimitFlowTest extends IsolatedCommittedDatabaseTestCase
{
    private const IP = '127.0.0.1';

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
        config(['voting.time_in_minutes' => 30, 'app.max_use_clientIP' => null]);
        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd278-ip-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-278 ip limit '.uniqid(), 'slug' => 'pbd278-ip-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
            'ip_restriction_enabled' => false, 'ip_whitelist' => null,   // ip_restriction_max_per_ip keeps its column default (4), unused while disabled
        ]);
        $this->postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $this->postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-278 post', 'required_number' => 1, 'is_national_wide' => true, 'created_at' => now(), 'updated_at' => now()]);
        $this->candidacyId = (string) Str::uuid();
        DB::table('candidacies')->insert(['id' => $this->candidacyId, 'organisation_id' => $this->org->id, 'post_id' => $this->postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);
    }

    protected function tearDown(): void
    {
        try {
            foreach (['voter_slug_steps', 'voter_slugs', 'codes', 'election_memberships'] as $table) {
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

    /** Completed votes by other voters from the same IP (the counting source: codes.client_ip with has_voted). */
    private function earlierVotesFromThisIp(int $n): void
    {
        for ($i = 0; $i < $n; $i++) {
            $other = $this->user();
            DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id,
                'election_id' => $this->election->id, 'user_id' => $other->id, 'code1' => (string) random_int(100000, 999999),
                'has_voted' => true, 'client_ip' => self::IP, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function as(User $user): static
    {
        return $this->actingAs($user)->withSession(['current_organisation_id' => $this->org->id]);
    }

    private function start(User $user): TestResponse
    {
        return $this->as($user)->post(route('elections.start', $this->election->slug));
    }

    private function slugOf(User $user): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $user->id)->whereNull('deleted_at')->value('slug');
    }

    private function slugRows(User $user): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')->where('user_id', $user->id)->orderBy('created_at')->get()->all());
    }

    /** start → code entry page → code; returns [link, code]. */
    private function throughCodeEntry(User $user): array
    {
        $this->start($user);
        $slug = $this->slugOf($user);
        $this->assertNotNull($slug, 'a link was issued at start');
        $page = $this->as($user)->get(route('slug.code.create', ['vslug' => $slug]));
        $this->assertSame(200, $page->status(), 'code entry refused: '.$this->errorsText());
        $code = DB::table('codes')->where('user_id', $user->id)->where('election_id', $this->election->id)->value('code_to_open_voting_form');

        return [$slug, $code];
    }

    /** … → agreement → ballot → submitted; the next page is verification. */
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

    private function errorsText(): string
    {
        $errors = session('errors');

        return trim(($errors ? implode(' ', $errors->all()) : '').' '.(string) session('error'));
    }

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    // ── 1, 4: the default limit 10, a voter below it passes every step ─────────────────────────────────────

    public function test_below_both_limits_a_voter_passes_start_code_and_verification(): void
    {
        $this->earlierVotesFromThisIp(2);
        $voter = $this->user();

        [$slug, $code] = $this->throughCodeEntry($voter);
        $this->throughBallot($voter, $slug, $code);
        $this->as($voter)->get(route('slug.vote.verify', ['vslug' => $slug]))->assertOk();
    }

    /**
     * Human PO 2026-10-13: the two per-IP limits stay SEPARATE. Code entry's authorization (PBDIGIT-239, trust
     * policy) applies its own constitutional limit (elections.max_votes_per_ip, default 6, scaled by evidence), so at
     * code entry the stricter of the two applies. Here the global limit (10) is not reached, the constitutional is.
     */
    public function test_the_constitutional_trust_limit_stays_separate_and_can_be_stricter_at_code_entry(): void
    {
        $this->earlierVotesFromThisIp(9);
        $voter = $this->user();

        $this->start($voter);
        $slug = $this->slugOf($voter);
        $this->assertNotNull($slug, 'start: 9 earlier votes are below the global limit of 10, a link is issued');

        $this->as($voter)->get(route('slug.code.create', ['vslug' => $slug]))->assertRedirect();
        $this->assertStringContainsString('constitutional verification', $this->errorsText(), 'code entry: the separate trust-policy limit refuses');
        $this->assertStringNotContainsString('vote(s) from your network', $this->errorsText());
    }

    // ── 1, 5, 6: at the default limit the voter is refused at start; no link ───────────────────────────────

    public function test_default_limit_ten_refuses_at_start_when_reached_and_issues_no_link(): void
    {
        $this->earlierVotesFromThisIp(10);
        $voter = $this->user();

        $this->start($voter)->assertRedirect(route('elections.show', $this->election->slug));

        $this->assertStringContainsString('The maximum of 10 vote(s) from your network has been reached.', $this->errorsText());
        $this->assertSame([], $this->slugRows($voter), 'no link issued');
    }

    // ── 2, 5, 6: an explicit limit of 1 is enforced as 1 at code entry, with a readable reason ─────────────

    public function test_explicit_limit_one_refuses_at_code_entry_with_a_readable_reason(): void
    {
        config(['app.max_use_clientIP' => '1']);
        $voter = $this->user();
        [$slug, $code] = $this->throughCodeEntry($voter);
        $linksBefore = $this->slugRows($voter);
        $this->earlierVotesFromThisIp(1);

        $this->as($voter)->post(route('slug.code.store', ['vslug' => $slug]), ['voting_code' => $code]);

        $this->assertStringContainsString('The maximum of 1 vote(s) from your network has been reached.', $this->errorsText());
        $this->assertSame(0, (int) DB::table('codes')->where('user_id', $voter->id)->value('can_vote_now'), 'the code is not verified');
        $this->assertSame($linksBefore, $this->slugRows($voter), 'the link is neither replaced nor extended');
    }

    // ── 2, 6, 7: at verification the refusal is readable, not a system error; no vote ──────────────────────

    public function test_explicit_limit_one_refuses_at_verification_with_a_readable_reason_not_a_system_error(): void
    {
        config(['app.max_use_clientIP' => '1']);
        $voter = $this->user();
        [$slug, $code] = $this->throughCodeEntry($voter);
        $this->throughBallot($voter, $slug, $code);
        $linksBefore = $this->slugRows($voter);
        $this->earlierVotesFromThisIp(1);

        $this->as($voter)->get(route('slug.vote.verify', ['vslug' => $slug]))->assertRedirect(route('dashboard'));

        $text = $this->errorsText();
        $this->assertStringContainsString('The maximum of 1 vote(s) from your network has been reached.', $text);
        $this->assertStringNotContainsString('System error', $text);
        $this->assertSame(0, $this->votes(), 'no vote persisted');
        $this->assertSame($linksBefore, $this->slugRows($voter), 'the link is neither replaced nor extended');
    }

    // ── 3: an enabled election restriction wins over the global setting ──────────────────────────────────

    public function test_the_election_restriction_wins_over_the_global_limit(): void
    {
        config(['app.max_use_clientIP' => '1']);
        DB::table('elections')->where('id', $this->election->id)->update(['ip_restriction_enabled' => true, 'ip_restriction_max_per_ip' => 3]);
        $this->earlierVotesFromThisIp(2);

        $below = $this->user();
        $this->start($below);
        $this->assertNotNull($this->slugOf($below), 'below the election limit (2 < 3) although above the global 1');

        $this->earlierVotesFromThisIp(1);
        $reached = $this->user();
        $this->start($reached)->assertRedirect(route('elections.show', $this->election->slug));
        $this->assertStringContainsString('The maximum of 3 vote(s) from your network has been reached.', $this->errorsText());
        $this->assertNull($this->slugOf($reached));
    }
}
