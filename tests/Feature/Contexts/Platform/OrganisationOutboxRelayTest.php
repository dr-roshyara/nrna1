<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Membership\Application\Organisational\IdempotencyKey;
use App\Contexts\Membership\Application\Organisational\PublishedLanguage\MembershipCoverageResolved;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Membership\Infrastructure\Organisational\PostgresMemberRepository;
use App\Contexts\Platform\Application\Ports\RegisteredOrganisations;
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
use App\Contexts\Platform\Infrastructure\PostgresRegisteredOrganisations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\Queue\OrganisationJobDispatcher;
use App\Contexts\Platform\Infrastructure\Queue\RelayOrganisationOutbox;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Shared\Infrastructure\Outbox\ConnectionOutboxProcessor;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydrator;
use App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxEventProcessor;
use Illuminate\Database\Connection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Fixtures\Platform\Queue\RecordVisitJob;
use Tests\Fixtures\Platform\Queue\T9FollowUpRequested;
use Tests\Fixtures\Platform\Queue\T9FollowUpRequestedHydrator;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;
use Tests\Support\ScriptedLedgerClockReader;

/**
 * PBDIGIT-345 task 9b on the isolated cluster: the per-organisation relay E-5 (tactical design §3.4, §3.10.2, ISO-9).
 * - atomicity: Membership's business change (an admission) and its C1 event commit, roll back or fail TOGETHER in the
 *   organisation's own database; an uncommitted event is invisible to the relay;
 * - T-6: C1 written in A is delivered in A (inside A's context) and never seen by B's relay; an event naming B found in
 *   A is dead-lettered and alerted (ISO-9), never dispatched;
 * - T-24: a rolled-back organisation transaction leaves no outbox row and so produces no job; the relay dispatches only
 *   committed rows and OUTSIDE any organisation transaction (a consumer can queue an organisation job, T-25 allowing);
 * - retries (5 attempts, then dead-letter), an unregistered type dead-lettered at once, the original row preserved;
 * - the claim lease: a claimed row is not claimed twice; a crashed claim is re-delivered (at least once);
 * - T-6, central half: the central relay (OutboxEventProcessor, unchanged) and the organisation relay never see each
 *   other's events;
 * - a REAL fan-out (`platform:fan-out outbox.relay`) and a REAL `queue:work organisation` worker deliver A's and B's
 *   events, each in its own database.
 */
final class OrganisationOutboxRelayTest extends MembershipPostgresTestCase
{
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';

    private OrganisationConnectionFactory $factory;
    private PostgresServedOrganisationRepository $registry;
    /** @var array<string, string> */
    private array $childEnv;
    /** @var list<array{organisation: string, membership: string, context: ?string}> */
    private array $delivered = [];

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
        $this->app->instance(RegisteredOrganisations::class, new PostgresRegisteredOrganisations(DB::connection('platform_central')));
        config(['queue.connections.organisation.connection' => 'platform_central']);

        $this->delivered = [];
        Event::listen(MembershipCoverageResolved::class, function (MembershipCoverageResolved $e): void {
            $this->delivered[] = ['organisation' => $e->organisationId, 'membership' => $e->membershipId,
                'context' => app()->bound(OrganisationContext::class) ? app(OrganisationContext::class)->organisationId->value() : null];
        });

