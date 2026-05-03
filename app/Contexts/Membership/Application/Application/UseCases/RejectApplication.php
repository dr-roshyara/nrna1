<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\UseCases;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Shared\Infrastructure\Events\LaravelEventBus;

final class RejectApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(ApplicationId $applicationId, TenantId $tenantId, string $reason = ''): void
    {
        $application = $this->applicationRepository->find($applicationId, $tenantId);

        if (!$application) {
            throw new \Exception("Application not found: {$applicationId->toString()}");
        }

        $application->reject($reason);

        $this->applicationRepository->save($application, $tenantId);

        $events = $application->pullEvents();
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
