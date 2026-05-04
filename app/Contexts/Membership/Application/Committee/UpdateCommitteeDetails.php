<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\UpdateCommitteeDetailsCommand;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Shared\Domain\Events\EventBus;
use Illuminate\Support\Facades\DB;

final class UpdateCommitteeDetails
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $repository,
        private readonly EventBus $eventBus,
    ) {}

    public function execute(UpdateCommitteeDetailsCommand $command): void
    {
        $events = DB::transaction(function () use ($command) {
            $committee = $this->repository->findForTenant($command->committeeId, $command->tenantId);
            if (!$committee) {
                throw new \RuntimeException('Committee not found');
            }

            if ($command->name !== null) {
                $committee->updateName(CommitteeName::fromString($command->name));
            }

            if ($command->status !== null) {
                $committee->updateStatus(CommitteeStatus::fromString($command->status));
            }

            $this->repository->saveForTenant($committee, $command->tenantId);

            return $committee->pullEvents();
        });

        $this->eventBus->dispatchAll($events);
    }
}