        $this->childEnv = ['APP_ENV' => 'testing', 'DATABASE_URL' => '', 'DB_CONNECTION' => 'pgsql', 'QUEUE_CONNECTION' => 'sync',
            'APP_ROUTES_CACHE' => 'bootstrap/cache/membership-pg-never-a-route-cache.php',
            'ORGANISATION_DB_CLUSTERS' => json_encode(['test-cluster' => ['host' => $s['host'], 'port' => (int) $s['port'], 'sslmode' => 'prefer']], JSON_THROW_ON_ERROR),
            'ORG_TEST_USERNAME' => (string) $s['username'], 'ORG_TEST_PASSWORD' => (string) $s['password']];
        foreach (['DB_', 'DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
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

    private static function org(string $id): Connection
    {
        return DB::connection($id === self::A ? 'platform_org_a' : 'platform_org_b');
    }

    /** Membership's business change in the organisation's own database: an admission (facts + C1, one transaction). */
    private static function admit(string $organisation, string $member): void
    {
        $repo = new PostgresMemberRepository(self::org($organisation), new ScriptedLedgerClockReader(static fn (): int => 1_760_000_000_000_123));
        $t = TenantId::fromString($organisation);
        $m = $repo->create($t, MemberId::fromString($member), PersonRef::fromString('person-' . $member))->member();
        $repo->append($t, $m->id(), $m->decideAdmit(FactId::fromString('A-' . $member), TimePoint::of(20_000), TimePoint::of(20_000), TimePoint::infinity(),
            Provenance::decision('registrar', null, 'decl-fixture')), IdempotencyKey::fromString('k-' . $member), 'fp-' . $member);
    }

    /** @return array<string, int> status => count in that organisation's outbox */
    private static function outbox(string $organisation): array
    {
        $out = [];
        foreach (self::org($organisation)->select('SELECT status, count(*) AS n FROM outbox_events GROUP BY status') as $r) {
            $out[$r->status] = (int) $r->n;
        }
        ksort($out);

        return $out;
    }

    /** The relay of one organisation, as production runs it: one relay job, processed by a worker (in this process). */
    private function relay(string $organisation): void
    {
        $failedBefore = DB::table('failed_jobs')->pluck('id')->all();
        app(OrganisationJobDispatcher::class)->dispatch(new RelayOrganisationOutbox(TenantId::fromString($organisation)));
        Artisan::call('queue:work', ['connection' => 'organisation', '--once' => true]);
        $this->assertSame(0, (int) DB::connection('platform_central')->table('organisation_jobs')->where('payload', 'like', '%RelayOrganisationOutbox%')->count(), 'the relay job ran');
        $failed = DB::table('failed_jobs')->whereNotIn('id', $failedBefore)->value('exception');
        $this->assertNull($failed, 'the relay job itself never fails: ' . substr((string) $failed, 0, 600));
    }

    public function test_atomicity_the_admission_and_its_event_commit_together_in_the_organisations_database(): void
    {
        self::admit(self::A, 'M1');

        $this->assertSame(['pending' => 1], self::outbox(self::A));
        $this->assertSame(1, (int) self::org(self::A)->selectOne('SELECT count(*) AS n FROM org_membership_facts')->n);
        $this->assertSame([], self::outbox(self::B));
    }

    public function test_atomicity_a_failing_event_write_rolls_the_admission_back(): void
    {
        self::org(self::A)->statement("ALTER TABLE outbox_events ADD CONSTRAINT t9_refuse_c1 CHECK (event_type <> 'MembershipCoverageResolved')");
        try {
            self::admit(self::A, 'M1');
            $this->fail('the admission committed without its event');
        } catch (QueryException $e) {
            $this->assertStringContainsString('t9_refuse_c1', $e->getMessage());
        }
        $this->assertSame(0, (int) self::org(self::A)->selectOne('SELECT count(*) AS n FROM org_membership_facts')->n, 'no fact without its event');
    }

    public function test_t6_c1_written_in_a_is_delivered_in_a_and_never_seen_by_bs_relay(): void
    {
        self::admit(self::A, 'M1');

        $this->relay(self::B);
        $this->assertSame([], $this->delivered, "B's relay saw nothing of A");
        $this->assertSame(['pending' => 1], self::outbox(self::A));

        $this->relay(self::A);
        $this->assertSame([['organisation' => self::A, 'membership' => 'M1', 'context' => self::A]], $this->delivered, 'delivered once, inside A\'s context');
        $this->assertSame(['completed' => 1], self::outbox(self::A));
        $this->assertFalse(app()->bound(OrganisationContext::class));
    }

    public function test_t6_iso9_an_event_naming_b_found_in_a_is_dead_lettered_and_alerted_never_dispatched(): void
    {
        self::admit(self::A, 'M1');
        $a = self::org(self::A);
        $a->statement('ALTER TABLE outbox_events DROP CONSTRAINT outbox_events_organisation_identity_fk');   // plant what the database itself refuses
        $planted = (string) Str::uuid();
        $payload = json_decode((string) $a->selectOne('SELECT payload FROM outbox_events')->payload, true);
        $a->table('outbox_events')->insert(['id' => $planted, 'event_id' => (string) Str::uuid(), 'organisation_id' => self::B, 'aggregate_type' => 'Membership',
            'aggregate_id' => (string) Str::uuid(), 'event_type' => 'MembershipCoverageResolved', 'payload' => json_encode(['organisationId' => self::B] + $payload), 'status' => 'pending']);
        Log::spy();

        $this->relay(self::A);

        $this->assertSame([['organisation' => self::A, 'membership' => 'M1', 'context' => self::A]], $this->delivered, 'only A\'s own event was dispatched');
        $row = $a->selectOne('SELECT status, organisation_id, payload FROM outbox_events WHERE id = ?', [$planted]);
        $this->assertSame('failed', $row->status, 'dead-lettered');
        $this->assertSame(self::B, $row->organisation_id, 'the original row is kept for audit');
        Log::shouldHaveReceived('critical')->withArgs(static fn (string $message, array $context): bool => $message === 'outbox.iso9.organisation_mismatch'
            && $context['outbox_event_id'] === $planted && $context['expected_organisation'] === self::A && $context['event_organisation'] === self::B)->once();
    }

    /**
     * T-24 fixtures: one organisation business change (a row in A's own test table) and its outbox event, in ONE
     * transaction on A's database; the event's consumer queues follow-up organisation work (Q-7). Membership's own
     * adapter cannot be held open by an outer transaction (it commits its own: AppendInsideTransactionRefused), so its
     * atomicity is proven by the admission tests above; this proves the general rule.
     */
    private function businessChangeWithFollowUp(string $label): void
    {
        $a = self::org(self::A);
        $a->insert('INSERT INTO t9_business (label) VALUES (?)', [$label]);
        $a->table('outbox_events')->insert(['id' => (string) Str::uuid(), 'event_id' => (string) Str::uuid(), 'organisation_id' => self::A, 'aggregate_type' => 'T9',
            'aggregate_id' => (string) Str::uuid(), 'event_type' => 'T9FollowUpRequested', 'payload' => json_encode(['organisationId' => self::A, 'label' => $label]), 'status' => 'pending']);
    }

    private function consumerQueuesFollowUpWork(): void
    {
        self::org(self::A)->statement('CREATE TABLE IF NOT EXISTS t9_business (label text)');
        app(EventHydratorRegistry::class)->register(new T9FollowUpRequestedHydrator());
        Event::listen(T9FollowUpRequested::class, static function (T9FollowUpRequested $e): void {
            // T-25 refuses this inside an open organisation transaction: it succeeds only if the relay dispatches outside one
            app(OrganisationJobDispatcher::class)->dispatch(new RecordVisitJob(TenantId::fromString($e->organisationId), 'after-' . $e->label));
        });
    }

    public function test_t24_a_rolled_back_organisation_transaction_leaves_no_event_and_so_no_job(): void
    {
        $this->consumerQueuesFollowUpWork();
        $a = self::org(self::A);
        $a->beginTransaction();
        $this->businessChangeWithFollowUp('rolled-back');
        $a->rollBack();

        $this->assertSame([], self::outbox(self::A), 'no outbox row');
        $this->assertSame(0, (int) $a->selectOne('SELECT count(*) AS n FROM t9_business')->n, 'no business change');
        $this->relay(self::A);

        $this->assertSame(0, (int) DB::connection('platform_central')->table('organisation_jobs')->count(), 'no job');
    }

    public function test_t24_the_relay_sees_only_committed_events_and_dispatches_outside_any_transaction_so_a_consumer_can_queue_a_job(): void
    {
        $this->consumerQueuesFollowUpWork();
        $a = self::org(self::A);   // a SECOND session on A's database, holding the business transaction open
        $a->beginTransaction();
        $this->businessChangeWithFollowUp('committed');

        $this->relay(self::A);   // the relay's own session cannot see the uncommitted event
        $this->assertSame(0, (int) DB::connection('platform_central')->table('organisation_jobs')->count(), 'not visible before commit: no job');

        $a->commit();
        $this->relay(self::A);

        $this->assertSame(['completed' => 1], self::outbox(self::A), 'the consumer\'s dispatch succeeded: the relay was outside any transaction');
        $queued = DB::connection('platform_central')->table('organisation_jobs')->get();
        $this->assertCount(1, $queued, 'the relay jobs were consumed; one follow-up job is queued');
        $this->assertStringContainsString('after-committed', $queued[0]->payload);
    }

    public function test_t24_the_processor_refuses_to_run_inside_an_open_transaction(): void
    {
        $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString(self::A), $this->registry->find(TenantId::fromString(self::A))->locator()));
        $context->connection->beginTransaction();
        try {
            $this->expectException(\LogicException::class);
            app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);
        } finally {
            $context->connection->rollBack();
            $this->factory->release($context);
        }
    }

    public function test_a_failing_delivery_is_retried_five_times_then_dead_lettered_and_an_unregistered_type_at_once(): void
    {
        app(EventHydratorRegistry::class)->register(new class implements EventHydrator {
            public function eventType(): string
            {
                return 'T9Fails';
            }

            public function hydrate(array $payload): object
            {
                throw new \RuntimeException('consumer not ready');
            }
        });
        $a = self::org(self::A);
        foreach (['T9Fails' => 'fails', 'T9Unregistered' => 'unregistered'] as $type => $id) {
            $a->table('outbox_events')->insert(['id' => (string) Str::uuid(), 'event_id' => (string) Str::uuid(), 'organisation_id' => self::A, 'aggregate_type' => 'T',
                'aggregate_id' => (string) Str::uuid(), 'event_type' => $type, 'payload' => json_encode(['marker' => $id]), 'status' => 'pending']);
        }
        $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString(self::A), $this->registry->find(TenantId::fromString(self::A))->locator()));
        $status = static fn (string $type) => $a->selectOne('SELECT status, attempts, payload FROM outbox_events WHERE event_type = ?', [$type]);

        app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);
        $this->assertSame(['failed', 0], [$status('T9Unregistered')->status, $status('T9Unregistered')->attempts], 'unregistered: dead-lettered at once');
        $this->assertSame(['pending', 1], [$status('T9Fails')->status, $status('T9Fails')->attempts], 'rescheduled');
        $this->assertTrue((bool) $a->selectOne("SELECT available_at > now() + interval '4 minutes' AS later FROM outbox_events WHERE event_type = 'T9Fails'")->later, 'after a delay');

        for ($i = 2; $i <= 5; $i++) {
            $a->update("UPDATE outbox_events SET available_at = now() - interval '1 second' WHERE event_type = 'T9Fails'");
            app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);
        }
        $this->assertSame(['failed', 5], [$status('T9Fails')->status, $status('T9Fails')->attempts], 'dead-lettered after five attempts');
        $this->assertSame(['marker' => 'fails'], json_decode((string) $status('T9Fails')->payload, true), 'the original event is kept');
        $this->factory->release($context);
    }

    /** available_at (precision 0) rounds UP in the second half of a second: the relay must still see the event at once */
    public function test_an_event_committed_in_the_second_half_of_a_second_is_relayed_in_that_same_second(): void
    {
        self::admit(self::A, 'M0');   // warm up the ledger path, then relay it away
        $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString(self::A), $this->registry->find(TenantId::fromString(self::A))->locator()));
        app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);
        $this->delivered = [];
        $a = self::org(self::A);
        $fraction = static fn (): int => (int) $a->selectOne('SELECT (extract(microseconds FROM clock_timestamp())::int % 1000000) AS us')->us;
        for ($tries = 0; !in_array(intdiv($fraction(), 100_000), [6, 7], true) && $tries < 2000; $tries++) {
            usleep(5_000);
        }
        $a->table('outbox_events')->insert(['id' => (string) Str::uuid(), 'event_id' => (string) Str::uuid(), 'organisation_id' => self::A, 'aggregate_type' => 'Membership',
            'aggregate_id' => (string) Str::uuid(), 'event_type' => 'MembershipCoverageResolved', 'payload' => $a->selectOne('SELECT payload FROM outbox_events LIMIT 1')->payload, 'status' => 'pending']);
        $rounded = (bool) $a->selectOne('SELECT max(available_at) > localtimestamp AS ahead FROM outbox_events')->ahead;

        app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);

        $this->assertTrue($rounded, 'precondition: the stored available_at was rounded ahead of the clock');
        $this->assertCount(1, $this->delivered, 'relayed in the same second it was committed');
        $this->factory->release($context);
    }

    public function test_the_claim_lease_prevents_a_double_claim_and_re_delivers_a_crashed_claim(): void
    {
        self::admit(self::A, 'M1');
        $a = self::org(self::A);
        $context = $this->factory->establish(new ResolvedOrganisation(TenantId::fromString(self::A), $this->registry->find(TenantId::fromString(self::A))->locator()));

        $a->update("UPDATE outbox_events SET status = 'processing', available_at = now() + interval '5 minutes'");   // claimed by a live relay
        app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);
        $this->assertSame([], $this->delivered, 'a row under a live claim is not claimed again');

        $a->update("UPDATE outbox_events SET available_at = now() - interval '1 second'");   // that relay crashed: the lease ran out
        app(ConnectionOutboxProcessor::class)->process($context->connection, self::A);
        $this->assertCount(1, $this->delivered, 'redelivered (at least once)');
        $this->assertSame(['completed' => 1], self::outbox(self::A));
        $this->factory->release($context);
    }

    public function test_t6_central_and_organisation_relays_never_see_each_others_events(): void
    {
        $centralOrg = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $centralOrg, 'name' => 'central', 'slug' => 'central-' . $centralOrg, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('outbox_events')->insert(['id' => (string) Str::uuid(), 'event_id' => (string) Str::uuid(), 'organisation_id' => $centralOrg, 'aggregate_type' => 'T',
            'aggregate_id' => (string) Str::uuid(), 'event_type' => 'T9Central', 'payload' => '{}', 'status' => 'pending',
            // explicitly due: the central relay compares a rounded `timestamp` with PHP's now(), so a row written in the same
            // second waits for its next run (PBDIGIT-350); this test is about the two routes, not that timing
            'available_at' => now()->subMinute(),
            // the central relay takes the 100 OLDEST pending rows; other suites leave committed pending rows in the shared
            // default test database, so this row is made the oldest (found in the full cluster run t9b-11a)
            'created_at' => '2000-01-01 00:00:00']);
        self::admit(self::A, 'M1');

        $ofA = fn (): array => array_values(array_filter($this->delivered, static fn (array $d): bool => $d['organisation'] === self::A));
        $this->relay(self::A);
        $this->assertSame('pending', DB::table('outbox_events')->where('event_type', 'T9Central')->value('status'), 'the organisation relay never reads the central outbox');
        $this->assertSame([['organisation' => self::A, 'membership' => 'M1', 'context' => self::A]], $ofA(), 'A\'s event: delivered by the organisation relay, in A\'s context');

        app(OutboxEventProcessor::class)->handle();   // the central relay, unchanged (it may also deliver other suites' central events)
        $this->assertSame('failed', DB::table('outbox_events')->where('event_type', 'T9Central')->value('status'), 'processed centrally (unregistered: its own dead-letter)');
        $this->assertSame(['completed' => 1], self::outbox(self::A), 'A\'s organisation outbox untouched by the central relay');
        $this->assertCount(1, $ofA(), 'the central relay never delivered A\'s organisation event');
    }

    public function test_a_real_fan_out_and_a_real_worker_deliver_each_organisations_events_in_its_own_database(): void
    {
        self::admit(self::A, 'M1');
        self::admit(self::B, 'M2');

        $this->assertSame(0, Artisan::call('platform:fan-out', ['operation' => 'outbox.relay']), Artisan::output());
        $worker = new Process([PHP_BINARY, base_path('artisan'), 'queue:work', 'organisation', '--stop-when-empty', '--no-interaction'], base_path(), $this->childEnv, null, 300);
        $worker->run();

        $this->assertTrue($worker->isSuccessful(), $worker->getErrorOutput());
        $this->assertSame(['completed' => 1], self::outbox(self::A));
        $this->assertSame(['completed' => 1], self::outbox(self::B));
        $this->assertSame(0, (int) DB::connection('platform_central')->table('failed_jobs')->count());
    }
}
