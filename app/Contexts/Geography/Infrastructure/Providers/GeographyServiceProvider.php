<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Providers;

use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Domain\Services\GeographyDomainService;
use App\Contexts\Geography\Domain\Services\GeoLevelMappingResolver;
use App\Contexts\Geography\Infrastructure\Services\DatabaseGeoLevelMappingResolver;
use App\Contexts\Geography\Infrastructure\Services\GeographyLookupService;
use App\Contexts\Membership\Domain\Services\GeographyLookupInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Geography Service Provider
 *
 * OPTIONAL GEOGRAPHY ARCHITECTURE - Service Bindings
 *
 * This provider registers the Geography context services with Laravel's
 * service container, enabling dependency injection throughout the application.
 *
 * Key Bindings:
 * - GeographyLookupInterface → GeographyLookupService (application-level validation)
 *
 * Architecture Pattern: Dependency Inversion Principle (DIP)
 * - Membership Domain depends on GeographyLookupInterface (abstraction)
 * - Geography Infrastructure provides GeographyLookupService (implementation)
 * - Service container handles the binding and injection
 *
 * Benefits:
 * - Loose coupling between Membership and Geography contexts
 * - Easy to mock in tests (inject fake implementations)
 * - Can swap implementations without changing Membership code
 * - Supports optional Geography installation (graceful degradation)
 *
 * Registration:
 * Add to config/app.php 'providers' array:
 *   App\Contexts\Geography\Infrastructure\Providers\GeographyServiceProvider::class,
 *
 * @package App\Contexts\Geography\Infrastructure\Providers
 */
class GeographyServiceProvider extends ServiceProvider
{
    /**
     * Register Geography context services.
     *
     * Bindings:
     * - GeographyLookupInterface: Used by Membership to validate geography IDs
     *
     * @return void
     */
    public function register(): void
    {
        // Bind Geography Lookup interface to implementation
        // This enables application-level validation instead of database FKs
        $this->app->bind(
            GeographyLookupInterface::class,
            GeographyLookupService::class
        );

        // Bind Geography Domain Service (hierarchy validation)
        $this->app->singleton(
            GeographyDomainService::class,
            fn($app) => new GeographyDomainService(
                $app->make(GeoUnitRepositoryInterface::class)
            )
        );

        // Bind GeoLevelMappingResolver for country-specific db_level resolution
        $this->app->bind(
            GeoLevelMappingResolver::class,
            DatabaseGeoLevelMappingResolver::class
        );
    }

    /**
     * Bootstrap Geography context services.
     *
     * Called after all services are registered.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/Landlord');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            GeographyLookupInterface::class,
            GeographyDomainService::class,
            GeoLevelMappingResolver::class,
        ];
    }
}
