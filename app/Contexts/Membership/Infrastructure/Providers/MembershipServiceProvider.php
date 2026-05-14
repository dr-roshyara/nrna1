<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Providers;

use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructure;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructureUseCase;
use App\Contexts\Membership\Application\CommitteeStructure\DefineCommitteeStructure;
use App\Contexts\Membership\Infrastructure\Application\TransactionalActivateCommitteeStructure;
use App\Contexts\Membership\Application\Handlers\RegisterMemberHandler;
use App\Contexts\Membership\Application\Services\MobileMemberRegistrationService;
use App\Contexts\Membership\Application\Services\DesktopMemberRegistrationService;
use App\Contexts\Membership\Application\Services\DesktopMemberApprovalService;
use App\Contexts\Membership\Application\Services\DesktopMemberRejectionService;
use App\Contexts\Membership\Application\Committee\AssignMemberToCommittee;
use App\Contexts\Membership\Application\Committee\CreateCommitteeUseCase;
use App\Contexts\Membership\Application\Committee\InternalCreateCommittee;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Application\Committee\RemoveMemberFromCommittee;
use App\Contexts\Membership\Application\Committee\UpdateCommitteeDetails;
use App\Contexts\Membership\Application\Committee\Policies\GovernanceAccessPolicyInterface;
use App\Contexts\Membership\Application\Committee\Policies\PermissiveGovernanceAccessPolicy;
use App\Contexts\Membership\Application\Committee\Policies\GovernanceCapabilityPolicy;
use App\Contexts\Membership\Application\Committee\Policies\GovernanceCapabilityContextFactory;
use App\Contexts\Membership\Domain\Committee\Capability\GovernanceCapabilityPolicyEngine;
use App\Contexts\Membership\Domain\Committee\Capability\CommitteeCreationPolicy as CapabilityCommitteeCreationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\StructureActivationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\CommitteeModificationPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\StructureDeprecationPolicy;
use App\Contexts\Membership\Infrastructure\Application\TransactionalCreateCommittee;
use App\Contexts\Membership\Application\Member\UseCases\RegisterMember;
use App\Contexts\Membership\Application\Member\UseCases\ActivateMember;
use App\Contexts\Membership\Application\Member\UseCases\SuspendMember;
use App\Contexts\Membership\Application\Member\UseCases\ArchiveMember;
use App\Contexts\Membership\Application\Application\UseCases\SubmitApplication;
use App\Contexts\Membership\Application\Application\UseCases\ApproveApplication;
use App\Contexts\Membership\Application\Application\UseCases\RejectApplication;
use App\Contexts\Membership\Application\Application\UseCases\SubmitMembershipApplication;
use App\Contexts\Membership\Application\Application\UseCases\RejectMembershipApplication;
use App\Contexts\Membership\Application\Application\UseCases\ApproveMembershipApplication;
use App\Contexts\Membership\Application\Fee\UseCases\RecordFeePayment;
use App\Contexts\Membership\Application\Fee\UseCases\WaiveFee;
use App\Contexts\Membership\Domain\Fee\Services\PaymentPolicy;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxWriter;
use App\Shared\Domain\Events\EventBus;
use App\Shared\Infrastructure\Events\LaravelEventBus;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface as CommitteeAggregateRepositoryInterface;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Committee\Application\ReadModel\CommitteeMembershipReadModelAdapter;
use App\Contexts\Committee\Infrastructure\Persistence\EloquentCommitteeMembershipReadModelAdapter;
use App\Contexts\Membership\Domain\Committee\CommitteeCreationPolicy;
use App\Contexts\Membership\Domain\Committee\Ports\GeoContextPort;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;
use App\Contexts\Membership\Application\Committee\Services\CommitteeCategoryDerivationService;
use App\Contexts\Membership\Domain\Committee\Services\CommitteePolicyResolver;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\Factories\CommitteeGeoIdentityFactory;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeClassificationPolicy;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Services\GeographicEligibilityValidator;
use App\Contexts\Membership\Application\Committee\Handlers\AssignMemberToCommitteeHandler;
use App\Contexts\Membership\Application\Committee\Services\NearbyCommitteesQueryService;
use App\Contexts\Membership\Infrastructure\Ports\GeographyContextAdapter;
use App\Contexts\Membership\Domain\Services\IdentityVerificationInterface;
use App\Contexts\Membership\Domain\Services\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\Services\GeographyResolverInterface;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentMemberRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentApplicationRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentFeeRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeAggregateRepository;
use App\Contexts\Membership\Infrastructure\Persistence\Repositories\EloquentCommitteeStructureRepository;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionStore;
use App\Contexts\Membership\Infrastructure\Persistence\Repositories\EloquentGovernanceDecisionStore;
use App\Contexts\Membership\Infrastructure\Services\TenantUserIdentityVerification;
use App\Contexts\Membership\Infrastructure\Services\TenantAuthProvisioningAdapter;
use App\Contexts\Membership\Infrastructure\Services\GeographyValidationAdapter;
use App\Contexts\Membership\Infrastructure\Services\GeographicJurisdictionProviderAdapter;
use Illuminate\Support\ServiceProvider;

