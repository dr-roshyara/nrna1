<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Application\UseCases;

use App\Contexts\Membership\Domain\Repositories\ApplicationRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Application\Application\DTOs\ApproveMembershipApplicationCommand;
use App\Shared\Infrastructure\Events\LaravelEventBus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class ApproveMembershipApplication
{
    public function __construct(
        private ApplicationRepositoryInterface $applicationRepository,
        private MemberRepositoryInterface $memberRepository,
        private FeeRepositoryInterface $feeRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(ApproveMembershipApplicationCommand $command): void
    {
        // 1. Find and approve the application
        $application = $this->applicationRepository->find(
            $command->applicationId,
            $command->tenantId
        );

        if (!$application) {
            throw new \RuntimeException(
                "Application not found: {$command->applicationId->toString()}"
            );
        }

        $application->approve();

        // 2. Create Member aggregate from approved application
        $personalInfo = PersonalInfo::create(
            $command->name,
            $command->email,
            $command->phone ?? ''
        );

        $member = Member::register(
            $command->tenantId,
            $personalInfo,
            $command->membershipTypeId
        );

        // 3. Create Fee aggregate for the new member
        $fee = Fee::create(
            $member->getId(),
            $command->membershipTypeId,
            $command->tenantId,
            (string) $command->membershipFeeAmount,
            $command->feeDueDate
        );

        \Log::info('After Fee creation', [
            'fee_id' => $fee->getId()->value(),
            'fee_member_id' => $fee->getMemberId()->value(),
        ]);

        // 4. Save all aggregates (controller handles transaction boundary)
        $this->applicationRepository->save($application, $command->tenantId);
        $this->memberRepository->save($member, $command->tenantId, $command->organisationUserId);
        $this->feeRepository->save($fee, $command->tenantId);

        // 5. Dispatch all domain events
        $events = array_merge(
            $application->pullEvents(),
            $member->pullEvents(),
            $fee->pullEvents()
        );

        $this->eventBus->dispatchAll($events);
    }
}
