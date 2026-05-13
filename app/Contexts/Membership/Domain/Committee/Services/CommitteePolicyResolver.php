<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Services;

use App\Contexts\Membership\Application\Committee\DTOs\InternalCreateCommitteeCommand;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureRegistry;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteePolicy;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;

/**
 * CommitteePolicyResolver
 *
 * Single authority for mapping committee creation requests to governance policy.
 * Responsible for resolving:
 * - committee type (central, geographic, wing types)
 * - committee structure (CentralCommitteeStructure, GeographicCommitteeStructure, etc.)
 * - governance level (delegated to CommitteeStructure::getLevelIndexForCategory())
 *
 * This is the ONLY place where committee category → type/structure/level mapping lives.
 * Level topology is delegated to the structure model.
 */
final class CommitteePolicyResolver
{
    /**
     * Resolve governance policy for a committee creation command.
     *
     * @param CommitteeStructure $structure Active governance structure
     * @param InternalCreateCommitteeCommand $command Creation command with category (enum)
     * @return CommitteePolicy Resolved type, structure, and level
     */
    public function resolve(
        CommitteeStructure $structure,
        InternalCreateCommitteeCommand $command
    ): CommitteePolicy {
        $levelIndex = $structure->getLevelIndexForCategory($command->committeeCategory);
        $level = $structure->getLevel($levelIndex);
        $type = $this->inferType($command->committeeCategory);
        $committeeStructure = CommitteeStructureRegistry::forType($type);

        return new CommitteePolicy($type, $committeeStructure, $level);
    }

    private function inferType(CommitteeCategory $category): CommitteeType
    {
        return match ($category) {
            CommitteeCategory::CENTRAL => CommitteeType::central(),
            CommitteeCategory::YOUTH => CommitteeType::youthWing(),
            CommitteeCategory::WOMEN => CommitteeType::womenWing(),
            CommitteeCategory::STUDENT => CommitteeType::studentWing(),
            CommitteeCategory::PROVINCE,
            CommitteeCategory::DISTRICT,
            CommitteeCategory::WARD => CommitteeType::geographic(),
        };
    }
}
