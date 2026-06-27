<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Membership;

use App\Contexts\Membership\Application\Membership\RestoreMembership\RestoreMembershipCommand;
use App\Contexts\Membership\Application\Membership\RestoreMembership\RestoreMembershipHandler;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;
use Tests\Doubles\FakeEventBus;
use Tests\Doubles\InMemoryMembershipLineageRepository;

final class RestoreMembershipHandlerTest extends TestCase
{
    private RestoreMembershipHandler $handler;

    private InMemoryMembershipLineageRepository $lineageRepository;

    private FakeEventBus $eventBus;

    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->lineageRepository = new InMemoryMembershipLineageRepository();
        $this->eventBus = new FakeEventBus();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');

        $this->handler = new RestoreMembershipHandler(
            $this->lineageRepository,
            $this->eventBus,
        );
    }

    public function test_restore_suspended_membership(): void
    {
        // ARRANGE: Establish and suspend lineage
        $lineageId = LineageId::generate();
        $memberId = MemberId::generate();
        $committeeId = CommitteeId::fromString('committee-1');
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: $this->tenantId,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->suspend('actor-1', 'Suspension reason', $now->modify('+1 day'));
        $this->lineageRepository->saveForTenant($lineage, $this->tenantId);

        // ACT: Restore the membership
        $command = new RestoreMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: 'actor-1',
        );

        $this->handler->handle($command);

        // ASSERT: Status changed back to ACTIVE
        $loadedLineage = $this->lineageRepository->findByLineageIdForTenant($lineageId, $this->tenantId);
        self::assertNotNull($loadedLineage);
        self::assertEquals(MembershipStatus::ACTIVE, $loadedLineage->currentStatus());
        self::assertCount(3, $loadedLineage->episodes());
    }

    public function test_restore_active_membership_throws(): void
    {
        $this->expectException(\DomainException::class);

        // ARRANGE: Establish active lineage (don't suspend)
        $lineageId = LineageId::generate();
        $memberId = MemberId::generate();
        $committeeId = CommitteeId::fromString('committee-1');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: $this->tenantId,
            reason: ApplicationReason::RESIDENCE,
            at: new \DateTimeImmutable('2026-05-14 10:00:00'),
        );

        $this->lineageRepository->saveForTenant($lineage, $this->tenantId);

        // ACT & ASSERT: Try to restore already active
        $command = new RestoreMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: 'actor-1',
        );

        $this->handler->handle($command);
    }

    public function test_restore_non_existent_lineage_throws(): void
    {
        $this->expectException(\DomainException::class);

        $command = new RestoreMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: '99999999-9999-9999-9999-999999999999',
            actorId: 'actor-1',
        );

        $this->handler->handle($command);
    }
}
