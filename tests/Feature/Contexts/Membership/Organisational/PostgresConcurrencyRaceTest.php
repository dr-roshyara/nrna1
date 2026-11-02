<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\AdmitMember;
use App\Contexts\Membership\Application\Organisational\AdmitMemberHandler;
use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\EndMembership;
use App\Contexts\Membership\Application\Organisational\EndMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * Slice 1b contract T12 (optional, additional evidence): real concurrent processes
 * (pcntl_fork), each with its own PostgreSQL connection, released together at a
 * barrier. This is evidence in addition to the transaction design, not a substitute
 * for it. PBDIGIT-260.
 */
#[Group('membership-race')]
final class PostgresConcurrencyRaceTest extends MembershipPostgresTestCase
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
     * @param bool $full also record the Appended fact id and recordedAt (F-2: identical outcomes, not just seq)
     * @return list<array>
     */
    private function race(\Closure $work, bool $full = false): array
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
                    if ($full && $o->isAppended()) {
                        $result[] = $o->appended()->factId()->value();
                        $result[] = $o->appended()->recordedAt()->ordinal();
                    }
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
                ->handle(new AdmitMember($org, 'P1', "M-$c", "A-$c", 100, 100, null, true, 'registrar', 'decl-fixture', null, "k-$c")));

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
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, 100, null, true, 'registrar', 'decl-fixture', null, 'k-admit'));

            $results = $this->race(fn (int $c) => (new EndMembershipHandler(self::repository(), new FakeAuthority()))
                ->handle(new EndMembership($org, 'M1', "E-$c", 'A1', 200 + $c, 200 + $c, 'resignation', 'registrar', null, "k-end-$c")));

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
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, 100, null, true, 'registrar', 'decl-fixture', null, 'k-admit'));

            $results = $this->race(fn (int $c) => (new EndMembershipHandler(self::repository(), new FakeAuthority()))
                ->handle(new EndMembership($org, 'M1', 'E1', 'A1', 200, 200, 'resignation', 'registrar', null, 'same-key')));

            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->count(), "round $round");
            $this->assertSame(array_fill(0, self::PROCESSES, ['appended', 'M1', 2]), $results, "round $round: every caller gets the original Appended");
        }
    }

    // ── PBDIGIT-264 F-2 (Part 2 plan step 4, AC-6; ratification pack §2 "F-2"): coverage, expected to pass ──

    public function test_f2a_concurrent_identical_admissions_of_a_new_person_create_one_member_and_one_fact_and_answer_alike(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();

            $results = $this->race(fn (int $c) => (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, 100, null, true, 'registrar', 'decl-fixture', null, 'k-same')), true);

            $this->assertSame([], array_values(array_filter($results, static fn ($r) => $r[0] !== 'appended')), "round $round: every caller is answered with Appended " . json_encode($results));
            $this->assertSame(1, DB::table('org_memberships')->where('organisation_id', $org)->count(), "round $round: one Member");
            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->count(), "round $round: one fact");
            $this->assertSame(['appended', 'M1', 1, 'A1'], array_slice($results[0], 0, 4), "round $round");
            $this->assertSame(array_fill(0, self::PROCESSES, $results[0]), $results, "round $round: identical Appended (member, seq, fact, recordedAt) for all");
        }
    }

    public function test_f2b_concurrent_admissions_of_different_persons_under_one_key_append_once_and_refuse_the_rest(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();
            $repo = self::repository();
            for ($c = 0; $c < self::PROCESSES; $c++) {
                $repo->create(TenantId::fromString($org), MemberId::fromString("M-$c"), PersonRef::fromString("P-$c"));
            }

            $results = $this->race(fn (int $c) => (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, "P-$c", "M-$c", "A-$c", 100, 100, null, true, 'registrar', 'decl-fixture', null, 'k-shared')));

            $this->assertSame([], array_values(array_filter($results, static fn ($r) => $r[0] === 'error')), "round $round: no errors " . json_encode($results));
            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->count(), "round $round: exactly one fact in the tenant");
            $this->assertCount(1, array_filter($results, static fn ($r) => $r[0] === 'appended'), "round $round: " . json_encode($results));
            $this->assertCount(self::PROCESSES - 1, array_filter($results, static fn ($r) => $r === ['refused', 'idempotency_key_conflict']), "round $round: " . json_encode($results));
            $this->assertSame(self::PROCESSES - 1, DB::table('org_memberships')->where('organisation_id', $org)->where('version', 0)->count(), "round $round: the losers' versions stay 0");
        }
    }

    // ── PBDIGIT-264 F-5 (Part 2 plan step 2, AC-3): the adapter's READ COMMITTED assumption is enforced, not inherited ──

    /** Each child's session defaults to REPEATABLE READ before it works; the adapter must still behave per contract. */
    private static function underRepeatableRead(\Closure $work): \Closure
    {
        return static function (int $c) use ($work) {
            DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION ISOLATION LEVEL REPEATABLE READ');

            return $work($c);
        };
    }

    public function test_f5_concurrent_different_ends_under_a_repeatable_read_session_still_record_exactly_one_end(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();
            (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, 100, null, true, 'registrar', 'decl-fixture', null, 'k-admit'));

            $results = $this->race(self::underRepeatableRead(fn (int $c) => (new EndMembershipHandler(self::repository(), new FakeAuthority()))
                ->handle(new EndMembership($org, 'M1', "E-$c", 'A1', 200 + $c, 200 + $c, 'resignation', 'registrar', null, "k-end-$c"))));

            $this->assertSame([], array_values(array_filter($results, static fn ($r) => $r[0] === 'error')), "round $round: errors " . json_encode($results));
            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->count(), "round $round: one End");
            $this->assertCount(1, array_filter($results, static fn ($r) => $r[0] === 'appended'), "round $round: " . json_encode($results));
            foreach (array_filter($results, static fn ($r) => $r[0] !== 'appended') as $r) {
                $this->assertContains($r, [['refused', 'period_already_ended'], ['refused', 'concurrent_modification']], "round $round");
            }
        }
    }

    public function test_f5_concurrent_identical_retries_under_a_repeatable_read_session_return_the_same_outcome(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $org = $this->organisation();
            (new AdmitMemberHandler(self::repository(), new FakeAuthority()))
                ->handle(new AdmitMember($org, 'P1', 'M1', 'A1', 100, 100, null, true, 'registrar', 'decl-fixture', null, 'k-admit'));

            $results = $this->race(self::underRepeatableRead(fn (int $c) => (new EndMembershipHandler(self::repository(), new FakeAuthority()))
                ->handle(new EndMembership($org, 'M1', 'E1', 'A1', 200, 200, 'resignation', 'registrar', null, 'same-key'))));

            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $org)->where('kind', 'end')->count(), "round $round");
            $this->assertSame(array_fill(0, self::PROCESSES, ['appended', 'M1', 2]), $results, "round $round: every caller gets the original Appended " . json_encode($results));
        }
    }
}
