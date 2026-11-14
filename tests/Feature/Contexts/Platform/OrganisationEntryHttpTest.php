<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Platform;

use App\Contexts\Platform\Application\Ports\BindingsUnavailable;
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
use App\Contexts\Platform\Infrastructure\Http\OrganisationEntry;
use App\Contexts\Platform\Infrastructure\OrganisationConnectionFactory;
use App\Contexts\Platform\Infrastructure\OrganisationContext;
use App\Contexts\Platform\Infrastructure\OrganisationMigrations;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase;
use Tests\Isolated\Pbdigit274\Support\DatabaseIdentityGuard;
use Tests\Support\MembershipPgIsolatedDatabase;
use Tests\Support\PlatformPgIsolatedDatabases;

/**
 * PBDIGIT-345 task 6 on the isolated cluster: the HTTP entry E-1 (shared hostname, D-219 (2)).
 * - T-1 (ISO-1, ISO-3): one account bound to two organisations; each request sees only the selected organisation's
 *   own database; switching the id in route, body or header is refused, and the selection cleared;
 * - T-15 (E-1, ISO-3): selecting an organisation the account is not bound to is refused; a binding revoked after
 *   selection refuses the next request and clears the selection; a forged session value is refused;
 * - T-10 part 3 (§3.2, D-160): the legacy and registry paths are disjoint: a legacy organisation is refused by the
 *   registry entry; the legacy session key and header are never a selection; the legacy TenantContext never builds an
 *   OrganisationContext; no route runs both entries.
 * - the context exists for one request only: afterwards it is unbound and its connection released.
 */
final class OrganisationEntryHttpTest extends MembershipPostgresTestCase
{
    private const ACCOUNT = '5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c';
    private const OTHER_ACCOUNT = '6e5d4c3b-2a19-4f8e-9d7c-6b5a4f3e2d10';
    private const A = '0b9e7a52-6c1d-4f7e-9a3b-2d5c8e1f4a60';
    private const B = '1c8f6b41-5d2e-4e6f-8b4a-3e6d9f2a5b71';
    private const C = '2d7a5c30-4e3f-4d5e-9c5b-4f7e0a3b6c82';
    private const KEY = OrganisationEntry::SESSION_KEY;

