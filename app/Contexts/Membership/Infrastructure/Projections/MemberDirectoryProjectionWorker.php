<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Projections;

use App\Contexts\Membership\Application\Projections\MemberDirectoryProjector;
use App\Contexts\Membership\Domain\Events\MemberRegistered;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Repositories\MemberDirectoryRepositoryInterface;
use App\Contexts\Shared\Infrastructure\Outbox\OutboxRepository;

final class MemberDirectoryProjectionWorker
{
    private MemberDirectoryProjector $projector;

    public function __construct(
        private MemberDirectoryRepositoryInterface $repository,
        private OutboxRepository $outbox,
    ) {
        $this->projector = new MemberDirectoryProjector();
    }

    public function handle(): void
    {
        $records = $this->outbox->getUnprocessed(MemberRegistered::class);

        foreach ($records as $record) {
            $payload = json_decode($record['payload'], true);

            $event = new MemberRegistered(
                memberId: MemberId::fromString($payload['memberId']),
                tenantId: TenantId::fromString($payload['tenantId']),
                displayName: $payload['displayName'],
                email: $payload['email'],
                membershipTypeId: MembershipTypeId::fromString($payload['membershipTypeId']),
                membershipTypeName: $payload['membershipTypeName'],
                organisationUserId: $payload['organisationUserId'],
            );

            $projection = $this->projector->fromMemberRegistered($event);
            $this->repository->upsert($projection);
            $this->outbox->markProcessed($record['id']);
        }
    }
}
