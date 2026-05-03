<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\UseCases;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Application\Application;
use App\Contexts\Membership\Application\Application\DTOs\SubmitApplicationCommand;
use App\Shared\Infrastructure\Events\LaravelEventBus;

final class SubmitApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(SubmitApplicationCommand $command): string
    {
        $application = Application::submit(
            $command->getTenantId(),
            $command->getMemberId(),
            $command->getMembershipTypeId()
        );

        $this->applicationRepository->save($application, $command->getTenantId());

        $events = $application->pullEvents();
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }

        return $application->getId()->toString();
    }
}
