<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority;

use App\Contexts\Governance\Domain\Authority\AuthorityAssignment;
use App\Contexts\Governance\Domain\Authority\Events\AuthorityDelegated;
use App\Contexts\Governance\Domain\Authority\Events\AuthorityRevoked;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityEffectivePeriod;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AuthorityAssignmentTest extends TestCase
{
    private DateTimeImmutable $now;
    private TenantId $tenantId;
    private CommitteeId $fromId;
    private CommitteeId $toId;
    private MemberId $delegatedBy;
    private DelegationScope $scope;
    private AuthorityEffectivePeriod $validity;

    protected function setUp(): void
    {
        $this->now = new DateTimeImmutable('2026-05-09T12:00:00Z');
        $this->tenantId = TenantId::fromString('org-nrna-eu');
        $this->fromId = CommitteeId::generate();
        $this->toId = CommitteeId::generate();
        $this->delegatedBy = MemberId::from('president-123');
        $this->scope = DelegationScope::from('COMMITTEE_FORMATION');
        $this->validity = AuthorityEffectivePeriod::openEnded(
            new DateTimeImmutable('2026-05-09T00:00:00Z')
        );
    }

    private function makeAssignment(
        ?CommitteeId $from = null,
        ?CommitteeId $to = null,
        DelegationType $type = DelegationType::AUTHORITY,
        bool $delegatorHasActiveAuthority = true,
        ?DateTimeImmutable $delegatedAt = null,
    ): AuthorityAssignment {
        return AuthorityAssignment::delegate(
            id: AuthorityAssignmentId::generate(),
            tenantId: $this->tenantId,
            fromCommitteeId: $from ?? $this->fromId,
            toCommitteeId: $to ?? $this->toId,
            type: $type,
            scope: $this->scope,
            validity: $this->validity,
            delegatedBy: $this->delegatedBy,
            delegatedAt: $delegatedAt ?? new DateTimeImmutable('2026-05-09T10:00:00Z'),
            now: $this->now,
            delegatorHasActiveAuthority: $delegatorHasActiveAuthority,
        );
    }

    // ── Construction ──────────────────────────────────────────────

    public function test_creates_active_assignment_with_all_fields(): void
    {
        $id = AuthorityAssignmentId::generate();
        $assignment = AuthorityAssignment::delegate(
            id: $id,
            tenantId: $this->tenantId,
            fromCommitteeId: $this->fromId,
            toCommitteeId: $this->toId,
            type: DelegationType::AUTHORITY,
            scope: $this->scope,
            validity: $this->validity,
            delegatedBy: $this->delegatedBy,
            delegatedAt: new DateTimeImmutable('2026-05-09T10:00:00Z'),
            now: $this->now,
            delegatorHasActiveAuthority: true,
        );

        $this->assertTrue($assignment->id()->equals($id));
        $this->assertTrue($assignment->tenantId()->equals($this->tenantId));
        $this->assertTrue($assignment->fromCommitteeId()->equals($this->fromId));
        $this->assertTrue($assignment->toCommitteeId()->equals($this->toId));
        $this->assertSame(DelegationType::AUTHORITY, $assignment->type());
        $this->assertSame(DelegationStatus::ACTIVE, $assignment->status());
        $this->assertTrue($assignment->isActive());
    }

    // ── INV-A01: self-delegation ──────────────────────────────────

    public function test_inv_a01_cannot_delegate_to_self(): void
    {
        $this->expectException(\DomainException::class);
        $this->makeAssignment(from: $this->fromId, to: $this->fromId);
    }

    // ── INV-A02: terminal status ──────────────────────────────────

    public function test_active_assignment_can_be_revoked(): void
    {
        $assignment = $this->makeAssignment();
        $assignment->revoke($this->delegatedBy, 'Constitutional order revoked', $this->now);

        $this->assertSame(DelegationStatus::REVOKED, $assignment->status());
        $this->assertFalse($assignment->isActive());
    }

    public function test_inv_a02_cannot_revoke_already_revoked_assignment(): void
    {
        $assignment = $this->makeAssignment();
        $assignment->revoke($this->delegatedBy, 'First revocation', $this->now);

        $this->expectException(\DomainException::class);
        $assignment->revoke($this->delegatedBy, 'Second revocation', $this->now);
    }

    // ── INV-A04: OVERRIDE requires delegator authority ────────────

    public function test_inv_a04_override_without_existing_authority_throws(): void
    {
        $this->expectException(\DomainException::class);
        $this->makeAssignment(
            type: DelegationType::OVERRIDE,
            delegatorHasActiveAuthority: false,
        );
    }

    public function test_authority_type_does_not_require_existing_authority_flag(): void
    {
        $assignment = $this->makeAssignment(
            type: DelegationType::AUTHORITY,
            delegatorHasActiveAuthority: false,
        );

        $this->assertSame(DelegationType::AUTHORITY, $assignment->type());
    }

    public function test_temporary_type_does_not_require_existing_authority_flag(): void
    {
        $assignment = $this->makeAssignment(
            type: DelegationType::TEMPORARY,
            delegatorHasActiveAuthority: false,
        );

        $this->assertSame(DelegationType::TEMPORARY, $assignment->type());
    }

    // ── INV-A05: clock injection ──────────────────────────────────

    public function test_inv_a05_delegated_at_in_future_throws(): void
    {
        $futureDelegatedAt = new DateTimeImmutable('2026-06-01T00:00:00Z');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('future');
        $this->makeAssignment(delegatedAt: $futureDelegatedAt);
    }

    public function test_delegated_at_equal_to_now_is_valid(): void
    {
        $assignment = $this->makeAssignment(delegatedAt: $this->now);
        $this->assertSame(DelegationStatus::ACTIVE, $assignment->status());
    }

    // ── Events ────────────────────────────────────────────────────

    public function test_emits_authority_delegated_event_on_creation(): void
    {
        $assignment = $this->makeAssignment();
        $events = $assignment->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(AuthorityDelegated::class, $events[0]);
    }

    public function test_emits_authority_revoked_event_on_revocation(): void
    {
        $assignment = $this->makeAssignment();
        $assignment->releaseEvents(); // clear creation event

        $assignment->revoke($this->delegatedBy, 'Revoked by ICC order', $this->now);
        $events = $assignment->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(AuthorityRevoked::class, $events[0]);
    }

    public function test_release_events_clears_buffer(): void
    {
        $assignment = $this->makeAssignment();
        $assignment->releaseEvents();

        $this->assertEmpty($assignment->releaseEvents());
    }

    // ── isActiveAt ────────────────────────────────────────────────

    public function test_is_active_at_current_time(): void
    {
        $assignment = $this->makeAssignment();
        $this->assertTrue($assignment->isActiveAt($this->now));
    }

    public function test_is_not_active_at_time_before_validity_start(): void
    {
        $assignment = $this->makeAssignment();
        $beforeStart = new DateTimeImmutable('2020-01-01T00:00:00Z');
        $this->assertFalse($assignment->isActiveAt($beforeStart));
    }

    public function test_revoked_assignment_is_not_active_at_any_time(): void
    {
        $assignment = $this->makeAssignment();
        $assignment->revoke($this->delegatedBy, 'Revoked', $this->now);

        $this->assertFalse($assignment->isActiveAt($this->now));
        $this->assertFalse($assignment->isActiveAt(new DateTimeImmutable('2020-01-01T00:00:00Z')));
    }

    // ── Immutability ──────────────────────────────────────────────

    public function test_has_no_public_setters(): void
    {
        $reflection = new \ReflectionClass(AuthorityAssignment::class);
        $setters = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => preg_match('/^set/', $m->getName())
        );

        $this->assertEmpty($setters, 'AuthorityAssignment must have no public setters');
    }
}
