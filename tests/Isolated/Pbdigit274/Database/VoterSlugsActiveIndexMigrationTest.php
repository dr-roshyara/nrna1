<?php

declare(strict_types=1);

namespace Tests\Isolated\Pbdigit274\Database;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use ReplaceVoterSlugsPairUniqueWithActivePartialIndex as Migration;
use RuntimeException;
use Tests\Isolated\Pbdigit274\IsolatedDatabaseTestCase;

/**
 * PBDIGIT-274 S2 acceptance on real PostgreSQL (plan 11 / 11.0): tests 1–5, behaviour tests S2-2 (a)–(d),
 * drift refusals (S2-1, S2-4) and the lock timeout (S2-5).
 *
 * WRITTEN, NOT RUN. Runs only through phpunit.pbdigit274.xml on `pbdigit274_isolated_test` (identity guard,
 * G5), after the PO authorises database execution of S2. RefreshDatabase has already applied every migration
 * (including this one), and each test runs inside a transaction that is rolled back, DDL included. A test that
 * needs the "before" schema first calls down() (no history yet).
 */
final class VoterSlugsActiveIndexMigrationTest extends IsolatedDatabaseTestCase
{
    private const FILE = 'database/migrations/2026_10_12_000001_replace_voter_slugs_pair_unique_with_active_partial_index.php';

    private Election $election;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        require_once base_path(self::FILE);

