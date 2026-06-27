<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain;

use App\Contexts\Governance\Domain\ValueObjects\AuthorityPath;
use App\Contexts\Governance\Domain\ValueObjects\ConstitutionalBasis;
use App\Contexts\Governance\Domain\ValueObjects\DecisionTrace;
use App\Contexts\Governance\Domain\ValueObjects\Legitimacy;
use App\Contexts\Governance\Domain\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class GovernanceDecisionTest extends TestCase
{
    private DateTimeImmutable $now;

    protected function setUp(): void
    {
        $this->now = new DateTimeImmutable('2026-05-09 12:00:00');
    }

    private function makeTrace(DateTimeImmutable $at): DecisionTrace
    {
        return DecisionTrace::from(
            evaluatedRules: ['RULE_A'],
            matchedClauses: [],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC']),
            adjudicatedBy: MemberId::from('user-123'),
            adjudicatedAt: $at,
        );
    }

    public function test_it_records_governance_decision_immutably(): void
    {
        $decisionId = GovernanceDecision::generateId();
        $committeeId = CommitteeId::generate();
        $basis = ConstitutionalBasis::from(
            article: 'Article 5.2',
            referenceText: 'Committee formation authority'
        );
        $trace = DecisionTrace::from(
            evaluatedRules: ['RULE_FORMATION'],
            matchedClauses: ['CLAUSE_5_2_A'],
            rejectedConstraints: [],
            authorityPath: AuthorityPath::fromArray(['ICC', 'CONTINENT']),
            adjudicatedBy: MemberId::from('president-123'),
            adjudicatedAt: new DateTimeImmutable('2026-05-09 10:00:00')
        );
        $effectiveFrom = new DateTimeImmutable('2026-05-09');
        $decidedAt = new DateTimeImmutable('2026-05-09 10:00:00');

        $decision = GovernanceDecision::record(
            decisionId: $decisionId,
            committeeId: $committeeId,
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: $basis,
            trace: $trace,
            effectiveFrom: $effectiveFrom,
            decidedAt: $decidedAt,
            now: $this->now,
        );

        $this->assertTrue($decision->decisionId()->equals($decisionId));
        $this->assertTrue($decision->committeeId()->equals($committeeId));
        $this->assertSame('COMMITTEE_FORMATION', $decision->capabilityType());
        $this->assertSame(Legitimacy::LEGITIMATE, $decision->legitimacy());
        $this->assertTrue($decision->constitutionalBasis()->equals($basis));
        $this->assertTrue($decision->trace()->equals($trace));
        $this->assertSame($decidedAt, $decision->decidedAt());
    }

    public function test_it_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecision::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate(),
            'GovernanceDecision must have private constructor - use record() factory'
        );
    }

    public function test_it_is_immutable_readonly(): void
    {
        $decidedAt = new DateTimeImmutable('2026-05-09 10:00:00');

        $decision = GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($decidedAt),
            effectiveFrom: $decidedAt,
            decidedAt: $decidedAt,
            now: $this->now,
        );

        $reflection = new \ReflectionClass($decision);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                sprintf('Property %s must be readonly', $property->getName())
            );
        }
    }

    public function test_it_is_append_only_immutable_aggregate(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecision::class);

        $allowedMethods = ['record', 'decisionId', 'committeeId', 'capabilityType', 'legitimacy', 'constitutionalBasis', 'trace', 'effectiveFrom', 'decidedAt', 'generateId', 'equals'];
        $publicMethods = array_map(
            fn($m) => $m->getName(),
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC)
        );

        $unexpectedMethods = array_diff($publicMethods, $allowedMethods);
        $this->assertEmpty($unexpectedMethods, 'Aggregate should have only accessor and factory methods');
    }

    public function test_capability_type_cannot_be_empty(): void
    {
        $this->expectException(\DomainException::class);

        $decidedAt = new DateTimeImmutable('2026-05-09 10:00:00');

        GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: '',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($decidedAt),
            effectiveFrom: $decidedAt,
            decidedAt: $decidedAt,
            now: $this->now,
        );
    }

    public function test_legitimacy_is_enforced_by_enum_type_system(): void
    {
        $decidedAt = new DateTimeImmutable('2026-05-09 10:00:00');

        $decision = GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::REVOKED,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($decidedAt),
            effectiveFrom: $decidedAt,
            decidedAt: $decidedAt,
            now: $this->now,
        );

        $this->assertSame(Legitimacy::REVOKED, $decision->legitimacy());
    }

    public function test_effective_from_must_not_be_after_decided_at(): void
    {
        $decidedAt = new DateTimeImmutable('2026-05-09 10:00:00');
        $effectiveFrom = new DateTimeImmutable('2026-05-10 10:00:00');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Effective date cannot be after decision date');

        GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($decidedAt),
            effectiveFrom: $effectiveFrom,
            decidedAt: $decidedAt,
            now: $this->now,
        );
    }

    public function test_decided_at_cannot_be_in_future(): void
    {
        $now = new DateTimeImmutable('2025-01-01T00:00:00Z');
        $futureDecidedAt = new DateTimeImmutable('2025-02-01T00:00:00Z');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Decision cannot be dated in the future');

        GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($futureDecidedAt),
            effectiveFrom: $futureDecidedAt,
            decidedAt: $futureDecidedAt,
            now: $now,
        );
    }

    public function test_all_properties_are_typed_value_objects(): void
    {
        $decidedAt = new DateTimeImmutable('2026-05-09 10:00:00');

        $decision = GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($decidedAt),
            effectiveFrom: $decidedAt,
            decidedAt: $decidedAt,
            now: $this->now,
        );

        $reflection = new \ReflectionClass($decision);

        $typedProperties = [
            'decisionId' => 'GovernanceDecisionId',
            'committeeId' => 'CommitteeId',
            'capabilityType' => 'string',
            'legitimacy' => 'Legitimacy',
            'constitutionalBasis' => 'ConstitutionalBasis',
            'trace' => 'DecisionTrace',
            'effectiveFrom' => 'DateTimeImmutable',
            'decidedAt' => 'DateTimeImmutable',
        ];

        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            $typeName = $type ? $type->getName() : 'unknown';

            if (array_key_exists($property->getName(), $typedProperties)) {
                $this->assertStringContainsString(
                    $typedProperties[$property->getName()],
                    $typeName,
                    sprintf('Property %s should be typed as %s', $property->getName(), $typedProperties[$property->getName()])
                );
            }
        }
    }

    public function test_no_public_setters_on_aggregate(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecision::class);
        $setters = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => preg_match('/^set/', $m->getName())
        );

        $this->assertEmpty($setters, 'Aggregate must not have public setter methods');
    }

    public function test_historical_decision_can_be_reconstructed_at_any_replay_time(): void
    {
        $decidedAt = new DateTimeImmutable('2024-01-01T00:00:00Z');
        $replayNow = new DateTimeImmutable('2035-06-15T00:00:00Z');

        $decision = GovernanceDecision::record(
            decisionId: GovernanceDecision::generateId(),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            constitutionalBasis: ConstitutionalBasis::from('Article 1', null),
            trace: $this->makeTrace($decidedAt),
            effectiveFrom: $decidedAt,
            decidedAt: $decidedAt,
            now: $replayNow,
        );

        $this->assertSame($decidedAt, $decision->decidedAt());
    }
}
