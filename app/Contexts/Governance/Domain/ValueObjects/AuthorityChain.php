<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;

final readonly class AuthorityChain
{
    private int $authorityLevel;

    private function __construct(
        private MemberId $actorId,
        private GovernanceRole $role,
        private AuthorityPath $delegationPath,
        private DateTimeImmutable $capturedAt,
    ) {
        $this->authorityLevel = $delegationPath->length();
    }

    public static function capture(
        MemberId $actorId,
        GovernanceRole $role,
        AuthorityPath $delegationPath,
        DateTimeImmutable $capturedAt,
    ): self {
        return new self($actorId, $role, $delegationPath, $capturedAt);
    }

    public function actorId(): MemberId
    {
        return $this->actorId;
    }

    public function role(): GovernanceRole
    {
        return $this->role;
    }

    public function delegationPath(): AuthorityPath
    {
        return $this->delegationPath;
    }

    public function capturedAt(): DateTimeImmutable
    {
        return $this->capturedAt;
    }

    public function authorityLevel(): int
    {
        return $this->authorityLevel;
    }

    public function equals(self $other): bool
    {
        return $this->actorId->equals($other->actorId)
            && $this->role === $other->role
            && $this->delegationPath->equals($other->delegationPath)
            && $this->capturedAt == $other->capturedAt;
    }
}
