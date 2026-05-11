<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo\Resolution;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\DefaultConflictResolutionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\StableAuthoritySelectionPolicy;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\AuthorityResolutionType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class DefaultConflictResolutionPolicyTest extends TestCase
{
    private DefaultConflictResolutionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new DefaultConflictResolutionPolicy(new StableAuthoritySelectionPolicy());
    }

    public function test_empty_classification_resolves_to_none(): void
    {
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [],
            exceptions: []
        );

        $decision = $this->policy->resolve($classification);

        $this->assertNull($decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::NONE, $decision->type);
        $this->assertFalse($decision->isResolved());
    }

    public function test_single_exception_wins(): void
    {
        $exceptionNode = new JurisdictionNode('node-ex', 'district', 'EX', true, true);
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [],
            exceptions: [$exceptionNode]
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($exceptionNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::EXCEPTION, $decision->type);
    }

    public function test_exception_beats_override_and_direct(): void
    {
        $directNode = new JurisdictionNode('node-d', 'state', 'ST', true, false);
        $overrideNode = new JurisdictionNode('node-ov', 'state', 'OV', true, false);
        $exceptionNode = new JurisdictionNode('node-ex', 'district', 'EX', true, true);

        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [],
            overrides: [$overrideNode],
            exceptions: [$exceptionNode]
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($exceptionNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::EXCEPTION, $decision->type);
    }

    public function test_override_wins_without_exception(): void
    {
        $overrideNode = new JurisdictionNode('node-ov', 'state', 'OV', true, false);
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [$overrideNode],
            exceptions: []
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($overrideNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::OVERRIDE, $decision->type);
    }

    public function test_override_beats_direct_and_delegated(): void
    {
        $directNode = new JurisdictionNode('node-d', 'state', 'ST', true, false);
        $delegatedNode = new JurisdictionNode('node-dg', 'state', 'DG', true, false);
        $overrideNode = new JurisdictionNode('node-ov', 'state', 'OV', true, false);

        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [$delegatedNode],
            overrides: [$overrideNode],
            exceptions: []
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($overrideNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::OVERRIDE, $decision->type);
    }

    public function test_direct_wins_without_exception_or_override(): void
    {
        $directNode = new JurisdictionNode('node-d', 'state', 'ST', true, false);
        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [],
            overrides: [],
            exceptions: []
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($directNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::DIRECT, $decision->type);
    }

    public function test_direct_beats_delegated(): void
    {
        $directNode = new JurisdictionNode('node-d', 'state', 'ST', true, false);
        $delegatedNode = new JurisdictionNode('node-dg', 'state', 'DG', true, false);

        $classification = new AuthorityClassification(
            direct: $directNode,
            delegated: [$delegatedNode],
            overrides: [],
            exceptions: []
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($directNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::DIRECT, $decision->type);
    }

    public function test_delegated_wins_as_last_resort(): void
    {
        $delegatedNode = new JurisdictionNode('node-dg', 'state', 'DG', true, false);
        $classification = new AuthorityClassification(
            direct: null,
            delegated: [$delegatedNode],
            overrides: [],
            exceptions: []
        );

        $decision = $this->policy->resolve($classification);

        $this->assertSame($delegatedNode, $decision->winningNode);
        $this->assertEquals(AuthorityResolutionType::DELEGATED, $decision->type);
    }

    public function test_multiple_exceptions_uses_stable_selection(): void
    {
        $exceptionZ = new JurisdictionNode('node-z', 'district', 'ZZ', true, true);
        $exceptionA = new JurisdictionNode('node-a', 'district', 'AA', true, true);
        $exceptionM = new JurisdictionNode('node-m', 'district', 'MM', true, true);

        $classification = new AuthorityClassification(
            direct: null,
            delegated: [],
            overrides: [],
            exceptions: [$exceptionZ, $exceptionA, $exceptionM]
        );

        $decision = $this->policy->resolve($classification);

        $this->assertEquals('node-a', $decision->winningNode->id);
        $this->assertEquals(AuthorityResolutionType::EXCEPTION, $decision->type);
    }
}
