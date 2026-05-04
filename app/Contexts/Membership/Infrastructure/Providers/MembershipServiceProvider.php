<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Providers;

use App\Contexts\Membership\Application\Handlers\RegisterMemberHandler;
use App\Contexts\Membership\Application\Services\MobileMemberRegistrationService;
use App\Contexts\Membership\Application\Services\DesktopMemberRegistrationService;
use App\Contexts\Membership\Application\Services\DesktopMemberApprovalService;
use App\Contexts\Membership\Application\Services\DesktopMemberRejectionService;
use App\Contexts\Membership\Application\Committee\AssignMemberToCommittee;
use App\Contexts\Membership\Application\Committee\CreateCommittee;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Application\Committee\RemoveMemberFromCommittee;
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
use App\Contexts\Membership\Domain\Services\IdentityVerificationInterface;
use App\Contexts\Membership\Domain\Services\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\Services\GeographyResolverInterface;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentMemberRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentApplicationRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentFeeRepository;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeRepository;
use App\Contexts\Membership\Infrastructure\Services\TenantUserIdentityVerification;
use App\Contexts\Membership\Infrastructure\Services\TenantAuthProvisioningAdapter;
use App\Contexts\Membership\Infrastructure\Services\GeographyValidationAdapter;
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

        $this->app->bind(
            CommitteeRepositoryInterface::class,
            EloquentCommitteeRepository::class
        );

        // Use case bindings
        $this->app->bind(GetCommitteeDashboard::class, function ($app) {
            return new GetCommitteeDashboard(
                $app->make(CommitteeRepositoryInterface::class)
            );
        });

        $this->app->bind(AssignMemberToCommittee::class, function ($app) {
            return new AssignMemberToCommittee(
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(EventBus::class)
            );
        });

        $this->app->bind(CreateCommittee::class, function ($app) {
            return new CreateCommittee(
                $app->make(CommitteeRepositoryInterface::class),
                $app->make(EventBus::class)
            );
        });

        $this->app->bind(RemoveMemberFromCommittee::class, function ($app) {
            return new RemoveMemberFromCommittee(
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
            GeographyValidationAdapter::class
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
