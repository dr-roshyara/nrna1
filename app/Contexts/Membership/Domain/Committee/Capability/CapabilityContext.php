<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Capability;

use App\Contexts\Membership\Domain\Committee\Context\ActorContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeLineageView;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeStructureEpochContext;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Membership\Domain\Committee\Context\OrganisationGovernanceContext;

final readonly class CapabilityContext
{
    public function __construct(
        public ActorContext $actor,
        public OrganisationGovernanceContext $organisation,
        public CommitteeStructureEpochContext $epoch,
        public CommitteeLineageView $lineage,
        public ?GeographicScope $targetScope = null,
    ) {}
}
