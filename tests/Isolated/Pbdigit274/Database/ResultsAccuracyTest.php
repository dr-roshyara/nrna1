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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * Results accuracy (PBDIGIT-274 follow-up, Human PO 2026-10-13): known ballots, cast through the real voting
 * route, must produce exactly the expected official result — per candidate, per abstention, per post — and refused
 * casts must not count. Votes and results must carry no reference to a voter (anonymity).
 *
 * Election: P1 national, choose 1 of {A, B, C}; P2 national, choose 2 of {D, E, F}; P3 regional (Bayern),
 * choose 1 of {G, H}; abstention enabled. Each voter casts from its own IP (the IP limits are not the subject).
 * The official numbers are read from the results page (ResultController::index, route result.index).
 *
 * Isolated database only; committed data, removed afterwards. The audit service is stubbed (no files).
 */
final class ResultsAccuracyTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;

    /** @var array<string, string> post key → id */
    private array $posts = [];
    /** @var array<string, string> candidate key → candidacy id */
    private array $candidates = [];
    /** @var list<string> */
    private array $users = [];
    private int $ip = 10;

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

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd-results-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'Results accuracy '.uniqid(), 'slug' => 'pbd-results-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
            'no_vote_option_enabled' => true,
        ]);

        $this->addPost('P1', 'President', 1, true, null, ['A', 'B', 'C']);
        $this->addPost('P2', 'Committee', 2, true, null, ['D', 'E', 'F']);
        $this->addPost('P3', 'Bayern representative', 1, false, 'Bayern', ['G', 'H']);
    }

    protected function tearDown(): void
    {
        try {
            $voteIds = DB::table('votes')->where('election_id', $this->election->id)->pluck('id');
            DB::table('results')->whereIn('vote_id', $voteIds)->delete();
            DB::table('votes')->where('election_id', $this->election->id)->delete();
            foreach (['receipt_codes', 'voter_slug_steps', 'voter_slugs', 'codes', 'election_memberships'] as $table) {
                DB::table($table)->where('election_id', $this->election->id)->delete();
            }
            DB::table('candidacies')->whereIn('post_id', array_values($this->posts))->delete();
            DB::table('posts')->whereIn('id', array_values($this->posts))->delete();
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

    private function addPost(string $key, string $name, int $required, bool $national, ?string $state, array $candidateKeys): void
    {
        $id = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => $name, 'required_number' => $required, 'is_national_wide' => $national, 'state_name' => $state,
            'created_at' => now(), 'updated_at' => now()]);
        $this->posts[$key] = $id;
        foreach ($candidateKeys as $c) {
            $cid = (string) Str::uuid();
            DB::table('candidacies')->insert(['id' => $cid, 'organisation_id' => $this->org->id, 'post_id' => $id,
                'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);
            $this->candidates[$c] = $cid;
        }
    }

    /** An entitled voter with a verified voting code and a voting link, in a region (or none). */
    private function voter(?string $region): array
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => $region,
            'organisation_id' => $this->org->id]);
        $this->users[] = $user->id;
        DB::table('user_organisation_roles')->insertOrIgnore(['id' => (string) Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);
        $ip = '203.0.113.'.($this->ip++);
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

        return ['user' => $user, 'slug' => $slug, 'code' => $code, 'ip' => $ip];
    }

    /** A post selection: candidate keys, or 'abstain'. */
    private function choice(string $postKey, array|string $pick): array
    {
        return $pick === 'abstain'
            ? ['post_id' => $this->posts[$postKey], 'no_vote' => true, 'candidates' => []]
            : ['post_id' => $this->posts[$postKey], 'no_vote' => false,
                'candidates' => array_map(fn ($c) => ['candidacy_id' => $this->candidates[$c]], $pick)];
    }

    private function cast(array $v, array $national, array $regional = []): TestResponse
    {
        $ballot = ['national_selected_candidates' => $national, 'regional_selected_candidates' => $regional, 'no_vote_posts' => []];

        return $this->actingAs($v['user'])
            ->withServerVariables(['REMOTE_ADDR' => $v['ip']])
            ->withSession(['current_organisation_id' => $this->org->id, 'vote_data_'.$v['user']->id => $ballot])
            ->post(route('slug.vote.store', ['vslug' => $v['slug']]), ['voting_code' => $v['code']]);
    }

    private function counted(TestResponse $response): bool
    {
        return $response->isRedirect(route('vote.verify_to_show')) && session()->has('success');
    }

    private function assertRefused(TestResponse $response, string $reason, string $case): void
    {
        $this->assertFalse($this->counted($response), "{$case}: refused");
        $errors = session('errors');
        $this->assertStringContainsString($reason, $errors ? implode(' ', $errors->all()) : '', "{$case}: for the right reason");
    }

    /** The official result page's numbers: [post name => [candidate key => votes, 'abstain' => n]], plus the total. */
    private function officialResult(array $viewer): array
    {
        DB::table('elections')->where('id', $this->election->id)->update(['results_published' => true, 'results_published_at' => now()]);
        $response = $this->actingAs($viewer['user'])->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('result.index', ['election' => $this->election->slug]));
        $response->assertOk();
        $result = $response->viewData('page')['props']['final_result'];

        $byId = array_flip($this->candidates);
        $out = ['total' => $result['total_votes']];
        foreach ($result['posts'] as $post) {
            $row = ['abstain' => $post['no_vote_count']];
            foreach ($post['candidates'] as $c) {
                $row[$byId[$c['candidacy_id']]] = $c['vote_count'];
            }
            ksort($row);
            $out[$post['post_name']] = $row;
        }

        return $out;
    }

    // ── the test ───────────────────────────────────────────────────────────────────────────────────────

    public function test_known_ballots_produce_exactly_the_expected_official_result_and_refusals_do_not_count(): void
    {
        $v1 = $this->voter('Bayern');
        $v2 = $this->voter('Bayern');
        $v3 = $this->voter(null);
        $v4 = $this->voter('Bayern');
        $invalid = $this->voter('Bayern');
        $outsider = $this->voter(null);

        $this->assertTrue($this->counted($this->cast($v1, [$this->choice('P1', ['A']), $this->choice('P2', ['D', 'E'])], [$this->choice('P3', ['G'])])), 'v1 counted');
        $this->assertTrue($this->counted($this->cast($v2, [$this->choice('P1', ['A']), $this->choice('P2', ['D', 'F'])], [$this->choice('P3', 'abstain')])), 'v2 counted');
        $this->assertTrue($this->counted($this->cast($v3, [$this->choice('P1', ['B']), $this->choice('P2', 'abstain')])), 'v3 counted (no region: no regional post)');
        $this->assertTrue($this->counted($this->cast($v4, [$this->choice('P1', 'abstain'), $this->choice('P2', ['E', 'F'])], [$this->choice('P3', ['H'])])), 'v4 counted');

        // Refusals that must not count, each for its own reason (so the test cannot pass on an unrelated refusal).
        $this->assertRefused($this->cast($v1, [$this->choice('P1', ['B']), $this->choice('P2', ['E', 'F'])], [$this->choice('P3', ['H'])]),
            'You have already voted in this election', 'double vote');
        $this->assertRefused($this->cast($invalid, [$this->choice('P1', ['A', 'B']), $this->choice('P2', ['D', 'E'])], [$this->choice('P3', ['G'])]),
            'does not match the ballot for this election', 'two candidates on a choose-1 post');
        $this->assertRefused($this->cast($outsider, [$this->choice('P1', ['C']), $this->choice('P2', ['D', 'E'])], [$this->choice('P3', ['G'])]),
            'does not match the ballot for this election', 'a regional post for a voter outside the region');

        $this->assertSame(4, DB::table('votes')->where('election_id', $this->election->id)->count(), 'four votes stored');
        $this->assertSame([
            'total' => 4,
            'President' => ['A' => 2, 'B' => 1, 'C' => 0, 'abstain' => 1],
            'Committee' => ['D' => 2, 'E' => 2, 'F' => 2, 'abstain' => 1],
            'Bayern representative' => ['G' => 1, 'H' => 1, 'abstain' => 1],
        ], $this->officialResult($v1));
    }

    public function test_votes_and_results_carry_no_reference_to_a_voter(): void
    {
        foreach (['votes', 'results'] as $table) {
            foreach (['user_id', 'voter_id', 'voter_slug_id', 'code_id', 'client_ip', 'ip_address'] as $column) {
                $this->assertFalse(Schema::hasColumn($table, $column), "{$table}.{$column} would link a vote to a voter");
            }
        }
    }
}
