<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\Ports\IdentityBindings;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\Ports\SignedInAccount;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Fixtures\Platform\Queue\OutageProbeJob;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 12 on the isolated cluster: outages are REAL, never mocks (D-178 §13.6: fail closed; tactical
 * design failure rows 7 and 10, §3.9):
 * - the registry served over a connection to a port where nothing listens (a TCP refusal);
 * - one organisation database closed with PostgreSQL's own ALLOW_CONNECTIONS false and its sessions terminated;
 * - the cluster unreachable: the registry AND every organisation connection pointed at that dead port.
 * T-11: registry or organisation database down → requests (E-1) and jobs (E-2) refused; nothing runs elsewhere or on
 * stale routing; after recovery both resume. T-18: the cluster unreachable → every organisation refused; after
 * recovery all resume, with fresh resolution and fresh connections; a long-running worker carries nothing across.
 */
final class OutageAndRecoveryTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const ACCOUNT = '5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c';
    private const DEAD_PORT = 1;   // nothing listens there: a real connection refusal

    /** @var array{host: string, port: string|int, username: string, password: string} */
    private array $s;
    private PostgresServedOrganisationRepository $registry;

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        Artisan::call('migrate', ['--database' => 'platform_central', '--force' => true, '--path' => [
            'database/migrations/2026_03_05_000014_create_standard_laravel_tables.php',
            'database/migrations/2026_11_13_170000_create_platform_served_organisations.php',
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',
            'database/migrations/2026_11_14_320000_create_organisation_jobs_table.php',
        ]]);
        $this->s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $factory = $this->factory((int) $this->s['port']);
        $this->registry = new PostgresServedOrganisationRepository(DB::connection('platform_central'));
        foreach ([self::A => 'platform_pg_org_a', self::B => 'platform_pg_org_b'] as $id => $database) {
            $locator = new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
            $context = $factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $locator));   // test setup only
            app(OrganisationMigrations::class)->apply($context);
            $context->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [$id]);
            $context->connection->statement('CREATE TABLE t12_visits (label text, identity text, backend_pid int)');
            $factory->release($context);
            $this->registry->add(self::operational($id, $locator));
        }
        $this->recover();
        config(['queue.connections.organisation.connection' => 'platform_central']);

        $bindings = new class implements IdentityBindings {
            public function isBound(string $accountId, TenantId $organisation): bool
            {
                return true;
            }
        };
        $this->app->instance(IdentityBindings::class, $bindings);
        $this->app->instance(SignedInAccount::class, new class implements SignedInAccount {
            public function accountId(): ?string
            {
                return '5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c';
            }
        });
        Route::middleware(['web', 'organisation.entry'])->get('/_t12/whoami', static fn (): array => [
            'organisation' => app(OrganisationContext::class)->connection->selectOne('SELECT id::text AS id FROM organisation_identity')->id,
        ]);
    }

    protected function tearDown(): void
    {
        foreach (['platform_pg_org_a', 'platform_pg_org_b'] as $db) {   // never leave a database closed for the next test's reset
            DB::connection('platform_central')->statement("ALTER DATABASE {$db} WITH ALLOW_CONNECTIONS true");
        }
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private function factory(int $port): OrganisationConnectionFactory
    {
        return new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $this->s['host'], 'port' => $port, 'sslmode' => 'prefer', 'connect_timeout' => 2]],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $this->s['username'], 'ORG_TEST_PASSWORD' => $this->s['password']]),
        );
    }

    private static function operational(string $id, DatabaseLocator $locator): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']));
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator($locator);
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    /** the registry unreachable: served over a connection to a dead port */
    private function registryDown(): void
    {
        config(['database.connections.t12_registry_down' => ['port' => self::DEAD_PORT, 'options' => [\PDO::ATTR_TIMEOUT => 2]] + config('database.connections.platform_central')]);
        $this->app->instance(ServedOrganisationRepository::class, new PostgresServedOrganisationRepository(DB::connection('t12_registry_down')));
    }

    /** every organisation database unreachable: the cluster's address leads nowhere */
    private function organisationDatabasesDown(): void
    {
        $this->app->instance(OrganisationConnectionFactory::class, $this->factory(self::DEAD_PORT));
    }

    /** one organisation database closed by PostgreSQL itself, its sessions ended */
    private function databaseDown(string $database): void
    {
        $central = DB::connection('platform_central');
        $central->statement("ALTER DATABASE {$database} WITH ALLOW_CONNECTIONS false");
        $central->select('SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = ? AND pid <> pg_backend_pid()', [$database]);
    }

    private function recover(): void
    {
        DB::connection('platform_central')->statement('ALTER DATABASE platform_pg_org_a WITH ALLOW_CONNECTIONS true');
        DB::connection('platform_central')->statement('ALTER DATABASE platform_pg_org_b WITH ALLOW_CONNECTIONS true');
        $this->app->instance(ServedOrganisationRepository::class, $this->registry);
        $this->app->instance(OrganisationConnectionFactory::class, $this->factory((int) $this->s['port']));
    }

    private function select(string $id): void
    {
        $this->post(route('platform.organisations.select'), ['organisation_id' => $id])->assertNoContent();
    }

    private function dispatch(string $id, string $label): void
    {
        app(OrganisationJobDispatcher::class)->dispatch(new OutageProbeJob(TenantId::fromString($id), $label));
    }

    private function work(): void
    {
        Artisan::call('queue:work', ['connection' => 'organisation', '--once' => true]);   // ONE long-running worker: this process
    }

    /** @return list<object{label: string, identity: string, backend_pid: int}> */
    private static function visits(string $database): array
    {
        return DB::connection($database)->select('SELECT label, identity, backend_pid FROM t12_visits ORDER BY label');
    }

    private static function queued(): int
    {
        return (int) DB::connection('platform_central')->table('organisation_jobs')->count();
    }

    private function assertNothingOrganisationalLeft(): void
    {
        $this->assertFalse($this->app->bound(OrganisationContext::class), 'no context survives');
        $this->assertSame([], array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
            'no organisation connection survives');
    }

    public function test_t11_with_the_registry_down_requests_and_jobs_are_refused_then_both_resume(): void
    {
        $this->select(self::A);
        $this->get('/_t12/whoami')->assertOk()->assertJson(['organisation' => self::A]);
        $this->dispatch(self::A, 'a1');

        $this->registryDown();
        $this->get('/_t12/whoami')->assertStatus(503);
        $this->work();
        $this->assertSame([], self::visits('platform_org_a'), 'the job did not run on stale routing');
        $this->assertSame(1, self::queued(), 'released for a retry, not lost and not failed');
        $this->assertNothingOrganisationalLeft();

        $this->recover();
        $this->get('/_t12/whoami')->assertOk()->assertJson(['organisation' => self::A]);
        $this->work();
        $this->assertSame(['a1'], array_map(static fn ($v) => $v->label, self::visits('platform_org_a')), 'resumed');
        $this->assertSame(0, self::queued());
        $this->assertSame(0, (int) DB::table('failed_jobs')->where('connection', 'organisation')->where('payload', 'like', '%OutageProbeJob%')->count(), 'nothing failed');
    }

    public function test_t11_one_organisation_database_down_refuses_it_only_and_it_resumes(): void
    {
        $this->select(self::A);
        $this->get('/_t12/whoami')->assertOk();
        $this->dispatch(self::A, 'a1');
        $this->dispatch(self::B, 'b1');

        $this->databaseDown('platform_pg_org_a');
        $this->get('/_t12/whoami')->assertStatus(503);
        $this->work();   // A: refused, released
        $this->work();   // B: runs, in B
        $this->assertSame(['b1'], array_map(static fn ($v) => $v->label, self::visits('platform_org_b')), 'B is not affected by A');
        $this->assertSame([self::B], array_map(static fn ($v) => $v->identity, self::visits('platform_org_b')), "B's job saw B, never A");
        $this->assertSame(1, self::queued(), "A's job waits");
        $this->assertNothingOrganisationalLeft();

        $this->recover();
        $this->get('/_t12/whoami')->assertOk()->assertJson(['organisation' => self::A]);
        $this->work();
        $this->assertSame(['a1'], array_map(static fn ($v) => $v->label, self::visits('platform_org_a')), 'A resumed');
    }

    /** a store that refuses connections can surface as a RAW PDOException (Laravel's reconnect path): still 503, selection kept */
    public function test_t11_a_raw_connection_refusal_during_entry_answers_service_unavailable_and_keeps_the_selection(): void
    {
        $this->select(self::A);
        $this->app->instance(IdentityBindings::class, new class implements IdentityBindings {
            public function isBound(string $accountId, TenantId $organisation): bool
            {
                throw new \PDOException('SQLSTATE[08006] connection refused (simulated Identity store)');
            }
        });

        $this->get('/_t12/whoami')->assertStatus(503);

        $this->assertSame(self::A, session(\App\Contexts\Platform\Infrastructure\Http\OrganisationEntry::SESSION_KEY), 'an outage is no refusal of the person');
        $this->assertNothingOrganisationalLeft();
    }

    public function test_t18_with_the_cluster_unreachable_every_organisation_is_refused_then_all_resume_freshly(): void
    {
        $this->dispatch(self::A, 'a1');
        $this->work();   // A processed before the outage
        $before = self::visits('platform_org_a')[0]->backend_pid;
        $this->dispatch(self::A, 'a2');
        $this->dispatch(self::B, 'b1');
        $outboxBefore = [DB::connection('platform_org_a')->select('SELECT id, status FROM outbox_events'), DB::connection('platform_org_b')->select('SELECT id, status FROM outbox_events')];

        $this->select(self::B);   // before the outage

        $this->registryDown();
        $this->organisationDatabasesDown();
        $this->post(route('platform.organisations.select'), ['organisation_id' => self::A])->assertStatus(503);   // selection needs the registry
        $this->get('/_t12/whoami')->assertStatus(503);
        $this->work();
        $this->work();
        $this->assertSame(['a1'], array_map(static fn ($v) => $v->label, self::visits('platform_org_a')), 'nothing ran during the outage');
        $this->assertSame([], self::visits('platform_org_b'));
        $this->assertSame(2, self::queued(), 'both jobs wait; none lost');
        $this->assertNothingOrganisationalLeft();

        $this->recover();
        $this->get('/_t12/whoami')->assertOk()->assertJson(['organisation' => self::B]);
        $this->work();
        $this->work();
        $a = self::visits('platform_org_a');
        $this->assertSame(['a1', 'a2'], array_map(static fn ($v) => $v->label, $a), 'A resumed');
        $this->assertNotSame($before, $a[1]->backend_pid, 'a FRESH connection after recovery: nothing carried over the outage');
        $this->assertSame([self::B], array_map(static fn ($v) => $v->identity, self::visits('platform_org_b')), 'B resumed, in B');
        $this->assertSame(0, self::queued());
        $this->assertSame(0, (int) DB::table('failed_jobs')->where('connection', 'organisation')->where('payload', 'like', '%OutageProbeJob%')->count(), 'no job failed');
        $this->assertEquals($outboxBefore, [DB::connection('platform_org_a')->select('SELECT id, status FROM outbox_events'), DB::connection('platform_org_b')->select('SELECT id, status FROM outbox_events')],
            'outbox state unchanged by the outage');
        $this->assertNothingOrganisationalLeft();
    }
}
