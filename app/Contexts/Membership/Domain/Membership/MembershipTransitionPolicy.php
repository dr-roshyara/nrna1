<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\TransitionReason;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipConstructionException;
use App\Contexts\Shared\Domain\ValueObjects\ActorId;

final class MembershipTransitionPolicy
{
    public static function canTransition(MembershipStatus $from, MembershipStatus $to): bool
    {
        return match (true) {
            $from->equals(MembershipStatus::ACTIVE)    && $to->equals(MembershipStatus::SUSPENDED)  => true,
            $from->equals(MembershipStatus::ACTIVE)    && $to->equals(MembershipStatus::TERMINATED) => true,
            $from->equals(MembershipStatus::SUSPENDED) && $to->equals(MembershipStatus::ACTIVE)     => true,
            $from->equals(MembershipStatus::SUSPENDED) && $to->equals(MembershipStatus::TERMINATED) => true,
            default => false,
        };
    }

    public static function assertAuditRequirementsMet(
        MembershipStatus $status,
        ?ActorId $actorId,
        ?TransitionReason $transitionReason,
        ?\DateTimeImmutable $transitionedAt,
    ): void {
        if ($status->equals(MembershipStatus::SUSPENDED)) {
            if ($actorId === null) {
                throw InvalidMembershipConstructionException::suspendedRequiresActorId();
            }
            if ($transitionReason === null) {
                throw InvalidMembershipConstructionException::suspendedRequiresTransitionReason();
            }
            if ($transitionedAt === null) {
                throw InvalidMembershipConstructionException::suspendedRequiresTimestamp();
            }
        }

        if ($status->equals(MembershipStatus::TERMINATED)) {
            if ($actorId === null) {
                throw InvalidMembershipConstructionException::terminatedRequiresActorId();
            }
            if ($transitionReason === null) {
                throw InvalidMembershipConstructionException::terminatedRequiresTransitionReason();
            }
            if ($transitionedAt === null) {
                throw InvalidMembershipConstructionException::terminatedRequiresTimestamp();
            }
        }
    }
}
