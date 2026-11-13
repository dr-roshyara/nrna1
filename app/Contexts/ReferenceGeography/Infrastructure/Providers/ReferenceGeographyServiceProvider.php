<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure\Providers;

use App\Contexts\ReferenceGeography\Application\Ports\ActingPerson;
use App\Contexts\ReferenceGeography\Application\Ports\CandidateRepository;
use App\Contexts\ReferenceGeography\Application\Ports\ObjectionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedClaims;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresObjectionRepository;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresPublishedClaims;
use App\Contexts\ReferenceGeography\Application\Ports\RosterReader;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresCandidateRepository;
use App\Contexts\ReferenceGeography\Application\Ports\GeographyReadSource;
use App\Contexts\ReferenceGeography\Application\Ports\PlatformOwnerAuthority;
use App\Contexts\ReferenceGeography\Application\Ports\PublicationLedger;
use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Application\Ports\RevisionRepository;
use App\Contexts\ReferenceGeography\Application\Ports\StewardRosterStore;
use App\Contexts\ReferenceGeography\Application\Ports\TimeBoundary;
use App\Contexts\ReferenceGeography\Application\Published\GeographyReadModel;
use App\Contexts\ReferenceGeography\Application\PublishedGeography;
use App\Contexts\ReferenceGeography\Infrastructure\IdentityActingPerson;
use App\Contexts\ReferenceGeography\Infrastructure\PostgresGeographyReadSource;
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
        // G-5a: the published read contract; the read side gets only the time boundary, never the publication lock
        $this->app->bind(TimeBoundary::class, static fn (): PostgresPublicationLedger => new PostgresPublicationLedger(DB::connection()));
        $this->app->bind(GeographyReadSource::class, static fn (): PostgresGeographyReadSource => new PostgresGeographyReadSource(DB::connection()));
        $this->app->bind(GeographyReadModel::class, PublishedGeography::class);
        // G-6a: candidates (operational, outside the knowledge stream)
        $this->app->bind(CandidateRepository::class, static fn (): PostgresCandidateRepository => new PostgresCandidateRepository(DB::connection()));
        $this->app->bind(RosterReader::class, static fn (): PostgresStewardRosterStore => new PostgresStewardRosterStore(DB::connection()));
        // G-6b: objections (operational) and the published-claim check they need
        $this->app->bind(ObjectionRepository::class, static fn (): PostgresObjectionRepository => new PostgresObjectionRepository(DB::connection()));
        $this->app->bind(PublishedClaims::class, static fn (): PostgresPublishedClaims => new PostgresPublishedClaims(DB::connection()));    }
}