    /** @var object{bound: array<string, list<string>>, unavailable: bool} */
    private object $bindings;
    /** @var object{id: ?string} */
    private object $account;

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
            'database/migrations/2026_11_14_310000_add_founding_application_to_platform_served_organisations.php',
        ]]);
        $s = DatabaseIdentityGuard::connectionSettings(getenv(), MembershipPgIsolatedDatabase::CREDENTIAL_PREFIX);
        $factory = new OrganisationConnectionFactory(
            ['test-cluster' => ['host' => $s['host'], 'port' => $s['port'], 'sslmode' => 'prefer']],
            new EnvironmentDatabaseLogin(['ORG_TEST_USERNAME' => $s['username'], 'ORG_TEST_PASSWORD' => $s['password']]),
        );
        $registry = new PostgresServedOrganisationRepository(DB::connection('platform_central'));
        foreach ([self::A => 'platform_pg_org_a', self::B => 'platform_pg_org_b'] as $id => $database) {
            $locator = new DatabaseLocator('test-cluster', $database, 'env:ORG_TEST');
            $context = $factory->establish(new ResolvedOrganisation(TenantId::fromString($id), $locator));   // test setup only
            app(OrganisationMigrations::class)->apply($context);
            $context->connection->insert('INSERT INTO organisation_identity (id) VALUES (?)', [$id]);
            $factory->release($context);
            $registry->add(self::operational($id, $locator));
        }
        $registry->add(ServedOrganisation::register(TenantId::fromString(self::C), self::application()));   // pending

        $this->app->instance(OrganisationConnectionFactory::class, $factory);
        $this->app->instance(ServedOrganisationRepository::class, $registry);
        $this->bindings = new class implements IdentityBindings {
            /** @var array<string, list<string>> */
            public array $bound = [];
            public bool $unavailable = false;

            public function isBound(string $accountId, TenantId $organisation): bool
            {
                if ($this->unavailable) {
                    throw new BindingsUnavailable('simulated');
                }

                return in_array($organisation->value(), $this->bound[$accountId] ?? [], true);
            }
        };
        $this->bindings->bound[self::ACCOUNT] = [self::A, self::B, self::C];
        $this->app->instance(IdentityBindings::class, $this->bindings);
        $this->account = new class implements SignedInAccount {
            public ?string $id = '5f4e3d2c-1b0a-4f9e-8d7c-6b5a4f3e2d1c';

            public function accountId(): ?string
            {
                return $this->id;
            }
        };
        $this->app->instance(SignedInAccount::class, $this->account);

        $whoami = static fn (): array => [
            'organisation' => app(OrganisationContext::class)->connection->selectOne('SELECT id::text AS id FROM organisation_identity')->id,
            'open' => array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
        ];
        Route::middleware(['web', 'organisation.entry'])->group(static function () use ($whoami): void {
            Route::get('/_t6/whoami', $whoami);
            Route::post('/_t6/whoami', $whoami);
            // never `{organisation}`: the legacy global binder Route::model('organisation') owns that name (T-10 part 3)
            Route::get('/_t6/o/{organisation_id}/whoami', $whoami);
        });
        Route::middleware(['web', 'tenant'])->get('/_t6/legacy', static fn (): array => [
            'context' => app()->bound(OrganisationContext::class),
            'open' => array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
        ]);
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

    private function select(string $id): \Illuminate\Testing\TestResponse
    {
        return $this->post(route('platform.organisations.select'), ['organisation_id' => $id]);
    }

    private function assertNothingLeftOpen(): void
    {
        $this->assertFalse($this->app->bound(OrganisationContext::class), 'the context lives for one request only');
        $this->assertSame([], array_values(array_filter(array_keys(DB::getConnections()), static fn (string $n): bool => str_starts_with($n, 'org_'))),
            'the organisation connection was released');
    }

    public function test_t1_each_request_sees_only_the_selected_organisation_and_switching_the_id_is_refused(): void
    {
        $this->select(self::A)->assertNoContent();
        $this->get('/_t6/whoami')->assertOk()->assertExactJson(['organisation' => self::A, 'open' => [OrganisationConnectionFactory::connectionName(TenantId::fromString(self::A))]]);
        $this->assertNothingLeftOpen();

        $this->select(self::B)->assertNoContent();
        $this->get('/_t6/whoami')->assertOk()->assertJson(['organisation' => self::B]);
        $this->get('/_t6/o/' . self::B . '/whoami')->assertOk()->assertJson(['organisation' => self::B], 'the same id carried by the route is no conflict');

        foreach ([
            'route' => fn () => $this->get('/_t6/o/' . self::A . '/whoami'),
            'body' => fn () => $this->post('/_t6/whoami', ['organisation_id' => self::A]),
            'header' => fn () => $this->withHeaders(['X-Organisation-Id' => self::A])->get('/_t6/whoami'),
            'legacy header' => fn () => $this->withHeaders(['X-Tenant-Id' => self::A])->get('/_t6/whoami'),
        ] as $where => $switch) {
            $this->select(self::B)->assertNoContent();
            $switch()->assertForbidden();
            $this->assertNull(session(self::KEY), "{$where}: the selection is cleared");
            $this->get('/_t6/whoami')->assertForbidden();
            $this->flushHeaders();
        }
        $this->assertNothingLeftOpen();
    }

    public function test_t15_a_selection_of_an_organisation_not_bound_to_the_account_is_refused(): void
    {
        $this->bindings->bound[self::ACCOUNT] = [self::A];
        $this->select(self::A)->assertNoContent();

        $this->select(self::B)->assertForbidden();

        $this->assertNull(session(self::KEY), 'a refused selection also clears the earlier one');
        $this->get('/_t6/whoami')->assertForbidden();
    }

    public function test_t15_a_binding_revoked_after_selection_refuses_the_next_request_and_clears_the_selection(): void
    {
        $this->select(self::A)->assertNoContent();
        $this->get('/_t6/whoami')->assertOk();

        $this->bindings->bound[self::ACCOUNT] = [self::B];

        $this->get('/_t6/whoami')->assertForbidden();
        $this->assertNull(session(self::KEY));
        $this->assertNothingLeftOpen();
    }

    public function test_t15_a_forged_session_value_is_refused(): void
    {
        foreach ([self::B => 'an organisation not bound', 'x\'; DROP TABLE organisations; --' => 'a malformed value', self::C => 'an organisation not operational'] as $forged => $why) {
            $this->bindings->bound[self::ACCOUNT] = [self::A, self::C];
            $this->withSession([self::KEY => $forged])->get('/_t6/whoami')->assertForbidden();
            $this->assertNull(session(self::KEY), "{$why}: cleared");
        }

        $this->select(self::A)->assertNoContent();
        $this->account->id = self::OTHER_ACCOUNT;
        $this->get('/_t6/whoami')->assertForbidden();   // a selection made by one account is no proof for another
        $this->account->id = null;
        $this->withSession([self::KEY => self::A])->get('/_t6/whoami')->assertForbidden();   // nobody signed in
        $this->assertNothingLeftOpen();
    }

    public function test_t10_part3_the_legacy_and_registry_paths_never_share_an_organisation(): void
    {
        $legacy = '9f8e7d6c-5b4a-4392-8170-6f5e4d3c2b1a';
        DB::table('organisations')->insert(['id' => $legacy, 'name' => 'Legacy test org', 'slug' => 'legacy-t6', 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $this->bindings->bound[self::ACCOUNT][] = $legacy;

        $this->select($legacy)->assertForbidden();   // a legacy organisation never enters through the registry path
        $this->withSession([self::KEY => $legacy])->get('/_t6/whoami')->assertForbidden();
        $this->withSession(['current_organisation_id' => self::A])->get('/_t6/whoami')->assertForbidden();   // the legacy key is no selection

        $legacyPath = $this->withHeaders(['X-Tenant-Id' => self::A])->get('/_t6/legacy')->assertOk();
        $legacyPath->assertExactJson(['context' => false, 'open' => []]);   // TenantContext never reaches a registry organisation's database
        $this->assertNull(session(self::KEY), 'the legacy path never makes a registry selection');

        $both = [];
        $legacyBound = [];
        $registryRoutes = 0;
        foreach (Route::getRoutes() as $route) {
            $middleware = Route::gatherRouteMiddleware($route);
            if (!in_array(OrganisationEntry::class, $middleware, true)) {
                continue;
            }
            $registryRoutes++;
            if (array_intersect($middleware, [\App\Http\Middleware\TenantContext::class, \App\Http\Middleware\IdentifyTenantFromHeader::class]) !== []) {
                $both[] = $route->uri();
            }
            // the legacy global binder Route::model('organisation', Organisation::class) (routes/committee/committeeRoutes.php)
            // would resolve such a parameter in the legacy `organisations` table
            if (in_array('organisation', $route->parameterNames(), true)) {
                $legacyBound[] = $route->uri();
            }
        }
        $this->assertGreaterThan(0, $registryRoutes, 'the check saw the registry routes');
        $this->assertSame([], $both, 'no route runs both the registry entry and a legacy tenant resolver');
        $this->assertSame([], $legacyBound, 'no registry route has a parameter the legacy model binder resolves');
    }

    public function test_identity_unavailable_refuses_with_service_unavailable_and_keeps_the_selection(): void
    {
        $this->select(self::A)->assertNoContent();
        $this->bindings->unavailable = true;

        $this->get('/_t6/whoami')->assertStatus(503);

        $this->assertSame(self::A, session(self::KEY), 'an outage is no refusal of the person: the selection resumes afterwards (D-178 §13.6)');
        $this->bindings->unavailable = false;
        $this->get('/_t6/whoami')->assertOk()->assertJson(['organisation' => self::A]);
    }
}
