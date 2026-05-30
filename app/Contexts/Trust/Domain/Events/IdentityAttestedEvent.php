<?php

declare(strict_types=1);

namespace App\Contexts\Trust\Domain\Events;

use DateTimeImmutable;

final readonly class IdentityAttestedEvent
{
    public function __construct(
        private string $verificationId,
        private string $userId,
        private string $attestedBy,
        private DateTimeImmutable $attestedAt,
        private ?string $notes = null,
    ) {}

    public function verificationId(): string
    {
        return $this->verificationId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function attestedBy(): string
    {
        return $this->attestedBy;
    }

    public function attestedAt(): DateTimeImmutable
    {
        return $this->attestedAt;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }
}
