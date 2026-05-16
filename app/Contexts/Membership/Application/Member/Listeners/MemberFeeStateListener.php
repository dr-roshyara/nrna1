<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Member\Listeners;

use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Member\ValueObjects\FeeState;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;

final readonly class MemberFeeStateListener
{
    public function __construct(
        private MemberRepositoryInterface $members
    ) {}

    public function handle(FeePaid $event): void
    {
        // Load Member using event context directly (no Fee aggregate reload needed)
        $member = $this->members->find($event->getMemberId(), $event->getTenantId());
        if (!$member) {
            return;
        }

        // Update Member's feeState snapshot
        $member->updateFeeStateSnapshot(FeeState::PAID);

        // Persist updated Member
        $this->members->save($member, $event->getTenantId());
    }
}
