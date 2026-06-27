<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Events;

use App\Contexts\Governance\Domain\Events\GovernanceDecisionRecorded;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Governance\Domain\ValueObjects\AuthorityPath;
use App\Contexts\Governance\Domain\ValueObjects\ConstitutionalBasis;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceRole;
use App\Contexts\Governance\Domain\ValueObjects\Legitimacy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class GovernanceDecisionRecordedTest extends TestCase
{
    private DateTimeImmutable $occurredAt;
    private AuthorityChain $authorityChain;
    private ConstitutionalBasis $constitutionalBasis;

    protected function setUp(): void
    {
        $this->occurredAt = new DateTimeImmutable('2026-05-09 10:00:00');
        $this->authorityChain = AuthorityChain::capture(
            actorId: MemberId::from('president-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $this->occurredAt,
        );
        $this->constitutionalBasis = ConstitutionalBasis::from(
            article: 'Article 5.2',
            referenceText: 'Committee formation authority',
        );
    }

    private function makeEvent(
        ?GovernanceDecisionId $decisionId = null,
        ?CommitteeId $committeeId = null,
        string $capabilityType = 'COMMITTEE_FORMATION',
        Legitimacy $legitimacy = Legitimacy::LEGITIMATE,
    ): GovernanceDecisionRecorded {
        return GovernanceDecisionRecorded::from(
            decisionId: $decisionId ?? GovernanceDecisionId::from('decision-uuid-123'),
            committeeId: $committeeId ?? CommitteeId::generate(),
            capabilityType: $capabilityType,
            legitimacy: $legitimacy,
            authorityChain: $this->authorityChain,
            constitutionalBasis: $this->constitutionalBasis,
            effectiveFrom: $this->occurredAt,
            occurredAt: $this->occurredAt,
        );
    }

    public function test_it_publishes_governance_fact_with_all_required_fields(): void
    {
        $decisionId = GovernanceDecisionId::from('decision-uuid-123');
        $committeeId = CommitteeId::generate();
        $effectiveFrom = $this->occurredAt;

        $event = GovernanceDecisionRecorded::from(
            decisionId: $decisionId,
            committeeId: $committeeId,
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            authorityChain: $this->authorityChain,
            constitutionalBasis: $this->constitutionalBasis,
            effectiveFrom: $effectiveFrom,
            occurredAt: $this->occurredAt,
        );

        $this->assertTrue($event->decisionId()->equals($decisionId));
        $this->assertTrue($event->committeeId()->equals($committeeId));
        $this->assertSame('COMMITTEE_FORMATION', $event->capabilityType());
        $this->assertSame(Legitimacy::LEGITIMATE, $event->legitimacy());
        $this->assertTrue($event->authorityChain()->equals($this->authorityChain));
        $this->assertTrue($event->constitutionalBasis()->equals($this->constitutionalBasis));
        $this->assertSame($effectiveFrom, $event->effectiveFrom());
        $this->assertSame($this->occurredAt, $event->occurredAt());
    }

    public function test_it_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecisionRecorded::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate(),
            'GovernanceDecisionRecorded must have private constructor - use from() factory'
        );
    }

    public function test_it_is_immutable_readonly(): void
    {
        $event = $this->makeEvent();
        $reflection = new \ReflectionClass($event);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                sprintf('Property %s must be readonly — events are immutable facts', $property->getName())
            );
        }
    }

    public function test_it_is_a_historical_fact_with_no_behavioral_methods(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecisionRecorded::class);
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
        );

        $allowedMethods = [
            'decisionId',
            'committeeId',
            'capabilityType',
            'legitimacy',
            'authorityChain',
            'constitutionalBasis',
            'effectiveFrom',
            'occurredAt',
        ];

        foreach ($publicMethods as $method) {
            $this->assertContains(
                $method->getName(),
                $allowedMethods,
                sprintf('Method %s not allowed — GovernanceDecisionRecorded is a historical fact, not a service', $method->getName())
            );
        }
    }

    public function test_all_properties_are_typed_value_objects(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecisionRecorded::class);

        foreach ($reflection->getProperties() as $property) {
            $type = $property->getType();
            $this->assertNotNull($type, sprintf('Property %s must be typed', $property->getName()));
            $this->assertFalse($type->allowsNull(), sprintf('Property %s must not be nullable', $property->getName()));
        }
    }

    public function test_it_has_no_public_setters(): void
    {
        $reflection = new \ReflectionClass(GovernanceDecisionRecorded::class);
        $setters = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => preg_match('/^set/', $m->getName())
        );

        $this->assertEmpty($setters, 'Historical facts must not have setter methods');
    }

    public function test_occurred_at_is_immutable_datetime(): void
    {
        $originalTime = new DateTimeImmutable('2026-05-09 10:00:00');

        $event = GovernanceDecisionRecorded::from(
            decisionId: GovernanceDecisionId::from('decision-uuid-789'),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            authorityChain: $this->authorityChain,
            constitutionalBasis: ConstitutionalBasis::from('Article 7', null),
            effectiveFrom: $originalTime,
            occurredAt: $originalTime,
        );

        $modifiedTime = $originalTime->modify('+1 hour');

        $this->assertNotEquals($modifiedTime, $event->occurredAt());
        $this->assertSame($originalTime, $event->occurredAt());
    }

    public function test_authority_chain_is_frozen_at_event_time(): void
    {
        $capturedAt = new DateTimeImmutable('2026-05-09 10:00:00');
        $authorityChain = AuthorityChain::capture(
            actorId: MemberId::from('president-123'),
            role: GovernanceRole::COUNTRY_PRESIDENT,
            delegationPath: AuthorityPath::fromArray(['ICC', 'CONTINENT', 'COUNTRY']),
            capturedAt: $capturedAt
        );

        $event = GovernanceDecisionRecorded::from(
            decisionId: GovernanceDecisionId::from('decision-uuid-999'),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            authorityChain: $authorityChain,
            constitutionalBasis: ConstitutionalBasis::from('Article 3', null),
            effectiveFrom: $capturedAt,
            occurredAt: $capturedAt,
        );

        $this->assertTrue($event->authorityChain()->equals($authorityChain));
        $this->assertSame($capturedAt, $event->authorityChain()->capturedAt());
    }

    public function test_capability_type_is_carried_in_event_for_consumer_projection(): void
    {
        $event = $this->makeEvent(capabilityType: 'COMMITTEE_SUSPENSION');

        $this->assertSame('COMMITTEE_SUSPENSION', $event->capabilityType());
    }

    public function test_effective_from_is_carried_in_event_for_temporal_projections(): void
    {
        $effectiveFrom = new DateTimeImmutable('2026-03-01T00:00:00Z');

        $event = GovernanceDecisionRecorded::from(
            decisionId: GovernanceDecisionId::from('decision-uuid-eff'),
            committeeId: CommitteeId::generate(),
            capabilityType: 'COMMITTEE_FORMATION',
            legitimacy: Legitimacy::LEGITIMATE,
            authorityChain: $this->authorityChain,
            constitutionalBasis: $this->constitutionalBasis,
            effectiveFrom: $effectiveFrom,
            occurredAt: new DateTimeImmutable('2026-04-01T00:00:00Z'),
        );

        $this->assertSame($effectiveFrom, $event->effectiveFrom());
    }
}
