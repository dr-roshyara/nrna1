<?php

namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Contracts\TenantContextInterface;
use App\Services\ManualPaymentGateway;
use App\Shared\Domain\Events\EventBus;
use App\Shared\Infrastructure\Events\LaravelEventBus;
use Illuminate\Support\ServiceProvider;
use App\Services\DemoElectionResolver;
use App\Services\VoterSlugService;
use App\Services\DemoElectionCreationService;
use App\Services\TenantContext;
use App\Services\DeviceFingerprint;
use App\Services\ElectionAuditService;
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

        // EventBus: swap LaravelEventBus for a queue-backed bus when ready
        $this->app->bind(EventBus::class, LaravelEventBus::class);

        // TenantContext: bind both the concrete class and its interface (HTTP implementation)
        $this->app->singleton(TenantContext::class, function () {
            return new TenantContext();
        });
        $this->app->bind(TenantContextInterface::class, TenantContext::class);

        // Register DeviceFingerprint service as singleton for device-based fraud detection
        $this->app->singleton(DeviceFingerprint::class, function () {
            return new DeviceFingerprint();
        });

        // Register ElectionAuditService as singleton for audit logging
        $this->app->singleton(ElectionAuditService::class, function () {
            return new ElectionAuditService();
        });

        // Register SeoService as singleton for injectable getMeta() usage
        $this->app->singleton(\App\Services\SeoService::class);

        // Membership payment gateway — Phase 1: manual (no-op). Swap for Stripe in Phase 5.
        $this->app->bind(PaymentGateway::class, ManualPaymentGateway::class);

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
        // Load migrations from context directories
        $this->loadMigrationsFrom(app_path('Contexts/Membership/Infrastructure/Database/Migrations/Tenant'));
        $this->loadMigrationsFrom(app_path('Contexts/Geography/Infrastructure/Database/Migrations'));

        // Validate election state machine configuration at boot time (fail fast)
        \App\Domain\Election\StateMachine\TransitionMatrix::validate();

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
