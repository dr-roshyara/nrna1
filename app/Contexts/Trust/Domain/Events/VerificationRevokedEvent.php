<?php

declare(strict_types=1);

namespace App\Contexts\Trust\Domain\Events;

use DateTimeImmutable;

final readonly class VerificationRevokedEvent
{
    public function __construct(
        private string $verificationId,
        private string $userId,
        private string $revokedBy,
        private DateTimeImmutable $revokedAt,
    ) {}

    public function verificationId(): string
    {
        return $this->verificationId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function revokedBy(): string
    {
        return $this->revokedBy;
    }

    public function revokedAt(): DateTimeImmutable
    {
        return $this->revokedAt;
    }
}
