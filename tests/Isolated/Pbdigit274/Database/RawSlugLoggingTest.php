<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Services\ElectionAuditService;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S9c (plan section 12, O-7 = B, L-11): no raw voting link reaches the logs on the real paths.
 *
 * Every log record (default stack and named channels such as voting_security / security) is captured through
 * MessageLogged while a real voter walks the whole voting flow over HTTP: start → code entry → code → agreement →
 * ballot → verification → cast. Further paths: the voting-exception renderer, a refused (not entitled) voter,
 * the demo start. The raw link string must occur in no message and in no (recursively serialised) context value.
 * Error responses are scanned too; legitimate pages and redirects that must carry the link are not.
 *
 * Isolated database only; committed data, removed afterwards. The audit service is stubbed (no files).
 */
final class RawSlugLoggingTest extends IsolatedCommittedDatabaseTestCase
{
    private Organisation $org;
    private Election $election;
    private string $postId;
    private string $candidacyId;

    /** @var list<string> */
    private array $users = [];
    /** @var list<array{level: string, message: string, context: string}> */
    private array $records = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        // max_use_clientIP has no default (env MAX_USE_IP_ADDRESS); unset, `0 >= null` refuses every real voter at the
        // verification step (PBDIGIT-278 item 8). Set as in tests/Feature/Characterization/Phase1/CastFixtures.php.
        config(['voting.time_in_minutes' => 30, 'app.max_use_clientIP' => 7]);
        Event::listen(MessageLogged::class, function (MessageLogged $e) {
            $this->records[] = ['level' => $e->level, 'message' => (string) $e->message,
                'context' => json_encode($e->context, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES) ?: print_r($e->context, true)];
        });
        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s9c-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-274 logs '.uniqid(), 'slug' => 'pbd274-s9c-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
        ]);
        $this->postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $this->postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-274 post', 'required_number' => 1, 'is_national_wide' => true, 'created_at' => now(), 'updated_at' => now()]);
        $this->candidacyId = (string) Str::uuid();
        DB::table('candidacies')->insert(['id' => $this->candidacyId, 'organisation_id' => $this->org->id, 'post_id' => $this->postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);
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
            DB::table('demo_voter_slugs')->whereIn('user_id', $this->users)->delete();
            DB::table('candidacies')->where('post_id', $this->postId)->delete();
            DB::table('posts')->where('id', $this->postId)->delete();
            DB::table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            DB::table('elections')->where('organisation_id', $this->org->id)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    private function voter(bool $entitled = true): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null,
            'organisation_id' => $this->org->id]);
        $this->users[] = $user->id;
        // insertOrIgnore: with organisation_id set, the user factory may already have created the membership role.
        DB::table('user_organisation_roles')->insertOrIgnore(['id' => (string) Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => $entitled ? 'active' : 'inactive']);

        return $user;
    }

    private function as(User $user): static
    {
        return $this->actingAs($user)->withSession(['current_organisation_id' => $this->org->id]);
    }

    private function slugOf(User $user): ?string
    {
        return DB::table('voter_slugs')->where('user_id', $user->id)->whereNull('deleted_at')->value('slug');
    }

    /** Assert that the secret occurs in no captured log record; returns how many records were inspected. */
    private function assertNotLogged(string $secret, string $path): int
    {
        $this->assertGreaterThan(20, strlen($secret), 'a real link string');
        $hits = [];
        foreach ($this->records as $i => $r) {
            if (str_contains($r['message'], $secret) || str_contains($r['context'], $secret)) {
                $hits[] = "#{$i} {$r['level']}: ".mb_substr($r['message'], 0, 80);
            }
        }
        $this->assertSame([], $hits, "{$path}: raw voting link in ".count($hits).' of '.count($this->records).' log record(s)');

        return count($this->records);
    }

    private function step(string $name, TestResponse $response, array &$trail): TestResponse
    {
        $location = $response->headers->get('Location');
        $trail[] = $name.'='.$response->status().($location ? '→'.(app('router')->getRoutes()->match(
            \Illuminate\Http\Request::create($location))->getName() ?? '?') : '');

        return $response;
    }

    public function test_a_real_voter_walking_the_whole_flow_leaves_no_raw_link_in_the_logs(): void
    {
        $voter = $this->voter();
        $trail = [];

        $this->step('start', $this->as($voter)->post(route('elections.start', $this->election->slug)), $trail);
        $slug = $this->slugOf($voter);
        $this->assertNotNull($slug);
        $v = ['vslug' => $slug];

        $this->step('code.create', $this->as($voter)->get(route('slug.code.create', $v)), $trail)->assertOk();
        $code = DB::table('codes')->where('user_id', $voter->id)->where('election_id', $this->election->id)->value('code_to_open_voting_form');
        $this->assertNotNull($code, 'code entry created the voter\'s code');

        $this->step('code.store', $this->as($voter)->post(route('slug.code.store', $v), ['voting_code' => $code]), $trail)
            ->assertRedirect(route('slug.code.agreement', $v));
        $this->step('agreement', $this->as($voter)->get(route('slug.code.agreement', $v)), $trail)->assertOk();
        $this->step('agreement.submit', $this->as($voter)->post(route('slug.code.agreement.submit', $v), ['agreement' => 1]), $trail)
            ->assertRedirect(route('slug.vote.create', $v));
        $this->step('vote.create', $this->as($voter)->get(route('slug.vote.create', $v)), $trail)->assertOk();
        $this->step('vote.submit', $this->as($voter)->post(route('slug.vote.submit', $v), [
            'user_id' => $voter->id, 'agree_button' => 1, 'regional_selected_candidates' => [], 'no_vote_posts' => [],
            'national_selected_candidates' => [['post_id' => $this->postId, 'no_vote' => false,
                'candidates' => [['candidacy_id' => $this->candidacyId]]]],
        ]), $trail)->assertRedirect(route('slug.vote.verify', $v));
        $this->step('vote.verify', $this->as($voter)->get(route('slug.vote.verify', $v)), $trail)->assertOk();
        $this->step('vote.store', $this->as($voter)->post(route('slug.vote.store', $v), ['voting_code' => $code]), $trail)
            ->assertRedirect(route('vote.verify_to_show'));

        $this->assertSame(1, DB::table('votes')->where('election_id', $this->election->id)->count(), 'the journey ended in a committed vote');
        $inspected = $this->assertNotLogged($slug, 'real voting flow');
        fwrite(STDERR, "\nS9C-FLOW ".implode(' ', $trail)." records={$inspected}\n");
    }

    public function test_the_voting_exception_renderer_logs_no_raw_link(): void
    {
        $voter = $this->voter();
        $this->as($voter)->post(route('elections.start', $this->election->slug));
        $slug = $this->slugOf($voter);
        // A link whose stored organisation no longer matches its election: VerifyVoterSlug raises a VotingException.
        $other = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s9c-other-'.uniqid()]);
        DB::table('voter_slugs')->where('slug', $slug)->update(['organisation_id' => $other->id]);

        $json = $this->as($voter)->getJson(route('slug.code.create', ['vslug' => $slug]));
        $web = $this->as($voter)->get(route('slug.code.create', ['vslug' => $slug]));

        DB::table('voter_slugs')->where('slug', $slug)->update(['organisation_id' => $this->org->id]);
        DB::table('organisations')->where('id', $other->id)->delete();
        $this->assertGreaterThanOrEqual(400, $json->status());
        $this->assertStringNotContainsString($slug, $json->getContent(), 'error response body');
        $inspected = $this->assertNotLogged($slug, 'voting exception renderer');
        fwrite(STDERR, "\nS9C-RENDERER json={$json->status()} web={$web->status()} records={$inspected}\n");
    }

    public function test_a_voter_refused_at_code_entry_leaves_no_raw_link_in_the_logs(): void
    {
        $voter = $this->voter();
        $this->as($voter)->post(route('elections.start', $this->election->slug));
        $slug = $this->slugOf($voter);
        DB::table('election_memberships')->where('user_id', $voter->id)->update(['status' => 'inactive']);

        $response = $this->as($voter)->get(route('slug.code.create', ['vslug' => $slug]));

        $this->assertNotSame(200, $response->status());
        $inspected = $this->assertNotLogged($slug, 'refused voter');
        fwrite(STDERR, "\nS9C-REFUSED status={$response->status()} records={$inspected}\n");
    }

    public function test_the_demo_start_and_demo_code_entry_leave_no_raw_link_in_the_logs(): void
    {
        $demo = Election::factory()->forOrganisation($this->org)->demo()->create([
            'status' => 'active', 'slug' => 'pbd274-s9c-demo-'.uniqid(), 'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $voter = $this->voter();

        $start = $this->as($voter)->post(route('election.demo.select'), ['election_id' => $demo->id]);
        $slug = DB::table('demo_voter_slugs')->where('user_id', $voter->id)->orderByDesc('created_at')->value('slug');
        $this->assertNotNull($slug, 'the demo start issued a demo link (start answered '.$start->status().')');
        $create = $this->as($voter)->get(route('slug.demo-code.create', ['vslug' => $slug]));

        $inspected = $this->assertNotLogged($slug, 'demo start and demo code entry');
        fwrite(STDERR, "\nS9C-DEMO start={$start->status()} code.create={$create->status()} records={$inspected}\n");
    }
}
