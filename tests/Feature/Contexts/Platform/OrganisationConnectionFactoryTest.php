<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationRoutingRefused;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 3 on the isolated cluster (C-1, C-2, D-222, T-10 part 1, T-26):
 * - an organisation context is ONE connection named after the organisation, built from its registry locator, on
 *   the organisation's own database; the default (central) connection is untouched;
 * - two organisations' contexts never see each other's data;
 * - T-26: a connection object held from A's context, used after A's release and B's establishment, never reaches B;
 * - release removes the connection; an unknown cluster or credential reference is refused.
 */
final class OrganisationConnectionFactoryTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    private OrganisationConnectionFactory $factory;

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        foreach (['platform_org_a' => 'a', 'platform_org_b' => 'b'] as $connection => $tag) {
            DB::connection($connection)->statement('CREATE TABLE whose (organisation text)');
            DB::connection($connection)->insert('INSERT INTO whose VALUES (?)', [$tag]);
        }
        $this->factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
    }

    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private static function resolved(string $id, string $database, string $cluster = 'test-cluster', string $credential = 'env:ORG_TEST'): ResolvedOrganisation
    {
        return new ResolvedOrganisation(TenantId::fromString($id), new DatabaseLocator($cluster, $database, $credential));
    }

    public function test_production_wiring_builds_the_factory_from_configured_clusters(): void
    {
        $this->assertInstanceOf(OrganisationConnectionFactory::class, app(OrganisationConnectionFactory::class));
        $this->assertIsArray(config('organisation_routing.clusters'));
    }

    public function test_a_context_is_one_named_connection_on_the_organisations_own_database(): void
    {
        $defaultBefore = DB::connection()->getDatabaseName();
        $context = $this->factory->establish(self::resolved(self::A, 'platform_pg_org_a'));

        $this->assertSame(self::A, $context->organisationId->value());
        $this->assertSame(OrganisationConnectionFactory::connectionName(TenantId::fromString(self::A)), $context->connection->getName());
        $this->assertSame('platform_pg_org_a', $context->connection->selectOne('SELECT current_database() AS d')->d);
        $this->assertSame('a', $context->connection->selectOne('SELECT organisation FROM whose')->organisation);
        $this->assertSame($defaultBefore, DB::connection()->getDatabaseName(), 'the default connection is never re-pointed');
        $this->assertSame(config('database.default'), DB::getDefaultConnection());
    }

    public function test_two_organisations_never_see_each_others_data(): void
    {
        $a = $this->factory->establish(self::resolved(self::A, 'platform_pg_org_a'));
        $b = $this->factory->establish(self::resolved(self::B, 'platform_pg_org_b'));

        $this->assertSame(['a'], array_column($a->connection->select('SELECT organisation FROM whose'), 'organisation'));
        $this->assertSame(['b'], array_column($b->connection->select('SELECT organisation FROM whose'), 'organisation'));
        $this->assertNotSame($a->connection->getName(), $b->connection->getName());
    }

    public function test_t26_a_connection_held_from_a_never_reaches_b_after_a_is_released_and_b_established(): void
    {
        $a = $this->factory->establish(self::resolved(self::A, 'platform_pg_org_a'));
        $held = $a->connection;
        $this->factory->release($a);
        $this->factory->establish(self::resolved(self::B, 'platform_pg_org_b'));

        try {
            $seen = $held->selectOne('SELECT current_database() AS d, (SELECT organisation FROM whose) AS o');
            $this->assertSame(['platform_pg_org_a', 'a'], [$seen->d, $seen->o], 'if it still answers, it is A');
        } catch (\Throwable $e) {
            $this->assertStringNotContainsString('platform_pg_org_b', $e->getMessage(), 'refused, never redirected to B');
        }
    }

    public function test_release_removes_the_connection(): void
    {
        $a = $this->factory->establish(self::resolved(self::A, 'platform_pg_org_a'));
        $this->factory->release($a);

        $this->assertNull(config('database.connections.' . $a->connection->getName()));
        $this->assertArrayNotHasKey($a->connection->getName(), DB::getConnections());
    }

    public function test_an_unknown_cluster_or_credential_reference_is_refused(): void
    {
        foreach ([self::resolved(self::A, 'platform_pg_org_a', 'no-such-cluster'), self::resolved(self::A, 'platform_pg_org_a', credential: 'env:NOT_SET')] as $resolved) {
            try {
                $this->factory->establish($resolved);
                $this->fail('established with an unusable locator');
            } catch (OrganisationRoutingRefused) {
                $this->assertNull(config('database.connections.' . OrganisationConnectionFactory::connectionName(TenantId::fromString(self::A))), 'nothing registered');
            }
        }
    }
}
