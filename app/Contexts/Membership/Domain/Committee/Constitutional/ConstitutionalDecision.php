<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final readonly class ConstitutionalDecision
{
    public function __construct(
        public ?JurisdictionNode             $winner,
        public GovernanceLegitimacy          $legitimacy,
        public ConstitutionalReason          $reason,
        public \DateTimeImmutable            $evaluatedAt,
        public ConstitutionalArbitrationTrace $trace,
    ) {}
}
