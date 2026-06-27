<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership\Exceptions;

final class DuplicateActiveAssociationException extends \DomainException
{
    public static function forMemberAndCommittee(string $memberId, string $committeeId): self
    {
        return new self(
            "Member {$memberId} already has an active association for committee {$committeeId}"
        );
    }
}
