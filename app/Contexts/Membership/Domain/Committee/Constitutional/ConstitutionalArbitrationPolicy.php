<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;

interface ConstitutionalArbitrationPolicy
{
    public function arbitrate(
        AuthorityClassification $classification,
        \DateTimeImmutable $at,
    ): ConstitutionalDecision;
}
