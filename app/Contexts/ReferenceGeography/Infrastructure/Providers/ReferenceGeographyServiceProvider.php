<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure\Providers;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\PlatformOwnerAuthority;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\Ports\RevisionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Infrastructure\IdentityActingPerson;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublicationLedger;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedAtlasSource;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresRevisionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresStewardRosterStore;
use App\Contexts\ReferenceGeography\Infrastructure\UnestablishedPlatformOwnerAuthority;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * Reference Geography's ports (PBDIGIT-346 G-4f), on the CENTRAL (default) connection (completion §4).
 * - ActingPerson → Identity (D-225): the actor is always the signed-in person's own Geography reference.
 * - PlatformOwnerAuthority → UnestablishedPlatformOwnerAuthority: CLOSED until a Principal decision establishes the
 *   platform owner (G-3 acceptance item 3). Do not rebind it to a fixture or a role.
 * - The ledger is bound without its test seams.
 * The application services (RevisionService, PublishRevision, StewardRosterService, IdentityNoticeConsumer) are
 * resolved by autowiring from these bindings.
 */
final class ReferenceGeographyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ActingPerson::class, IdentityActingPerson::class);
        $this->app->bind(PlatformOwnerAuthority::class, UnestablishedPlatformOwnerAuthority::class);
        $this->app->bind(RevisionRepository::class, static fn (): PostgresRevisionRepository => new PostgresRevisionRepository(DB::connection()));
        $this->app->bind(StewardRosterStore::class, static fn (): PostgresStewardRosterStore => new PostgresStewardRosterStore(DB::connection()));
        $this->app->bind(PublicationLedger::class, static fn (): PostgresPublicationLedger => new PostgresPublicationLedger(DB::connection()));
        $this->app->bind(PublishedAtlasSource::class, static fn (): PostgresPublishedAtlasSource => new PostgresPublishedAtlasSource(DB::connection()));
    }
}
