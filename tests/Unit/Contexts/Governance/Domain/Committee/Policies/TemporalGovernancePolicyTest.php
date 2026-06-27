<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Committee\Policies;

use App\Contexts\Governance\Domain\Committee\Policies\TemporalGovernancePolicy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeFacts;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class TemporalGovernancePolicyTest extends TestCase
{
    private TemporalGovernancePolicy $policy;
    private CommitteeId $committeeId;
    private DateTimeImmutable $now;

    protected function setUp(): void
    {
        $this->policy = new TemporalGovernancePolicy();
        $this->committeeId = CommitteeId::fromString('test-committee');
        $this->now = new DateTimeImmutable('2026-05-10 12:00:00');
    }

    public function test_returns_no_term_when_term_is_null(): void
    {
        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: null,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts, $this->now);

        $this->assertSame(TemporalGovernanceState::NO_TERM, $result);
    }

    public function test_returns_valid_when_now_within_term(): void
    {
        $start = new DateTimeImmutable('2026-01-01 00:00:00');
        $end = new DateTimeImmutable('2026-12-31 23:59:59');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: $term,
            parentId: null,
        );

        $now = new DateTimeImmutable('2026-06-15 12:00:00');

        $result = $this->policy->evaluate($facts, $now);

        $this->assertSame(TemporalGovernanceState::VALID, $result);
    }

    public function test_returns_expiring_when_within_30_days_of_end(): void
    {
        $start = new DateTimeImmutable('2026-01-01 00:00:00');
        $end = new DateTimeImmutable('2026-05-20 23:59:59');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: $term,
            parentId: null,
        );

        $now = new DateTimeImmutable('2026-04-25 12:00:00');

        $result = $this->policy->evaluate($facts, $now);

        $this->assertSame(TemporalGovernanceState::EXPIRING, $result);
    }

    public function test_returns_expiring_when_exactly_30_days_before_end(): void
    {
        $start = new DateTimeImmutable('2026-01-01 00:00:00');
        $end = new DateTimeImmutable('2026-06-15 23:59:59');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: $term,
            parentId: null,
        );

        $now = new DateTimeImmutable('2026-05-16 12:00:00');

        $result = $this->policy->evaluate($facts, $now);

        $this->assertSame(TemporalGovernanceState::EXPIRING, $result);
    }

    public function test_returns_expired_when_now_after_term_end(): void
    {
        $start = new DateTimeImmutable('2026-01-01 00:00:00');
        $end = new DateTimeImmutable('2026-04-01 23:59:59');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'DISSOLVED',
            term: $term,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts, $this->now);

        $this->assertSame(TemporalGovernanceState::EXPIRED, $result);
    }

    public function test_returns_expired_when_now_exactly_at_end(): void
    {
        $start = new DateTimeImmutable('2026-01-01 00:00:00');
        $end = new DateTimeImmutable('2026-05-10 12:00:00');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'DISSOLVED',
            term: $term,
            parentId: null,
        );

        $now = new DateTimeImmutable('2026-05-10 12:00:00');

        $result = $this->policy->evaluate($facts, $now);

        $this->assertSame(TemporalGovernanceState::EXPIRED, $result);
    }

    public function test_returns_caretaker_when_post_expiry_without_renewal(): void
    {
        $start = new DateTimeImmutable('2024-01-01 00:00:00');
        $end = new DateTimeImmutable('2025-12-31 23:59:59');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: $term,
            parentId: null,
        );

        $result = $this->policy->evaluate($facts, $this->now);

        $this->assertSame(TemporalGovernanceState::CARETAKER, $result);
    }

    public function test_returns_not_yet_active_when_now_before_term_start(): void
    {
        $start = new DateTimeImmutable('2026-07-01 00:00:00');
        $end = new DateTimeImmutable('2027-06-30 23:59:59');
        $term = TermPeriod::from($start, $end);

        $facts = new CommitteeFacts(
            id: $this->committeeId,
            operationalState: 'ACTIVE',
            term: $term,
            parentId: null,
        );

        $now = new DateTimeImmutable('2026-05-10 12:00:00');

        $result = $this->policy->evaluate($facts, $now);

        $this->assertSame(TemporalGovernanceState::NOT_YET_ACTIVE, $result);
    }

    public function test_policy_is_stateless(): void
    {
        $reflection = new \ReflectionClass(TemporalGovernancePolicy::class);
        $properties = $reflection->getProperties();

        $this->assertEmpty(
            $properties,
            'TemporalGovernancePolicy must have no instance properties'
        );
    }
}
