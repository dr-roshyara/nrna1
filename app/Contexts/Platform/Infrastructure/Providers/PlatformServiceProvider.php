<?php

declare(strict_types=1);

namespace App\Contexts\Platform\Infrastructure\Providers;

use App\Contexts\Platform\Application\Ports\ServedOrganisationRepository;
use App\Contexts\Platform\Infrastructure\PostgresServedOrganisationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

/**
 * PBDIGIT-345: Platform's production wiring. The registry lives in the CENTRAL database (the default connection), never
 * in an organisation database (D-193, D-222). ResolutionService needs no binding of its own (autowired).
 */
final class PlatformServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServedOrganisationRepository::class, static fn (): PostgresServedOrganisationRepository => new PostgresServedOrganisationRepository(DB::connection()));
    }
}
