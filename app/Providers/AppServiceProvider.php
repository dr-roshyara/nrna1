<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DemoElectionResolver;
use App\Services\VoterSlugService;
use App\Services\DemoElectionCreationService;
use App\Models\UserOrganisationRole;
use App\Observers\UserOrganisationObserver;

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

        // Register custom Fortify login response
        // This ensures LoginResponse handles post-authentication redirection via DashboardResolver
        $this->app->bind(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register mail components as Blade aliases
        // This allows x-mail::message etc. to work in custom email templates
        // Each component is mapped to its view file in resources/views/vendor/mail/html/
        \Illuminate\Support\Facades\Blade::component('vendor.mail.html.message', 'mail::message');
        \Illuminate\Support\Facades\Blade::component('vendor.mail.html.button', 'mail::button');
        \Illuminate\Support\Facades\Blade::component('vendor.mail.html.panel', 'mail::panel');
        \Illuminate\Support\Facades\Blade::component('vendor.mail.html.subcopy', 'mail::subcopy');
        \Illuminate\Support\Facades\Blade::component('vendor.mail.html.table', 'mail::table');

        // Load helper functions
        foreach (['TenantHelper.php', 'ElectionAudit.php'] as $helperFile) {
            $helperPath = app_path('Helpers/' . $helperFile);
            if (file_exists($helperPath)) {
                require_once $helperPath;
            }
        }
    }
}
