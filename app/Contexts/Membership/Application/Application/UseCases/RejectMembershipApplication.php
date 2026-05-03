<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\UseCases;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Shared\Infrastructure\Events\LaravelEventBus;
use Illuminate\Support\Facades\DB;

final class RejectMembershipApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(
        ApplicationId $applicationId,
        TenantId $tenantId,
        string $rejectionReason
    ): void {
        DB::transaction(function () use ($applicationId, $tenantId, $rejectionReason) {
            $application = $this->applicationRepository->find($applicationId, $tenantId);

            if (!$application) {
                throw new \Exception("Application not found: {$applicationId->value()}");
            }

            $application->reject($rejectionReason);

            $this->applicationRepository->save($application, $tenantId);

            $this->eventBus->dispatchAll($application->pullEvents());
        });
    }
}
