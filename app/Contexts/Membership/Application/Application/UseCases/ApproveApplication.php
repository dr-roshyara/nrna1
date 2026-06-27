<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\UseCases;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Infrastructure\Events\LaravelEventBus;

final class ApproveApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(ApplicationId $applicationId, TenantId $tenantId): void
    {
        $application = $this->applicationRepository->find($applicationId, $tenantId);

        if (!$application) {
            throw new \Exception("Application not found: {$applicationId->toString()}");
        }

        $application->approve();

        $this->applicationRepository->save($application, $tenantId);

        $events = $application->pullEvents();
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
