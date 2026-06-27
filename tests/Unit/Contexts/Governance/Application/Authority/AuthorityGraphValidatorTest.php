<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\Authority;

use App\Contexts\Governance\Application\Authority\AuthorityGraphValidator;
use App\Contexts\Governance\Domain\Authority\AuthorityAssignment;
use App\Contexts\Governance\Domain\Authority\AuthorityAssignmentRepository;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityEffectivePeriod;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class AuthorityGraphValidatorTest extends TestCase
{
    private AuthorityGraphValidator $validator;
    private DateTimeImmutable $now;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->validator = new AuthorityGraphValidator();
        $this->now = new DateTimeImmutable('2026-05-09T12:00:00Z');
        $this->tenantId = TenantId::fromString('org-nrna-eu');
    }

    private function makeActiveAssignment(CommitteeId $from, CommitteeId $to): AuthorityAssignment
    {
        return AuthorityAssignment::delegate(
            id: AuthorityAssignmentId::generate(),
            tenantId: $this->tenantId,
            fromCommitteeId: $from,
            toCommitteeId: $to,
            type: DelegationType::AUTHORITY,
            scope: DelegationScope::from('FULL'),
            validity: AuthorityEffectivePeriod::openEnded(new DateTimeImmutable('2026-01-01T00:00:00Z')),
            delegatedBy: MemberId::from('actor-123'),
            delegatedAt: new DateTimeImmutable('2026-01-01T00:00:00Z'),
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

    public function test_no_cycle_passes_without_exception(): void
    {
        $a = CommitteeId::generate();
        $b = CommitteeId::generate();
        $c = CommitteeId::generate();

        $repo = $this->repoWith([
            $this->makeActiveAssignment($a, $b),
        ]);

        // Adding B→C: no cycle (A→B, B→C is a chain, not a cycle)
        $this->validator->assertNoCycle($b, $c, $repo);
        $this->addToAssertionCount(1); // passed without exception
    }

    public function test_direct_cycle_throws_domain_exception(): void
    {
        $a = CommitteeId::generate();
        $b = CommitteeId::generate();

        // Existing: A→B
        $repo = $this->repoWith([
            $this->makeActiveAssignment($a, $b),
        ]);

        // Proposed: B→A would form cycle A↔B
        $this->expectException(\DomainException::class);
        $this->validator->assertNoCycle($b, $a, $repo);
    }

    public function test_transitive_cycle_throws_domain_exception(): void
    {
        $a = CommitteeId::generate();
        $b = CommitteeId::generate();
        $c = CommitteeId::generate();

        // Existing: A→B, B→C
        $repo = $this->repoWith([
            $this->makeActiveAssignment($a, $b),
            $this->makeActiveAssignment($b, $c),
        ]);

        // Proposed: C→A would form cycle A→B→C→A
        $this->expectException(\DomainException::class);
        $this->validator->assertNoCycle($c, $a, $repo);
    }

    public function test_empty_graph_always_passes(): void
    {
        $repo = $this->repoWith([]);
        $a = CommitteeId::generate();
        $b = CommitteeId::generate();

        $this->validator->assertNoCycle($a, $b, $repo);
        $this->addToAssertionCount(1);
    }

    public function test_is_stateless(): void
    {
        $reflection = new \ReflectionClass(AuthorityGraphValidator::class);
        $this->assertEmpty($reflection->getProperties(), 'AuthorityGraphValidator must be stateless');
    }
}
