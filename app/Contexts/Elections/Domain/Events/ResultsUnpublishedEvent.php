<?php

namespace App\Contexts\Elections\Domain\Events;

use DateTimeImmutable;

final readonly class ResultsUnpublishedEvent
{
    public function __construct(
        private string $electionId,
        private string $unpublishedBy,
        private DateTimeImmutable $unpublishedAt,
        private string $previousState,
    ) {}

    public function electionId(): string
    {
        return $this->electionId;
    }

    public function unpublishedBy(): string
    {
        return $this->unpublishedBy;
    }

    public function unpublishedAt(): DateTimeImmutable
    {
        return $this->unpublishedAt;
    }

    public function previousState(): string
    {
        return $this->previousState;
    }
}
