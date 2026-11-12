<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership\Organisational;

use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * COR-4 step 4 (PBDIGIT-329; Q-1, Q-4, Q-5; contract §8): the schema of org_membership_facts for the five fact kinds,
 * checked by direct inserts that bypass the adapter. The database is the second line; the rule is load validation (Q-3).
 * Runs only on the PBDIGIT-324 throwaway cluster.
 */
final class PostgresCorrectionSchemaTest extends MembershipPostgresTestCase
{
    private const MIGRATION = 'database/migrations/2026_11_03_120000_extend_org_membership_facts_for_corrections.php';

    private function organisationWithMember(string $member = 'M1'): TenantId
    {
        $id = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $id, 'name' => 'cor4', 'slug' => 'cor4-' . $id, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $t = TenantId::fromString($id);
        (new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => 1_760_000_000_000_000)))->create($t, MemberId::fromString($member), PersonRef::fromString("person-$member"));
        $this->insert($t, ['seq' => 1, 'fact_id' => 'A1', 'kind' => 'admit', 'effective_at' => '2026-01-01', 'initial_term' => 'none'], $member);

        return $t;
    }

    /** @param array<string, mixed> $row */
    private function insert(TenantId $t, array $row, string $member = 'M1'): void
    {
        DB::table('org_membership_facts')->insert($row + [
            'organisation_id' => $t->value(), 'membership_id' => $member,
            'recorded_at' => '2026-01-01 00:00:00+00', 'provenance' => '{"kind":"decision","actor":"registrar"}',
        ]);
    }

    /** @return list<string> */
    private static function columns(): array
    {
        return array_map(static fn ($r) => $r->column_name, DB::select(
            "SELECT column_name FROM information_schema.columns WHERE table_name = 'org_membership_facts' ORDER BY ordinal_position",
        ));
    }

    private const CONSEQUENCES = '[{"period":"A1","removed":[["2026-01-01","2026-02-01"]]}]';

    public function test_the_schema_has_the_target_column_and_the_correction_columns(): void
    {
        $columns = self::columns();

        $this->assertContains('target_fact_id', $columns, 'Q-1: renamed');
        $this->assertNotContains('period_fact_id', $columns, 'Q-1: renamed, not duplicated');
        $this->assertContains('consequences', $columns, 'Q-5');
        $this->assertContains('g2_version', $columns, 'Q-5');
        $nullable = DB::selectOne("SELECT is_nullable FROM information_schema.columns WHERE table_name = 'org_membership_facts' AND column_name = 'effective_at'")->is_nullable;
        $this->assertSame('YES', $nullable, 'Q-4: Annul / Void carry no valid time');
    }

    public function test_well_formed_rows_of_every_new_kind_are_accepted(): void
    {
        $t = $this->organisationWithMember();
        $this->insert($t, ['seq' => 2, 'fact_id' => 'C2', 'kind' => 'correct', 'effective_at' => '2025-12-01', 'target_fact_id' => 'A1', 'consequences' => '[]', 'g2_version' => 1]);
        $this->insert($t, ['seq' => 3, 'fact_id' => 'X3', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'C2']);
        $this->insert($t, ['seq' => 4, 'fact_id' => 'V4', 'kind' => 'void', 'effective_at' => null, 'target_fact_id' => 'X3']);
        $this->insert($t, ['seq' => 5, 'fact_id' => 'C5', 'kind' => 'correct', 'effective_at' => '2025-11-01', 'target_fact_id' => 'A1', 'consequences' => self::CONSEQUENCES, 'g2_version' => 1]);

        $this->assertSame(5, DB::table('org_membership_facts')->where('organisation_id', $t->value())->count());
    }

    /** @return array<string, array{array<string, mixed>}> rows at seq 2 that a CHECK must refuse */
    public static function malformedRows(): array
    {
        $annul = ['fact_id' => 'X2', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'A1'];
        $correct = ['fact_id' => 'C2', 'kind' => 'correct', 'effective_at' => '2025-12-01', 'target_fact_id' => 'A1', 'consequences' => '[]', 'g2_version' => 1];

        return [
            'unknown kind' => [['kind' => 'renew'] + $annul],
            'annul with a valid time' => [['effective_at' => '2026-01-01'] + $annul],
            'annul without a target' => [['target_fact_id' => null] + $annul],
            'annul with consequences' => [['consequences' => '[]'] + $annul],
            'void with an end kind' => [['kind' => 'void', 'end_kind' => 'resignation'] + $annul],
            'correct without a value' => [['effective_at' => null] + $correct],
            'correct without a target' => [['target_fact_id' => null] + $correct],
            'correct without consequences' => [['consequences' => null] + $correct],
            'correct whose consequences are not an array' => [['consequences' => '{"period":"A1"}'] + $correct],
            'correct without g2_version' => [['g2_version' => null] + $correct],
            'correct with g2_version 0' => [['g2_version' => 0] + $correct],
            'correct with an initial term' => [['initial_term' => 'none'] + $correct],
            'end with consequences' => [['fact_id' => 'E2', 'kind' => 'end', 'effective_at' => '2026-06-01', 'target_fact_id' => 'A1', 'end_kind' => 'resignation', 'consequences' => '[]']],
            'admit with a g2_version' => [['fact_id' => 'A2', 'kind' => 'admit', 'effective_at' => '2026-06-01', 'initial_term' => 'none', 'g2_version' => 1]],
            'a fact targeting itself' => [['fact_id' => 'X2', 'target_fact_id' => 'X2'] + $annul],
        ];
    }

    /** @param array<string, mixed> $row */
    #[DataProvider('malformedRows')]
    public function test_a_malformed_row_is_refused_by_a_check(array $row): void
    {
        $t = $this->organisationWithMember();

        try {
            $this->insert($t, ['seq' => 2] + $row);
            $this->fail('the row must be refused');
        } catch (QueryException $e) {
            $this->assertContains($e->errorInfo[0] ?? null, ['23514', '23503'], 'check_violation (or, for a self-target, the FK first)');
        }
    }

    public function test_a_target_of_another_member_is_refused_by_the_same_member_fk(): void
    {
        $t = $this->organisationWithMember('M1');
        (new PostgresMemberRepository(DB::connection(), new ScriptedLedgerClockReader(fn (): int => 1_760_000_000_000_000)))->create($t, MemberId::fromString('M2'), PersonRef::fromString('person-M2'));

        try {
            $this->insert($t, ['seq' => 1, 'fact_id' => 'X1', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'A1'], 'M2');
            $this->fail('a target of another Member must be refused');
        } catch (QueryException $e) {
            $this->assertSame('23503', $e->errorInfo[0] ?? null, 'foreign_key_violation (same-Member target FK)');
        }
    }

    public function test_down_refuses_to_drop_stored_corrections_and_round_trips_otherwise(): void
    {
        $migration = require base_path(self::MIGRATION);
        $t = $this->organisationWithMember();
        $this->insert($t, ['seq' => 2, 'fact_id' => 'X2', 'kind' => 'annul', 'effective_at' => null, 'target_fact_id' => 'A1']);

        try {
            DB::transaction(fn () => $migration->down());
            $this->fail('down() must refuse while correction rows exist');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('correction', $e->getMessage());
        }
        $this->assertContains('target_fact_id', self::columns(), 'nothing changed');

        // The suite does not wrap tests in transactions (F-5: append refuses inside one), so other tests' correction
        // rows are committed in this throwaway cluster. The restore half therefore runs inside one outer transaction
        // that removes every correction row (and the command rows naming them), runs down() and up(), and is rolled
        // back: PostgreSQL DDL is transactional, so no other test sees any of it.
        DB::beginTransaction();
        try {
            DB::statement(<<<'SQL'
                DELETE FROM org_membership_commands c USING org_membership_facts f
                 WHERE f.organisation_id = c.organisation_id AND f.membership_id = c.membership_id AND f.seq = c.seq
                   AND f.kind IN ('annul', 'void', 'correct')
                SQL);
            DB::table('org_membership_facts')->whereIn('kind', ['annul', 'void', 'correct'])->delete();

            $migration->down();
            $this->assertContains('period_fact_id', self::columns(), 'down() restores the 1b shape');
            $this->assertNotContains('target_fact_id', self::columns());
            $this->assertNotContains('consequences', self::columns());
            $migration->up();
            $this->assertContains('target_fact_id', self::columns());
            $this->assertSame(1, DB::table('org_membership_facts')->where('organisation_id', $t->value())->count(), 'the Admit row survives down → up');
        } finally {
            DB::rollBack();
        }
        $this->assertContains('target_fact_id', self::columns(), 'the rollback leaves the migrated schema');
    }
}
