<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Handlers;

use App\Contexts\Membership\Application\Committee\Commands\AssignMemberWithGeoCommand;
use App\Contexts\Membership\Domain\Committee\Factories\GeoPathChainFactory;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\TenantId as MembershipTenantId;
use App\Shared\Domain\Events\EventBus;
use DomainException;

/**
 * AssignMemberToCommitteeHandler — Application service for geo-validated member assignment.
 *
 * Orchestrates the full assignment flow:
 * 1. Load member (to get residence geography)
 * 2. Load committee (to get operational geography)
 * 3. Build projections for both
 * 4. Derive GeoPathChains
 * 5. Evaluate eligibility via CommitteeEligibilityPolicy
 * 6. Delegate to committee aggregate for assignment
 * 7. Persist and dispatch events
 *
 * Canonical Geo Architecture: This is the ONLY entry point for member assignment
 * that includes geographic eligibility validation.
 */
final readonly class AssignMemberToCommitteeHandler
{
    public function __construct(
        private readonly GeoSemanticProjectionBuilder $projectionBuilder,
        private readonly CommitteeEligibilityPolicy $policy,
        private readonly MemberRepositoryInterface $members,
        private readonly CommitteeRepositoryInterface $committees,
        private readonly EventBus $eventBus,
    ) {}

    /**
     * Handle the command with full geo eligibility validation.
     *
     * @throws DomainException If member not found, committee not found, or geo ineligible
     */
    public function handle(AssignMemberWithGeoCommand $command): void
    {
        // 1. Load aggregates
        $memberTenantId = new MembershipTenantId($command->tenantId->value());
        $member = $this->members->find($command->memberId, $memberTenantId);
        if ($member === null) {
            throw new DomainException('Member not found');
        }

        $committee = $this->committees->findForTenant($command->committeeId, $command->tenantId);
        if ($committee === null) {
            throw new DomainException('Committee not found');
        }

        // 2. Geo eligibility check via projection → chain → policy.
        //    Returns true if geo validation was performed (member has residence, committee has geo).
        $geoValidated = $this->validateGeoEligibility($committee, $member);

        // 3. Delegate to aggregate for assignment.
        //    When the policy already validated eligibility, pass the committee's own operational
        //    GeoReference to satisfy the aggregate's coversGeography() invariant (equal → true).
        //    For central committees (no geo), pass null.
        $memberGeography = $geoValidated ? $committee->getOperationalGeo() : null;

        $committee->assignMember(
            memberId: new \App\Contexts\Membership\Domain\ValueObjects\MemberId($command->memberId->value()),
            rolePath: $command->rolePath,
            nominationType: $command->nominationType,
            memberGeography: $memberGeography,
            electionDate: $command->electionDate,
            termEndDate: $command->termEndDate,
            appointedByUserId: $command->appointedByUserId,
            notes: $command->notes,
            metadata: $command->metadata,
        );

        // 4. Persist and dispatch events
        $this->committees->saveForTenant($committee);
        $this->eventBus->dispatchAll($committee->pullEvents());
    }

    /**
     * Validate geographic eligibility via the projection/chain/policy pipeline.
     *
     * Builds projections for both member residence and committee operational geo,
     * derives semantic chains, and evaluates the policy.
     *
     * @return bool True if geo validation was performed (committee has geoUnitId), false for central committees
     * @throws DomainException If member is geographically ineligible for this committee
     */
    private function validateGeoEligibility($committee, $member): bool
    {
        $committeeGeoUnitId = $committee->getGeoUnitId();
        $memberResidenceGeoUnitId = $member->getResidenceGeoIdentity()?->residenceGeoUnitId;

        // Central committees (no geoUnitId) cover all members — skip check
        if ($committeeGeoUnitId === null) {
            return false;
        }

        // Geographic committees require member residence
        if ($memberResidenceGeoUnitId === null) {
            throw new DomainException(
                'Member without residence geography cannot be assigned to a geographic committee'
            );
        }

        // Build projections
        $memberProjection = $this->projectionBuilder->build($memberResidenceGeoUnitId);
        $committeeProjection = $this->projectionBuilder->build($committeeGeoUnitId);

        if ($memberProjection === null || $committeeProjection === null) {
            throw new DomainException('Cannot resolve geography for eligibility check');
        }

        // Derive chains
        $memberChain = GeoPathChainFactory::from($memberProjection);
        $committeeChain = GeoPathChainFactory::from($committeeProjection);

        // Evaluate policy
        if (!$this->policy->isEligible($committeeChain, $memberChain)) {
            throw new DomainException(
                'Member residence geography is not within the committee\'s operational geography'
            );
        }

        return true;
    }
}
