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
use App\Shared\Domain\Events\EventBus;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Services\IdentityVerificationInterface;
use App\Contexts\Membership\Domain\Services\TenantUserProvisioningInterface;
use App\Contexts\Membership\Domain\Services\GeographyResolverInterface;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentMemberRepository;
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
