<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;
use App\Contexts\Membership\Domain\Committee\Actor\ActorPosition;
use App\Contexts\Membership\Domain\Committee\Context\ActorContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeLineageView;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeStructureEpochContext;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Membership\Domain\Committee\Context\OrganisationGovernanceContext;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;

final class GovernanceCapabilityContextFactory
{
    public function build(string $userId, TenantId $tenantId, ?GeographicScope $targetScope = null): CapabilityContext
    {
        $organisation = Organisation::find($tenantId->value());
        if ($organisation === null) {
            throw new \DomainException("Organisation not found for tenant {$tenantId->value()}");
        }

        $orgCtx = new OrganisationGovernanceContext(
            organisationId: $organisation->id,
            governanceStatus: $organisation->governance_status ?? 'pending_setup'
        );

        $epochCtx = CommitteeStructureEpochContext::fromOrganisationStatus(
            $organisation->governance_status ?? 'pending_setup'
        );

        if ($userId === 'system') {
            $actorCtx = ActorContext::system($tenantId);
        } else {
            $userRole = UserOrganisationRole::where('user_id', $userId)
                ->where('organisation_id', $tenantId->value())
                ->first();

            $position = ActorPosition::fromRole($userRole?->role ?? 'member');

            $actorCtx = new ActorContext(
                userId: $userId,
                tenantId: $tenantId,
                position: $position,
                geographicScope: new GeographicScope('national', null),
                isSystemActor: false,
            );
        }

        $lineageCtx = CommitteeLineageView::root();

        return new CapabilityContext(
            actor: $actorCtx,
            organisation: $orgCtx,
            epoch: $epochCtx,
            lineage: $lineageCtx,
            targetScope: $targetScope,
        );
    }
}
