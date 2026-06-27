<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\StoreCommittee;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\Policies\GovernanceAssignment;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Infrastructure\Persistence\CommitteeRepositoryPort;
use DateTimeImmutable;

final readonly class StoreCommitteeUseCase
{
    public function __construct(
        private CommitteeRepositoryPort $repository,
        private GovernancePolicy $policy,
    ) {}

    public function execute(StoreCommitteeCommand $command): CommitteeId
    {
        $assignment = new GovernanceAssignment(
            governanceLevel: $command->governanceLevel,
            geoLevel: $command->governanceLevel,
            geoUnitId: GeoUnitId::fromInt($command->geoUnitId),
        );

        $committee = ConstitutionalCommittee::establish(
            id: CommitteeId::generate(),
            name: $command->name,
            assignment: $assignment,
            at: new DateTimeImmutable(),
            policy: $this->policy,
        );

        $this->repository->save($committee);

        return $committee->getId();
    }
}
