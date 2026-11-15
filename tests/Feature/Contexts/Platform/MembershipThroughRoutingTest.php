<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Membership\Application\Organisational\CoverageKnownAt;
use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\Ports\LedgerBoundaryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipSourceOfTruthPort;
use App\Contexts\Membership\Application\Organisational\Ports\WorkingOrganisationPort;
use App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageKnownAtRefusal;
use App\Contexts\Membership\Application\Organisational\RenewMembership;
use App\Contexts\Membership\Application\Organisational\RenewMembershipHandler;
use App\Contexts\Membership\Application\Organisational\RestoreMembership;
use App\Contexts\Membership\Application\Organisational\RestoreMembershipHandler;
use App\Contexts\Membership\Application\Organisational\SuspendMembership;
use App\Contexts\Membership\Application\Organisational\SuspendMembershipHandler;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\LedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresLedgerClockReader;
use App\Contexts\Membership\Infrastructure\Organisational\RegistryRoutedLedgerSource;
use App\Contexts\Membership\Infrastructure\Organisational\RoutedLedgerBoundary;
use App\Contexts\Membership\Infrastructure\Organisational\RoutedMemberRepository;
use App\Contexts\Membership\Infrastructure\Organisational\RoutedWorkingOrganisation;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\Published\RoutedOrganisation;
use App\Contexts\Platform\Application\ResolvedOrganisation;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\ContainerRoutedOrganisation;
use App\Contexts\Platform\Infrastructure\EnvironmentDatabaseLogin;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\Published\RoutedOrganisationConnection;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Unit\Contexts\Membership\Application\Organisational\Support\FakeAuthority;
use Tests\Fixtures\Platform\Queue\C2ProbeJob;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-345 task 10 on the isolated cluster (routing design §3.5 M-1 … M-3, §3.7 L-3; D-233; proposal §8).
 * - T-7: C2 end to end THROUGH ROUTING, with Membership's PRODUCTION bindings, inside a real E-2 job routed to A:
 *   complete for A; CROSS_ORGANISATION for B while routed to A, and outside any routed operation;
 *   SOURCE_NOT_LEDGER once A stops being registry-routable during the operation (D-3, §8.1);
 * - §8.2: A flipped to migration-failed after M-2's check and before M-3 obtains the connection: the in-flight call
 *   reads A's own database only; the next call answers SOURCE_NOT_LEDGER; a registry that cannot answer raises;
 * - T-12 (D-4): the slice-1 behaviours task 10 binds (repository create/append/load/findByHolder/appliedCommand,
 *   the closer, C2 complete and BOUNDARY_NOT_CLOSED) on a REAL organisation database through the factory;
 * - production wiring of the four ports.
 */
