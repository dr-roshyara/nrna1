<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\ReferenceGeography;

use App\Contexts\Identity\Application\Ports\AccountDirectory;
use App\Contexts\Identity\Application\Ports\ContextPersonRefs;
use App\Contexts\Identity\Application\Ports\CurrentAccount;
use App\Contexts\Identity\Infrastructure\LaravelCurrentAccount;
use App\Contexts\Identity\Infrastructure\PostgresAccountDirectory;
use App\Contexts\Identity\Infrastructure\PostgresContextPersonRefs;
use App\Contexts\ReferenceGeography\Application\ActingPersonRefused;
use App\Contexts\ReferenceGeography\Application\ConfirmPublication;
use App\Contexts\ReferenceGeography\Application\IdentityNoticeConsumer;
use App\Contexts\ReferenceGeography\Application\PlatformOwnerRequired;
use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\PlatformOwnerAuthority;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\Ports\RevisionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Application\PublishRevision;
use App\Contexts\ReferenceGeography\Application\RevisionService;
use App\Contexts\ReferenceGeography\Application\StewardRosterService;
use App\Contexts\ReferenceGeography\Domain\PersonReference;
use App\Contexts\ReferenceGeography\Domain\RevisionId;
use App\Contexts\ReferenceGeography\Infrastructure\IdentityActingPerson;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlasSource;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresStewardRosterStore;
use App\Contexts\ReferenceGeography\Infrastructure\UnestablishedPlatformOwnerAuthority;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * PBDIGIT-346 G-4f, the production wiring (the application container as configured, nothing replaced):
 * - Identity's ports (D-225, deferred from G-0) and Geography's ports resolve to the PostgreSQL / Laravel adapters;
 * - ActingPerson resolves through Identity: with nobody signed in, every act is refused; signed in, the actor is the
 *   person's own Geography reference, never a caller-supplied one;
 * - the platform-owner boundary is bound CLOSED (G-3 acceptance item 3): even a signed-in, active person is refused;
 * - the publication ledger is bound without test seams.
 */
final class ProductionWiringTest extends RefgeoPostgresTestCase
{
    public function test_every_port_resolves_to_its_production_adapter(): void
    {
        foreach ([
            CurrentAccount::class => LaravelCurrentAccount::class,
            AccountDirectory::class => PostgresAccountDirectory::class,
            ContextPersonRefs::class => PostgresContextPersonRefs::class,
            ActingPerson::class => IdentityActingPerson::class,
            PlatformOwnerAuthority::class => UnestablishedPlatformOwnerAuthority::class,
            RevisionRepository::class => PostgresRevisionRepository::class,
            StewardRosterStore::class => PostgresStewardRosterStore::class,
            PublicationLedger::class => PostgresPublicationLedger::class,
            PublishedAtlasSource::class => PostgresPublishedAtlasSource::class,
        ] as $port => $adapter) {
            $this->assertInstanceOf($adapter, app($port), $port);
        }
        foreach ([PublishRevision::class, RevisionService::class, StewardRosterService::class, IdentityNoticeConsumer::class, ConfirmPublication::class] as $service) {
            $this->assertInstanceOf($service, app($service));
        }
    }

    public function test_with_nobody_signed_in_every_act_is_refused(): void
    {
        Auth::logout();

        $this->expectException(ActingPersonRefused::class);
        app(RevisionService::class)->prepare(RevisionId::fromString((string) Str::uuid()));
    }

    public function test_the_actor_is_the_signed_in_persons_own_reference_and_the_platform_owner_boundary_is_closed(): void
    {
        $org = (string) Str::uuid();
        DB::table('organisations')->insert(['id' => $org, 'name' => 'g4f', 'slug' => 'g4f-' . $org, 'type' => 'tenant', 'created_at' => now(), 'updated_at' => now()]);
        $user = (string) Str::uuid();
        DB::table('users')->insert(['id' => $user, 'organisation_id' => $org, 'name' => 'G4f user', 'region' => 'x', 'email' => "g4f-{$user}@example.test",
            'password' => 'x', 'email_verified_at' => now(), 'created_at' => now(), 'updated_at' => now()]);
        Auth::onceUsingId($user);

        $actor = app(ActingPerson::class)->current();
        $this->assertInstanceOf(PersonReference::class, $actor);
        $this->assertNotSame(str_replace('-', '', $user), $actor->value(), 'an opaque Geography reference, not the account id');
        $this->assertTrue($actor->equals(app(ActingPerson::class)->current()), 'stable for the person');

        $this->expectException(PlatformOwnerRequired::class);
        app(StewardRosterService::class)->appointSteward(PersonReference::fromString(md5('anyone')));
    }
}
