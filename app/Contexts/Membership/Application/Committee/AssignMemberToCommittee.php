<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\AssignMemberDto;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Shared\Domain\Events\EventBus;
use Illuminate\Support\Facades\DB;

final class AssignMemberToCommittee
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}

    public function execute(AssignMemberDto $dto): void
    {
        DB::transaction(function () use ($dto) {
            $committee = $this->committees->findForTenant($dto->committeeId, $dto->tenantId);

            if ($committee === null) {
                throw new CommitteeNotFoundException($dto->committeeId);
            }

            $committee->assignMember(
                memberId: $dto->memberId,
                rolePath: $dto->rolePath,
                nominationType: $dto->nominationType,
                memberGeography: $dto->memberGeography,
                electionDate: $dto->electionDate,
                termEndDate: $dto->termEndDate,
                appointedByUserId: $dto->appointedByUserId,
                notes: $dto->notes,
                metadata: $dto->metadata
            );

            $this->committees->saveForTenant($committee);
            $this->eventBus->dispatchAll($committee->pullEvents());
        });
    }
}
