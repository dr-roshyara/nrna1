<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\DefaultConstitutionalArbitrationPolicy;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationPolicy;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\ConflictResolutionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\FinalAuthorityDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\AuthorityResolutionType;

final class DefaultConstitutionalArbitrationPolicyTest extends TestCase
{
    private function makeNode(string $id, bool $isException = false): JurisdictionNode
    {
        return new JurisdictionNode($id, 'state', 'BY', true, $isException);
    }

    private function makeResolutionPolicy(FinalAuthorityDecision $returns): ConflictResolutionPolicy
    {
        $policy = $this->createMock(ConflictResolutionPolicy::class);
        $policy->method('resolve')->willReturn($returns);
        return $policy;
    }

    public function test_empty_classification_produces_expired_legitimacy(): void
    {
        $classification = new AuthorityClassification(null, [], [], []);
        $resolutionPolicy = $this->makeResolutionPolicy(FinalAuthorityDecision::none());

        $arbitrationPolicy = new DefaultConstitutionalArbitrationPolicy($resolutionPolicy);
        $result = $arbitrationPolicy->arbitrate($classification, new \DateTimeImmutable());

        $this->assertSame(GovernanceLegitimacy::EXPIRED, $result->legitimacy);
    }

    public function test_exception_authority_produces_legitimate_decision(): void
    {
        $exceptionNode = $this->makeNode('node-exc', true);
        $classification = new AuthorityClassification(null, [], [], [$exceptionNode]);
        $finalDecision = FinalAuthorityDecision::from($exceptionNode, AuthorityResolutionType::EXCEPTION, 'exception_precedence');

        $arbitrationPolicy = new DefaultConstitutionalArbitrationPolicy(
            $this->makeResolutionPolicy($finalDecision)
        );

        $result = $arbitrationPolicy->arbitrate($classification, new \DateTimeImmutable());

        $this->assertSame(GovernanceLegitimacy::LEGITIMATE, $result->legitimacy);
    }

    public function test_override_authority_produces_legitimate_decision(): void
    {
        $overrideNode = $this->makeNode('node-ov');
        $classification = new AuthorityClassification(null, [], [$overrideNode], []);
        $finalDecision = FinalAuthorityDecision::from($overrideNode, AuthorityResolutionType::OVERRIDE, 'override_precedence');

        $arbitrationPolicy = new DefaultConstitutionalArbitrationPolicy(
            $this->makeResolutionPolicy($finalDecision)
        );

        $result = $arbitrationPolicy->arbitrate($classification, new \DateTimeImmutable());

        $this->assertSame(GovernanceLegitimacy::LEGITIMATE, $result->legitimacy);
    }

    public function test_trace_records_winning_node_id(): void
    {
        $winnerNode = $this->makeNode('node-4');
        $classification = new AuthorityClassification(null, [], [], [$winnerNode]);
        $finalDecision = FinalAuthorityDecision::from($winnerNode, AuthorityResolutionType::EXCEPTION, 'exception_precedence');

        $arbitrationPolicy = new DefaultConstitutionalArbitrationPolicy(
            $this->makeResolutionPolicy($finalDecision)
        );

        $result = $arbitrationPolicy->arbitrate($classification, new \DateTimeImmutable());

        $this->assertSame('node-4', $result->trace->selectedNodeId);
    }

    public function test_trace_records_doctrine_rule_applied(): void
    {
        $exceptionNode = $this->makeNode('node-5', true);
        $classification = new AuthorityClassification(null, [], [], [$exceptionNode]);
        $finalDecision = FinalAuthorityDecision::from($exceptionNode, AuthorityResolutionType::EXCEPTION, 'exception_precedence');

        $arbitrationPolicy = new DefaultConstitutionalArbitrationPolicy(
            $this->makeResolutionPolicy($finalDecision)
        );

        $result = $arbitrationPolicy->arbitrate($classification, new \DateTimeImmutable());

        $this->assertContains('exception', $result->trace->doctrineRulesApplied);
    }

    public function test_no_winner_produces_no_authority_reason(): void
    {
        $classification = new AuthorityClassification(null, [], [], []);
        $resolutionPolicy = $this->makeResolutionPolicy(FinalAuthorityDecision::none());

        $arbitrationPolicy = new DefaultConstitutionalArbitrationPolicy($resolutionPolicy);
        $result = $arbitrationPolicy->arbitrate($classification, new \DateTimeImmutable());

        $this->assertSame('no_authority', $result->reason->code);
    }
}
