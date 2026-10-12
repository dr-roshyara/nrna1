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
 * PBDIGIT-274 S9b: the count invariant for P1 slug-flow casts (plan section 10.2, L-14, O-6 = B, O-6b = i).
 *
 * Per election: V = votes, C = codes with has_voted, S = voter slugs with has_voted (soft-deleted included).
 * The votes table has no voter reference, so only aggregates are compared.
 *
 * (1) Isolated P1 test: a fresh election, no other writers, real HTTP casts through POST slug.vote.store with an
 *     expiry and replacement, one forced failure and a double submission: ΔV = ΔC = ΔS = successful casts.
 * (4) Baseline: the read-only snapshot query used before deployment runs in a READ ONLY transaction, so PostgreSQL
 *     itself refuses any write; differences are reported, never corrected (O-6b = i).
 *
 * Isolated database only; committed data, removed afterwards. The audit service is stubbed (no files).
 */
final class CountInvariantTest extends IsolatedCommittedDatabaseTestCase
{
    private const C2 = 't274_c2';

    /** The baseline snapshot (O-6b = i): read-only, per real election. Also the query for the release runbook. */
    public const BASELINE_SQL = <<<'SQL'
        SELECT e.id AS election_id,
               (SELECT count(*) FROM votes v WHERE v.election_id = e.id) AS v,
               (SELECT count(*) FROM codes c WHERE c.election_id = e.id AND c.has_voted) AS c,
               (SELECT count(*) FROM voter_slugs s WHERE s.election_id = e.id AND s.has_voted) AS s
        FROM elections e
        WHERE e.type = 'real'
        ORDER BY e.id
        SQL;

    private Organisation $org;
    private Election $election;
    private string $postId;
    private string $candidacyId;

    /** @var list<string> */
    private array $users = [];
    /** @var list<array{string, string, string}> */
    private array $injected = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        config(['voting.time_in_minutes' => 30]);
        config(['database.connections.'.self::C2 => config('database.connections.'.config('database.default'))]);
        $this->assertSame('pbdigit274_isolated_test', DB::connection(self::C2)->selectOne('SELECT current_database() AS d')->d);

        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s9b-cnt-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-274 counts '.uniqid(), 'slug' => 'pbd274-s9b-cnt-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(5),
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
        $this->travelBack();
        $c2 = DB::connection(self::C2);
        try {
            foreach ($this->injected as [$trigger, $table, $function]) {
                $c2->statement("DROP TRIGGER IF EXISTS {$trigger} ON {$table}");
                $c2->statement("DROP FUNCTION IF EXISTS {$function}()");
            }
            $voteIds = $c2->table('votes')->where('election_id', $this->election->id)->pluck('id');
            $c2->table('results')->whereIn('vote_id', $voteIds)->delete();
            $c2->table('votes')->where('election_id', $this->election->id)->delete();
            foreach (['receipt_codes', 'voter_slug_steps', 'voter_slugs', 'codes', 'election_memberships'] as $table) {
                $c2->table($table)->where('election_id', $this->election->id)->delete();
            }
            $c2->table('candidacies')->where('post_id', $this->postId)->delete();
            $c2->table('posts')->where('id', $this->postId)->delete();
            $c2->table('user_organisation_roles')->whereIn('user_id', $this->users)->delete();
            $c2->table('elections')->where('id', $this->election->id)->delete();
            $c2->table('organisations')->where('id', $this->org->id)->delete();
            $c2->table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        DB::purge(self::C2);
        parent::tearDown();
    }

    // ── helpers ──────────────────────────────────────────────────────────────────────────────────────────

