<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Internal;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;

final readonly class CommitteeIdentity
{
    public function __construct(
        private CommitteeId $id,
        private string $name,
        private int $levelIndex,
    ) {}

    public function id(): CommitteeId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function levelIndex(): int
    {
        return $this->levelIndex;
    }
}
