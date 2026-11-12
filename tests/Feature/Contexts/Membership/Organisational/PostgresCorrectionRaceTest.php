<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\CommandOutcome;
use App\Contexts\Membership\Application\Organisational\DecideAndAppend;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\Correct;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresLedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;

/**
 * COR-4 step 6 (PBDIGIT-329, AC-7; contract §8 race cases; FP §16.10): concurrent correction commands on PostgreSQL,
 * through the existing DecideAndAppend loop (load → authorise → decide → append; re-decide on conflict).
 *
 * No COR-3 handler is written or bound (roadmap V-4 sequencing rule). The MembershipAct passed is a placeholder:
 * MembershipAct has no correction acts yet (COR-3), and FakeAuthority grants every act; authorisation is not under test.
 *
 * Order is not deterministic, so every round checks properties:
 * - no child errors; every refusal is a contract reason;
 * - the stored history reloads (load validation, step 1) with contiguous seq;
 * - every stored Correct's consequences equal a fresh kernel decision on the stored prefix before it: g2 is
 *   recomputed by the re-decide loop, never by the adapter;
 * - case-specific outcomes (e.g. never both a Correct and an End: the lost-update case).
 */
#[Group('membership-race')]
final class PostgresCorrectionRaceTest extends MembershipPostgresTestCase
{
    private const ROUNDS = 8;
    private const PROCESSES = 4;
    private const DECISION_DATE = 1_000_000;

    protected function setUp(): void
    {
        parent::setUp();
        if (!function_exists('pcntl_fork')) {
            $this->markTestSkipped('pcntl is not available');
        }
    }

    private static function repository(): PostgresMemberRepository
    {
        return new PostgresMemberRepository(DB::connection(), new PostgresLedgerClockReader());
    }

    private static function p(): Provenance
    {
        return Provenance::decision('registrar');
    }

    /** A0 = [0, 2) ended by E1, A1 = [2, ∞), C3 corrects E1 to 4 (trimming A1 by [2, 4)) when $withCorrection. */
    private function adjacentPeriods(bool $withCorrection): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'cor4-race', 'slug' => 'cor4-race-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        $repo = self::repository();
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
        $steps = [
            fn (Member $m) => $m->decideAdmit(FactId::fromString('A0'), TimePoint::of(0), TimePoint::of(self::DECISION_DATE), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')),
            fn (Member $m) => $m->decideEnd(FactId::fromString('E1'), PeriodId::fromString('A0'), TimePoint::of(2), TimePoint::of(self::DECISION_DATE), EndKind::fromString('resignation'), self::p()),
            fn (Member $m) => $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(2), TimePoint::of(self::DECISION_DATE), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')),
        ];
        if ($withCorrection) {
            $steps[] = fn (Member $m) => $m->decideCorrect(FactId::fromString('C3'), FactId::fromString('E1'), TimePoint::of(4), TimePoint::of(self::DECISION_DATE), self::p(), Instant::of(900));
        }
        foreach ($steps as $k => $step) {
            $this->assertTrue($repo->append($t, $m->id(), $step($m), IdempotencyKey::fromString("setup-$k"), "setup-$k")->outcome()->value === 'appended');
            $m = $repo->load($t, $m->id());
        }

        return $t;
    }

    /** @param \Closure(Member): Decision $decide */
    private static function command(TenantId $t, string $key, \Closure $decide): CommandOutcome
    {
        $repo = self::repository();

        return (new DecideAndAppend($repo, new FakeAuthority()))->run(
            $t, IdempotencyKey::fromString($key), "fp-$key", 'registrar', MembershipAct::End,   // placeholder act (COR-3)
            static fn () => $repo->load($t, MemberId::fromString('M1')),
            static fn (Member $m) => $decide($m),
        );
    }

    /**
     * Runs $work in PROCESSES forked children released together (same mechanism as PostgresConcurrencyRaceTest).
     *
     * @param \Closure(int $child): CommandOutcome $work
     * @return list<array{string, string}> [appended|refused|error, fact id or reason]
     */
    private function race(\Closure $work): array
    {
        $dir = sys_get_temp_dir() . '/cor4-race-' . Str::random(8);
        mkdir($dir);
        $barrier = "$dir/go";
        DB::disconnect();

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
                        ? ['appended', $o->appended()->factId()->value()]
                        : ['refused', ($o->domainRefusal() ?? $o->applicationRefusal())->value];
                } catch (\Throwable $e) {
                    $result = ['error', $e::class . ': ' . $e->getMessage()];
                }
                file_put_contents("$dir/$c.json", json_encode($result));
                DB::disconnect();
                posix_kill(posix_getpid(), SIGKILL);
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