    /** An entitled voter whose voting code is verified (step 1–3 done), ready to start and cast. */
    private function voter(string $code): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null]);
        $this->users[] = $user->id;
        DB::table('user_organisation_roles')->insert(['id' => (string) Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $user->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);
        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'code1' => (string) random_int(100000, 999999), 'code_to_open_voting_form' => $code, 'code_to_save_vote' => 'SAVE1234',
            'is_code_to_open_voting_form_usable' => 0, 'code_to_open_voting_form_used_at' => now(), 'is_code_to_save_vote_usable' => 1,
            'can_vote_now' => 1, 'has_voted' => false, 'vote_submitted' => 0, 'has_agreed_to_vote' => 1, 'client_ip' => '127.0.0.1',
            'session_name' => 'vote_data_'.$user->id, 'voting_time_in_minutes' => 30, 'created_at' => now(), 'updated_at' => now()]);

        return $user;
    }

    private function startVoting(User $user): string
    {
        $this->actingAs($user)->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('elections.start', $this->election->slug));

        return DB::table('voter_slugs')->where('user_id', $user->id)->where('election_id', $this->election->id)
            ->whereNull('deleted_at')->value('slug');
    }

    private function cast(User $user, string $slug, string $code): TestResponse
    {
        $ballot = ['national_selected_candidates' => [['post_id' => $this->postId, 'no_vote' => false,
            'candidates' => [['candidacy_id' => $this->candidacyId]]]], 'regional_selected_candidates' => [], 'no_vote_posts' => []];

        return $this->actingAs($user)
            ->withSession(['current_organisation_id' => $this->org->id, 'vote_data_'.$user->id => $ballot])
            ->post(route('slug.vote.store', ['vslug' => $slug]), ['voting_code' => $code]);
    }

    private function succeeded(TestResponse $response): bool
    {
        return $response->isRedirect(route('vote.verify_to_show')) && session()->has('success');
    }

    /** @return array{v: int, c: int, s: int} committed counts for this election, read on C2 */
    private function counts(): array
    {
        $c2 = DB::connection(self::C2);

        return [
            'v' => $c2->table('votes')->where('election_id', $this->election->id)->count(),
            'c' => $c2->table('codes')->where('election_id', $this->election->id)->where('has_voted', true)->count(),
            's' => $c2->table('voter_slugs')->where('election_id', $this->election->id)->where('has_voted', true)->count(),
        ];
    }

    private function injectStepFailure(): void
    {
        $name = 't274_fail_'.bin2hex(random_bytes(6));
        DB::statement("CREATE FUNCTION {$name}_fn() RETURNS trigger LANGUAGE plpgsql AS \$\$ BEGIN RAISE EXCEPTION 't274 injected'; END \$\$");
        DB::statement("CREATE TRIGGER {$name} BEFORE INSERT ON voter_slug_steps FOR EACH ROW EXECUTE FUNCTION {$name}_fn()");
        $this->injected[] = [$name, 'voter_slug_steps', $name.'_fn'];
    }

    private function removeInjected(): void
    {
        foreach ($this->injected as [$trigger, $table, $function]) {
            DB::connection(self::C2)->statement("DROP TRIGGER IF EXISTS {$trigger} ON {$table}");
            DB::connection(self::C2)->statement("DROP FUNCTION IF EXISTS {$function}()");
        }
        $this->injected = [];
    }

    // ── tests ────────────────────────────────────────────────────────────────────────────────────────────

    public function test_isolated_p1_casts_keep_deltas_equal(): void
    {
        $before = $this->counts();
        $successes = 0;
        $log = [];

        // A: start and cast.
        $a = $this->voter('AAAA1111');
        $slugA = $this->startVoting($a);
        $ok = $this->succeeded($this->cast($a, $slugA, 'AAAA1111'));
        $successes += (int) $ok;
        $log[] = 'A cast: '.($ok ? 'success' : 'refused');

        // B: start; the link expires; start again (replacement, history kept); cast with the new link.
        $b = $this->voter('BBBB2222');
        $firstB = $this->startVoting($b);
        $this->travel(31)->minutes();
        $slugB = $this->startVoting($b);
        $this->assertNotSame($firstB, $slugB, 'B got a replacement link');
        $ok = $this->succeeded($this->cast($b, $slugB, 'BBBB2222'));
        $successes += (int) $ok;
        $log[] = 'B cast after replacement: '.($ok ? 'success' : 'refused');

        // C: a forced failure inside the cast (step-5 record) commits nothing.
        $c = $this->voter('CCCC3333');
        $slugC = $this->startVoting($c);
        $this->injectStepFailure();
        $ok = $this->succeeded($this->cast($c, $slugC, 'CCCC3333'));
        $this->removeInjected();
        $this->assertFalse($ok, 'the forced failure is not reported as success');
        $log[] = 'C forced failure: refused';

        // A again: a double submission is refused.
        $ok = $this->succeeded($this->cast($a, $slugA, 'AAAA1111'));
        $this->assertFalse($ok, 'a second cast by the same voter is refused');
        $log[] = 'A double submission: refused';

        $after = $this->counts();
        $delta = ['v' => $after['v'] - $before['v'], 'c' => $after['c'] - $before['c'], 's' => $after['s'] - $before['s']];
        fwrite(STDERR, "\nS9B-COUNTS before=".json_encode($before).' after='.json_encode($after).' delta='.json_encode($delta)
            .' successes='.$successes.' steps='.json_encode($log)."\n");

        $this->assertSame(2, $successes, 'A and B succeed');
        $this->assertSame(['v' => 2, 'c' => 2, 's' => 2], $delta, 'ΔV = ΔC = ΔS = successful casts');
        $this->assertSame(1, DB::connection(self::C2)->table('voter_slugs')->where('user_id', $b->id)
            ->whereNotNull('deleted_at')->count(), "B's replaced link is kept as history and was not voted with");
    }

    public function test_baseline_read_only(): void
    {
        $voter = $this->voter('DDDD4444');
        $this->assertTrue($this->succeeded($this->cast($voter, $this->startVoting($voter), 'DDDD4444')));

        $rows = DB::connection(self::C2)->transaction(function () {
            $c2 = DB::connection(self::C2);
            $c2->statement('SET TRANSACTION READ ONLY');
            $rows = $c2->select(self::BASELINE_SQL);
            try {
                $c2->statement('CREATE TEMP TABLE t274_probe (x int)');
                $this->fail('a write inside the baseline transaction must be refused');
            } catch (\Illuminate\Database\QueryException $e) {
                $this->assertStringContainsString('read-only', $e->getMessage());
            }

            return $rows;
        });

        $mine = array_values(array_filter($rows, fn ($r) => $r->election_id === $this->election->id));
        $this->assertCount(1, $mine);
        $this->assertSame([1, 1, 1], [(int) $mine[0]->v, (int) $mine[0]->c, (int) $mine[0]->s]);

        $mismatches = array_values(array_filter($rows, fn ($r) => !((int) $r->v === (int) $r->c && (int) $r->c === (int) $r->s)));
        fwrite(STDERR, sprintf("\nS9B-BASELINE elections=%d mismatched=%d (reported, never corrected; O-6b = i)\n", count($rows), count($mismatches)));
    }
}
