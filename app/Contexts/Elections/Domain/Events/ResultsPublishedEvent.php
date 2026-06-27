<?php

namespace App\Contexts\Elections\Domain\Events;

use DateTimeImmutable;

final readonly class ResultsPublishedEvent
{
    public function __construct(
        private string $electionId,
        private string $publishedBy,
        private DateTimeImmutable $publishedAt,
        private string $state,
    ) {}

    public function electionId(): string
    {
        return $this->electionId;
    }

    public function publishedBy(): string
    {
        return $this->publishedBy;
    }

    public function publishedAt(): DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function state(): string
    {
        return $this->state;
    }
}
