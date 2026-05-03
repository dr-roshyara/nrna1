<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Member\UseCases;

use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Application\Member\DTOs\RegisterMemberCommand;
use App\Contexts\Membership\Application\Member\DTOs\MemberView;
use App\Shared\Infrastructure\Events\LaravelEventBus;

final class RegisterMember
{
    public function __construct(
        private MemberRepositoryInterface $memberRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(RegisterMemberCommand $command): MemberView
    {
        $personalInfo = PersonalInfo::create(
            $command->getFullName(),
            $command->getEmail(),
            $command->getPhone()
        );

        $member = Member::register(
            $command->getTenantId(),
            $personalInfo,
            $command->getMembershipTypeId()
        );

        $this->memberRepository->save($member, $command->getTenantId());

        $events = $member->pullEvents();
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }

        return new MemberView(
            $member->getId()->toString(),
            $personalInfo->getFullName(),
            $personalInfo->getEmail(),
            $personalInfo->getPhone(),
            $member->getStatus()->value(),
            $member->getMembershipTypeId()->toString(),
            $command->getTenantId()->toString()
        );
    }
}
