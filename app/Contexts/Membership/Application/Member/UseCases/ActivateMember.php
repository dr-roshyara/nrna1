<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Member\UseCases;

use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Infrastructure\Events\LaravelEventBus;

final class ActivateMember
{
    public function __construct(
        private MemberRepositoryInterface $memberRepository,
        private LaravelEventBus $eventBus
    ) {}

    public function execute(MemberId $memberId, TenantId $tenantId): void
    {
        $member = $this->memberRepository->find($memberId, $tenantId);

        if (!$member) {
            throw new \Exception("Member not found: {$memberId->toString()}");
        }

        $member->activate();

        $this->memberRepository->save($member, $tenantId);

        $events = $member->pullEvents();
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}
