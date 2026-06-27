<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationKernel;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationPolicy;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Constitutional\FixedClock;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionKernel;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassifier;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\ConflictDetectionEngine;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\DefaultAuthorityPrecedencePolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\DefaultConflictResolutionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\StableAuthoritySelectionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\UuidGovernanceDecisionIdGenerator;
use App\Contexts\Membership\Domain\Committee\Capability\InstitutionalCapabilityPolicy;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityContext;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Capability\EvaluationResult;
use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;
use App\Contexts\Membership\Domain\Committee\Context\ActorContext;
use App\Contexts\Membership\Domain\Committee\Context\OrganisationGovernanceContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeStructureEpochContext;
use App\Contexts\Membership\Domain\Committee\Context\CommitteeLineageView;
use App\Contexts\Membership\Domain\Committee\Actor\ActorPosition;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class ConstitutionalArbitrationKernelTest extends TestCase
{
    private GovernanceDecisionKernel $governanceKernel;

    protected function setUp(): void
    {
        parent::setUp();

        $mockEngine = $this->createMock(InstitutionalCapabilityPolicy::class);
        $allowAll = new EvaluationResult(CapabilityEvaluation::allow('test'), []);
        $mockEngine->method('evaluateCreateCommittee')->willReturn($allowAll);
        $mockEngine->method('evaluateActivateStructure')->willReturn($allowAll);
        $mockEngine->method('evaluateModifyCommittee')->willReturn($allowAll);
        $mockEngine->method('evaluateDeprecateStructure')->willReturn($allowAll);

        $this->governanceKernel = new GovernanceDecisionKernel(
            $mockEngine,
            new AuthorityClassifier(),
            new ConflictDetectionEngine(),
            new DefaultAuthorityPrecedencePolicy(),
            new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy()),
            new UuidGovernanceDecisionIdGenerator(),
            null,
        );
    }

    private function makeCapabilityContext(): CapabilityContext
    {
        $actor = new ActorContext(
            userId: 'user-1',
            tenantId: TenantId::fromString('tenant-1'),
            position: ActorPosition::OWNER,
            geographicScope: new GeographicScope('national', null),
            isSystemActor: false,
        );

        return new CapabilityContext(
            $actor,
            new OrganisationGovernanceContext('org-1', 'active'),
            new CommitteeStructureEpochContext(1, 'active', 0, 'CENTRAL', null, true),
            CommitteeLineageView::root('committee-1'),
        );
    }

    private function makeConstitutionalDecision(\DateTimeImmutable $at): ConstitutionalDecision
    {
        return new ConstitutionalDecision(
            winner: null,
            legitimacy: GovernanceLegitimacy::EXPIRED,
            reason: ConstitutionalReason::noAuthority(),
            evaluatedAt: $at,
            trace: new ConstitutionalArbitrationTrace([], null, 'no_authority', []),
        );
    }

    private function makeArbitrationPolicy(\DateTimeImmutable $expectedAt = null): ConstitutionalArbitrationPolicy
    {
        $mock = $this->createMock(ConstitutionalArbitrationPolicy::class);

        if ($expectedAt !== null) {
            $mock->expects($this->once())
                ->method('arbitrate')
                ->with($this->isInstanceOf(AuthorityClassification::class), $this->identicalTo($expectedAt))
                ->willReturn($this->makeConstitutionalDecision($expectedAt));
        } else {
            $mock->method('arbitrate')
                ->willReturnCallback(fn($c, $at) => $this->makeConstitutionalDecision($at));
        }

        return $mock;
    }

    public function test_kernel_returns_constitutional_governance_decision(): void
    {
        $clock = new FixedClock(new \DateTimeImmutable());
        $kernel = new ConstitutionalArbitrationKernel(
            $this->governanceKernel,
            $this->makeArbitrationPolicy(),
            $clock,
        );

        $result = $kernel->decide($this->makeCapabilityContext(), CapabilityType::COMMITTEE_CREATION, null);

        $this->assertInstanceOf(ConstitutionalGovernanceDecision::class, $result);
    }

    public function test_kernel_uses_clock_when_at_is_null(): void
    {
        $fixedTime = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $clock = new FixedClock($fixedTime);

        $kernel = new ConstitutionalArbitrationKernel(
            $this->governanceKernel,
            $this->makeArbitrationPolicy($fixedTime),
            $clock,
        );

        $result = $kernel->decide($this->makeCapabilityContext(), CapabilityType::COMMITTEE_CREATION, null);

        $this->assertInstanceOf(ConstitutionalGovernanceDecision::class, $result);
    }

    public function test_kernel_uses_provided_at_when_given(): void
    {
        $clockTime = new \DateTimeImmutable('2026-01-01T00:00:00Z');
        $explicitAt = new \DateTimeImmutable('2026-05-08T10:00:00Z');
        $clock = new FixedClock($clockTime);

        $kernel = new ConstitutionalArbitrationKernel(
            $this->governanceKernel,
            $this->makeArbitrationPolicy($explicitAt),
            $clock,
        );

        $result = $kernel->decide($this->makeCapabilityContext(), CapabilityType::COMMITTEE_CREATION, $explicitAt);

        $this->assertInstanceOf(ConstitutionalGovernanceDecision::class, $result);
    }

    public function test_kernel_decision_id_matches_governance_decision(): void
    {
        $clock = new FixedClock(new \DateTimeImmutable());
        $kernel = new ConstitutionalArbitrationKernel(
            $this->governanceKernel,
            $this->makeArbitrationPolicy(),
            $clock,
        );

        $result = $kernel->decide($this->makeCapabilityContext(), CapabilityType::COMMITTEE_CREATION, null);

        $this->assertSame(
            $result->governanceDecision->id,
            $result->id(),
        );
    }
}
