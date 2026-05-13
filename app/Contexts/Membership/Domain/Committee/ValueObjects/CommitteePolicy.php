<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\Strategies\CommitteeStructure;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;

/**
 * CommitteePolicy Value Object
 *
 * Encapsulates resolved governance classification decisions for committee creation.
 * Produced by CommitteePolicyResolver and consumed by Committee::create().
 *
 * Bundles three related concepts into one authority:
 * - CommitteeType: semantic type (central, geographic, youth, etc.)
 * - CommitteeStructure: strategy for business rules (age, gender, role limits)
 * - CommitteeLevel: governance level definition (index, geo policy, scope)
 */
final readonly class CommitteePolicy
{
    public function __construct(
        public CommitteeType $type,
        public CommitteeStructure $structure,
        public CommitteeLevel $level,
    ) {}
}