/**
 * Membership Service Provider
 *
 * Registers all Membership Context services and bindings.
 *
 * Responsibilities:
 * - Bind repository interfaces to implementations
 * - Bind domain service interfaces to implementations
 * - Register application handlers
 * - Load migrations from context
 * - Register routes (if needed)
 *
 * Architectural Note (ADR-001):
 * - This is the ONLY place where concrete implementations are bound
 * - Application code depends on interfaces, not implementations
 */
class MembershipServiceProvider extends ServiceProvider
{
    /**
     * Register services
     *
     * Binds interfaces to concrete implementations
     */
    public function register(): void
    {
        // Read model adapters
        $this->app->bind(
            CommitteeMembershipReadModelAdapter::class,
            EloquentCommitteeMembershipReadModelAdapter::class
        );

        // Repository bindings
        $this->app->bind(
            MemberRepositoryInterface::class,
            EloquentMemberRepository::class
        );

        $this->app->bind(
            ApplicationRepositoryInterface::class,
            EloquentApplicationRepository::class
        );

        $this->app->bind(
            FeeRepositoryInterface::class,
            EloquentFeeRepository::class
        );

        // Repository binding (no dependencies in constructor)
        $this->app->singleton(
            \App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort::class,
            \App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeAssociationRepository::class
        );

        // Lifecycle policy binding (depends on repository for queries)
        $this->app->singleton(
            \App\Contexts\Membership\Domain\Membership\CommitteeAssociationLifecyclePolicy::class,
            function ($app) {
                return new \App\Contexts\Membership\Domain\Membership\CommitteeAssociationLifecyclePolicy(
                    $app->make(\App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort::class)
                );
            }
        );

        // After repository is resolved, inject the policy
        $this->app->afterResolving(
            \App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort::class,
            function ($repository, $app) {
                $repository->setLifecyclePolicy(
                    $app->make(\App\Contexts\Membership\Domain\Membership\CommitteeAssociationLifecyclePolicy::class)
                );
            }
        );

        // A2.2: MembershipLineage repository (strangler pattern - new constitutional model)
        $this->app->singleton(
            \App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort::class,
            \App\Contexts\Membership\Infrastructure\Repositories\EloquentMembershipLineageRepository::class
        );

        // F3.1: Constitutional lifecycle handlers
        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\SuspendMembership\SuspendMembershipHandler::class,
            function ($app) {
                return new \App\Contexts\Membership\Application\Membership\SuspendMembership\SuspendMembershipHandler(
                    $app->make(\App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort::class),
                    $app->make(EventBus::class),
                );
            }
        );

        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\TerminateMembership\TerminateMembershipHandler::class,
            function ($app) {
                return new \App\Contexts\Membership\Application\Membership\TerminateMembership\TerminateMembershipHandler(
                    $app->make(\App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort::class),
                    $app->make(EventBus::class),
                );
            }
        );

        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\RestoreMembership\RestoreMembershipHandler::class,
            function ($app) {
                return new \App\Contexts\Membership\Application\Membership\RestoreMembership\RestoreMembershipHandler(
                    $app->make(\App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort::class),
                    $app->make(EventBus::class),
                );
            }
        );

        $this->app->bind(
            CommitteeRepositoryInterface::class,
            EloquentCommitteeRepository::class
        );

        $this->app->bind(
            CommitteeStructureRepositoryInterface::class,
            EloquentCommitteeStructureRepository::class
        );

        $this->app->bind(
            CommitteeAggregateRepositoryInterface::class,
            EloquentCommitteeAggregateRepository::class
        );

        $this->app->bind(
            GovernanceDecisionStore::class,
            EloquentGovernanceDecisionStore::class
        );

        // Domain service bindings
        $this->app->bind(CommitteeCreationPolicy::class, function ($app) {
            return new CommitteeCreationPolicy(
                $app->make(GeoContextPort::class)
            );
        });

        $this->app->bind(
            GeoContextPort::class,
            GeographyContextAdapter::class
        );

        $this->app->bind(
            GeographicJurisdictionProvider::class,
            GeographicJurisdictionProviderAdapter::class
        );

        // Phase 8C.2C: Geo identity, projection builder, and classification policy
        $this->app->bind(GeoSemanticProjectionBuilder::class, function ($app) {
            return new GeoSemanticProjectionBuilder(
                $app->make(GeographicJurisdictionProvider::class)
            );
        });

        $this->app->singleton(CommitteeGeoIdentityFactory::class);
        $this->app->singleton(CommitteeClassificationPolicy::class);

        // Phase 8C.2D: Category derivation service (frontend simplification)
        $this->app->bind(CommitteeCategoryDerivationService::class, function ($app) {
            return new CommitteeCategoryDerivationService(
                $app->make(GeoSemanticProjectionBuilder::class),
                $app->make(CommitteeClassificationPolicy::class),
            );
        });

        // Phase 8E: Geographic eligibility and member assignment
        $this->app->singleton(CommitteeEligibilityPolicy::class);
        $this->app->singleton(GeographicEligibilityValidator::class);

        // F1: Member geo path port — resolves member's geo context (session-backed)
        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\Ports\MemberGeoPathProviderPort::class,
            \App\Contexts\Membership\Infrastructure\Query\SessionMemberGeoPathProvider::class
        );

        // F2: Real implementations — replace F1 stubs
        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort::class,
            \App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeMembershipApplicationRepository::class
        );

        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort::class,
            \App\Contexts\Membership\Infrastructure\Query\EloquentCommitteeGeoPathProvider::class
        );

        // F1: Phase C eligibility service (single source of truth for eligibility)
        // Depends on stubs being bound first (see above)
        $this->app->bind(
            \App\Contexts\Membership\Application\Membership\Query\EligibleCommitteeQueryService::class,
            function ($app) {
                return new \App\Contexts\Membership\Application\Membership\Query\EligibleCommitteeQueryServiceImpl(
                    $app->make(\App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface::class),
                    $app->make(\App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort::class),
                    $app->make(\App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort::class),
                    $app->make(\App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy::class),
                    $app->make(\App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort::class),
                );
            }
        );

        $this->app->bind(NearbyCommitteesQueryService::class, function ($app) {
            return new NearbyCommitteesQueryService(
                $app->make(GeoSemanticProjectionBuilder::class),
            );
        });

        $this->app->bind(AssignMemberToCommitteeHandler::class, function ($app) {
            return new AssignMemberToCommitteeHandler(
                $app->make(GeoSemanticProjectionBuilder::class),
                $app->make(CommitteeEligibilityPolicy::class),
                $app->make(MemberRepositoryInterface::class),
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(EventBus::class),
            );
        });

        // CommitteeStructure use case bindings (with transactional decorators)
        $this->app->bind(DefineCommitteeStructure::class, function ($app) {
            return new DefineCommitteeStructure(
                $app->make(CommitteeStructureRepositoryInterface::class)
            );
        });

        // CRITICAL: Bind ONLY the interface, never the concrete class
        // This makes it STRUCTURALLY IMPOSSIBLE to bypass the decorator
        $this->app->bind(ActivateCommitteeStructureUseCase::class, function ($app) {
            $core = new ActivateCommitteeStructure(
                $app->make(CommitteeStructureRepositoryInterface::class)
            );

            return new TransactionalActivateCommitteeStructure($core);
        });

        // Use case bindings
        $this->app->bind(GetCommitteeDashboard::class, function ($app) {
            return new GetCommitteeDashboard(
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(MembershipLineageRepositoryPort::class),
                $app->make(CommitteeMembershipReadModelAdapter::class),
            );
        });

        $this->app->bind(AssignMemberToCommittee::class, function ($app) {
            return new AssignMemberToCommittee(
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(EventBus::class)
            );
        });

        // Phase C: Transactional Hardening - Bind the interface to the decorator
        // CRITICAL: Bind ONLY the interface, never the concrete class
        // This makes it STRUCTURALLY IMPOSSIBLE to bypass the decorator
        $this->app->bind(CreateCommitteeUseCase::class, function ($app) {
            $internal = new InternalCreateCommittee(
                $app->make(CommitteeStructureRepositoryInterface::class),
                $app->make(CommitteeAggregateRepositoryInterface::class),
                $app->make(CommitteeCreationPolicy::class),
                $app->make(GovernanceAccessPolicyInterface::class),
                $app->make(CommitteePolicyResolver::class),
                $app->make(CommitteeGeoIdentityFactory::class),
                $app->make(GeoSemanticProjectionBuilder::class),
                $app->make(CommitteeClassificationPolicy::class),
            );

            return new TransactionalCreateCommittee($internal);
        });

        // Phase B: Bind real governance enforcement
        $this->app->bind(GovernanceAccessPolicyInterface::class, function ($app) {
            $engine = new GovernanceCapabilityPolicyEngine(
                new CapabilityCommitteeCreationPolicy(),
                new StructureActivationPolicy(),
                new CommitteeModificationPolicy(),
                new StructureDeprecationPolicy(),
            );

            $factory = new GovernanceCapabilityContextFactory();

            return new GovernanceCapabilityPolicy($factory, $engine);
        });

        $this->app->bind(RemoveMemberFromCommittee::class, function ($app) {
            return new RemoveMemberFromCommittee(
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(EventBus::class)
            );
        });

        $this->app->bind(UpdateCommitteeDetails::class, function ($app) {
            return new UpdateCommitteeDetails(
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(EventBus::class)
            );
        });

        // Member use case bindings
        $this->app->bind(RegisterMember::class, function ($app) {
            return new RegisterMember(
                $app->make(MemberRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(ActivateMember::class, function ($app) {
            return new ActivateMember(
                $app->make(MemberRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(SuspendMember::class, function ($app) {
            return new SuspendMember(
                $app->make(MemberRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(ArchiveMember::class, function ($app) {
            return new ArchiveMember(
                $app->make(MemberRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        // Application use case bindings
        $this->app->bind(SubmitApplication::class, function ($app) {
            return new SubmitApplication(
                $app->make(ApplicationRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(ApproveApplication::class, function ($app) {
            return new ApproveApplication(
                $app->make(ApplicationRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(RejectApplication::class, function ($app) {
            return new RejectApplication(
                $app->make(ApplicationRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(SubmitMembershipApplication::class, function ($app) {
            return new SubmitMembershipApplication(
                $app->make(ApplicationRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(RejectMembershipApplication::class, function ($app) {
            return new RejectMembershipApplication(
                $app->make(ApplicationRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        $this->app->bind(ApproveMembershipApplication::class, function ($app) {
            return new ApproveMembershipApplication(
                $app->make(ApplicationRepositoryInterface::class),
                $app->make(MemberRepositoryInterface::class),
                $app->make(FeeRepositoryInterface::class),
                $app->make(LaravelEventBus::class)
            );
        });

        // Fee use case bindings
        $this->app->bind(RecordFeePayment::class, function ($app) {
            return new RecordFeePayment(
                $app->make(FeeRepositoryInterface::class),
                $app->make(PaymentPolicy::class),
                $app->make(OutboxWriter::class)
            );
        });

        $this->app->bind(WaiveFee::class, function ($app) {
            return new WaiveFee(
                $app->make(FeeRepositoryInterface::class),
                $app->make(OutboxWriter::class)
            );
        });

        // Domain service bindings (Anti-Corruption Layer)
        $this->app->bind(
            IdentityVerificationInterface::class,
            TenantUserIdentityVerification::class
        );

        $this->app->bind(
            TenantUserProvisioningInterface::class,
            TenantAuthProvisioningAdapter::class
        );

        $this->app->bind(
            GeographyResolverInterface::class,
            fn($app) => new GeographyValidationAdapter(
                $app->make(\App\Contexts\Geography\Domain\Services\GeographyDomainService::class),
                $app->make(\App\Contexts\Geography\Application\Services\GeographyService::class)
            )
        );

        // Application service bindings (singleton for performance)
        $this->app->singleton(MobileMemberRegistrationService::class, function ($app) {
            return new MobileMemberRegistrationService(
                $app->make(TenantUserProvisioningInterface::class),
                $app->make(GeographyResolverInterface::class)
            );
        });

        $this->app->singleton(DesktopMemberRegistrationService::class, function ($app) {
            return new DesktopMemberRegistrationService(
                $app->make(GeographyResolverInterface::class)
            );
        });

        $this->app->singleton(DesktopMemberApprovalService::class, function ($app) {
            return new DesktopMemberApprovalService(
                $app->make(MemberRepositoryInterface::class)
            );
        });

        $this->app->singleton(DesktopMemberRejectionService::class, function ($app) {
            return new DesktopMemberRejectionService(
                $app->make(MemberRepositoryInterface::class)
            );
        });

        // Application handler bindings (singleton for performance)
        $this->app->singleton(RegisterMemberHandler::class, function ($app) {
            return new RegisterMemberHandler(
                $app->make(MemberRepositoryInterface::class),
                $app->make(IdentityVerificationInterface::class)
            );
        });
    }

    /**
     * Bootstrap services
     *
     * Loads migrations, routes, and other context resources
     */
    public function boot(): void
    {
        // Load migrations from context (both tests and production)
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/Tenant');

        // Register routes (if needed in future)
        // $this->loadRoutesFrom(__DIR__ . '/../Http/Routes/membership.php');
    }
}
