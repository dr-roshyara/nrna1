<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\UseCases;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Application\Application;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Application\Application\DTOs\SubmitMembershipApplicationCommand;
use App\Shared\Infrastructure\Events\LaravelEventBus;
use Illuminate\Support\Facades\DB;

final class SubmitMembershipApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(SubmitMembershipApplicationCommand $command): ApplicationId
    {
        return DB::transaction(function () use ($command) {
            $application = Application::submit(
                $command->getTenantId(),
                $command->getUserId(),
                $command->getMembershipTypeId(),
                $command->getApplicationData()
            );

            $this->applicationRepository->save($application, $command->getTenantId());

            $this->eventBus->dispatchAll($application->pullEvents());

            return $application->getId();
        });
    }
}
