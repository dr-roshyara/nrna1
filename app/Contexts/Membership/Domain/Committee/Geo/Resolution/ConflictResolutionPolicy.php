<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Resolution;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;

interface ConflictResolutionPolicy
{
    public function resolve(AuthorityClassification $classification): FinalAuthorityDecision;
}
