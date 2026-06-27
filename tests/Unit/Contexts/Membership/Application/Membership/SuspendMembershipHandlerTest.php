<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Membership;

use App\Contexts\Membership\Application\Membership\SuspendMembership\SuspendMembershipCommand;
use App\Contexts\Membership\Application\Membership\SuspendMembership\SuspendMembershipHandler;
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

final class SuspendMembershipHandlerTest extends TestCase
{
    private SuspendMembershipHandler $handler;

    private InMemoryMembershipLineageRepository $lineageRepository;

    private FakeEventBus $eventBus;

    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->lineageRepository = new InMemoryMembershipLineageRepository();
        $this->eventBus = new FakeEventBus();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');

        $this->handler = new SuspendMembershipHandler(
            $this->lineageRepository,
            $this->eventBus,
        );
    }

    public function test_suspend_active_membership(): void
    {
        // ARRANGE: Establish active lineage
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

        // ACT: Suspend the membership
        $actorId = 'actor-' . uniqid();
        $command = new SuspendMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: $actorId,
            reason: 'Disciplinary suspension',
        );

        $this->handler->handle($command);

        // ASSERT: Status changed to SUSPENDED
        $loadedLineage = $this->lineageRepository->findByLineageIdForTenant($lineageId, $this->tenantId);
        self::assertNotNull($loadedLineage);
        self::assertEquals(MembershipStatus::SUSPENDED, $loadedLineage->currentStatus());
        self::assertCount(2, $loadedLineage->episodes());
    }

    public function test_suspend_non_active_membership_throws(): void
    {
        $this->expectException(\DomainException::class);

        // ARRANGE: Establish and suspend lineage
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

        $initialEpisode = $lineage->current();
        $lineage->suspend('actor-1', 'First suspension', new \DateTimeImmutable('2026-05-14 11:00:00'));

        $this->lineageRepository->saveForTenant($lineage, $this->tenantId);

        // ACT & ASSERT: Try to suspend already suspended
        $command = new SuspendMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: 'actor-2',
            reason: 'Second suspension',
        );

        $this->handler->handle($command);
    }

    public function test_suspend_non_existent_lineage_throws(): void
    {
        $this->expectException(\DomainException::class);

        $command = new SuspendMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: '99999999-9999-9999-9999-999999999999',
            actorId: 'actor-1',
            reason: 'Suspension reason',
        );

        $this->handler->handle($command);
    }
}
