<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DemoElectionResolver;
use App\Services\VoterSlugService;
use App\Services\DemoElectionCreationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register singleton services for dependency injection
        $this->app->singleton(DemoElectionResolver::class, function () {
            return new DemoElectionResolver();
        });

        $this->app->singleton(DemoElectionCreationService::class, function () {
            return new DemoElectionCreationService();
        });

        $this->app->singleton(VoterSlugService::class, function () {
            return new VoterSlugService(
                $this->app->make(DemoElectionResolver::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load helper functions
        foreach (['TenantHelper.php', 'ElectionAudit.php'] as $helperFile) {
            $helperPath = app_path('Helpers/' . $helperFile);
            if (file_exists($helperPath)) {
                require_once $helperPath;
            }
        }
    }
}
