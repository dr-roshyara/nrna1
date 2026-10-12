<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Services\Voting\VoterSlugIssuance;
use App\Services\Voting\VoterSlugIssuer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 G6: REAL concurrency of the voter-slug issuer (plan section 5.1): two separate PHP processes,
 * each with its own PostgreSQL connection (tests/Isolated/Pbdigit274/Concurrency/g6-worker.php), plus this
 * test's connection as the controller. The order is forced with a PostgreSQL advisory lock that the test holds
 * and the workers wait on at test-only pause points (D3); that a worker is really blocked is read from
 * pg_locks / pg_stat_activity before the test releases it. "cast" is a stand-in for the participation update
 * (D2), not the full cast path (S9).
 *
 * Isolated database only (phpunit.pbdigit274.xml); data is committed so the workers can see it, and removed
 * afterwards.
 */
final class VoterSlugIssuerRealConcurrencyTest extends IsolatedCommittedDatabaseTestCase
{
    private const WORKER = 'tests/Isolated/Pbdigit274/Concurrency/g6-worker.php';
    private const WAIT_SECONDS = 20;

    private Organisation $org;
    private Election $election;
    private User $voter;
    private int $key;

    /** @var list<string> */
    private array $organisations = [];
    /** @var list<string> */
    private array $users = [];
    /** @var list<array{process: resource, pipes: array}> */
    private array $workers = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->tenant()->create();
        $this->organisations[] = $this->org->id;
        $this->election = Election::factory()->forOrganisation($this->org)->real()->create();
        $this->voter = User::factory()->create();
        $this->users[] = $this->voter->id;
        $this->key = random_int(1_000_000, 2_000_000_000);
    }

    protected function tearDown(): void
    {
        foreach ($this->workers as $w) {
            if (is_resource($w['process'])) {
                proc_terminate($w['process']);
            }
        }
        DB::select('SELECT pg_advisory_unlock_all()');
        DB::table('organisations')->whereIn('id', $this->organisations)->delete();
        DB::table('users')->whereIn('id', $this->users)->delete();
        parent::tearDown();
    }

    // ── helpers ───────────────────────────────────────────────────────────

    private function hold(): void
    {
        DB::select('SELECT pg_advisory_lock(?)', [$this->key]);
    }

    private function release(): void
    {
        DB::select('SELECT pg_advisory_unlock(?)', [$this->key]);
    }

    private function start(array $job): int
    {
        $job += ['key' => $this->key];
        $process = proc_open([PHP_BINARY, base_path(self::WORKER), json_encode($job)], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, base_path());
        $this->assertIsResource($process);
        $this->workers[] = ['process' => $process, 'pipes' => $pipes];

        return array_key_last($this->workers);
    }

    /** @return array<string, mixed> the worker's JSON result */
    private function finish(int $index): array
    {
        $w = $this->workers[$index];
        $stdout = stream_get_contents($w['pipes'][1]);
        $stderr = stream_get_contents($w['pipes'][2]);
        fclose($w['pipes'][1]);
        fclose($w['pipes'][2]);
        $exit = proc_close($w['process']);
        $result = json_decode(trim((string) strrchr("\n".trim($stdout), "\n")), true);
        $this->assertSame(0, $exit, "worker failed: {$stdout} {$stderr}");
        $this->assertIsArray($result, "no JSON from worker: {$stdout} {$stderr}");

        return $result;
    }

    private function waitUntil(callable $condition, string $what): void
    {
        $deadline = microtime(true) + self::WAIT_SECONDS;
        while (microtime(true) < $deadline) {
            if ($condition()) {
                return;
            }
            usleep(50_000);
        }
        $this->fail("timed out waiting until {$what}");
    }

    private function advisoryWaiters(): int
    {
        return (int) DB::selectOne("SELECT count(*) AS n FROM pg_locks
            WHERE locktype = 'advisory' AND NOT granted AND classid = 0 AND objid = ?", [$this->key])->n;
    }

    private function waitingOnALock(string $name): bool
    {
        return DB::selectOne("SELECT count(*) AS n FROM pg_stat_activity
            WHERE application_name = ? AND wait_event_type = 'Lock'", ['pbd274-g6-'.$name])->n > 0;
    }

    private function slugRow(array $attributes = []): string
    {
        $id = (string) Str::uuid();
        DB::table('voter_slugs')->insert($attributes + [
            'id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'slug' => Str::random(32), 'is_active' => true, 'has_voted' => false,
            'status' => 'active', 'current_step' => 1, 'created_at' => now(), 'updated_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        return $id;
    }

    private function expiredSlugRow(): string
    {
        return $this->slugRow(['created_at' => now()->subMinutes(31), 'expires_at' => now()->subMinute()]);
    }

    private function codeRow(): string
    {
        $id = (string) Str::uuid();
        DB::table('codes')->insert([
            'id' => $id, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $this->voter->id, 'code1' => (string) random_int(100000, 999999), 'has_voted' => false,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return $id;
    }

    private function issueJob(string $name, ?string $pause = null): array
    {
        return ['role' => 'issue', 'name' => $name, 'voter' => $this->voter->id, 'election' => $this->election->id, 'pause' => $pause];
    }

    /** @return list<array<string, mixed>> */
    private function rows(): array
    {
        return array_map(fn ($r) => (array) $r, DB::table('voter_slugs')
            ->where('user_id', $this->voter->id)->where('election_id', $this->election->id)->orderBy('created_at')->get()->all());
    }

    private function currentRows(): array
    {
        return array_values(array_filter($this->rows(), fn ($r) => $r['deleted_at'] === null));
    }

    // ── G6 cases (plan 5.1) ───────────────────────────────────────────────

    public function test_no_row_two_processes_end_with_one_link_both_return(): void
    {
        $this->hold();
        $a = $this->start($this->issueJob('A', 'beforeInsert'));
        $b = $this->start($this->issueJob('B', 'beforeInsert'));
        $this->waitUntil(fn () => $this->advisoryWaiters() === 2, 'both issuers decided "no row" and wait before inserting');
        $this->release();

        $ra = $this->finish($a);
        $rb = $this->finish($b);

        $outcomes = [$ra['outcome'], $rb['outcome']];
        sort($outcomes);
        $this->assertSame([VoterSlugIssuance::ISSUED, VoterSlugIssuance::REUSED], $outcomes, 'one inserts; the other loses on the index and reuses');
        $this->assertSame($ra['slug_id'], $rb['slug_id'], 'both return the same link');
        $this->assertCount(1, $this->rows(), 'exactly one row in total');
    }

    public function test_expired_row_two_processes_end_with_one_replacement(): void
    {
        $old = $this->expiredSlugRow();
        $this->hold();
        $a = $this->start($this->issueJob('A', 'afterLocks'));
        $this->waitUntil(fn () => $this->advisoryWaiters() === 1, 'A holds the row lock and waits');
        $b = $this->start($this->issueJob('B'));
        $this->waitUntil(fn () => $this->waitingOnALock('B'), 'B is blocked on the row lock');
        $this->release();

        $ra = $this->finish($a);
        $rb = $this->finish($b);

        $this->assertSame(VoterSlugIssuance::ISSUED, $ra['outcome']);
        $this->assertSame(VoterSlugIssuance::REUSED, $rb['outcome']);
        $this->assertSame($ra['slug_id'], $rb['slug_id']);
        $rows = $this->rows();
        $this->assertCount(2, $rows, 'the old link and exactly one replacement');
        $this->assertSame($old, $rows[0]['id']);
        $this->assertNotNull($rows[0]['deleted_at']);
        $this->assertSame('expired', $rows[0]['status']);
        $this->assertCount(1, $this->currentRows());
    }

    public function test_cast_first_then_issuer_refuses_without_writing(): void
    {
        $code = $this->codeRow();
        $slug = $this->slugRow();
        $this->hold();
        $cast = $this->start(['role' => 'cast', 'name' => 'CAST', 'code' => $code, 'slug' => $slug, 'pause' => 'afterCas']);
        $this->waitUntil(fn () => $this->advisoryWaiters() === 1, 'the cast consumed participation and holds the code row');
        $issuer = $this->start($this->issueJob('ISSUER'));
        $this->waitUntil(fn () => $this->waitingOnALock('ISSUER'), 'the issuer is blocked on the code row');
        $this->release();

        $rc = $this->finish($cast);
        $ri = $this->finish($issuer);

        $this->assertSame(1, $rc['consumed']);
        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, $ri['outcome']);
        $this->assertNull($ri['slug_id'], 'never a link after the cast committed');
        $this->assertCount(1, $this->rows(), 'the issuer wrote nothing');
        $this->assertTrue((bool) $this->rows()[0]['has_voted']);
    }

    public function test_issuer_first_then_cast_ends_in_the_accepted_o13_state(): void
    {
        $code = $this->codeRow();
        $s1 = $this->expiredSlugRow();
        $this->hold();
        $issuer = $this->start($this->issueJob('ISSUER', 'afterLocks'));
        $this->waitUntil(fn () => $this->advisoryWaiters() === 1, 'the issuer holds the code and slug rows');
        $cast = $this->start(['role' => 'cast', 'name' => 'CAST', 'code' => $code, 'slug' => $s1]);
        $this->waitUntil(fn () => $this->waitingOnALock('CAST'), 'the cast is blocked on the code row');
        $this->release();

        $ri = $this->finish($issuer);
        $rc = $this->finish($cast);

        // The issuer committed first: a replacement S2; then the cast consumed participation and marked S1.
        $this->assertSame(VoterSlugIssuance::ISSUED, $ri['outcome']);
        $this->assertSame(1, $rc['consumed']);
        $this->assertTrue((bool) DB::table('codes')->where('id', $code)->value('has_voted'), 'one vote: participation consumed once');
        $first = (array) DB::table('voter_slugs')->where('id', $s1)->first();
        $this->assertNotNull($first['deleted_at']);
        $this->assertTrue((bool) $first['has_voted'], 'O-13 (a): the retired S1 became voted');
        $s2 = (array) DB::table('voter_slugs')->where('id', $ri['slug_id'])->first();
        $this->assertNull($s2['deleted_at']);
        $this->assertFalse((bool) $s2['has_voted'], 'S2 is current but unvoted');

        // S2 cannot be used to cast again: the participation CAS refuses.
        $again = $this->finish($this->start(['role' => 'cast', 'name' => 'CAST2', 'code' => $code, 'slug' => $ri['slug_id']]));
        $this->assertSame(0, $again['consumed']);

        // Any later issuance refuses (accepted residual end state).
        $entitled = new class () implements VotingEntitlementQuery {
            public function standingOf(string $u, string $e): VotingEntitlementReason { return VotingEntitlementReason::Entitled; }
            public function decide(string $u, string $e): VotingEntitlementReason { return VotingEntitlementReason::Entitled; }
        };
        $this->assertSame(VoterSlugIssuance::ALREADY_VOTED, (new VoterSlugIssuer($entitled))->issue($this->voter, $this->election)->outcome);
    }
}
