<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\CreateCommittee;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\Jurisdiction;
use DateTimeImmutable;

final readonly class CreateCommitteeCommand
{
    public function __construct(
        public CommitteeId $committeeId,
        public string $name,
        public Jurisdiction $jurisdiction,
        public DateTimeImmutable $establishedAt,
    ) {}
}
