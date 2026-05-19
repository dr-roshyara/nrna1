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
        // Membership context: Committee creation ports
        $this->app->bind(
            \App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort::class,
            \App\Contexts\Membership\Infrastructure\Repositories\CommitteeRepository::class
        );

        $this->app->bind(
            \App\Contexts\Membership\Application\Committee\Ports\EventBusPort::class,
            \App\Contexts\Membership\Infrastructure\Events\LaravelEventBusAdapter::class
        );

        // Committee creation: canonical handler with matrix-aware governance validation
        $this->app->bind(
            \App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler::class,
            function ($app) {
                $rows = \Illuminate\Support\Facades\DB::table('governance_level_definitions')
                    ->select('level', 'is_active')
                    ->get()
                    ->map(fn($row) => (array) $row)
                    ->toArray();

                return new \App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee\CreateCommitteeHandler(
                    repository: $app->make(\App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort::class),
                    eventBus: $app->make(\App\Contexts\Membership\Application\Committee\Ports\EventBusPort::class),
                    policy: new \App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy(
                        \App\Contexts\Membership\Domain\Committee\Policies\GovernanceMatrix::fromRows($rows)
                    ),
                );
            }
        );

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

        // Election Context: Voter assignment (Phase C strangler)
        $this->app->bind(
            \App\Contexts\Elections\Domain\Repositories\VoterRepositoryInterface::class,
            \App\Contexts\Elections\Infrastructure\Repositories\EloquentVoterRepository::class
        );

        // Election Context: VoterEligibilityPolicy (Phase B strangler)
        $this->app->bind(
            \App\Contexts\Elections\Domain\Policies\ElectionOnlyPolicy::class,
            \App\Contexts\Elections\Domain\Policies\ElectionOnlyPolicy::class
        );

        $this->app->bind(
            \App\Contexts\Elections\Domain\Policies\FullMembershipPolicy::class,
            \App\Contexts\Elections\Domain\Policies\FullMembershipPolicy::class
        );

        $this->app->bind(
            \App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy::class,
            \App\Contexts\Elections\Infrastructure\Policies\EloquentVoterEligibilityQueryService::class
        );

        // Election Lifecycle: SSOT (Single Source of Truth) engine for state derivation
        $this->app->bind(
            \App\Domain\Election\Services\ElectionLifecycleEngine::class,
            \App\Application\Election\Services\ElectionLifecycleEngineImpl::class
        );

        // Election Constitutional Guard: Hard gate enforcement for state transitions
        $this->app->singleton(
            \App\Application\Election\Services\ConstitutionalTransitionGuard::class
        );

        // Constitutional Drift Monitor: Records SSOT violations for tracking and analysis
        $this->app->singleton(
            \App\Application\Election\Monitoring\ConstitutionalDriftMonitor::class
        );

        // Deprecation Enforcement Layer (DEL): Runtime field access control
        $this->app->singleton(
            \App\Application\Election\Deprecation\DeprecationAccessGuard::class,
            fn($app) => new \App\Application\Election\Deprecation\DeprecationAccessGuard(
                $app->make(\App\Application\Election\Monitoring\ConstitutionalDriftMonitor::class)
            )
        );

        // Query Policy Guard (DEL): SQL-level deprecation enforcement
        $this->app->singleton(
            \App\Application\Election\Deprecation\QueryPolicyGuard::class,
            fn($app) => new \App\Application\Election\Deprecation\QueryPolicyGuard(
                $app->make(\App\Application\Election\Monitoring\ConstitutionalDriftMonitor::class)
            )
        );

        // Election Lifecycle Contract: Injectable factory interface (Stream 3)
        $this->app->singleton(
            \App\Application\Election\Contracts\ElectionLifecycleContract::class,
            \App\Application\Election\Contracts\ElectionLifecycleFactory::class
        );

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

        // Load migrations from database/migrations/landlord/ (temporal governance constraints)
        $this->loadMigrationsFrom(database_path('migrations/landlord'));

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
