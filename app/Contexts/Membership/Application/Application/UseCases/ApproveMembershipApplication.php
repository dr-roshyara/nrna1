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
        \Log::info('ApproveMembershipApplication.execute - START');

        // 1. Find and approve the application
        $application = $this->applicationRepository->find(
            $command->applicationId,
            $command->tenantId
        );

        \Log::info('ApproveMembershipApplication.execute - found application', [
            'found' => $application !== null,
        ]);

        if (!$application) {
            throw new \RuntimeException(
                "Application not found: {$command->applicationId->toString()}"
            );
        }

        \Log::info('ApproveMembershipApplication.execute - before approve()', [
            'status_before' => $application->getStatus()->value(),
        ]);

        $application->approve();

        \Log::info('ApproveMembershipApplication.execute - after approve()', [
            'status_after' => $application->getStatus()->value(),
        ]);

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
            $command->tenantId,
            (string) $command->membershipFeeAmount,
            $command->feeDueDate
        );

        // 4. Save all aggregates (controller handles transaction boundary)
        \Log::info('ApproveMembershipApplication.execute - before saving aggregates');

        $this->applicationRepository->save($application, $command->tenantId);

        \Log::info('ApproveMembershipApplication.execute - application saved');

        $this->memberRepository->save($member, $command->tenantId);

        \Log::info('ApproveMembershipApplication.execute - member saved');

        $this->feeRepository->save($fee, $command->tenantId);

        \Log::info('ApproveMembershipApplication.execute - fee saved');

        // 5. Dispatch all domain events
        $events = array_merge(
            $application->pullEvents(),
            $member->pullEvents(),
            $fee->pullEvents()
        );

        $this->eventBus->dispatchAll($events);

        Log::info('Membership application approved and member created', [
            'application_id' => $command->applicationId->toString(),
            'member_id' => $member->getId()->toString(),
            'fee_id' => $fee->getId()->toString(),
            'tenant_id' => $command->tenantId->value(),
            'user_id' => $command->userId,
        ]);
    }
}
