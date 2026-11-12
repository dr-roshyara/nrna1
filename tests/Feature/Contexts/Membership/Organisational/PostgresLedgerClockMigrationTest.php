<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\InstantMicros;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-343 task 2 (plan 20261112-1251 §6 test 17, D-10): the per-organisation ledger clock table.
 * - Shape: organisation_id uuid primary key; last_recorded_at and closed_through timestamptz, nullable;
 *   no foreign key (so the same migration works in a per-organisation database, slice 2).
 * - Backfill: last_recorded_at = the latest fact per organisation, closed_through null; it reads only the
 *   database it runs in, and re-running down() then up() gives the same rows.
 */
final class PostgresLedgerClockMigrationTest extends MembershipPostgresTestCase
{
    private const MIGRATION = 'database/migrations/2026_11_12_130000_create_org_membership_ledger_clocks_table.php';

    private function migration(): Migration
    {
        return require base_path(self::MIGRATION);
    }

    private function organisation(): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert([
            'id' => $id, 'name' => "Ledger clock test $id", 'slug' => 'lc-' . $id, 'type' => 'tenant',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        return TenantId::fromString($id);
    }

    /** Admits one member of $tenant at application-clock instant $micros (the pre-D-209 adapter). */
    private function admitAt(TenantId $tenant, string $member, int $micros): void
    {
        $repo = new PostgresMemberRepository(DB::connection(), fn (): int => $micros);
        $repo->create($tenant, MemberId::fromString($member), PersonRef::fromString("P-$member"));
        $loaded = $repo->load($tenant, MemberId::fromString($member));
        $decision = $loaded->decideAdmit(FactId::fromString("A-$member"), TimePoint::of(1), TimePoint::of(1), TimePoint::infinity(), Provenance::decision('registrar', null, 'decl-fixture'));
        $repo->append($tenant, MemberId::fromString($member), $decision, IdempotencyKey::fromString("k-$member"), 'fp');
    }

    /** @return array<string, array{last: ?int, closed: ?string}> */
    private function clockRows(TenantId ...$tenants): array
    {
        $rows = [];
        foreach ($tenants as $tenant) {
            $row = DB::table('org_membership_ledger_clocks')
                ->where('organisation_id', $tenant->value())
                ->select([DB::raw(InstantMicros::selectExpression('last_recorded_at') . ' AS last_us'), 'closed_through'])
                ->first();
            $rows[$tenant->value()] = $row === null ? ['last' => null, 'closed' => 'NO ROW'] : ['last' => $row->last_us === null ? null : (int) $row->last_us, 'closed' => $row->closed_through];
        }

        return $rows;
    }

    public function test_the_table_has_the_planned_columns(): void
    {
        $columns = DB::select(
            "SELECT column_name, data_type, is_nullable FROM information_schema.columns
             WHERE table_schema = current_schema() AND table_name = 'org_membership_ledger_clocks' ORDER BY column_name",
        );
        $shape = array_map(static fn (object $c): string => "{$c->column_name}:{$c->data_type}:{$c->is_nullable}", $columns);

        $this->assertSame([
            'closed_through:timestamp with time zone:YES',
            'last_recorded_at:timestamp with time zone:YES',
            'organisation_id:uuid:NO',
        ], $shape);
    }

    public function test_organisation_id_is_the_primary_key_and_there_is_no_foreign_key(): void
    {
        $constraints = DB::select(
            "SELECT tc.constraint_type, kcu.column_name FROM information_schema.table_constraints tc
             JOIN information_schema.key_column_usage kcu ON kcu.constraint_name = tc.constraint_name AND kcu.table_schema = tc.table_schema
             WHERE tc.table_schema = current_schema() AND tc.table_name = 'org_membership_ledger_clocks'
             ORDER BY tc.constraint_type, kcu.column_name",
        );

        $this->assertSame(['PRIMARY KEY:organisation_id'], array_map(static fn (object $c): string => "{$c->constraint_type}:{$c->column_name}", $constraints));
    }

    public function test_the_backfill_takes_the_latest_fact_of_each_organisation_and_leaves_nothing_closed(): void
    {
        $o1 = $this->organisation();
        $o2 = $this->organisation();
        $this->admitAt($o1, 'M1', 1_760_000_000_000_100);
        $this->admitAt($o1, 'M2', 1_760_000_000_000_300);
        $this->admitAt($o2, 'M3', 1_760_000_000_000_200);
        $empty = $this->organisation();

        $migration = $this->migration();
        $migration->down();
        $migration->up();

        $this->assertSame([
            $o1->value() => ['last' => 1_760_000_000_000_300, 'closed' => null],
            $o2->value() => ['last' => 1_760_000_000_000_200, 'closed' => null],
            $empty->value() => ['last' => null, 'closed' => 'NO ROW'],
        ], $this->clockRows($o1, $o2, $empty));
    }

    public function test_running_down_and_up_again_gives_the_same_rows(): void
    {
        $o1 = $this->organisation();
        $this->admitAt($o1, 'M1', 1_760_000_000_000_500);
        $migration = $this->migration();
        $migration->down();
        $migration->up();
        $first = $this->clockRows($o1);

        $migration->down();
        $migration->up();

        $this->assertSame($first, $this->clockRows($o1));
        $this->assertSame(1_760_000_000_000_500, $first[$o1->value()]['last']);
    }
}
