<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
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
use App\Contexts\Platform\Infrastructure\Queue\DispatchInsideOrganisationTransactionRefused;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationDispatchRefused;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobRefused;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\Process\Process;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Fixtures\Platform\Queue\FailingOrganisationJob;
use Tests\Fixtures\Platform\Queue\LegacyStyleJob;
use Tests\Fixtures\Platform\Queue\RecordVisitJob;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 7 on the isolated cluster: the separate `organisation` queue connection (E-2; tactical design
 * §3.10 Q-1 … Q-8, §3.10.1, §3.10.2).
 * - T-20 separation: dispatched through OrganisationJobDispatcher into `organisation_jobs` (central), never `jobs`;
 *   the dispatcher refuses a non-organisation job, another organisation than the current context, a connection override;
 * - T-25: refused while the organisation connection has an open transaction; nothing queued;
 * - T-21 / T-4: run inline (sync), on another connection, or without an organisation id → refused, never run;
 * - T-3 / T-22: ONE real worker runs A, B, A, B: each job sees only its own database, on a NEW connection, with no
 *   other organisation connection open; afterwards no context and no organisation connection remain;
 * - T-23: a REAL `queue:work organisation` worker: A's job fails, is retried; A becomes migration-failed in between, so
 *   the retry is refused with that reason into `failed_jobs` (connection `organisation`, identifier-only payload);
 *   B's jobs complete throughout;
 * - T-19 step 2: after the connection exists, the default is still `sync` and a legacy job never reaches
 *   `organisation_jobs` (T-19 itself is re-run unchanged in LegacyQueueCharacterisationTest).
 * Worker child processes get an EXPLICIT environment pointing every connection at the isolated cluster.
 */
