<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Notifications\SendVoteSavingCode;
use App\Services\ElectionAuditService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S9a: the cast success / failure contract (plan section 9, L-16 / L-17 / L-18, gate G7).
 *
 * Contract L-16: the voter is told "success" only if the vote, the participation (codes.has_voted) and the slug's
 * has_voted committed. L-17: once the cast has committed, a failure in post-commit work must not tell the voter to
 * "try again". L-18 (O-15 = a): characterise whether the "vote confirmed" email goes out for a cast whose commit
 * fails. If the current code violates L-16 or L-17 the test fails and S9 STOPS for a PO / ARB decision; nothing is
 * repaired in 274.
 *
 * Mechanics (section 9.1): C1 = the default connection, shared in-process by the test and the HTTP request; failures
 * are injected with uniquely named plpgsql triggers created on C1 outside any transaction. C2 = a second connection
 * to the same, identity-checked database that reads committed state only and drops the triggers. The audit service
 * is replaced by a stub (no files written; L-17a makes it throw). Isolated database only; committed data, removed.
 */
final class CastSuccessContractTest extends IsolatedCommittedDatabaseTestCase
{
    private const C2 = 't274_c2';
    private const VOTING_CODE = 'ABCD1234';

    private Organisation $org;
    private Election $election;
    private User $voter;
    private VoterSlug $slug;
    private string $postId;
    private string $candidacyId;

    /** @var list<array{string, string, string}> [trigger, table, function] */
    private array $injected = [];

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        config(['voting.time_in_minutes' => 30]);

        // C2: a clone of the identity-checked default connection with its own session.
        config(['database.connections.'.self::C2 => config('database.connections.'.config('database.default'))]);
        $this->assertSame(
            DB::connection()->selectOne('SELECT current_database() AS d')->d,
            DB::connection(self::C2)->selectOne('SELECT current_database() AS d')->d,
        );
        $this->assertNotSame(
            DB::connection()->selectOne('SELECT pg_backend_pid() AS p')->p,
            DB::connection(self::C2)->selectOne('SELECT pg_backend_pid() AS p')->p,
            'C2 has its own database session',
        );