    /**
     * The properties every round must satisfy; returns the appended fact ids.
     *
     * @param list<array{string, string}> $results
     * @param list<string> $allowedRefusals
     * @return list<string>
     */
    private function verify(TenantId $t, array $results, array $allowedRefusals, string $round): array
    {
        $this->assertSame([], array_values(array_filter($results, static fn ($r) => $r[0] === 'error')), "$round: errors " . json_encode($results));
        foreach ($results as $r) {
            if ($r[0] === 'refused') {
                $this->assertContains($r[1], $allowedRefusals, "$round: " . json_encode($results));
            }
        }

        $member = self::repository()->load($t, MemberId::fromString('M1'));   // load validation (step 1) passes
        $log = $member->log();
        $this->assertSame(range(1, count($log)), array_map(static fn ($r) => $r->seq(), $log), "$round: contiguous seq");
        foreach ($log as $k => $recorded) {
            $fact = $recorded->fact();
            if (!$fact instanceof Correct) {
                continue;
            }
            $prefix = Member::fromLog($member->id(), $member->holder(), $member->organisation(), ...array_slice($log, 0, $k));
            $fresh = $prefix->decideCorrect($fact->id(), $fact->target(), $fact->value(), TimePoint::of(self::DECISION_DATE), $fact->provenance(), $fact->decidedAt());
            $this->assertTrue($fresh->isAccepted(), "$round: stored Correct {$fact->id()->value()} is acceptable on its stored prefix");
            $this->assertEquals($fresh->fact()->consequences(), $fact->consequences(), "$round: stored consequences = g2 on the stored prefix");
        }

        $appended = array_map(static fn ($r) => $r[1], array_filter($results, static fn ($r) => $r[0] === 'appended'));
        $stored = array_map(static fn ($r) => $r->fact()->id()->value(), $log);
        foreach ($appended as $factId) {
            $this->assertContains($factId, $stored, "$round: an appended outcome names a stored fact");
        }

        return array_values($appended);
    }

    // Contract §8: Annul vs Correct on the same target (C3). Exactly one Annul of C3 is stored; the losing Annul is
    // refused (TargetNotLive); a Correct of C3 decided after the Annul is accepted inert (P-6), or ValueUnchanged.
    public function test_annul_and_correct_of_the_same_correction_race_to_contract_outcomes(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $t = $this->adjacentPeriods(true);

            $results = $this->race(fn (int $c) => $c < 2
                ? self::command($t, "annul-$c", fn (Member $m) => $m->decideAnnul(FactId::fromString("X-$c"), FactId::fromString('C3'), self::p(), Instant::of(950)))
                : self::command($t, "correct-$c", fn (Member $m) => $m->decideCorrect(FactId::fromString("C-$c"), FactId::fromString('C3'), TimePoint::of(5), TimePoint::of(self::DECISION_DATE), self::p())));

            $appended = $this->verify($t, $results, ['target_not_live', 'value_unchanged', 'concurrent_modification'], "round $round");
            $this->assertCount(1, array_filter($appended, static fn ($id) => str_starts_with($id, 'X-')), "round $round: exactly one Annul of C3 " . json_encode($results));
            $this->assertLessThanOrEqual(1, count(array_filter($appended, static fn ($id) => str_starts_with($id, 'C-'))), "round $round: at most one Correct to 5 " . json_encode($results));
        }
    }

    // Contract §8: two Corrects of the same fact (E1), with different values. Each appended one was decided on the
    // state it was appended to (re-decide), so its consequences are g2 of its stored prefix (verify()).
    public function test_two_corrections_of_the_same_fact_are_serialised_with_recomputed_consequences(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $t = $this->adjacentPeriods(false);

            $results = $this->race(fn (int $c) => self::command($t, "correct-$c", fn (Member $m) => $m->decideCorrect(
                FactId::fromString("C-$c"), FactId::fromString('E1'), TimePoint::of($c % 2 === 0 ? 3 : 4), TimePoint::of(self::DECISION_DATE), self::p(),
            )));

            $appended = $this->verify($t, $results, ['value_unchanged', 'concurrent_modification'], "round $round");
            $this->assertNotEmpty($appended, "round $round: at least one correction is stored");
        }
    }

    // FP §16.10, the smallest lost-update case: a Correct that extends A0 into A1 vs an End of A1 inside the extension.
    // In either order the second would leave A1 absorbed (ER, PeriodAbsorbed), so never both are stored.
    public function test_a_correction_and_an_end_on_the_adjacent_period_never_both_succeed(): void
    {
        for ($round = 0; $round < self::ROUNDS; $round++) {
            $t = $this->adjacentPeriods(false);

            $results = $this->race(fn (int $c) => $c < 2
                ? self::command($t, "correct-$c", fn (Member $m) => $m->decideCorrect(FactId::fromString("C-$c"), FactId::fromString('E1'), TimePoint::of(4), TimePoint::of(self::DECISION_DATE), self::p()))
                : self::command($t, "end-$c", fn (Member $m) => $m->decideEnd(FactId::fromString("E-$c"), PeriodId::fromString('A1'), TimePoint::of(3), TimePoint::of(self::DECISION_DATE), EndKind::fromString('resignation'), self::p())));

            $appended = $this->verify($t, $results, ['period_absorbed', 'value_unchanged', 'period_already_ended', 'concurrent_modification'], "round $round");
            $corrects = count(array_filter($appended, static fn ($id) => str_starts_with($id, 'C-')));
            $ends = count(array_filter($appended, static fn ($id) => str_starts_with($id, 'E-')));
            $this->assertSame(1, $corrects + $ends, "round $round: exactly one of the four is stored " . json_encode($results));
        }
    }
}