final class OrganisationQueueTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    private OrganisationConnectionFactory $factory;
    private PostgresServedOrganisationRepository $registry;
    /** @var array<string, string> */
    private array $childEnv;

    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        Artisan::call('migrate', ['--database' => 'platform_central', '--force' => true, '--path' => [
            'database/migrations/2026_03_05_000014_create_standard_laravel_tables.php',   // failed_jobs (§3.10.1)
            'database/migrations/2026_11_13_170000_create_platform_served_organisations.php',
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',
            'database/migrations/2026_11_14_320000_create_organisation_jobs_table.php',
        ]]);
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $this->factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
        $this->registry = new PostgresServedOrganisationRepository(DB::connection('platform_central'));
        foreach ([self::A => 'platform_pg_org_a', self::B => 'platform_pg_org_b'] as $id => $database) {
            $locator = new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
            $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $locator));   // test setup only
            app(OrganisationMigrations::class)->apply($context);
            $context->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [$id]);
            $context->connection->statement('CREATE TABLE t7_job_visits (label text, identity text, backend_pid int, open_org text)');
            $this->factory->release($context);
            $this->registry->add(self::operational($id, $locator));
        }
        $this->app->instance(OrganisationConnectionFactory::class, $this->factory);
        $this->app->instance(ServedOrganisationRepository::class, $this->registry);
        config(['queue.connections.organisation.connection' => 'platform_central']);   // the central database of this test

        $this->childEnv = ['APP_ENV' => 'testing', 'DATABASE_URL' => '', 'DB_CONNECTION' => 'pgsql', 'QUEUE_CONNECTION' => 'sync',
            'APP_ROUTES_CACHE' => 'bootstrap/cache/membership-pg-never-a-route-cache.php',
            'ORGANISATION_DB_CLUSTERS' => json_encode(['test-cluster' => ['host' => $s['host'], 'port' => (int) $s['port'], 'sslmode' => 'prefer']], JSON_THROW_ON_ERROR),
            'ORG_TEST_USERNAME' => (string) $s['username'], 'ORG_TEST_PASSWORD' => (string) $s['password']];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {   // every connection: the isolated central platform database
            $this->childEnv += [$prefix . 'HOST' => (string) $s['host'], $prefix . 'PORT' => (string) $s['port'], $prefix . 'DATABASE' => 'platform_pg_central',
                $prefix . 'USERNAME' => (string) $s['username'], $prefix . 'PASSWORD' => (string) $s['password']];
        }
    }

    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private static function application(): FoundingApplication
    {
        return new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d']);
    }

    private static function operational(string $id, DatabaseLocator $locator): ServedOrganisation
    {
        $o = ServedOrganisation::register(TenantId::fromString($id), self::application());
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-14T10:00:00Z'), 'account:1', [str_repeat('a', 64)]));
        $o->startProvisioning();
        $o->assignLocator($locator);
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));

        return $o;
    }

    private static function dispatcher(): OrganisationJobDispatcher
    {
        return app(OrganisationJobDispatcher::class);
    }

    private static function id(string $id): TenantId
    {
        return TenantId::fromString($id);
    }

    private static function queued(): int
    {
        return (int) DB::connection('platform_central')->table('organisation_jobs')->count();
    }

    /** @return list<object{label: string, identity: string, backend_pid: int, open_org: string}> */
    private static function visits(string $database): array
    {
        return DB::connection($database)->select('SELECT label, identity, backend_pid, open_org FROM t7_job_visits ORDER BY label');
    }

    private function worker(string ...$options): Process
    {
        $p = new Process([PHP_BINARY, base_path('artisan'), 'queue:work', 'organisation', ...$options, '--no-interaction'], base_path(), $this->childEnv, null, 300);
        $p->run();

        return $p;
    }

    private function assertNoContextLeft(): void
    {
        $this->assertFalse($this->app->bound(OrganisationContext::class), 'no context outlives a job');
        $this->assertSame([], array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
            'no organisation connection outlives a job');
    }

    public function test_t20_dispatched_jobs_go_to_organisation_jobs_never_jobs_and_a_worker_runs_them(): void
    {
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::A), 'a1'));

        $this->assertSame(1, self::queued());
        $row = DB::connection('platform_central')->table('organisation_jobs')->first();
        $this->assertStringContainsString(self::A, $row->payload);
        foreach (['platform_central', 'pgsql'] as $c) {
            $this->assertFalse((bool) DB::connection($c)->selectOne("SELECT to_regclass('public.jobs') IS NOT NULL AS t")->t, "no `jobs` table on {$c}: nothing goes there");
        }
        $this->assertSame([], self::visits('platform_org_a'), 'queued, not run');

        Artisan::call('queue:work', ['connection' => 'organisation', '--once' => true]);

        $this->assertSame(['a1'], array_map(static fn ($v) => $v->label, self::visits('platform_org_a')));
        $this->assertSame(0, self::queued());
        $this->assertNoContextLeft();
    }

    public function test_t20_the_dispatcher_refuses_other_jobs_other_organisations_and_connection_overrides(): void
    {
        $context = $this->factory->establish(new ResolvedOrganisation(self::id(self::A), $this->registry->find(self::id(self::A))->locator()));
        $this->app->instance(OrganisationContext::class, $context);
        $override = new RecordVisitJob(self::id(self::A), 'x');
        $override->onConnection('database');
        foreach ([
            'a non-organisation job' => new LegacyStyleJob('x'),
            'another organisation than the current context' => new RecordVisitJob(self::id(self::B), 'x'),
            'a connection override' => $override,
        ] as $why => $job) {
            try {
                self::dispatcher()->dispatch($job);
                $this->fail("dispatched: {$why}");
            } catch (OrganisationDispatchRefused) {
                $this->addToAssertionCount(1);
            }
        }
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::A), 'own'));   // its own organisation: accepted
        $this->assertSame(1, self::queued());
    }

    public function test_t25_dispatch_inside_an_open_organisation_transaction_is_refused(): void
    {
        $context = $this->factory->establish(new ResolvedOrganisation(self::id(self::A), $this->registry->find(self::id(self::A))->locator()));
        $this->app->instance(OrganisationContext::class, $context);
        $context->connection->beginTransaction();
        try {
            self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::A), 'premature'));
            $this->fail('dispatched before the organisation transaction committed');
        } catch (DispatchInsideOrganisationTransactionRefused) {
            $this->assertSame(0, self::queued(), 'nothing queued');
        } finally {
            $context->connection->rollBack();
        }
    }

    public function test_t21_an_organisation_job_run_inline_or_on_another_connection_is_refused_and_never_runs(): void
    {
        try {
            Bus::dispatchSync(new RecordVisitJob(self::id(self::A), 'inline'));
            $this->fail('ran inline');
        } catch (OrganisationJobRefused $e) {
            $this->assertSame('wrong-connection', $e->reason);
        }
        try {
            Queue::connection('sync')->push(new RecordVisitJob(self::id(self::A), 'sync'));
            $this->fail('ran on sync');
        } catch (OrganisationJobRefused $e) {
            $this->assertSame('wrong-connection', $e->reason);
        }

        // pushed onto another database connection, bypassing the dispatcher: the worker of that connection refuses it
        DB::connection('platform_central')->statement('CREATE TABLE t7_misrouted_jobs (LIKE organisation_jobs INCLUDING ALL)');
        config(['queue.connections.misrouted' => ['driver' => 'database', 'connection' => 'platform_central', 'table' => 't7_misrouted_jobs', 'queue' => 'default', 'retry_after' => 90]]);
        Queue::connection('misrouted')->push(new RecordVisitJob(self::id(self::A), 'misrouted'));
        Artisan::call('queue:work', ['connection' => 'misrouted', '--once' => true, '--tries' => 1]);

        $this->assertSame([], self::visits('platform_org_a'), 'never ran');
        $this->assertStringContainsString('wrong-connection', (string) DB::table('failed_jobs')->where('connection', 'misrouted')->value('exception'));
        $this->assertNoContextLeft();
    }

    public function test_t4_a_job_without_an_organisation_id_is_refused_and_failed(): void
    {
        $job = (new \ReflectionClass(RecordVisitJob::class))->newInstanceWithoutConstructor();   // a forged or corrupted payload
        $job->onConnection('organisation');
        // the worker's failer writes outside this test's transaction: count only what this test adds
        $before = DB::table('failed_jobs')->where('connection', 'organisation')->pluck('id')->all();
        Queue::connection('organisation')->push($job);

        Artisan::call('queue:work', ['connection' => 'organisation', '--once' => true]);

        $this->assertSame([], self::visits('platform_org_a'));
        $this->assertSame([], self::visits('platform_org_b'));
        $failed = DB::table('failed_jobs')->where('connection', 'organisation')->whereNotIn('id', $before)->get();
        $this->assertCount(1, $failed);
        $this->assertStringContainsString('no-organisation-id', $failed[0]->exception);
        $this->assertSame(0, self::queued(), 'failed at once, not retried');
    }

    public function test_t3_t22_one_real_worker_runs_a_b_a_b_and_nothing_crosses_or_survives(): void
    {
        foreach ([[self::A, 'a1'], [self::B, 'b1'], [self::A, 'a2'], [self::B, 'b2']] as [$org, $label]) {
            self::dispatcher()->dispatch(new RecordVisitJob(self::id($org), $label));
        }

        $worker = $this->worker('--stop-when-empty');

        $this->assertTrue($worker->isSuccessful(), $worker->getErrorOutput());
        foreach (['platform_org_a' => [self::A, ['a1', 'a2']], 'platform_org_b' => [self::B, ['b1', 'b2']]] as $database => [$org, $labels]) {
            $visits = self::visits($database);
            $this->assertSame($labels, array_map(static fn ($v) => $v->label, $visits), "{$database}: only its own jobs");
            $own = json_encode([OrganisationConnectionFactory::connectionName(self::id($org))]);
            foreach ($visits as $v) {
                $this->assertSame($org, $v->identity);
                $this->assertSame($own, $v->open_org, "{$v->label}: no other organisation connection open in the worker");
            }
            $this->assertNotSame($visits[0]->backend_pid, $visits[1]->backend_pid, "{$database}: a NEW connection per job (purged in between)");
        }
        $this->assertSame(0, self::queued());

        // the same, in this process: afterwards no context and no organisation connection remain (C-2)
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::A), 'a3'));
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::B), 'b3'));
        Artisan::call('queue:work', ['connection' => 'organisation', '--stop-when-empty' => true]);
        $this->assertNoContextLeft();
        $this->assertCount(3, self::visits('platform_org_a'));
    }

    public function test_t23_a_retry_re_resolves_and_is_refused_once_the_organisation_is_not_routable_while_b_completes(): void
    {
        self::dispatcher()->dispatch(new FailingOrganisationJob(self::id(self::A)));
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::B), 'b1'));

        $first = $this->worker('--stop-when-empty');   // A fails (attempt 1, released with a long backoff); B completes

        $this->assertTrue($first->isSuccessful(), $first->getErrorOutput());
        $this->assertSame(['b1'], array_map(static fn ($v) => $v->label, self::visits('platform_org_b')));
        $this->assertSame(1, self::queued(), 'A released for a retry');
        $this->assertSame(0, (int) DB::connection('platform_central')->table('failed_jobs')->count());

        $a = $this->registry->find(self::id(self::A));
        $a->markMigrationFailed();
        $this->registry->save($a);
        DB::connection('platform_central')->table('organisation_jobs')->update(['available_at' => time() - 1]);   // the backoff has passed
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::B), 'b2'));

        $second = $this->worker('--stop-when-empty');

        $this->assertTrue($second->isSuccessful(), $second->getErrorOutput());
        $failed = DB::connection('platform_central')->table('failed_jobs')->get();
        $this->assertCount(1, $failed, 'the retry was refused into failed_jobs, not run');
        $this->assertSame('organisation', $failed[0]->connection);
        $this->assertStringContainsString('schema-not-current', $failed[0]->exception, 'refused with the reason: migration failed');
        $this->assertStringContainsString(self::A, $failed[0]->payload);
        $this->assertStringNotContainsString('Illuminate\\\\Database\\\\Eloquent', $failed[0]->payload, 'identifier-only payload');
        $this->assertSame(['b1', 'b2'], array_map(static fn ($v) => $v->label, self::visits('platform_org_b')), "B's jobs completed throughout");
        $this->assertSame(0, self::queued());
    }

    /** Q-5 in THIS process (mutant Q2: T-23's worker is a child process, which copy-loaded mutants never reach) */
    public function test_q5_an_unroutable_organisation_job_is_failed_with_its_reason_and_never_runs(): void
    {
        self::dispatcher()->dispatch(new RecordVisitJob(self::id(self::A), 'a-refused'));
        $a = $this->registry->find(self::id(self::A));
        $a->markMigrationFailed();
        $this->registry->save($a);

        Artisan::call('queue:work', ['connection' => 'organisation', '--once' => true, '--tries' => 3]);

        $this->assertSame([], self::visits('platform_org_a'), 'never ran');
        // the worker's failer writes outside this test's transaction: only this test's job is counted
        $failed = DB::table('failed_jobs')->where('connection', 'organisation')->where('payload', 'like', '%a-refused%')->get();
        $this->assertCount(1, $failed, 'failed at once, not left for retries');
        $this->assertStringContainsString('Organisation job refused: schema-not-current.', $failed[0]->exception);
        $this->assertSame(0, self::queued());
        $this->assertNoContextLeft();
    }

    public function test_t19_step2_the_default_stays_sync_and_a_legacy_job_never_reaches_organisation_jobs(): void
    {
        $this->assertSame('sync', config('queue.default'));
        $this->assertStringContainsString("'default' => env('QUEUE_CONNECTION', 'sync')", (string) file_get_contents(config_path('queue.php')));
        LegacyStyleJob::$ran = [];

        LegacyStyleJob::dispatch('legacy');

        $this->assertSame(['legacy'], LegacyStyleJob::$ran, 'still inline');
        $this->assertSame(0, self::queued(), 'nothing in organisation_jobs');
    }
}
