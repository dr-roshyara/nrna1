<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Membership;

use App\Contexts\Membership\Application\Membership\TerminateMembership\TerminateMembershipCommand;
use App\Contexts\Membership\Application\Membership\TerminateMembership\TerminateMembershipHandler;
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

final class TerminateMembershipHandlerTest extends TestCase
{
    private TerminateMembershipHandler $handler;

    private InMemoryMembershipLineageRepository $lineageRepository;

    private FakeEventBus $eventBus;

    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->lineageRepository = new InMemoryMembershipLineageRepository();
        $this->eventBus = new FakeEventBus();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');

        $this->handler = new TerminateMembershipHandler(
            $this->lineageRepository,
            $this->eventBus,
        );
    }

    public function test_terminate_active_membership(): void
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

        // ACT: Terminate the membership
        $command = new TerminateMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: 'actor-1',
            reason: 'Resignation',
        );

        $this->handler->handle($command);

        // ASSERT: Status changed to TERMINATED
        $loadedLineage = $this->lineageRepository->findByLineageIdForTenant($lineageId, $this->tenantId);
        self::assertNotNull($loadedLineage);
        self::assertEquals(MembershipStatus::TERMINATED, $loadedLineage->currentStatus());
        self::assertCount(2, $loadedLineage->episodes());
    }

    public function test_terminate_suspended_membership(): void
    {
        // ARRANGE: Establish, suspend, then terminate
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

        $lineage->suspend('actor-1', 'Suspension', $now->modify('+1 day'));
        $this->lineageRepository->saveForTenant($lineage, $this->tenantId);

        // ACT: Terminate from SUSPENDED
        $command = new TerminateMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: 'actor-1',
            reason: 'Term expiration',
        );

        $this->handler->handle($command);

        // ASSERT: Status changed to TERMINATED
        $loadedLineage = $this->lineageRepository->findByLineageIdForTenant($lineageId, $this->tenantId);
        self::assertNotNull($loadedLineage);
        self::assertEquals(MembershipStatus::TERMINATED, $loadedLineage->currentStatus());
        self::assertCount(3, $loadedLineage->episodes());
    }

    public function test_terminate_already_terminated_throws(): void
    {
        $this->expectException(\DomainException::class);

        // ARRANGE: Establish, suspend, terminate
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

        $lineage->terminate('actor-1', 'First termination', new \DateTimeImmutable('2026-05-14 11:00:00'));
        $this->lineageRepository->saveForTenant($lineage, $this->tenantId);

        // ACT & ASSERT: Try to terminate again — should throw
        $command = new TerminateMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: $lineageId->value(),
            actorId: 'actor-1',
            reason: 'Second termination',
        );

        $this->handler->handle($command);
    }

    public function test_terminate_non_existent_lineage_throws(): void
    {
        $this->expectException(\DomainException::class);

        $command = new TerminateMembershipCommand(
            tenantId: $this->tenantId->value(),
            lineageId: '99999999-9999-9999-9999-999999999999',
            actorId: 'actor-1',
            reason: 'Termination reason',
        );

        $this->handler->handle($command);
    }
}
