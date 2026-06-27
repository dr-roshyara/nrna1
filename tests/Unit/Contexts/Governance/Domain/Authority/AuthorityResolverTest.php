<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Domain\Authority;

use App\Contexts\Governance\Domain\Authority\AuthorityAssignment;
use App\Contexts\Governance\Domain\Authority\AuthorityAssignmentRepository;
use App\Contexts\Governance\Domain\Authority\AuthorityResolver;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityEffectivePeriod;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AuthorityResolverTest extends TestCase
{
    private AuthorityResolver $resolver;
    private DateTimeImmutable $now;
    private TenantId $tenantId;
    private MemberId $delegatedBy;

    protected function setUp(): void
    {
        $this->resolver = new AuthorityResolver();
        $this->now = new DateTimeImmutable('2026-05-09T12:00:00Z');
        $this->tenantId = TenantId::fromString('org-nrna-eu');
        $this->delegatedBy = MemberId::from('president-123');
    }

    private function makeAssignment(
        CommitteeId $from,
        CommitteeId $to,
        DelegationType $type,
        DateTimeImmutable $validFrom,
        ?DateTimeImmutable $validUntil = null,
    ): AuthorityAssignment {
        $validity = $validUntil !== null
            ? AuthorityEffectivePeriod::bounded($validFrom, $validUntil)
            : AuthorityEffectivePeriod::openEnded($validFrom);

        return AuthorityAssignment::delegate(
            id: AuthorityAssignmentId::generate(),
            tenantId: $this->tenantId,
            fromCommitteeId: $from,
            toCommitteeId: $to,
            type: $type,
            scope: DelegationScope::from('COMMITTEE_FORMATION'),
            validity: $validity,
            delegatedBy: $this->delegatedBy,
            delegatedAt: $validFrom,
            now: $this->now,
            delegatorHasActiveAuthority: true,
        );
    }

    private function repoWith(array $assignments): AuthorityAssignmentRepository
    {
        return new class($assignments) implements AuthorityAssignmentRepository {
            public function __construct(private array $assignments) {}

            public function findById(AuthorityAssignmentId $id): ?AuthorityAssignment
            {
                return null;
            }

            public function findActiveByCommittee(CommitteeId $id, DateTimeImmutable $at): array
            {
                return array_values(array_filter(
                    $this->assignments,
                    fn($a) => $a->toCommitteeId()->equals($id) && $a->isActiveAt($at)
                ));
            }

            public function findAllByFromCommittee(CommitteeId $id): array
            {
                return array_values(array_filter(
                    $this->assignments,
                    fn($a) => $a->fromCommitteeId()->equals($id)
                ));
            }

            public function append(AuthorityAssignment $assignment): void {}
            public function save(AuthorityAssignment $assignment): void {}
        };
    }

    public function test_returns_null_when_no_assignments_exist(): void
    {
        $repo = $this->repoWith([]);
        $target = CommitteeId::generate();

        $result = $this->resolver->resolveActiveAt($target, $this->now, $repo);

        $this->assertNull($result);
    }

    public function test_returns_single_active_assignment(): void
    {
        $from = CommitteeId::generate();
        $to = CommitteeId::generate();
        $assignment = $this->makeAssignment(
            from: $from,
            to: $to,
            type: DelegationType::AUTHORITY,
            validFrom: new DateTimeImmutable('2026-01-01T00:00:00Z'),
        );

        $repo = $this->repoWith([$assignment]);
        $result = $this->resolver->resolveActiveAt($to, $this->now, $repo);

        $this->assertNotNull($result);
        $this->assertTrue($result->id()->equals($assignment->id()));
    }

    public function test_returns_null_for_expired_assignment(): void
    {
        $from = CommitteeId::generate();
        $to = CommitteeId::generate();
        $assignment = $this->makeAssignment(
            from: $from,
            to: $to,
            type: DelegationType::AUTHORITY,
            validFrom: new DateTimeImmutable('2024-01-01T00:00:00Z'),
            validUntil: new DateTimeImmutable('2025-01-01T00:00:00Z'),
        );

        $repo = $this->repoWith([$assignment]);
        $result = $this->resolver->resolveActiveAt($to, $this->now, $repo);

        $this->assertNull($result);
    }

    public function test_resolves_highest_priority_type_when_multiple_active(): void
    {
        $from = CommitteeId::generate();
        $to = CommitteeId::generate();
        $validFrom = new DateTimeImmutable('2026-01-01T00:00:00Z');

        $authority = $this->makeAssignment($from, $to, DelegationType::AUTHORITY, $validFrom);
        $temporary = $this->makeAssignment($from, $to, DelegationType::TEMPORARY, $validFrom);

        $repo = $this->repoWith([$temporary, $authority]);
        $result = $this->resolver->resolveActiveAt($to, $this->now, $repo);

        $this->assertNotNull($result);
        $this->assertSame(DelegationType::AUTHORITY, $result->type());
    }

    public function test_returns_null_for_revoked_assignment(): void
    {
        $from = CommitteeId::generate();
        $to = CommitteeId::generate();
        $assignment = $this->makeAssignment(
            from: $from,
            to: $to,
            type: DelegationType::AUTHORITY,
            validFrom: new DateTimeImmutable('2026-01-01T00:00:00Z'),
        );
        $assignment->revoke($this->delegatedBy, 'Revoked', $this->now);

        $repo = $this->repoWith([$assignment]);
        $result = $this->resolver->resolveActiveAt($to, $this->now, $repo);

        $this->assertNull($result);
    }
}
