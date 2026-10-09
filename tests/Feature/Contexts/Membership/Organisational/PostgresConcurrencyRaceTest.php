<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * Slice 1b contract T12 (optional, additional evidence): real concurrent processes
 * (pcntl_fork), each with its own PostgreSQL connection, released together at a
 * barrier. This is evidence in addition to the transaction design, not a substitute
 * for it. PBDIGIT-260.
 */
#[Group('membership-race')]
final class PostgresConcurrencyRaceTest extends TestCase
{
    private const ROUNDS = 8;
    private const PROCESSES = 4;

    protected function setUp(): void
    {
        parent::setUp();
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
    }

    private function organisation(): string
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'm1b-race', 'slug' => 'm1b-race-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);

        return $id;
    }

    private static function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), static fn (): int => (int) (microtime(true) * 1_000_000));
    }

    /**
     * Runs $work in PROCESSES forked children released together; returns each child's normalised outcome.
     *
     * @param \Closure(int $child): CommandOutcome $work
     * @return list<array>
     */
    private function race(\Closure $work): array
    {
        $dir = sys_get_temp_dir() . '/m1b-race-' . Str::random(8);
        mkdir($dir);
        $barrier = "$dir/go";
        DB::disconnect();   // children open their own connections; the parent's socket is never shared

        $pids = [];
        for ($c = 0; $c < self::PROCESSES; $c++) {
            $pid = pcntl_fork();
            if ($pid === 0) {
                DB::purge();
                $deadline = microtime(true) + 5;
                while (!file_exists($barrier) && microtime(true) < $deadline) {
                    usleep(200);
                }
                try {
                    $o = $work($c);
                    $result = $o->isAppended()
                        ? ['appended', $o->appended()->memberId()->value(), $o->appended()->seq()]
                        : ['refused', ($o->domainRefusal() ?? $o->applicationRefusal())->value];
                } catch (\Throwable $e) {
                    $result = ['error', $e::class . ': ' . $e->getMessage()];
                }
                file_put_contents("$dir/$c.json", json_encode($result));
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);   // no PHPUnit shutdown in the child
            }
            $pids[] = $pid;
        }
        usleep(50_000);
        touch($barrier);
        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }
        DB::reconnect();

        $results = [];
        for ($c = 0; $c < self::PROCESSES; $c++) {
            $results[] = json_decode((string) @file_get_contents("$dir/$c.json"), true) ?? ['error', 'no result'];
        }
        array_map('unlink', glob("$dir/*") ?: []);
        rmdir($dir);

        return $results;
    }

    public function test_concurrent_first_admissions_of_one_person_create_one_member_and_one_period(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();

            $results = $this->race(fn (int $c) => (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, 'P1', "M-$c", "A-$c", 100, null, true, 'registrar', null, null, "k-$c")));

            $this->assertSame([], array_values(array_filter($results, static fn ($r) => $r[0] === 'error')), "round $round: errors " . json_encode($results));
            $this->assertSame(1, DB::table('org_memberships')->where('organisation_id', $org)->count(), "round $round: INV-M");
            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->count(), "round $round: one admission");
            $this->assertCount(1, array_filter($results, static fn ($r) => $r[0] === 'appended'), "round $round: " . json_encode($results));
        }
    }

    public function test_concurrent_different_ends_of_one_period_record_exactly_one_end(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();
            (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, null, true, 'registrar', null, null, 'k-admit'));

            $results = $this->race(fn (int $c) => (new EndMembershipHandler(self::repository(), new FakeAuthority()))
                ->handle(new EndMembership($org, 'M1', "E-$c", 'A1', 200 + $c, 'resignation', 'registrar', null, "k-end-$c")));

            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->count(), "round $round: one End " . json_encode($results));
            $this->assertCount(1, array_filter($results, static fn ($r) => $r[0] === 'appended'), "round $round: " . json_encode($results));
            foreach (array_filter($results, static fn ($r) => $r[0] !== 'appended') as $r) {
                $this->assertContains($r, [['refused', 'period_already_ended'], ['refused', 'concurrent_modification']], "round $round: losers are re-decided, never appended");
            }
            $this->assertSame(2, DB::table('org_memberships')->where('organisation_id', $org)->value('version'));
        }
    }

    public function test_concurrent_identical_retries_record_one_fact_and_return_the_same_outcome(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();
            (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, null, true, 'registrar', null, null, 'k-admit'));

            $results = $this->race(fn (int $c) => (new EndMembershipHandler(self::repository(), new FakeAuthority()))
                ->handle(new EndMembership($org, 'M1', 'E1', 'A1', 200, 'resignation', 'registrar', null, 'same-key')));

            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->count(), "round $round");
            $this->assertSame(array_fill(0, self::PROCESSES, ['appended', 'M1', 2]), $results, "round $round: every caller gets the original Appended");
        }
    }
}
