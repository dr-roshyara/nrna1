<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\RemoveMemberDto;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Shared\Domain\Events\EventBus;
use Illuminate\Support\Facades\DB;

final class RemoveMemberFromCommittee
{
    public function __construct(
        private CommitteeRepositoryInterface $repository,
        private EventBus $eventBus,
    ) {}

    public function execute(RemoveMemberDto $command): void
    {
        $events = DB::transaction(function () use ($command) {
            $committee = $this->repository->findForTenant(
                $command->committeeId,
                $command->tenantId
            );

            if ($committee === null) {
                throw new \DomainException('Committee not found');
            }

            $assignment = $committee->getAssignment($command->assignmentId);
            if ($assignment === null) {
                throw new \DomainException('Assignment not found');
            }

            $committee->endAssignment(
                $command->assignmentId,
                new \DateTimeImmutable(),
                $command->notes
            );

            $this->repository->saveForTenant($committee);

            return $committee->pullEvents();
        });

        $this->eventBus->dispatchAll($events);
    }
}
