<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Handlers;

use App\Contexts\Membership\Application\Committee\Commands\AssignMemberWithGeoCommand;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\Exceptions\CommitteeEligibilityException;
use App\Contexts\Membership\Domain\Committee\Factories\GeoPathChainFactory;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeEligibilityPolicy;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Domain\Events\EventBus;
use DomainException;
use Illuminate\Support\Facades\DB;

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
        private readonly MembershipLineageRepositoryPort $lineageRepository,
    ) {}

    /**
     * Handle the command with full geo eligibility validation.
     *
     * Wraps both CommitteeAssignment (operational data) and MembershipLineage (constitutional truth)
     * in a single transaction. Events are dispatched after transaction commits.
     *
     * @throws DomainException If member not found, committee not found, geo ineligible, or duplicate assignment
     */
    public function handle(AssignMemberWithGeoCommand $command): void
    {
        // 1. Load aggregates
        $member = $this->members->find($command->memberId, $command->tenantId);
        if ($member === null) {
            throw new DomainException('Member not found');
        }

        $committee = $this->committees->findForTenant($command->committeeId, $command->tenantId);
        if ($committee === null) {
            throw new DomainException('Committee not found');
        }

        // 2. Check for duplicate assignment (prevent reassigning active lineage)
        $existingLineage = $this->lineageRepository->findLineageByMemberAndCommitteeForTenant(
            $command->memberId,
            $command->committeeId,
            $command->tenantId,
        );

        if ($existingLineage !== null && $existingLineage->isActive()) {
            throw new DomainException('Member already has an active membership for this committee');
        }

        // 3. Geo eligibility check via projection → chain → policy.
        //    Returns true for eligible assignments (including central committees with no geo).
        $geoValidated = $this->validateGeoEligibility($committee, $member);

        // 4. Wrap both writes in transaction with post-commit event dispatch
        $lineageToDispatch = null;

        DB::transaction(function () use ($command, $geoValidated, &$lineageToDispatch) {
            // Create constitutional membership (MembershipLineage)
            // This is the ONLY write authority for membership state
            $lineageToDispatch = MembershipLineage::establish(
                lineageId: LineageId::generate(),
                memberId: $command->memberId,
                committeeId: $command->committeeId,
                tenantId: $command->tenantId,
                reason: $this->mapNominationTypeToApplicationReason($command->nominationType),
                at: new \DateTimeImmutable(),
            );

            // Persist to single authority (committee_associations via lineage)
            $this->lineageRepository->saveForTenant($lineageToDispatch, $command->tenantId);
        });

        // 5. Dispatch lineage events AFTER transaction commits (if any exist)
        if ($lineageToDispatch !== null) {
            $this->eventBus->dispatchAll($lineageToDispatch->releaseEvents());
        }
    }

    /**
     * Validate geographic eligibility via the projection/chain/policy pipeline.
     *
     * Builds projections for both member residence and committee operational geo,
     * derives semantic chains, and evaluates the policy.
     *
     * @return bool True if member is eligible (all committees, including central with no geo)
     * @throws DomainException If member is geographically ineligible for this committee
     */
    private function validateGeoEligibility($committee, $member): bool
    {
        $committeeGeoUnitId = $committee->getGeoUnitId();
        $memberResidenceGeoUnitId = $member->getResidenceGeoIdentity()?->residenceGeoUnitId;

        // Central committees (no geoUnitId) cover all members — skip check
        if ($committeeGeoUnitId === null) {
            return true;
        }

        // Geographic committees require member residence
        if ($memberResidenceGeoUnitId === null) {
            throw CommitteeEligibilityException::missingResidenceGeo();
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
            throw CommitteeEligibilityException::geoOutOfScope();
        }

        return true;
    }

    private function mapNominationTypeToApplicationReason(\App\Contexts\Membership\Domain\ValueObjects\NominationType $nominationType): ApplicationReason
    {
        return match ($nominationType->value()) {
            'appointed' => ApplicationReason::MANUAL,
            'elected' => ApplicationReason::EXCEPTION,
            'volunteered' => ApplicationReason::RESIDENCE,
            default => ApplicationReason::MANUAL,
        };
    }
}
