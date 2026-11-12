<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\DueScheduledResignations;
use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockCloser;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerCloseInsideTransactionRefused;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-343 task 6 (plan 20261112-1251 D-4; D-209 §2; proofs LC-6, test 10, K-2 A1/A2): the closedThrough step.
 * - Free lock: read the database clock; if it has not regressed, (a) the scheduled-resignation hook, (b) advance.
 * - Busy lock (NOWAIT, SQLSTATE 55P03): that transaction is rolled back and never reused; the last COMMITTED value
 *   is read in a new statement. An uncommitted advance by the lock holder is never returned.
 * - Regression (reading before last_recorded_at or closed_through): no advance; the stored value is returned.
 * - Never inside a caller's transaction.
 */
final class PostgresLedgerClockCloserTest extends MembershipPostgresTestCase
{
    /** @var list<int> */
    private array $readings = [];

    private function reader(): ScriptedLedgerClockReader
    {
        return new ScriptedLedgerClockReader(fn (): int => array_shift($this->readings) ?? throw new \LogicException('unexpected reading'));
    }

    private function closer(?DueScheduledResignations $due = null, ?string $connection = null): LedgerClockCloser
    {
        return new LedgerClockCloser(DB::connection($connection), $this->reader(), $due);
    }

    private function organisationWithFactAt(int $reading): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'lc-close', 'slug' => 'lc-close-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        $repo = new PostgresMemberRepository(DB::connection(), $this->reader());
        $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('person-M1'))->member();
        $this->readings = [$reading];
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');

        return $t;
    }

    private function storedClosedThrough(TenantId $t): ?int
    {
        $v = DB::table('org_membership_ledger_clocks')->where('organisation_id', $t->value())->value(DB::raw(InstantMicros::selectExpression('closed_through')));

        return $v === null ? null : (int) $v;
    }

    private static function ordinal(?Instant $i): ?int
    {
        return $i?->ordinal();
    }

    public function test_lc6_the_closing_step_never_advances_on_a_regressed_clock_and_may_advance_at_equality(): void
    {
        $t = $this->organisationWithFactAt(5_000);

        $this->readings = [4_000];   // closed_through (null) < t < last_recorded_at
        $this->assertNull(self::ordinal($this->closer()->closeOrRead($t)));
        $this->assertNull($this->storedClosedThrough($t), 'stored value unchanged');

        $this->readings = [5_000];   // t = last_recorded_at: allowed
        $this->assertSame(5_000, self::ordinal($this->closer()->closeOrRead($t)));

        $this->readings = [4_500];   // t < closed_through
        $this->assertSame(5_000, self::ordinal($this->closer()->closeOrRead($t)));
        $this->assertSame(5_000, $this->storedClosedThrough($t));

        $this->readings = [6_000];
        $this->assertSame(6_000, self::ordinal($this->closer()->closeOrRead($t)));
        $this->assertSame(6_000, $this->storedClosedThrough($t));
    }

    public function test_an_organisation_without_facts_closes_at_the_reading(): void
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'lc-close', 'slug' => 'lc-close-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->readings = [7_000];

        $this->assertSame(7_000, self::ordinal($this->closer()->closeOrRead(TenantId::fromString($id))));
    }

    public function test_a_busy_clock_row_returns_the_last_committed_value_never_an_uncommitted_one(): void
    {
        $t = $this->organisationWithFactAt(5_000);
        $this->readings = [6_000];
        $this->closer()->closeOrRead($t);   // committed: 6 000
        config(['database.connections.pbdigit343_holder' => config('database.connections.' . config('database.default'))]);
        $holder = DB::connection('pbdigit343_holder');
        $holder->beginTransaction();
        $holder->update('UPDATE org_membership_ledger_clocks SET closed_through = ? WHERE organisation_id = ?', [InstantMicros::toTimestamp(Instant::of(9_000)), $t->value()]);   // holds the row lock, uncommitted

        try {
            $this->readings = [];   // a busy close takes no reading
            $busy = $this->closer()->closeOrRead($t);
            $nextStatementWorks = (int) DB::selectOne('SELECT 1 AS one')->one;
        } finally {
            $holder->rollBack();
        }

        $this->assertSame(6_000, self::ordinal($busy), 'the committed value, not the holder\'s uncommitted 9 000');
        $this->assertSame(1, $nextStatementWorks, 'the aborted transaction was not reused');
        $this->assertSame(0, DB::connection()->transactionLevel());
    }

    public function test_the_scheduled_resignation_hook_runs_only_when_the_lock_is_free_and_the_clock_has_not_regressed(): void
    {
        $t = $this->organisationWithFactAt(5_000);
        $hook = new class () implements DueScheduledResignations {
            /** @var list<int> */
            public array $calls = [];

            public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
            {
                $this->calls[] = $t->ordinal();
            }
        };

        $this->readings = [4_000];
        $this->closer($hook)->closeOrRead($t);   // regression: no hook
        $this->readings = [6_000];
        $this->closer($hook)->closeOrRead($t);

        $this->assertSame([6_000], $hook->calls);
    }

    public function test_a_failing_hook_rolls_the_close_back_and_returns_the_stored_value(): void
    {
        $t = $this->organisationWithFactAt(5_000);
        $this->readings = [6_000];
        $this->closer()->closeOrRead($t);
        $failing = new class () implements DueScheduledResignations {
            public function executeDue(Connection $db, TenantId $organisation, Instant $t): void
            {
                throw new \RuntimeException('a due declaration could not be executed');
            }
        };

        $this->readings = [8_000];
        $this->assertSame(6_000, self::ordinal($this->closer($failing)->closeOrRead($t)), 'D-209 (a): a failed execution leaves the boundary unchanged');
        $this->assertSame(6_000, $this->storedClosedThrough($t));
    }

    public function test_closing_inside_a_callers_transaction_is_refused(): void
    {
        $t = $this->organisationWithFactAt(5_000);
        $this->expectException(LedgerCloseInsideTransactionRefused::class);

        DB::transaction(function () use ($t): void {
            $this->closer()->closeOrRead($t);
        });
    }

    public function test_a_repeatable_read_session_default_does_not_change_the_close(): void
    {
        $t = $this->organisationWithFactAt(5_000);
        DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION ISOLATION LEVEL REPEATABLE READ');
        try {
            $this->readings = [6_000];
            $this->assertSame(6_000, self::ordinal($this->closer()->closeOrRead($t)));
        } finally {
            DB::statement('SET SESSION CHARACTERISTICS AS TRANSACTION ISOLATION LEVEL READ COMMITTED');
        }
    }
}
