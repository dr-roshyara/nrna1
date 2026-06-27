<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\ValueObjects;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

final readonly class MemberSnapshot
{
    public function __construct(
        public MemberId $id,
        public ?GeoPathChain $residenceGeo,
        public string $status,
    ) {}
}
