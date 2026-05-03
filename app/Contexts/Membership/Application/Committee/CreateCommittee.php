<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\CreateCommitteeCommand;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Shared\Domain\Events\EventBus;
use DomainException;
use Illuminate\Support\Facades\DB;

final class CreateCommittee
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus
    ) {}

    public function execute(CreateCommitteeCommand $command): CommitteeId
    {
        $committeeId = CommitteeId::generate();

        $committee = $this->buildCommittee($committeeId, $command);

        DB::transaction(function () use ($committee) {
            $this->committees->saveForTenant($committee);
            $this->eventBus->dispatchAll($committee->pullEvents());
        });

        return $committeeId;
    }

    private function buildCommittee(CommitteeId $id, CreateCommitteeCommand $command): Committee
    {
        if ($command->type->isCentral()) {
            return Committee::createCentral(
                $id,
                $command->tenantId,
                $command->name,
                $command->code
            );
        }

        if ($command->type->isGeographic()) {
            if ($command->geoReference === null) {
                throw new DomainException(
                    "Geographic committee type '{$command->type->value()}' requires a geo reference"
                );
            }

            return Committee::createForGeography(
                $id,
                $command->tenantId,
                $command->name,
                $command->code,
                $command->type,
                $command->geoReference,
                ''
            );
        }

        throw new DomainException(
            "Committee type '{$command->type->value()}' is not supported by CreateCommittee — use the full form() factory for wing types"
        );
    }
}
