<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Kernel;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;
use App\Contexts\Membership\Domain\Committee\Capability\InstitutionalCapabilityPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassifier;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\ConflictDetectionEngine;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedencePolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\ConflictResolutionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionIdGenerator;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class GovernanceDecisionKernel
{
    public function __construct(
        private readonly InstitutionalCapabilityPolicy $engine,
        private readonly AuthorityClassifier $classifier,
        private readonly ConflictDetectionEngine $conflictEngine,
        private readonly AuthorityPrecedencePolicy $precedencePolicy,
        private readonly ConflictResolutionPolicy $resolutionPolicy,
        private readonly GovernanceDecisionIdGenerator $idGenerator,
        private readonly ?GeoAuthorityGraph $authorityGraph = null,
    ) {}

    public function decide(CapabilityContext $ctx, CapabilityType $capability, \DateTimeImmutable $at): GovernanceDecision
    {
        $evalResult = $this->evaluateCapability($ctx, $capability);
        [$rootNode, $reachable] = $this->traverseGraph($ctx, $at);
        $classification = $this->classifier->classify($rootNode, $reachable);
        $conflicts = $this->conflictEngine->detect($classification);
        $ranking = $this->precedencePolicy->rank($classification);
        $finalDecision = $this->resolutionPolicy->resolve($classification);

        $id = $this->idGenerator->next();
        $event = new GovernanceDecisionProduced(
            decisionId: $id->toString(),
            tenantId: $ctx->actor->tenantId->value(),
            capabilityType: $capability->value,
            resolutionType: $finalDecision->type->value,
            winningAuthorityId: $finalDecision->winningNode?->id,
        );

        $conflictCollection = AuthorityConflictCollection::fromArray($conflicts);
        $rankingVO = AuthorityPrecedenceRanking::fromArray($ranking);

        return new GovernanceDecision(
            id: $id,
            decidedAt: $at,
            evaluation: $evalResult->evaluation,
            classification: $classification,
            detectedConflicts: $conflictCollection,
            precedenceRanking: $rankingVO,
            producedEvent: $event,
            finalDecision: $finalDecision,
        );
    }

    private function evaluateCapability(CapabilityContext $ctx, CapabilityType $capability)
    {
        return match ($capability) {
            CapabilityType::COMMITTEE_CREATION => $this->engine->evaluateCreateCommittee($ctx),
            CapabilityType::STRUCTURE_ACTIVATION => $this->engine->evaluateActivateStructure($ctx),
            CapabilityType::COMMITTEE_MODIFICATION => $this->engine->evaluateModifyCommittee($ctx),
            CapabilityType::STRUCTURE_DEPRECATION => $this->engine->evaluateDeprecateStructure($ctx),
        };
    }

    /**
     * @return array{0: \App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode|null, 1: array}
     */
    private function traverseGraph(CapabilityContext $ctx, \DateTimeImmutable $at): array
    {
        if ($this->authorityGraph === null) {
            return [null, []];
        }

        $scope = $ctx->targetScope ?? $ctx->actor->geographicScope;
        $rootNode = $this->authorityGraph->findNodeByScope($scope);

        if ($rootNode === null) {
            return [null, []];
        }

        $reachable = $this->authorityGraph->reachableJurisdictions($rootNode->id, $at);

        return [$rootNode, $reachable];
    }
}
