<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Application\Committee\Ports\CommitteeRepositoryPort;
use App\Contexts\Membership\Application\Committee\Ports\EventBusPort;
use App\Contexts\Membership\Domain\Committee\ConstitutionalCommittee;
use App\Contexts\Membership\Domain\Committee\Policies\GovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

final readonly class CreateCommitteeHandler
{
    public function __construct(
        private CommitteeRepositoryPort $repository,
        private EventBusPort $eventBus,
        private GovernancePolicy $policy,
    ) {}

    public function handle(CreateCommitteeCommand $command): string
    {
        $committee = ConstitutionalCommittee::establish(
            id: CommitteeId::generate(),
            name: $command->name,
            assignment: $command->assignment,
            at: new DateTimeImmutable(),
            policy: $this->policy,
        );

        $this->repository->save($committee);

        foreach ($committee->releaseEvents() as $event) {
            $this->eventBus->publish($event);
        }

        return $committee->id()->value();
    }
}
