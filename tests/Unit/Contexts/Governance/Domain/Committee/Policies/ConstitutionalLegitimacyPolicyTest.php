<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Committee\Policies;

use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use App\Contexts\Governance\Domain\Committee\Policies\ConstitutionalLegitimacyPolicy;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityPath;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceRole;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\ConstitutionalLegitimacy;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ConstitutionalLegitimacyPolicyTest extends TestCase
{
    private ConstitutionalLegitimacyPolicy $policy;
    private CommitteeId $committeeId;
    private DateTimeImmutable $now;

    protected function setUp(): void
    {
        $this->policy = new ConstitutionalLegitimacyPolicy();
        $this->committeeId = CommitteeId::fromString('test-committee');
        $this->now = new DateTimeImmutable('2026-05-10 12:00:00');
    }

    private function createValidAuthorityChain(): AuthorityChain
    {
        return AuthorityChain::capture(
            actorId: MemberId::from('actor'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $this->now,
        );
    }

    public function test_returns_legitimate_when_active_committee_with_valid_authority_chain(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $chain = $this->createValidAuthorityChain();

        $result = $this->policy->evaluate($facts, $chain);

        $this->assertSame(ConstitutionalLegitimacy::LEGITIMATE, $result);
    }

    public function test_returns_legitimate_when_delegation_status_is_active(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $chain = $this->createValidAuthorityChain();

        $result = $this->policy->evaluate($facts, $chain, DelegationStatus::ACTIVE);

        $this->assertSame(ConstitutionalLegitimacy::LEGITIMATE, $result);
    }

    public function test_returns_unauthorized_when_committee_is_dissolved(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'DISSOLVED',
            term: null,
            parentId: null,
        );

        $chain = $this->createValidAuthorityChain();

        $result = $this->policy->evaluate($facts, $chain);

        $this->assertSame(ConstitutionalLegitimacy::UNAUTHORIZED, $result);
    }

    public function test_returns_unauthorized_when_committee_is_suspended(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'SUSPENDED',
            term: null,
            parentId: null,
        );

        $chain = $this->createValidAuthorityChain();

        $result = $this->policy->evaluate($facts, $chain);

        $this->assertSame(ConstitutionalLegitimacy::UNAUTHORIZED, $result);
    }

    public function test_returns_unauthorized_when_no_authority_chain_provided(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts, null);

        $this->assertSame(ConstitutionalLegitimacy::UNAUTHORIZED, $result);
    }

    public function test_returns_revoked_when_delegation_status_is_revoked(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $chain = $this->createValidAuthorityChain();

        $result = $this->policy->evaluate($facts, $chain, DelegationStatus::REVOKED);

        $this->assertSame(ConstitutionalLegitimacy::REVOKED, $result);
    }

    public function test_returns_unauthorized_when_authority_chain_too_shallow(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $chain = AuthorityChain::capture(
            actorId: MemberId::from('actor'),
            role: GovernanceRole::CHAPTER_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC']),
            capturedAt: $this->now,
        );

        $result = $this->policy->evaluate($facts, $chain);

        $this->assertSame(ConstitutionalLegitimacy::UNAUTHORIZED, $result);
    }

    public function test_policy_is_stateless(): void
    {
        $reflection = new \ReflectionClass(ConstitutionalLegitimacyPolicy::class);
        $properties = $reflection->getProperties();

        $this->assertEmpty(
            $properties,
            'ConstitutionalLegitimacyPolicy must have no instance properties'
        );
    }
}