final class MembershipThroughRoutingTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    private OrganisationConnectionFactory $factory;
    private PostgresServedOrganisationRepository $registry;
    /** @var list<int> */
    private array $readings = [];

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
            $this->factory->release($context);
            $this->registry->add(self::operational($id, $locator));
        }
        $this->app->instance(OrganisationConnectionFactory::class, $this->factory);
        $this->app->instance(ServedOrganisationRepository::class, $this->registry);
        $this->app->instance(LedgerClockReader::class, new ScriptedLedgerClockReader(fn (): int => array_shift($this->readings) ?? throw new \LogicException('unexpected clock reading')));
        config(['queue.connections.organisation.connection' => 'platform_central']);
        C2ProbeJob::$answers = [];
        C2ProbeJob::$between = null;
    }

    protected function tearDown(): void
    {
        C2ProbeJob::$between = null;
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
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

    /** Routes this test process to $id as the E-2 middleware would for a job attempt (the context), then runs $work. */
    private function routedTo(string $id, \Closure $work): mixed
    {
        $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $this->registry->find(TenantId::fromString($id))->locator()));
        $this->app->instance(OrganisationContext::class, $context);
        try {
            return $work($context->connection);
        } finally {
            $this->app->forgetInstance(OrganisationContext::class);
            $this->factory->release($context);
        }
    }

    /** M1 admitted in $id's own ledger through the PRODUCTION repository binding (recorded at 100) */
    private function admitM1(string $id): void
    {
        $this->routedTo($id, function () use ($id): void {
            $repo = app(MemberRepositoryPort::class);
            $t = TenantId::fromString($id);
            $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
            $this->readings = [100];
            $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(),
                Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');
        });
    }

    private function runJob(C2ProbeJob $job): void
    {
        app(OrganisationJobDispatcher::class)->dispatch($job);
        Artisan::call('queue:work', ['connection' => 'organisation', '--once' => true]);
    }

    public function test_production_wiring_binds_the_four_ports_to_the_routed_adapters(): void
    {
        $this->assertInstanceOf(RoutedWorkingOrganisation::class, app(WorkingOrganisationPort::class));
        $this->assertInstanceOf(RegistryRoutedLedgerSource::class, app(MembershipSourceOfTruthPort::class));
        $this->assertInstanceOf(RoutedLedgerBoundary::class, app(LedgerBoundaryPort::class));
        $this->assertInstanceOf(RoutedMemberRepository::class, app(MemberRepositoryPort::class));
        $this->assertInstanceOf(ContainerRoutedOrganisation::class, app(RoutedOrganisation::class));
        $this->assertInstanceOf(ContainerRoutedOrganisation::class, app(RoutedOrganisationConnection::class));
        $this->app->forgetInstance(LedgerClockReader::class);
        $this->assertInstanceOf(PostgresLedgerClockReader::class, app(LedgerClockReader::class));
    }

    public function test_t7_c2_is_complete_for_the_routed_organisation_inside_a_real_e2_job(): void
    {
        $this->admitM1(self::A);
        $this->readings = [2_000];

        $this->runJob(new C2ProbeJob(TenantId::fromString(self::A), self::A));

        $answer = self::answer(0);
        $this->assertTrue($answer->isComplete(), 'complete');
        $this->assertSame(2_000, $answer->closedThroughMicros());
        $this->assertSame(1, $answer->items()[0]->seq, "A's own ledger");
        $this->assertBoundaryClosedInAOnly();
        $this->assertFalse(app()->bound(OrganisationContext::class));
    }

    /** an answer, never an exception the probe caught (so a wrong path fails an assertion, not an Error) */
    private static function answer(int $i): \App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageKnownAtAnswer
    {
        $answer = C2ProbeJob::$answers[$i] ?? null;
        self::assertInstanceOf(\App\Contexts\Membership\Application\Organisational\PublishedLanguage\CoverageKnownAtAnswer::class, $answer,
            'C2 answered (not ' . (is_object($answer) ? $answer::class : 'nothing') . ')');

        return $answer;
    }

    /** M-3 closed the boundary in A's OWN database: A's clock row records it; the central database has no clock row for A */
    private function assertBoundaryClosedInAOnly(): void
    {
        $this->assertNotNull(DB::connection('platform_org_a')->selectOne('SELECT closed_through FROM org_membership_ledger_clocks WHERE organisation_id = ?::uuid', [self::A])?->closed_through,
            "the boundary was closed in A's own database");
        $this->assertNull(DB::selectOne('SELECT 1 AS x FROM org_membership_ledger_clocks WHERE organisation_id = ?::uuid', [self::A]), 'nothing written centrally for A');
    }

    public function test_t7_cross_organisation_while_routed_to_a_and_outside_any_routed_operation(): void
    {
        $this->admitM1(self::B);
        $this->runJob(new C2ProbeJob(TenantId::fromString(self::A), self::B));
        $this->assertSame(CoverageKnownAtRefusal::CrossOrganisation, self::answer(0)->refusal(), 'B asked while routed to A');

        $outside = app(CoverageKnownAt::class)->byMembershipIds(TenantId::fromString(self::A), [MemberId::fromString('M1')], Instant::of(200));
        $this->assertSame(CoverageKnownAtRefusal::CrossOrganisation, $outside->refusal(), 'no routed operation at all (D-3)');
    }

    public function test_t7_source_not_ledger_once_the_routed_organisation_stops_being_registry_routable(): void
    {
        $this->admitM1(self::A);
        $this->readings = [2_000, 2_100];   // a second reading, so a wrongly allowed second call completes (and fails an assertion)
        C2ProbeJob::$between = function (): void {
            $a = $this->registry->find(TenantId::fromString(self::A));
            $a->markMigrationFailed();   // during the operation (D-3, proposal §8.1)
            $this->registry->save($a);
        };

        $this->runJob(new C2ProbeJob(TenantId::fromString(self::A), self::A, calls: 2));

        $this->assertTrue(self::answer(0)->isComplete(), 'routable at the first call');
        $this->assertSame(CoverageKnownAtRefusal::SourceNotLedger, self::answer(1)->refusal(), 're-checked: no longer a registry-routed ledger');
    }

    public function test_consistency_a_flip_after_m2_and_before_m3_affects_the_next_call_never_another_database(): void
    {
        $this->admitM1(self::A);
        $flipped = false;
        $real = app(RoutedOrganisationConnection::class);
        $this->app->instance(RoutedOrganisationConnection::class, new class ($real, function () use (&$flipped): void {
            if (!$flipped) {
                $flipped = true;
                $a = $this->registry->find(TenantId::fromString(self::A));
                $a->markMigrationFailed();   // AFTER M-2 said yes, BEFORE M-3 obtains the connection
                $this->registry->save($a);
            }
        }) implements RoutedOrganisationConnection {
            public function __construct(private readonly RoutedOrganisationConnection $real, private readonly \Closure $beforeConnection)
            {
            }

            public function connectionFor(TenantId $organisation): Connection
            {
                ($this->beforeConnection)();

                return $this->real->connectionFor($organisation);
            }
        });

        [$inFlight, $next] = $this->routedTo(self::A, function (): array {
            $this->readings = [2_000, 2_100];   // a second reading, so a wrongly allowed next call completes (and fails an assertion)
            $first = app(CoverageKnownAt::class)->byMembershipIds(TenantId::fromString(self::A), [MemberId::fromString('M1')], Instant::of(200));

            return [$first, app(CoverageKnownAt::class)->byMembershipIds(TenantId::fromString(self::A), [MemberId::fromString('M1')], Instant::of(200))];
        });

        $this->assertTrue($flipped);
        $this->assertTrue($inFlight->isComplete(), 'the call in flight completes ...');
        $this->assertSame(1, $inFlight->items()[0]->seq, "... reading A's own database only (the locator and identity never change)");
        $this->assertSame(CoverageKnownAtRefusal::SourceNotLedger, $next->refusal(), 'the next call sees the change');
    }

    public function test_a_registry_that_cannot_answer_raises_and_is_never_a_refusal(): void
    {
        $this->admitM1(self::A);
        $this->routedTo(self::A, function (): void {
            DB::connection('platform_central')->statement('ALTER TABLE platform_served_organisations RENAME TO t10_registry_away');
            $this->readings = [2_000];   // so a call that wrongly skips the registry completes, and fails the assertion below
            try {
                app(CoverageKnownAt::class)->byMembershipIds(TenantId::fromString(self::A), [MemberId::fromString('M1')], Instant::of(200));
                $this->fail('answered while the registry could not be read');
            } catch (QueryException) {
                $this->addToAssertionCount(1);
            }
        });
    }

    public function test_t12_the_bound_slice1_behaviours_on_a_real_organisation_database(): void
    {
        $this->routedTo(self::A, function (Connection $a): void {
            $t = TenantId::fromString(self::A);
            $repo = app(MemberRepositoryPort::class);
            $created = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'));
            $m = $created->member();
            $this->readings = [100];
            $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(),
                Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');

            $this->assertSame('M1', $repo->load($t, MemberId::fromString('M1'))?->id()->value(), 'load');
            $this->assertSame('M1', $repo->findByHolder($t, PersonRef::fromString('P1'))?->id()->value(), 'findByHolder');
            $this->assertNotNull($repo->appliedCommand($t, IdempotencyKey::fromString('k1')), 'appliedCommand');
            $this->assertSame(1, (int) $a->selectOne('SELECT count(*) AS n FROM org_membership_facts')->n, "written into A's own database");
            $this->assertSame(0, (int) DB::connection('platform_org_b')->selectOne('SELECT count(*) AS n FROM org_membership_facts')->n, 'B untouched');

            $this->readings = [2_000];
            $this->assertSame(2_000, app(LedgerBoundaryPort::class)->closeOrRead($t)?->ordinal(), 'the closer on the routed connection');
            $this->assertBoundaryClosedInAOnly();
            $this->readings = [2_100];
            $complete = app(CoverageKnownAt::class)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(200));
            $this->assertTrue($complete->isComplete());
            $this->readings = [2_200];
            $beyond = app(CoverageKnownAt::class)->byMembershipIds($t, [MemberId::fromString('M1')], Instant::of(9_999_999));
            $this->assertSame(CoverageKnownAtRefusal::BoundaryNotClosed, $beyond->refusal());
        });
    }

    /**
     * PBDIGIT-354 S2-1: a Renew through the PRODUCTION repository binding lands in the routed organisation's OWN
     * database (organisation migration 300500) and publishes C1 in its own outbox; B is untouched. The authority is
     * a test double: no production AuthorityCheckPort exists until Governance S3 (blueprint G-3), so the container
     * cannot build the use case at all (fail closed).
     */
    public function test_s2_1_a_renewal_is_recorded_and_published_in_the_routed_organisations_own_database(): void
    {
        $this->routedTo(self::A, function (Connection $a): void {
            $t = TenantId::fromString(self::A);
            $repo = app(MemberRepositoryPort::class);
            $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
            $this->readings = [100];
            $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::of(20_100),
                Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');

            $this->readings = [200];
            $outcome = (new RenewMembershipHandler($repo, new FakeAuthority()))->handle(
                new RenewMembership(self::A, 'M1', 'R1', 20_090, 20_090, 20_465, false, 'treasurer-1', null, 'k2'),
            );

            $this->assertTrue($outcome->isAppended());
            $this->assertSame(['admit', 'renew'], array_map(static fn ($r) => $r->kind, $a->select('SELECT kind FROM org_membership_facts ORDER BY seq')), "in A's own database");
            $this->assertSame(0, (int) DB::connection('platform_org_b')->selectOne('SELECT count(*) AS n FROM org_membership_facts')->n, 'B untouched');
            $this->assertTrue($repo->load($t, MemberId::fromString('M1'))->resolve()->isMember(TimePoint::of(20_300)));
            // by the event's own seq: created_at has second precision and the id is random
            $c1 = array_map(static fn ($r) => json_decode($r->payload), $a->select("SELECT payload FROM outbox_events WHERE event_type = 'MembershipCoverageResolved'"));
            usort($c1, static fn (object $x, object $y): int => $x->seq <=> $y->seq);
            $this->assertSame([1, 2], array_map(static fn (object $e): int => $e->seq, $c1), "both C1 events in A's own outbox");
            $this->assertSame('2026-01-12', $c1[1]->inForce[0]->to, 'the renewed term is published');
            $this->assertSame(0, (int) DB::connection('platform_org_b')->selectOne('SELECT count(*) AS n FROM outbox_events')->n);
        });

        try {
            app(RenewMembershipHandler::class);
            $this->fail('a production authority is bound: S2 must stay fail-closed until Governance S3');
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            $this->assertStringContainsString('AuthorityCheckPort', $e->getMessage());
        }
    }

    /**
     * PBDIGIT-354 S2-2: a suspension and its restoration through the PRODUCTION repository binding land in the routed
     * organisation's OWN database (organisation migration 300600) and publish C1 in its own outbox; B is untouched,
     * and the same member id in B is a different member that knows nothing of it. Neither use case can be built in
     * production without Governance's authority (G-3).
     */
    public function test_s2_2_suspension_and_restoration_are_recorded_and_published_in_the_routed_organisations_own_database(): void
    {
        $this->admitM1(self::B);   // the same member id, in another organisation
        $this->routedTo(self::A, function (Connection $a): void {
            $t = TenantId::fromString(self::A);
            $repo = app(MemberRepositoryPort::class);
            $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
            $this->readings = [200];
            $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::of(20_365),
                Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');

            $this->readings = [300];
            $suspended = (new SuspendMembershipHandler($repo, new FakeAuthority()))->handle(new SuspendMembership(self::A, 'M1', 'S1', 20_010, 20_010, null, 'board-secretary', null, 'k2'));
            $this->readings = [400];
            $restored = (new RestoreMembershipHandler($repo, new FakeAuthority()))->handle(new RestoreMembership(self::A, 'M1', 'R1', 'S1', 20_020, 20_020, 'board-secretary', null, 'k3'));

            $this->assertTrue($suspended->isAppended());
            $this->assertTrue($restored->isAppended());
            $this->assertSame(['admit', 'suspend', 'restore'], array_map(static fn ($r) => $r->kind, $a->select('SELECT kind FROM org_membership_facts ORDER BY seq')), "in A's own database");
            $c1 = array_map(static fn ($r) => json_decode($r->payload), $a->select("SELECT payload FROM outbox_events WHERE event_type = 'MembershipCoverageResolved'"));
            usort($c1, static fn (object $x, object $y): int => $x->seq <=> $y->seq);
            $this->assertSame([1, 2, 3], array_map(static fn (object $e): int => $e->seq, $c1), "three C1 events in A's own outbox");
            $this->assertSame([['2024-10-14', '2024-10-24']], array_map(static fn ($i) => [$i->from, $i->to], $c1[2]->suspended));
        });

        $b = DB::connection('platform_org_b');
        $this->assertSame(['admit'], array_map(static fn ($r) => $r->kind, $b->select('SELECT kind FROM org_membership_facts ORDER BY seq')), "B's M1 knows nothing of A's suspension");
        $this->assertSame([], array_map(static fn ($r) => json_decode($r->payload)->suspended, $b->select("SELECT payload FROM outbox_events WHERE event_type = 'MembershipCoverageResolved'"))[0]);
        $this->routedTo(self::B, function (): void {
            $this->assertFalse(app(MemberRepositoryPort::class)->load(TenantId::fromString(self::B), MemberId::fromString('M1'))->resolve()->isSuspended(TimePoint::of(20_015)));
        });

        foreach ([SuspendMembershipHandler::class, RestoreMembershipHandler::class, \App\Contexts\Membership\Application\Organisational\AdmitMemberHandler::class,
            \App\Contexts\Membership\Application\Organisational\EndMembershipHandler::class, RenewMembershipHandler::class] as $handler) {
            try {
                app($handler);
                $this->fail("$handler: a production authority is bound; S2 must stay fail-closed until Governance S3");
            } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
                $this->assertStringContainsString('AuthorityCheckPort', $e->getMessage(), $handler);
            }
        }
    }

    /**
     * PBDIGIT-354 S2-3a (D-238): through the PRODUCTION routed repository, a member's own resignation answered with an
     * `ownAct` basis is recorded in A's own database with that basis; B's member with the same id is untouched.
     */
    public function test_s2_3a_the_answers_basis_is_recorded_in_the_routed_organisations_own_database(): void
    {
        $this->admitM1(self::B);
        $this->routedTo(self::A, function (Connection $a): void {
            $t = TenantId::fromString(self::A);
            $repo = app(MemberRepositoryPort::class);
            $m = $repo->create($t, MemberId::fromString('M1'), PersonRef::fromString('P1'))->member();
            $this->readings = [200];
            $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A1'), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::of(20_365),
                Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k1'), 'fp1');
            $ownAct = new class implements \App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort {
                public function check(TenantId $tenant, \App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest $request): \App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision
                {
                    return \App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision::of(\App\Contexts\Membership\Domain\Organisational\AuthorityEvidence::of(
                        $request->actor, 'the member concerned', 'D-194', 'v1', true, Instant::of(1), true,
                        \App\Contexts\Membership\Domain\Organisational\AuthorityBasis::of(\App\Contexts\Membership\Domain\Organisational\AuthorityOutcome::Authorised, null, $request->at,
                            \App\Contexts\Membership\Domain\Organisational\CompetenceKind::OwnAct, null, [])));
                }
            };

            $this->readings = [300];
            $outcome = (new \App\Contexts\Membership\Application\Organisational\EndMembershipHandler($repo, $ownAct))->handle(
                new \App\Contexts\Membership\Application\Organisational\EndMembership(self::A, 'M1', 'E1', 'A1', 20_050, 20_050, 'resignation', 'P1', null, 'k2'),
            );

            $this->assertTrue($outcome->isAppended());
            $stored = json_decode($a->selectOne("SELECT provenance::text AS p FROM org_membership_facts WHERE fact_id = 'E1'")->p, true);
            $this->assertSame('own-act', $stored['authorityEvidence']['basis']['competenceKind'], "in A's own database");
        });

        $this->assertSame(['admit'], array_map(static fn ($r) => $r->kind, DB::connection('platform_org_b')->select('SELECT kind FROM org_membership_facts ORDER BY seq')), "B's M1 untouched");
    }
}
