<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Events;

use App\Shared\Domain\Events\AbstractDomainEvent;

final class CommitteeEstablished extends AbstractDomainEvent
{
    public function __construct(
        public readonly string $committeeId,
        public readonly string $name,
        public readonly string $jurisdiction,
    ) {
        parent::__construct();
    }
}