        $org = Organisation::factory()->tenant()->create();
        $this->election = Election::factory()->forOrganisation($org)->real()->create();
        $this->voter = User::factory()->create();
    }

    private function migration(): Migration
    {
        return new Migration();
    }

    private function slug(array $overrides = []): VoterSlug
    {
        return VoterSlug::factory()->create($overrides + [
            'user_id' => $this->voter->id,
            'organisation_id' => $this->election->organisation_id,
            'election_id' => $this->election->id,
        ]);
    }

    /** Runs $write in a savepoint so a PostgreSQL error does not abort the test transaction. */
    private function uniqueViolation(callable $write): UniqueConstraintViolationException
    {
        try {
            DB::transaction(fn () => $write());
        } catch (UniqueConstraintViolationException $e) {
            return $e;
        }
        $this->fail('expected a unique violation');
    }

    private function refusal(callable $run, string $reasonFragment): void
    {
        try {
            $run();
            $this->fail('expected a refusal');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('refused', $e->getMessage());
            $this->assertStringContainsString($reasonFragment, $e->getMessage());
        }
    }

    private function oldConstraintExists(): bool
    {
        return DB::selectOne("SELECT 1 AS f FROM pg_constraint c JOIN pg_class t ON t.oid = c.conrelid
            WHERE t.relname = 'voter_slugs' AND c.conname = ? AND c.contype = 'u'", [Migration::OLD_CONSTRAINT]) !== null;
    }

    private function newIndexDef(): ?string
    {
        return DB::selectOne("SELECT pg_get_indexdef(c.oid) AS def FROM pg_class c WHERE c.relname = ? AND c.relkind = 'i'",
            [Migration::NEW_INDEX])?->def;
    }

    /** @return list<array<string, mixed>> */
    private function rows(): array
    {
        return array_map(fn ($r) => (array) $r, DB::select('SELECT * FROM voter_slugs ORDER BY id'));
    }

    // ── after the migration: behaviour (S2-2) ─────────────────────────────

    public function test_the_migration_has_run_and_left_only_the_partial_index(): void
    {
        $this->assertFalse($this->oldConstraintExists());
        $this->assertMatchesRegularExpression('/UNIQUE INDEX .* \(election_id, user_id\) WHERE \(deleted_at IS NULL\)$/', (string) $this->newIndexDef());
    }

    public function test_a_soft_deleted_row_and_a_new_current_row_coexist(): void            // (a)
    {
        $this->slug()->delete();
        $this->slug();

        $this->assertSame(2, VoterSlug::withoutGlobalScope('tenant')->withTrashed()->where('user_id', $this->voter->id)->count());
    }

    public function test_a_second_current_row_is_rejected_naming_the_new_index(): void         // (b)
    {
        $this->slug();
        $e = $this->uniqueViolation(fn () => $this->slug());

        $this->assertSame('23505', $e->getPrevious()->getCode());
        $this->assertStringContainsString(Migration::NEW_INDEX, $e->getPrevious()->errorInfo[2]);
    }

    public function test_several_soft_deleted_rows_for_one_pair_are_allowed(): void            // (c)
    {
        $this->slug()->delete();
        $this->slug()->delete();
        $this->slug()->delete();

        $this->assertSame(3, VoterSlug::withoutGlobalScope('tenant')->onlyTrashed()->where('user_id', $this->voter->id)->count());
    }

    public function test_restoring_an_old_row_while_a_current_row_exists_is_rejected(): void   // (d)
    {
        $old = $this->slug();
        $old->delete();
        $this->slug();

        $e = $this->uniqueViolation(fn () => VoterSlug::withoutGlobalScope('tenant')->withTrashed()->findOrFail($old->id)->restore());
        $this->assertStringContainsString(Migration::NEW_INDEX, $e->getPrevious()->errorInfo[2]);
    }

    // ── test 4 (extended): down() without history, then up() again ───────

    public function test_down_without_history_restores_the_old_rule_and_up_recreates_the_identical_index(): void
    {
        $before = $this->newIndexDef();
        $this->slug();                                    // one row per pair: no history

        $this->migration()->down();
        $this->assertTrue($this->oldConstraintExists());
        $this->assertNull($this->newIndexDef());
        $this->assertStringContainsString(Migration::OLD_CONSTRAINT,
            $this->uniqueViolation(fn () => $this->slug())->getPrevious()->errorInfo[2], 'a second current row');
        $this->assertStringContainsString(Migration::OLD_CONSTRAINT,
            $this->uniqueViolation(function () {
                $row = VoterSlug::factory()->make([
                    'user_id' => $this->voter->id,
                    'organisation_id' => $this->election->organisation_id,
                    'election_id' => $this->election->id,
                ]);
                $row->deleted_at = now();   // inserted already soft-deleted, not deleted afterwards
                $row->save();
            })->getPrevious()->errorInfo[2], 'even a soft-deleted one');

        $this->migration()->up();
        $this->assertFalse($this->oldConstraintExists());
        $this->assertSame($before, $this->newIndexDef(), 'direct up() and the Migrator produce the same index');
    }

    // ── test 5: down() with history refuses ───────────────────────────────

    public function test_down_refuses_when_replaced_slugs_exist_and_changes_nothing(): void
    {
        $this->slug()->delete();
        $this->slug();
        $rows = $this->rows();
        $index = $this->newIndexDef();

        $this->refusal(fn () => $this->migration()->down(), 'replaced voter slugs exist');

        $this->assertSame($rows, $this->rows());
        $this->assertSame($index, $this->newIndexDef());
        $this->assertFalse($this->oldConstraintExists());
    }

    // ── drift refusals (S2-1, S2-4) ───────────────────────────────────────

    public function test_down_refuses_when_the_partial_index_is_missing(): void
    {
        DB::statement('DROP INDEX '.Migration::NEW_INDEX);

        $this->refusal(fn () => $this->migration()->down(), 'schema drift');
        $this->assertFalse($this->oldConstraintExists(), 'nothing was added');
    }

    public function test_up_refuses_when_the_old_constraint_is_missing(): void
    {
        $this->refusal(fn () => $this->migration()->up(), Migration::OLD_CONSTRAINT);   // state after the migration
        $this->assertNotNull($this->newIndexDef());
    }

    public function test_up_refuses_when_the_old_rule_is_a_plain_index_not_a_constraint(): void
    {
        $this->migration()->down();
        DB::statement('ALTER TABLE voter_slugs DROP CONSTRAINT '.Migration::OLD_CONSTRAINT);
        DB::statement('CREATE UNIQUE INDEX '.Migration::OLD_CONSTRAINT.' ON voter_slugs (election_id, user_id)');

        $this->refusal(fn () => $this->migration()->up(), Migration::OLD_CONSTRAINT);
        $this->assertNull($this->newIndexDef());
    }

    public function test_up_refuses_when_the_old_constraint_has_other_columns(): void
    {
        $this->migration()->down();
        DB::statement('ALTER TABLE voter_slugs DROP CONSTRAINT '.Migration::OLD_CONSTRAINT);
        DB::statement('ALTER TABLE voter_slugs ADD CONSTRAINT '.Migration::OLD_CONSTRAINT.' UNIQUE (user_id, election_id)');

        $this->refusal(fn () => $this->migration()->up(), Migration::OLD_CONSTRAINT);
        $this->assertNull($this->newIndexDef());
    }

    public function test_up_refuses_when_the_target_name_is_taken(): void
    {
        $this->migration()->down();
        DB::statement('CREATE INDEX '.Migration::NEW_INDEX.' ON voter_slugs (user_id)');

        $this->refusal(fn () => $this->migration()->up(), 'already exists');
        $this->assertTrue($this->oldConstraintExists());
    }

    // ── test 1 and S2-5: failure after the drop, direct call ──────────────

    public function test_a_failure_after_the_drop_rolls_everything_back_direct(): void
    {
        $this->migration()->down();
        $this->slug();
        $rows = $this->rows();
        $outer = DB::transactionLevel();

        $failing = new class () extends Migration {
            public ?int $levelAtFailure = null;
            public ?string $lockTimeout = null;

            protected function afterDrop(): void
            {
                $this->levelAtFailure = DB::transactionLevel();
                $this->lockTimeout = DB::selectOne('SHOW lock_timeout')->lock_timeout;
                throw new RuntimeException('injected after drop');
            }
        };

        try {
            $failing->up();
            $this->fail('expected the injected failure');
        } catch (RuntimeException $e) {
            $this->assertSame('injected after drop', $e->getMessage());
        }

        $this->assertSame($outer + 1, $failing->levelAtFailure, 'the migration runs in its own transaction');
        $this->assertSame(Migration::LOCK_TIMEOUT, $failing->lockTimeout);
        $this->assertSame($outer, DB::transactionLevel());
        $this->assertTrue($this->oldConstraintExists(), 'the dropped constraint is back');
        $this->assertNull($this->newIndexDef());
        $this->assertSame($rows, $this->rows());
        $this->assertStringContainsString(Migration::OLD_CONSTRAINT, $this->uniqueViolation(fn () => $this->slug())->getPrevious()->errorInfo[2]);
    }

    // ── test 2: the same failure through Laravel's Migrator ───────────────

    public function test_a_failure_after_the_drop_rolls_everything_back_through_the_migrator(): void
    {
        $this->migration()->down();
        $rows = $this->rows();
        $outer = DB::transactionLevel();

        $dir = sys_get_temp_dir().'/pbd274-migrator-'.bin2hex(random_bytes(4));
        mkdir($dir);
        $name = '2099_01_01_000000_pbdigit274_failing_active_index';
        file_put_contents("{$dir}/{$name}.php", '<?php require_once '.var_export(base_path(self::FILE), true).';'
            .' return new class () extends ReplaceVoterSlugsPairUniqueWithActivePartialIndex {'
            .' protected function afterDrop(): void { $GLOBALS["pbd274_level"] = \Illuminate\Support\Facades\DB::transactionLevel();'
            .' throw new \RuntimeException("injected after drop (migrator)"); } };');

        try {
            app('migrator')->run([$dir]);
            $this->fail('expected the injected failure');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('injected after drop (migrator)', $e->getMessage());
        } finally {
            unlink("{$dir}/{$name}.php");
            rmdir($dir);
        }

        $this->assertSame($outer + 2, $GLOBALS['pbd274_level'], 'Migrator transaction plus the migration\'s own');
        $this->assertSame($outer, DB::transactionLevel());
        $this->assertTrue($this->oldConstraintExists());
        $this->assertNull($this->newIndexDef());
        $this->assertSame($rows, $this->rows());
        $this->assertSame(0, DB::table('migrations')->where('migration', $name)->count(), 'not recorded');
    }
}
