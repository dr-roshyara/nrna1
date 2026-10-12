<?php

namespace Tests\Isolated\Pbdigit274;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Tests\CreatesApplication;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;

/**
 * Base class for PBDIGIT-274 database tests whose subject must COMMIT (the voter-slug issuer refuses to run
 * inside a transaction, and G6 needs data visible to other processes). Run only through phpunit.pbdigit274.xml.
 *
 * - The same identity guard as IsolatedDatabaseTestCase, before ANY database work: unconfined PostgreSQL
 *   connections are removed, every configured connection is checked, then current_database() is confirmed.
 * - It never refreshes, migrates or truncates. It requires the schema to be migrated already (the S2 migration
 *   recorded) and fails otherwise.
 * - Data is committed on the isolated, wipeable database. Subclasses delete their own fixtures in tearDown.
 */
abstract class IsolatedCommittedDatabaseTestCase extends BaseTestCase
{
    use CreatesApplication;

    private const S2_MIGRATION = '2026_10_12_000001_replace_voter_slugs_pair_unique_with_active_partial_index';

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections' => DatabaseIdentityGuard::withoutUnconfinedPostgresConnections(config('database.connections'))]);
        DatabaseIdentityGuard::confirmBeforeRefresh(
            config('database.connections'),
            DatabaseIdentityGuard::approvedName(getenv(DatabaseIdentityGuard::ENV_NAME)),
            (string) config('database.default'),
            fn () => DB::connection()->selectOne('SELECT current_database() AS name')->name,
        );

        $migrated = DB::table('migrations')->where('migration', self::S2_MIGRATION)->exists();
        $this->assertTrue($migrated, 'The isolated database is not migrated to S2; this base class never migrates.');
    }
}
