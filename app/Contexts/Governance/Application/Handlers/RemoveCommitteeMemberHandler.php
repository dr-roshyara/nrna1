<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Handlers;

use App\Contexts\Governance\Application\Commands\RemoveCommitteeMemberCommand;
use App\Contexts\Governance\Domain\Committee\CommitteeRepositoryInterface;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

// Pure PHP Handler — No HTTP, no Laravel framework coupling
final class RemoveCommitteeMemberHandler
{
    public function __construct(
        private CommitteeRepositoryInterface $repository
    ) {}

    public function handle(RemoveCommitteeMemberCommand $command): void
    {
        // Map command inputs to domain value objects
        $committeeId = CommitteeId::fromString($command->committeeId);
        $memberId = MemberId::fromString($command->memberId);
        $tenantId = TenantId::fromString($command->tenantId);

        // Load the committee aggregate root
        $committee = $this->repository->findById($committeeId, $tenantId);

        if (!$committee) {
            throw new \DomainException("Committee not found", 404);
        }

        // Execute domain logic (aggregate handles invariants, events, etc.)
        $committee->removeMember($memberId);

        // Persist the aggregate root back to the repository
        $this->repository->save($committee, $tenantId);
    }
}
