<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Isolated\Pbdigit274\IsolatedCommittedDatabaseTestCase;

/**
 * PBDIGIT-274 S9b: the P1 count invariant under REAL concurrency (plan section 10.2 (2), G6).
 *
 * Separate PHP processes (tests/Isolated/Pbdigit274/Concurrency/g6-worker.php), each with its own PostgreSQL
 * connection, cast for two different voters while one of them also submits twice at the same moment. The
 * overlap is forced: the first submission pauses after its participation compare-and-set while holding the
 * codes row lock (test-only pause, D3); that the duplicate is really blocked on that row is read from
 * pg_stat_activity before the test releases it.
 *
 * Limit (decision D2): "cast" is the G6 stand-in (codes CAS plus slug marking, as VoteController does), so this
 * proves ΔC = ΔS and exactly one participation per voter; ΔV is proven by CountInvariantTest with real casts.
 *
 * Isolated database only; committed data, removed afterwards.
 */
final class CountInvariantRealConcurrencyTest extends IsolatedCommittedDatabaseTestCase
{
    private const WORKER = 'tests/Isolated/Pbdigit274/Concurrency/g6-worker.php';
    private const WAIT_SECONDS = 20;

    private Organisation $org;
    private Election $election;
    private int $key;

    /** @var list<string> */
    private array $users = [];
    /** @var list<array{process: resource, pipes: array}> */
    private array $workers = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->tenant()->create();
        $this->election = Election::factory()->forOrganisation($this->org)->real()->create();
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
        try {
            DB::table('voter_slugs')->where('election_id', $this->election->id)->delete();
            DB::table('codes')->where('election_id', $this->election->id)->delete();
            DB::table('elections')->where('id', $this->election->id)->delete();
            DB::table('organisations')->where('id', $this->org->id)->delete();
            DB::table('users')->whereIn('id', $this->users)->delete();
        } catch (\Throwable) {
            // isolated, wipeable database
        }
        parent::tearDown();
    }

    /** @return array{string, string} [code id, slug id] for a fresh voter who has not voted */
    private function voterRows(): array
    {
        $user = User::factory()->create();
        $this->users[] = $user->id;
        $code = (string) Str::uuid();
        DB::table('codes')->insert(['id' => $code, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'code1' => (string) random_int(100000, 999999), 'has_voted' => false,
            'created_at' => now(), 'updated_at' => now()]);
        $slug = (string) Str::uuid();
        DB::table('voter_slugs')->insert(['id' => $slug, 'organisation_id' => $this->org->id, 'election_id' => $this->election->id,
            'user_id' => $user->id, 'slug' => Str::random(32), 'is_active' => true, 'has_voted' => false, 'status' => 'active',
            'current_step' => 4, 'created_at' => now(), 'updated_at' => now(), 'expires_at' => now()->addMinutes(30)]);

        return [$code, $slug];
    }

    private function start(array $job): int
    {
        $job += ['key' => $this->key];
        $process = proc_open([PHP_BINARY, base_path(self::WORKER), json_encode($job)], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, base_path());
        $this->assertIsResource($process);
        $this->workers[] = ['process' => $process, 'pipes' => $pipes];

        return array_key_last($this->workers);
    }

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

    private function counts(): array
    {
        return [
            'c' => DB::table('codes')->where('election_id', $this->election->id)->where('has_voted', true)->count(),
            's' => DB::table('voter_slugs')->where('election_id', $this->election->id)->where('has_voted', true)->count(),
        ];
    }

    public function test_p1_concurrent_casts(): void
    {
        [$codeX, $slugX] = $this->voterRows();
        [$codeY, $slugY] = $this->voterRows();
        $before = $this->counts();

        DB::select('SELECT pg_advisory_lock(?)', [$this->key]);
        $x1 = $this->start(['role' => 'cast', 'name' => 'X1', 'code' => $codeX, 'slug' => $slugX, 'pause' => 'afterCas']);
        $this->waitUntil(fn () => (int) DB::selectOne("SELECT count(*) AS n FROM pg_locks WHERE locktype = 'advisory'
            AND NOT granted AND classid = 0 AND objid = ?", [$this->key])->n === 1, 'X1 consumed participation and holds the code row');

        $x2 = $this->start(['role' => 'cast', 'name' => 'X2', 'code' => $codeX, 'slug' => $slugX, 'pause' => null]);
        $this->waitUntil(fn () => DB::selectOne("SELECT count(*) AS n FROM pg_stat_activity WHERE application_name = ?
            AND wait_event_type = 'Lock'", ['pbd274-g6-X2'])->n > 0, 'the duplicate X2 is blocked on the same code row');

        $y = $this->start(['role' => 'cast', 'name' => 'Y', 'code' => $codeY, 'slug' => $slugY, 'pause' => null]);
        $ry = $this->finish($y);                                  // another voter is not held up by X's lock

        DB::select('SELECT pg_advisory_unlock(?)', [$this->key]);
        $rx1 = $this->finish($x1);
        $rx2 = $this->finish($x2);

        $after = $this->counts();
        fwrite(STDERR, "\nS9B-CONCURRENCY X1=".json_encode($rx1).' X2='.json_encode($rx2).' Y='.json_encode($ry)
            .' before='.json_encode($before).' after='.json_encode($after)."\n");

        $this->assertSame([1, 0, 1], [$rx1['consumed'], $rx2['consumed'], $ry['consumed']], 'one participation per voter; the duplicate consumed nothing');
        $this->assertSame(['c' => 2, 's' => 2], ['c' => $after['c'] - $before['c'], 's' => $after['s'] - $before['s']], 'ΔC = ΔS = successful casts');
    }
}
