<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority\Events;

use App\Contexts\Governance\Domain\Authority\Events\AuthorityDelegated;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AuthorityDelegatedTest extends TestCase
{
    private DateTimeImmutable $delegatedAt;
    private AuthorityAssignmentId $assignmentId;
    private TenantId $tenantId;
    private CommitteeId $fromId;
    private CommitteeId $toId;

    protected function setUp(): void
    {
        $this->delegatedAt = new DateTimeImmutable('2026-05-09T10:00:00Z');
        $this->assignmentId = AuthorityAssignmentId::from('assignment-uuid-111');
        $this->tenantId = TenantId::fromString('org-nrna-eu');
        $this->fromId = CommitteeId::generate();
        $this->toId = CommitteeId::generate();
    }

    private function makeEvent(
        ?DateTimeImmutable $validUntil = null,
    ): AuthorityDelegated {
        return AuthorityDelegated::from(
            assignmentId: $this->assignmentId,
            tenantId: $this->tenantId,
            fromCommitteeId: $this->fromId,
            toCommitteeId: $this->toId,
            type: DelegationType::AUTHORITY,
            scope: DelegationScope::from('COMMITTEE_FORMATION'),
            validFrom: $this->delegatedAt,
            validUntil: $validUntil,
            delegatedAt: $this->delegatedAt,
        );
    }

    public function test_carries_all_required_fields(): void
    {
        $event = $this->makeEvent();

        $this->assertTrue($event->assignmentId()->equals($this->assignmentId));
        $this->assertTrue($event->tenantId()->equals($this->tenantId));
        $this->assertTrue($event->fromCommitteeId()->equals($this->fromId));
        $this->assertTrue($event->toCommitteeId()->equals($this->toId));
        $this->assertSame(DelegationType::AUTHORITY, $event->type());
        $this->assertSame('COMMITTEE_FORMATION', $event->scope()->value());
        $this->assertSame($this->delegatedAt, $event->validFrom());
        $this->assertNull($event->validUntil());
        $this->assertSame($this->delegatedAt, $event->delegatedAt());
    }

    public function test_carries_bounded_validity_when_provided(): void
    {
        $end = new DateTimeImmutable('2028-01-01T00:00:00Z');
        $event = $this->makeEvent(validUntil: $end);

        $this->assertSame($end, $event->validUntil());
    }

    public function test_is_readonly(): void
    {
        $reflection = new \ReflectionClass(AuthorityDelegated::class);

        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue(
                $property->isReadOnly(),
                "Property {$property->getName()} must be readonly — events are immutable facts"
            );
        }
    }

    public function test_has_private_constructor(): void
    {
        $reflection = new \ReflectionClass(AuthorityDelegated::class);
        $constructor = $reflection->getConstructor();

        $this->assertTrue($constructor->isPrivate(), 'Use from() factory');
    }

    public function test_has_no_public_setters(): void
    {
        $reflection = new \ReflectionClass(AuthorityDelegated::class);
        $setters = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => preg_match('/^set/', $m->getName())
        );

        $this->assertEmpty($setters);
    }

    public function test_allowed_public_methods_only(): void
    {
        $reflection = new \ReflectionClass(AuthorityDelegated::class);
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
        );

        $allowed = [
            'assignmentId', 'tenantId', 'fromCommitteeId', 'toCommitteeId',
            'type', 'scope', 'validFrom', 'validUntil', 'delegatedAt',
        ];

        foreach ($publicMethods as $method) {
            $this->assertContains(
                $method->getName(),
                $allowed,
                "Unexpected method: {$method->getName()} — AuthorityDelegated is a historical fact"
            );
        }
    }
}
