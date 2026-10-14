<?php

declare(strict_types=1);

namespace Tests\Unit\Database;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use ReplaceVoterSlugsPairUniqueWithActivePartialIndex;
use RuntimeException;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

/**
 * PBDIGIT-274 S2: database-free checks of the voter_slugs migration (plan 11 / 11.0). Its behaviour on real
 * PostgreSQL is tested in tests/Isolated/Pbdigit274/Database/VoterSlugsActiveIndexMigrationTest.php.
 *
 * Every connection used here points at a dead host, and each test asserts that no query ran.
 */
final class VoterSlugsActiveIndexMigrationTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;

    private const FILE = 'database/migrations/2026_10_12_000001_replace_voter_slugs_pair_unique_with_active_partial_index.php';

    private int $queries = 0;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.connections.pgsql.host' => '/nonexistent-migration-test-socket-dir',
            'database.connections.pgsql.url' => null,
            'database.connections.mysql.host' => '/nonexistent-migration-test-socket-dir',
            'database.connections.mysql.url' => null,
        ]);
        DB::purge('pgsql');
        DB::purge('mysql');
        DB::listen(function () {
            $this->queries++;
        });
    }

    private function migration(): ReplaceVoterSlugsPairUniqueWithActivePartialIndex
    {
        return require base_path(self::FILE);
    }

    public function test_the_file_can_be_required_twice_and_returns_the_migration(): void
    {
        $first = $this->migration();
        $second = $this->migration();

        $this->assertInstanceOf(ReplaceVoterSlugsPairUniqueWithActivePartialIndex::class, $first);
        $this->assertNotSame($first, $second);
        $this->assertTrue($first->withinTransaction, 'the Migrator must wrap it in a transaction');
    }

    public function test_the_new_index_is_partial_unique_over_the_same_pair(): void
    {
        $this->assertSame(
            'CREATE UNIQUE INDEX voter_slugs_active_election_user_unique ON voter_slugs (election_id, user_id) WHERE deleted_at IS NULL',
            ReplaceVoterSlugsPairUniqueWithActivePartialIndex::CREATE_INDEX_SQL,
        );
        $this->assertSame('voter_slugs_election_id_user_id_unique', ReplaceVoterSlugsPairUniqueWithActivePartialIndex::OLD_CONSTRAINT);
        $this->assertSame('5s', ReplaceVoterSlugsPairUniqueWithActivePartialIndex::LOCK_TIMEOUT);
    }

    public static function directions(): array
    {
        return ['up' => ['up'], 'down' => ['down']];
    }

    #[DataProvider('directions')]
    public function test_a_non_postgres_connection_is_refused_before_any_query(string $direction): void
    {
        config(['database.default' => 'mysql']);
        $migration = $this->migration();

        try {
            $migration->{$direction}();
            $this->fail('expected a refusal');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('requires PostgreSQL', $e->getMessage());
        }
        $this->assertSame(0, $this->queries);
    }

    public function test_the_migration_never_deletes_or_rewrites_rows_and_never_drops_silently(): void
    {
        $source = (string) file_get_contents(base_path(self::FILE));
        $code = preg_replace('#/\*.*?\*/#s', '', $source);   // ignore the docblock

        foreach (['DELETE FROM', 'TRUNCATE', 'UPDATE ', 'forceDelete', 'IF EXISTS', 'DROP TABLE', 'dropColumn', 'CONCURRENTLY'] as $forbidden) {
            $this->assertStringNotContainsStringIgnoringCase($forbidden, $code, $forbidden);
        }
        $this->assertStringContainsString('dropUnique(self::OLD_CONSTRAINT)', $code, 'the old rule is dropped as a constraint, never with DROP INDEX');
        $this->assertSame(1, substr_count($code, "'DROP INDEX '"), 'DROP INDEX only for the new partial index, in down()');
    }
}
