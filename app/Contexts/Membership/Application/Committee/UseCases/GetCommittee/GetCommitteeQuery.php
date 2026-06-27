<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\UseCases\GetCommittee;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;

final readonly class GetCommitteeQuery
{
    public function __construct(
        public CommitteeId $committeeId,
    ) {}
}
