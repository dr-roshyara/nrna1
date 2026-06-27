<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;
use DateTimeImmutable;

final class CommitteeEstablished extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly int $governanceLevel,
        public readonly int $geoLevel,
        public readonly string $geoUnitId,
        public readonly DateTimeImmutable $establishedAt,
    ) {
        parent::__construct();
    }
}
