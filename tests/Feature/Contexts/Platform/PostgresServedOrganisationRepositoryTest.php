<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\OrganisationNotRoutable;
use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Application\ResolutionService;
use App\Contexts\Platform\Application\RoutingRefusal;
use App\Contexts\Platform\Domain\CounterpartyVerification;
use App\Contexts\Platform\Domain\FoundingApplication;
use App\Contexts\Platform\Application\Ports\LegacyOrganisationRegister;
use App\Contexts\Platform\Infrastructure\PostgresLegacyOrganisationRegister;
use App\Contexts\Platform\Domain\DatabaseLocator;
use App\Contexts\Platform\Domain\OperationalReadiness;
use App\Contexts\Platform\Domain\ServedOrganisation;
use App\Contexts\Platform\Domain\ServiceOperationGrant;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 2 on the isolated cluster (the central platform database only):
 * - the registry round-trips every lifecycle state through the aggregate's own rebuild path;
 * - T-2: an unknown, non-operational or schema-not-current organisation is refused; an operational one resolves to
 *   its registry locator;
 * - the database also refuses states the domain forbids, and refuses two organisations sharing one database (D-222);
 * - a stored state the domain could never produce is refused on reading (fail closed), never routed.
 */
final class PostgresServedOrganisationRepositoryTest extends MembershipPostgresTestCase
{
    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();
        Artisan::call('migrate', ['--database' => 'platform_central', '--force' => true, '--path' => [
            'database/migrations/2026_11_13_170000_create_platform_served_organisations.php',
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',   // task 5a
        ]]);
    }

    /** task 5a: every registry entry carries its founding application */
    private static function application(): FoundingApplication
    {
        return new FoundingApplication('founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', ['1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d', '2b3c4d5e-6f7a-4b8c-9d0e-1f2a3b4c5d6e']);
    }

    public function test_5a_the_founding_application_is_stored_once_and_never_rewritten(): void
    {
        $o = self::upTo('pending');
        self::repo()->add($o);
        $this->assertTrue(self::repo()->find($o->id())->foundingApplication()->equals(self::application()), 'stored and read back');
        DB::connection('platform_central')->update("UPDATE platform_served_organisations SET founding_evidence_ref = 'tampered' WHERE id = ?::uuid", [$o->id()->value()]);
        $found = self::repo()->find($o->id());
        $found->recordCounterpartyVerified(new CounterpartyVerification('agreement:x'));
        self::repo()->save($found);
        $this->assertSame('tampered', DB::connection('platform_central')->selectOne('SELECT founding_evidence_ref AS e FROM platform_served_organisations WHERE id = ?::uuid', [$o->id()->value()])->e,
            'save() never writes the application (it is recorded at registration only)');
        foreach (["UPDATE platform_served_organisations SET initial_contact_accounts = '[]'::jsonb", "UPDATE platform_served_organisations SET registrar_account = NULL"] as $sql) {
            try {
                DB::connection('platform_central')->statement($sql . " WHERE id = ?::uuid", [$o->id()->value()]);
                $this->fail("accepted: {$sql}");
            } catch (\Illuminate\Database\QueryException $e) {
                $this->assertMatchesRegularExpression('/platform_served_organisations_contacts_present|not-null|null value/', $e->getMessage());
            }
        }
    }

    public function test_5a_the_migration_stops_rather_than_invent_founding_evidence_for_existing_entries(): void
    {
        $migration = require base_path('database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php');
        $central = DB::connection('platform_central');
        $default = DB::getDefaultConnection();
        DB::setDefaultConnection('platform_central');   // the migration's DB:: facade, as `migrate --database` does
        $central->beginTransaction();
        try {
            $migration->down();
            $central->insert("INSERT INTO platform_served_organisations (id, status) VALUES (?, 'pending')", [(string) Str::uuid()]);
            try {
                $migration->up();
                $this->fail('founding evidence was invented for an existing entry');
            } catch (\RuntimeException $e) {
                $this->assertStringContainsString('without a founding application', $e->getMessage());
            }
        } finally {
            $central->rollBack();
            DB::setDefaultConnection($default);
        }
        $this->assertNotNull($central->selectOne("SELECT 1 AS x FROM information_schema.columns WHERE table_name = 'platform_served_organisations' AND column_name = 'founding_evidence_ref'"), 'rolled back: the real schema is untouched');
    }

    public function test_5a_the_legacy_register_finds_a_legacy_organisation_and_nothing_else(): void
    {
        $legacy = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $legacy, 'name' => 'Legacy test org', 'slug' => 'legacy-' . substr($legacy, 0, 8), 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $register = new PostgresLegacyOrganisationRegister(DB::connection());

        $this->assertTrue($register->contains(TenantId::fromString($legacy)));
        $this->assertFalse($register->contains(TenantId::fromString((string) Str::uuid())));
        $this->assertInstanceOf(PostgresLegacyOrganisationRegister::class, app(LegacyOrganisationRegister::class), 'production wiring');
    }

    protected function tearDown(): void
    {
        foreach (array_keys(DB::getConnections()) as $name) {
            DB::disconnect($name);
        }
        parent::tearDown();
    }

    private static function repo(): PostgresServedOrganisationRepository
    {
        return new PostgresServedOrganisationRepository(DB::connection('platform_central'));
    }

    private static function upTo(string $stage, ?string $database = null): ServedOrganisation
    {
        $id = (string) Str::uuid();
        $o = ServedOrganisation::register(TenantId::fromString($id), self::application());
        if ($stage === 'pending') {
            return $o;
        }
        $o->recordCounterpartyVerified(new CounterpartyVerification('agreement:' . $id));
        $o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', new \DateTimeImmutable('2026-11-12T10:00:00.123456Z'), 'account:1', [str_repeat('a', 64), str_repeat('b', 64)]));
        $o->startProvisioning();
        if ($stage === 'provisioning') {
            return $o;
        }
        $o->assignLocator(new DatabaseLocator('cluster-1', $database ?? 'org_' . substr(str_replace('-', '', $id), 0, 12), 'secret-store:' . $id));
        $o->markSchemaCurrent();
        $o->markOperational(new OperationalReadiness(true, true, true, 1));
        if ($stage === 'failed') {
            $o->markMigrationFailed();
        }

        return $o;
    }

    public function test_production_wiring_binds_the_registry_and_the_resolution_service(): void
    {
        $this->assertInstanceOf(PostgresServedOrganisationRepository::class, app(ServedOrganisationRepository::class));
        $this->assertInstanceOf(ResolutionService::class, app(ResolutionService::class));
    }

    public function test_every_lifecycle_state_round_trips_through_the_aggregate(): void
    {
        foreach (['pending', 'provisioning', 'operational', 'failed'] as $stage) {
            $o = self::upTo($stage);
            self::repo()->add($o);
            $this->assertEquals($o->snapshot(), self::repo()->find($o->id())?->snapshot(), $stage);
        }
        $this->assertNull(self::repo()->find(TenantId::fromString((string) Str::uuid())));
    }

    public function test_save_records_a_transition_and_add_never_overwrites(): void
    {
        $o = self::upTo('provisioning');
        self::repo()->add($o);
        $o->assignLocator(new DatabaseLocator('cluster-1', 'org_saved_' . substr(md5($o->id()->value()), 0, 8), 'secret-store:x'));
        $o->markSchemaCurrent();
        self::repo()->save($o);
        $this->assertEquals($o->snapshot(), self::repo()->find($o->id())?->snapshot());

        $this->expectException(\RuntimeException::class);
        self::repo()->add($o);
    }

    public function test_saving_an_entry_that_was_never_added_is_refused(): void
    {
        $this->expectException(\RuntimeException::class);
        self::repo()->save(self::upTo('pending'));
    }

    public function test_t2_only_an_operational_organisation_with_a_current_schema_resolves(): void
    {
        $repo = self::repo();
        $states = [];
        foreach (['pending', 'provisioning', 'operational', 'failed'] as $stage) {
            $states[$stage] = self::upTo($stage);
            $repo->add($states[$stage]);
        }
        $service = new ResolutionService($repo);

        $resolved = $service->resolve($states['operational']->id()->value());
        $this->assertTrue($states['operational']->locator()->equals($resolved->locator), 'the locator comes from the registry');

        foreach ([
            [(string) Str::uuid(), RoutingRefusal::Unknown],
            [$states['pending']->id()->value(), RoutingRefusal::NotOperational],
            [$states['provisioning']->id()->value(), RoutingRefusal::NotOperational],
            [$states['failed']->id()->value(), RoutingRefusal::SchemaNotCurrent],
        ] as [$id, $reason]) {
            try {
                $service->resolve($id);
                $this->fail("{$reason->value} resolved");
            } catch (OrganisationNotRoutable $e) {
                $this->assertSame($reason, $e->reason);
            }
        }
    }

    public function test_two_organisations_can_never_share_one_database(): void
    {
        self::repo()->add(self::upTo('operational', 'org_shared_db'));

        $this->expectException(QueryException::class);
        self::repo()->add(self::upTo('operational', 'org_shared_db'));
    }

    public function test_the_database_refuses_a_pending_entry_with_a_locator(): void
    {
        $this->expectException(QueryException::class);
        DB::connection('platform_central')->insert("INSERT INTO platform_served_organisations (id, status, locator_cluster, locator_database, locator_credential_ref)
            VALUES (?, 'pending', 'c', 'org_x', 'secret-store:x')", [(string) Str::uuid()]);
    }

    public function test_a_stored_state_the_domain_never_produces_is_refused_on_reading(): void
    {
        $id = (string) Str::uuid();
        // A row the table's checks allow but the domain's value objects refuse (a founding-document fingerprint that is
        // not SHA-256): the adapter must refuse to rebuild it, as a corrupt registry entry, rather than route it.
        // (task 5a: the founding columns are valid, so the grant fingerprint stays the only corrupt part)
        DB::connection('platform_central')->insert("INSERT INTO platform_served_organisations (id, status, counterparty_evidence_ref, grant_terms_version, grant_accepted_at, grant_accepted_by, grant_fingerprints,
                founding_evidence_ref, registrar_account, initial_contact_accounts)
            VALUES (?, 'provisioning', 'agreement:x', 'terms', now(), 'account:1', '[\"not-a-digest\"]',
                'founding-evidence:test', '7e6d5c4b-3a29-4180-9f8e-7d6c5b4a3921', '[\"1a2b3c4d-5e6f-4a7b-8c9d-0e1f2a3b4c5d\"]')", [$id]);

        $this->expectException(\UnexpectedValueException::class);
        self::repo()->find(TenantId::fromString($id));
    }
}