        $this->app->instance(ElectionAuditService::class, new class extends ElectionAuditService {
            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
            }

            public function logVoterAction($election, $voter, int $step, string $action, array $metadata = []): void
            {
            }
        });

        $this->org = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'pbd274-s9a-'.uniqid(), 'uses_full_membership' => false]);
        $this->election = Election::factory()->forOrganisation($this->org)->real()->inVotingState()->create([
            'status' => 'active', 'name' => 'PBDIGIT-274 cast contract '.uniqid(), 'slug' => 'pbd274-s9a-election-'.uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
            'voting_locked' => true, 'voting_starts_at' => now()->subHour(), 'voting_ends_at' => now()->addHours(2),
        ]);
        $this->postId = (string) Str::uuid();
        DB::table('posts')->insert(['id' => $this->postId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'name' => 'PBDIGIT-274 post', 'required_number' => 1, 'is_national_wide' => true, 'created_at' => now(), 'updated_at' => now()]);
        $this->candidacyId = (string) Str::uuid();
        DB::table('candidacies')->insert(['id' => $this->candidacyId, 'organisation_id' => $this->org->id, 'post_id' => $this->postId,
            'status' => 'approved', 'created_at' => now(), 'updated_at' => now()]);

        $this->voter = User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now(), 'region' => null]);
        DB::table('user_organisation_roles')->insert(['id' => (string) Str::uuid(), 'user_id' => $this->voter->id,
            'organisation_id' => $this->org->id, 'role' => 'member', 'created_at' => now(), 'updated_at' => now()]);
        ElectionMembership::create(['user_id' => $this->voter->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'voter', 'status' => 'active']);

        $slugId = (string) Str::uuid();
        DB::table('voter_slugs')->insert(['id' => $slugId, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'slug' => Str::random(40), 'is_active' => true, 'has_voted' => false, 'status' => 'active',
            'current_step' => 4, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30)]);
        $this->slug = VoterSlug::withoutGlobalScopes()->findOrFail($slugId);

        DB::table('codes')->insert(['id' => (string) Str::uuid(), 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => '111111', 'code_to_open_voting_form' => self::VOTING_CODE,
            'code_to_save_vote' => 'SAVE1234', 'is_code_to_open_voting_form_usable' => 0, 'code_to_open_voting_form_used_at' => now(),
            'is_code_to_save_vote_usable' => 1, 'can_vote_now' => 1, 'has_voted' => false, 'vote_submitted' => 0,
            'has_agreed_to_vote' => 1, 'client_ip' => '127.0.0.1', 'session_name' => 'vote_data_'.$this->voter->id,
            'voting_time_in_minutes' => 30, 'created_at' => now(), 'updated_at' => now()]);
    }

    protected function tearDown(): void
    {
        $failure = null;
        try {
            while (DB::connection()->transactionLevel() > 0) {
                DB::connection()->rollBack();
            }
            foreach ($this->injected as [$trigger, $table, $function]) {
                DB::connection(self::C2)->statement("DROP TRIGGER IF EXISTS {$trigger} ON {$table}");
                DB::connection(self::C2)->statement("DROP FUNCTION IF EXISTS {$function}()");
            }
            $left = DB::connection(self::C2)->selectOne("SELECT (SELECT count(*) FROM pg_trigger WHERE tgname LIKE 't274\\_%')
                + (SELECT count(*) FROM pg_proc WHERE proname LIKE 't274\\_%') AS n")->n;
            if ((int) $left !== 0) {
                $failure = "{$left} injected trigger(s) / function(s) left behind";
            }
        } catch (\Throwable $e) {
            $failure = 'trigger cleanup failed: '.$e->getMessage();
        }

        try {
            $c2 = DB::connection(self::C2);
            $voteIds = $c2->table('votes')->where('election_id', $this->election->id)->pluck('id');
            $c2->table('results')->whereIn('vote_id', $voteIds)->delete();
            $c2->table('votes')->where('election_id', $this->election->id)->delete();
            $c2->table('receipt_codes')->where('election_id', $this->election->id)->delete();
            $c2->table('voter_slug_steps')->where('election_id', $this->election->id)->delete();
            $c2->table('voter_slugs')->where('election_id', $this->election->id)->delete();
            $c2->table('codes')->where('election_id', $this->election->id)->delete();
            $c2->table('election_memberships')->where('election_id', $this->election->id)->delete();
            $c2->table('candidacies')->where('post_id', $this->postId)->delete();
            $c2->table('posts')->where('id', $this->postId)->delete();
            $c2->table('user_organisation_roles')->where('user_id', $this->voter->id)->delete();
            $c2->table('elections')->where('id', $this->election->id)->delete();
            $c2->table('organisations')->where('id', $this->org->id)->delete();
            $c2->table('users')->where('id', $this->voter->id)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        DB::purge(self::C2);
        parent::tearDown();

        if ($failure !== null) {
            $this->fail($failure);
        }
    }

    // ── helpers ──────────────────────────────────────────────────────────────────────────────────────────

    /** A plpgsql trigger raising 't274 injected' (created on C1, outside any transaction, so it commits). */
    private function inject(string $table, bool $atCommit = false): void
    {
        $this->assertSame(0, DB::connection()->transactionLevel());
        $name = 't274_fail_'.substr(bin2hex(random_bytes(6)), 0, 12);
        $function = $name.'_fn';
        DB::statement("CREATE FUNCTION {$function}() RETURNS trigger LANGUAGE plpgsql AS \$\$ BEGIN RAISE EXCEPTION 't274 injected'; END \$\$");
        DB::statement($atCommit
            ? "CREATE CONSTRAINT TRIGGER {$name} AFTER INSERT ON {$table} DEFERRABLE INITIALLY DEFERRED FOR EACH ROW EXECUTE FUNCTION {$function}()"
            : "CREATE TRIGGER {$name} BEFORE INSERT ON {$table} FOR EACH ROW EXECUTE FUNCTION {$function}()");
        $this->injected[] = [$name, $table, $function];
    }

    private function cast(): TestResponse
    {
        $ballot = [
            'national_selected_candidates' => [[
                'post_id' => $this->postId, 'no_vote' => false, 'candidates' => [['candidacy_id' => $this->candidacyId]],
            ]],
            'regional_selected_candidates' => [],
            'no_vote_posts' => [],
        ];

        return $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id, 'vote_data_'.$this->voter->id => $ballot])
            ->post(route('slug.vote.store', ['vslug' => $this->slug->slug]), ['voting_code' => self::VOTING_CODE]);
    }

    /** Committed state, read on C2: [votes in the election, codes.has_voted, slug has_voted]. */
    private function committed(): array
    {
        $c2 = DB::connection(self::C2);

        return [
            'votes' => $c2->table('votes')->where('election_id', $this->election->id)->count(),
            'code_has_voted' => (bool) $c2->table('codes')->where('election_id', $this->election->id)->where('user_id', $this->voter->id)->value('has_voted'),
            'slug_has_voted' => (bool) $c2->table('voter_slugs')->where('id', $this->slug->id)->value('has_voted'),
            'receipts' => $c2->table('receipt_codes')->where('election_id', $this->election->id)->count(),
        ];
    }

    private function reportedSuccess(TestResponse $response): bool
    {
        return $response->isRedirect(route('vote.verify_to_show')) && session()->has('success');
    }

    private function toldToTryAgain(): bool
    {
        $errors = session('errors');

        return $errors !== null && str_contains(implode(' ', $errors->all()), 'try again');
    }

    /** Evidence line per test (section 9.2 minimum evidence); the slug string is never printed. */
    private function evidence(string $case, TestResponse $response, array $committed): void
    {
        $location = (string) $response->headers->get('Location', '-');
        $location = str_replace($this->slug->slug, '[slug]', $location);
        $flash = array_values(array_intersect(['success', 'errors', 'error'], array_keys(session()->all())));
        $lockFree = Cache::lock('voter_slug_transition:'.$this->slug->id, 1)->get();
        fwrite(STDERR, sprintf("\nS9A-EVIDENCE %s db=%s status=%d location=%s flash=%s committed=%s txlevel=%d lock_free=%s errors=%s\n",
            $case, DB::connection(self::C2)->selectOne('SELECT current_database() AS d')->d, $response->status(), $location,
            json_encode($flash), json_encode($committed), DB::connection()->transactionLevel(), $lockFree ? 'yes' : 'no',
            json_encode(session('errors')?->all() ?? [])));
        $this->assertSame(0, DB::connection()->transactionLevel(), 'the application left no transaction open');
        $this->assertTrue($lockFree, 'the cast lock was released');
    }

    // ── baseline ─────────────────────────────────────────────────────────────────────────────────────────

    public function test_a_normal_cast_reports_success_and_everything_committed(): void
    {
        $response = $this->cast();
        $committed = $this->committed();
        $this->evidence('baseline', $response, $committed);

        $this->assertTrue($this->reportedSuccess($response));
        $this->assertSame(['votes' => 1, 'code_has_voted' => true, 'slug_has_voted' => true, 'receipts' => 1], $committed);
        Notification::assertSentTo($this->voter, SendVoteSavingCode::class);
    }

    // ── G7: failures before the commit never report success (L-16) ───────────────────────────────────────

    public function test_failure_in_step5_record_never_reports_success(): void
    {
        $this->inject('voter_slug_steps');

        $response = $this->cast();
        $committed = $this->committed();
        $this->evidence('step5_failure', $response, $committed);

        $this->assertFalse($this->reportedSuccess($response), 'L-16: no success when the step-5 record fails');
        $this->assertSame(['votes' => 0, 'code_has_voted' => false, 'slug_has_voted' => false, 'receipts' => 0], $committed, 'nothing committed');
    }

    /**
     * Characterisation (finding F-A, PO decision 2026-10-13: accepted for 274, follow-up story). The plan expected
     * this failure to abort the cast; it does not: ReceiptCode::firstOrCreate runs in a savepoint (Laravel
     * createOrFirst), the controller swallows the error, and the cast commits WITHOUT a receipt row. L-16 holds
     * (vote, participation and slug committed), but the emailed receipt cannot be found later.
     */
    public function test_receipt_code_failure_is_absorbed_the_vote_counts_without_a_receipt(): void
    {
        $this->inject('receipt_codes');

        $response = $this->cast();
        $committed = $this->committed();
        $this->evidence('receipt_failure', $response, $committed);

        $this->assertTrue($this->reportedSuccess($response), 'L-16 holds: success with vote, participation and slug committed');
        $this->assertSame(['votes' => 1, 'code_has_voted' => true, 'slug_has_voted' => true, 'receipts' => 0], $committed, 'accepted residual: no receipt row');
    }

    // ── L-17: failures after the commit never tell the voter to try again ────────────────────────────────

    public function test_post_commit_audit_failure_never_reports_retry(): void
    {
        $levels = [];
        $this->app->instance(ElectionAuditService::class, new class ($levels) extends ElectionAuditService {
            public function __construct(private array &$levels)
            {
            }

            public function log($election, string $event, $user = null, string $category = 'voters', ?string $ip = null, array $metadata = []): void
            {
                $this->levels[] = DB::connection()->transactionLevel();
                throw new \RuntimeException('t274 injected audit failure');
            }
        });

        $response = $this->cast();
        $committed = $this->committed();
        $this->evidence('post_commit_audit_failure level_at_failure='.json_encode($levels), $response, $committed);

        $this->assertSame([0], $levels, 'the audit failure happened after the commit');
        $this->assertSame(['votes' => 1, 'code_has_voted' => true, 'slug_has_voted' => true, 'receipts' => 1], $committed, 'the vote is committed');
        $this->assertFalse($this->toldToTryAgain(), 'L-17a: a committed vote must not be answered with "please try again"');
        $this->assertTrue($this->reportedSuccess($response), 'PO 2026-10-13: the voter sees the success page');
    }

    public function test_post_commit_current_step_failure_never_reports_retry(): void
    {
        $levels = [];
        VoterSlug::updating(function (VoterSlug $slug) use (&$levels) {
            if ($slug->isDirty('current_step') && (int) $slug->current_step === 5) {
                $levels[] = DB::connection()->transactionLevel();
                throw new \RuntimeException('t274 injected current_step failure');
            }
        });

        $response = $this->cast();
        $committed = $this->committed();
        $this->evidence('post_commit_current_step_failure level_at_failure='.json_encode($levels), $response, $committed);

        $this->assertSame([0], $levels, 'the current_step failure happened after the commit');
        $this->assertSame(['votes' => 1, 'code_has_voted' => true, 'slug_has_voted' => true, 'receipts' => 1], $committed, 'the vote is committed');
        $this->assertFalse($this->toldToTryAgain(), 'L-17b: a committed vote must not be answered with "please try again"');
        $this->assertTrue($this->reportedSuccess($response), 'PO 2026-10-13: the voter sees the success page');
    }

    // ── L-18 (O-15 = a): the confirmation email versus a failed commit — characterisation ────────────────

    public function test_confirmation_email_vs_commit_failure(): void
    {
        $this->inject('votes', atCommit: true);

        $response = $this->cast();
        $committed = $this->committed();
        $sent = Notification::sent($this->voter, SendVoteSavingCode::class)->count();
        $this->evidence('commit_failure_email_sent='.$sent, $response, $committed);

        $this->assertSame(['votes' => 0, 'code_has_voted' => false, 'slug_has_voted' => false, 'receipts' => 0], $committed, 'the commit failed: nothing committed');
        $this->assertFalse($this->reportedSuccess($response), 'no success for a vote that did not commit');
        // Accepted residual (O-15 = a): the "vote confirmed" email is sent before the commit, so it went out anyway.
        $this->assertSame(1, $sent, 'characterisation: the pre-commit confirmation email is sent even though the commit failed');
    }
}
