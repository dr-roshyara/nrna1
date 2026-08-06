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
        // Event Registry (Blueprint Push B §6, §16 step 4 · ADR-T3/T5):
        // container singleton — each context's provider registers its own
        // hydrators; adding a context event never edits shared infrastructure.
        $this->app->singleton(\App\Contexts\Shared\Infrastructure\Outbox\EventHydratorRegistry::class);

        // Inbox Handler Registry (Blueprint Push B §6, §16 step 6 · ADR-T4 · PB-003-C4):
        // container singleton — consuming contexts register their inbox handlers
        // from their own providers; adding a context never edits shared infrastructure.
        $this->app->singleton(\App\Contexts\Shared\Infrastructure\Inbox\InboxHandlerRegistry::class);

        // Messaging delivery capability (ADR-MP-06 · PB-006-6A): Consumer Discovery is a
        // Messaging-owned port; the registry is the implementation behind it.
        $this->app->bind(
            \App\Contexts\Shared\Infrastructure\Messaging\ConsumerResolver::class,
            \App\Contexts\Shared\Infrastructure\Messaging\RegistryConsumerResolver::class
        );

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

        // Register ConstitutionalMetrics for operational governance observability (Phase 3.1.D)
        $this->app->singleton(
            \App\Application\Election\Monitoring\ConstitutionalMetricsContract::class,
            \App\Application\Election\Monitoring\ConstitutionalMetrics::class
        );

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

        // Election Constitutional Guard: Hard gate enforcement for state transitions with metrics
        $this->app->singleton(
            \App\Application\Election\Services\ConstitutionalTransitionGuard::class,
            fn($app) => new \App\Application\Election\Services\ConstitutionalTransitionGuard(
                $app->make(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class)
            )
        );

        // Constitutional Trust Infrastructure: Phase D.5 Resolver Wiring
        // Priority 3: Clock Isolation (Temporal Determinism)
        // Register ClockInterface with production SystemClock
        $this->app->singleton(
            \App\Domain\Shared\Clock\ClockInterface::class,
            \App\Infrastructure\Shared\Clock\SystemClock::class
        );

        // Register trust evidence privacy policy
        $this->app->singleton(
            \App\Domain\Election\Security\TrustEvidencePrivacyPolicy::class,
            fn($app) => new \App\Domain\Election\Security\TrustEvidencePrivacyPolicy()
        );

        // Register trust evaluation overlays (Phase D.4)
        $this->app->singleton(
            \App\Application\Election\Security\Overlays\EmergencyConditionOverlay::class,
            fn($app) => new \App\Application\Election\Security\Overlays\EmergencyConditionOverlay()
        );

        $this->app->singleton(
            \App\Application\Election\Security\Overlays\RegistrarAttestationElevation::class,
            fn($app) => new \App\Application\Election\Security\Overlays\RegistrarAttestationElevation()
        );

        $this->app->singleton(
            \App\Application\Election\Security\Overlays\SuspiciousActivityOverlay::class,
            fn($app) => new \App\Application\Election\Security\Overlays\SuspiciousActivityOverlay()
        );

        $this->app->singleton(
            \App\Application\Election\Security\Overlays\IpVelocityOverlay::class,
            fn($app) => new \App\Application\Election\Security\Overlays\IpVelocityOverlay(
                clock: $app->make(\App\Domain\Shared\Clock\ClockInterface::class)
            )
        );

        $this->app->singleton(
            \App\Application\Election\Security\Overlays\DeviceAnomalyOverlay::class,
            fn($app) => new \App\Application\Election\Security\Overlays\DeviceAnomalyOverlay()
        );

        // Phase C: Constitutional IP Migration Overlays
        $this->app->singleton(
            \App\Application\Election\Security\Overlays\NetworkContinuityObservation::class,
            fn($app) => new \App\Application\Election\Security\Overlays\NetworkContinuityObservation()
        );

        $this->app->singleton(
            \App\Application\Election\Security\Overlays\ParticipationDensityObservation::class,
            fn($app) => new \App\Application\Election\Security\Overlays\ParticipationDensityObservation()
        );

        // Register overlay coordinator with all 7 overlays
        $this->app->singleton(
            \App\Application\Election\Security\OverlayAggregator::class,
            fn($app) => new \App\Application\Election\Security\OverlayAggregator([
                $app->make(\App\Application\Election\Security\Overlays\EmergencyConditionOverlay::class),
                $app->make(\App\Application\Election\Security\Overlays\RegistrarAttestationElevation::class),
                $app->make(\App\Application\Election\Security\Overlays\SuspiciousActivityOverlay::class),
                $app->make(\App\Application\Election\Security\Overlays\IpVelocityOverlay::class),
                $app->make(\App\Application\Election\Security\Overlays\DeviceAnomalyOverlay::class),
                $app->make(\App\Application\Election\Security\Overlays\NetworkContinuityObservation::class),
                $app->make(\App\Application\Election\Security\Overlays\ParticipationDensityObservation::class),
            ])
        );

        // Register trust policies
        $this->app->singleton(
            \App\Application\Election\Security\Policies\VerificationAttestationPolicy::class,
            fn($app) => new \App\Application\Election\Security\Policies\VerificationAttestationPolicy()
        );

        $this->app->singleton(
            \App\Application\Election\Security\Policies\NetworkBindingPolicy::class,
            fn($app) => new \App\Application\Election\Security\Policies\NetworkBindingPolicy()
        );

        $this->app->singleton(
            \App\Application\Election\Security\Policies\DeviceBindingPolicy::class,
            fn($app) => new \App\Application\Election\Security\Policies\DeviceBindingPolicy()
        );

        // Register policy sequence
        $this->app->singleton(
            \App\Application\Election\Security\PolicySequence::class,
            fn($app) => new \App\Application\Election\Security\PolicySequence(
                verificationPolicy: $app->make(\App\Application\Election\Security\Policies\VerificationAttestationPolicy::class),
                networkPolicy: $app->make(\App\Application\Election\Security\Policies\NetworkBindingPolicy::class),
                devicePolicy: $app->make(\App\Application\Election\Security\Policies\DeviceBindingPolicy::class),
            )
        );

        // Register security event recorder with injected clock
        $this->app->singleton(
            \App\Application\Election\Security\SecurityEventRecorder::class,
            fn($app) => new \App\Application\Election\Security\SecurityEventRecorder(
                clock: $app->make(\App\Domain\Shared\Clock\ClockInterface::class)
            )
        );

        // Register trust snapshot assembler
        $this->app->singleton(
            \App\Application\Election\Security\TrustSnapshotAssembler::class,
            fn($app) => new \App\Application\Election\Security\TrustSnapshotAssembler()
        );

        // Register trust policy evaluator with all dependencies
        $this->app->singleton(
            \App\Application\Election\Security\TrustPolicyEvaluator::class,
            fn($app) => new \App\Application\Election\Security\TrustPolicyEvaluator(
                OverlayAggregator: $app->make(\App\Application\Election\Security\OverlayAggregator::class),
                policySequence: $app->make(\App\Application\Election\Security\PolicySequence::class),
                eventRecorder: $app->make(\App\Application\Election\Security\SecurityEventRecorder::class),
                privacyPolicy: $app->make(\App\Domain\Election\Security\TrustEvidencePrivacyPolicy::class),
                assembler: $app->make(\App\Application\Election\Security\TrustSnapshotAssembler::class),
            )
        );

        // Election Capability System: Resolver orchestrates policies for advisory capability projection
        $this->app->singleton(
            \App\Application\Election\Capabilities\ElectionConstitutionRegistry::class,
            fn($app) => new \App\Application\Election\Capabilities\ElectionConstitutionRegistry()
        );

        $this->app->singleton(
            \App\Application\Election\Services\ElectionCapabilityResolver::class,
            fn($app) => new \App\Application\Election\Services\ElectionCapabilityResolver([
                new \App\Application\Election\Capabilities\Policy\OverlayCapabilityPolicy(),
                new \App\Application\Election\Capabilities\Policies\EvidenceCapabilityPolicy(),
                new \App\Application\Election\Capabilities\Policy\LifecycleCapabilityBaselinePolicy(),
            ])
        );

        // Constitutional Drift Monitor: Records SSOT violations for tracking and analysis
        $this->app->singleton(
            \App\Application\Election\Monitoring\ConstitutionalDriftMonitor::class
        );

        // Deprecation Enforcement Layer (DEL): Runtime field access control with metrics
        $this->app->singleton(
            \App\Application\Election\Deprecation\DeprecationAccessGuard::class,
            fn($app) => new \App\Application\Election\Deprecation\DeprecationAccessGuard(
                $app->make(\App\Application\Election\Monitoring\ConstitutionalDriftMonitor::class),
                $app->make(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class)
            )
        );

        // Query Policy Guard (DEL): SQL-level deprecation enforcement with metrics
        $this->app->singleton(
            \App\Application\Election\Deprecation\QueryPolicyGuard::class,
            fn($app) => new \App\Application\Election\Deprecation\QueryPolicyGuard(
                $app->make(\App\Application\Election\Monitoring\ConstitutionalDriftMonitor::class),
                $app->make(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class)
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
        // PBDIGIT-33: attach the routing-cache invalidator. The observer and its model
        // were already imported above but never attached, so a login routing decision
        // (cached 300s, config/login-routing.php) survived membership changes.
        UserOrganisationRole::observe(UserOrganisationObserver::class);

        // Messaging delivery (ADR-MP-06, D-2): the dispatcher is a LISTENER on the
        // relay's dispatched IntegrationEvent — the relay stays byte-identical; the
        // dispatcher is simply another consumer of relay output.
        \Illuminate\Support\Facades\Event::listen(
            \App\Contexts\Shared\Infrastructure\Outbox\IntegrationEvent::class,
            [\App\Contexts\Shared\Infrastructure\Messaging\IntegrationEventDispatcher::class, 'handle']
        );

        // Load migrations from context directories
        $this->loadMigrationsFrom(app_path('Contexts/Membership/Infrastructure/Database/Migrations/Tenant'));
        $this->loadMigrationsFrom(app_path('Contexts/Geography/Infrastructure/Database/Migrations'));

        // Load migrations from database/migrations/landlord/ (temporal governance constraints)
        $this->loadMigrationsFrom(database_path('migrations/landlord'));

        // @deprecated: TransitionMatrix validation has been migrated to ElectionConstitution
        // The constitution is code-based, so invalid configs surface at parse time
        // Future: Add ElectionConstitution::validate() for runtime checks if needed
        // \App\Domain\Election\StateMachine\TransitionMatrix::validate();

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
