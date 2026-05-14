<?php

declare(strict_types=1);

namespace Tests\Feature\Contexts\Membership;

use App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CommitteeAssociationRepositoryTest
 *
 * Tests GOVERNANCE LIFECYCLE semantics, not CRUD persistence.
 *
 * These tests encode the constitutional model:
 * - association = immutable historical decision
 * - ACTIVE = voting right (revocable via suspension)
 * - TERMINATED = final, immutable, reapplication creates NEW record
 * - audit = mandatory (actor + reason)
 *
 * NOT CRUD semantics like:
 * - overwrite is allowed
 * - identity is implicit
 * - history is mutable
 */
final class CommitteeAssociationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeAssociationRepositoryPort $repository;
    private TenantId $tenant1;
    private TenantId $tenant2;
    private MemberId $member1;
    private MemberId $member2;
    private CommitteeId $committee1;
    private CommitteeId $committee2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(CommitteeAssociationRepositoryPort::class);

        // Create actual organisations and committees for FK integrity
        $tenantId1 = (string) \Illuminate\Support\Str::uuid();
        $tenantId2 = (string) \Illuminate\Support\Str::uuid();
        $committeeId1 = (string) \Illuminate\Support\Str::random(26);
        $committeeId2 = (string) \Illuminate\Support\Str::random(26);

        // Set session to tenant1 for global scope filter
        // Tests that query tenant1 will match; tenant2 tests will set session separately
        session(['current_organisation_id' => $tenantId1]);

        \DB::table('organisations')->insert([
            'id' => $tenantId1,
            'name' => 'Test Organisation 1',
            'slug' => 'test-org-1',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        \DB::table('organisations')->insert([
            'id' => $tenantId2,
            'name' => 'Test Organisation 2',
            'slug' => 'test-org-2',
            'type' => 'tenant',
            'is_default' => false,
        ]);

        \DB::table('committees')->insert([
            'id' => $committeeId1,
            'organisation_id' => $tenantId1,
            'name' => 'Committee 1',
            'slug' => 'committee-1',
            'code' => 'COMM1',
            'type' => 'local',
            'level' => 1,
            'status' => 'active',
        ]);
        \DB::table('committees')->insert([
            'id' => $committeeId2,
            'organisation_id' => $tenantId1,
            'name' => 'Committee 2',
            'slug' => 'committee-2',
            'code' => 'COMM2',
            'type' => 'regional',
            'level' => 2,
            'status' => 'active',
        ]);

        $this->tenant1 = TenantId::fromString($tenantId1);
        $this->tenant2 = TenantId::fromString($tenantId2);
        $this->member1 = MemberId::fromString((string) \Illuminate\Support\Str::uuid());
        $this->member2 = MemberId::fromString((string) \Illuminate\Support\Str::uuid());
        $this->committee1 = CommitteeId::fromString($committeeId1);
        $this->committee2 = CommitteeId::fromString($committeeId2);
    }

    // ===== IDENTITY TESTS =====

    public function test_association_has_explicit_identity(): void
    {
        $association = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($association, $this->tenant1);

        $actives = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertCount(1, $actives);

        // Verify it persisted to database
        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $this->tenant1->value(),
            'member_id' => $this->member1->value(),
            'committee_id' => $this->committee1->value(),
            'status' => 'active',
        ]);
    }

    // ===== GOVERNANCE INVARIANT: DUPLICATE ACTIVE PREVENTION =====

    public function test_member_cannot_have_two_active_associations_for_same_committee(): void
    {
        $active1 = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($active1, $this->tenant1);

        // Attempt second active association for same member+committee
        $active2 = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::MANUAL,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        // This MUST throw — governance invariant violation
        try {
            $this->repository->saveForTenant($active2, $this->tenant1);
            $this->fail('Expected DomainException for duplicate active association');
        } catch (\DomainException $e) {
            $this->assertStringContainsString('already has an active association', $e->getMessage());
        }
    }

    // ===== GOVERNANCE INVARIANT: HISTORICAL PRESERVATION =====

    public function test_terminated_association_remains_queryable_in_history(): void
    {
        $association = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            status: MembershipStatus::TERMINATED,
        );

        $this->repository->saveForTenant($association, $this->tenant1);

        // Terminated is NOT visible in active queries
        $actives = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertCount(0, $actives);

        // But it IS persisted to database as immutable historical record
        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $this->tenant1->value(),
            'member_id' => $this->member1->value(),
            'committee_id' => $this->committee1->value(),
            'status' => 'terminated',
        ]);
    }

    // ===== GOVERNANCE INVARIANT: REAPPLICATION =====

    public function test_member_can_reapply_after_termination_creating_new_association(): void
    {
        // First association (terminated)
        $terminated = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            status: MembershipStatus::TERMINATED,
        );

        $this->repository->saveForTenant($terminated, $this->tenant1);

        // Reapplication creates NEW record with NEW identity (not overwrite)
        $reapplied = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::MANUAL,
            associatedAt: \DateTimeImmutable::createFromMutable(now()->addDay()),
            
        );

        // This MUST succeed — new association, not overwrite
        $this->repository->saveForTenant($reapplied, $this->tenant1);

        // Member now has 1 ACTIVE
        $actives = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertCount(1, $actives);

        // But history still shows terminated record
        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $this->tenant1->value(),
            'member_id' => $this->member1->value(),
            'committee_id' => $this->committee1->value(),
            'status' => 'terminated',
        ]);

        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $this->tenant1->value(),
            'member_id' => $this->member1->value(),
            'committee_id' => $this->committee1->value(),
            'status' => 'active',
        ]);
    }

    // ===== GOVERNANCE INVARIANT: SUSPENSION REVERSIBILITY =====

    public function test_suspended_association_can_be_reactivated(): void
    {
        $suspended = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            status: MembershipStatus::SUSPENDED,
        );

        $this->repository->saveForTenant($suspended, $this->tenant1);

        // Suspended is NOT visible in active queries
        $actives = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertCount(0, $actives);

        // Reactivation (same association, status changed)
        $reactivated = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        // This MUST be allowed via lifecycle transition, not as overwrite
        // (implementation detail: may update in-place or create new record — domain decides)
        $this->repository->saveForTenant($reactivated, $this->tenant1);

        // Now visible in active queries
        $actives = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertCount(1, $actives);
    }

    // ===== GOVERNANCE INVARIANT: TERMINAL IMMUTABILITY =====

    public function test_terminated_association_cannot_be_reactivated(): void
    {
        $terminated = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            status: MembershipStatus::TERMINATED,
        );

        $this->repository->saveForTenant($terminated, $this->tenant1);

        // Attempt to reactivate (governance violation)
        $reactivated = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        // This MUST throw — TERMINATED is terminal
        try {
            $this->repository->saveForTenant($reactivated, $this->tenant1);
            $this->fail('Expected DomainException for reactivating terminated association');
        } catch (\DomainException $e) {
            $this->assertStringContainsString('Cannot transition from terminated', $e->getMessage());
        }
    }

    // ===== AUDIT ENFORCEMENT =====

    public function test_suspension_requires_actor_and_reason(): void
    {
        $active = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($active, $this->tenant1);

        // TODO: Implement suspension with mandatory audit
        // This test documents the requirement
        $this->markTestSkipped('Suspension with audit enforcement requires update to repository API');
    }

    public function test_termination_requires_actor_and_reason(): void
    {
        $active = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($active, $this->tenant1);

        // TODO: Implement termination with mandatory audit
        // This test documents the requirement
        $this->markTestSkipped('Termination with audit enforcement requires update to repository API');
    }

    // ===== VOTING AUTHORIZATION =====

    public function test_only_active_association_grants_voting_rights(): void
    {
        // ACTIVE grants voting
        $active = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($active, $this->tenant1);
        $actives = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertTrue(collect($actives)->contains(
            fn($a) => $a->committeeId->value() === $this->committee1->value()
        ));

        // SUSPENDED does NOT grant voting
        $suspended = CommitteeAssociation::create(
            memberId: $this->member2,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            status: MembershipStatus::SUSPENDED,
        );

        $this->repository->saveForTenant($suspended, $this->tenant1);
        $activesByCommittee = $this->repository->findActiveByCommitteeForTenant($this->committee1, $this->tenant1);
        $this->assertFalse(collect($activesByCommittee)->contains(
            fn($a) => $a->memberId->value() === $this->member2->value()
        ));

        // TERMINATED does NOT grant voting
        $terminated = CommitteeAssociation::create(
            memberId: $this->member2,
            committeeId: $this->committee2,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            status: MembershipStatus::TERMINATED,
        );

        $this->repository->saveForTenant($terminated, $this->tenant1);
        $activesByMember = $this->repository->findActiveByMemberForTenant($this->member2, $this->tenant1);
        $this->assertEmpty($activesByMember);
    }

    // ===== TENANT ISOLATION =====

    public function test_tenant_isolation_prevents_cross_tenant_read(): void
    {
        $assoc1 = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($assoc1, $this->tenant1);

        // Same member, same committee, different tenant
        $assoc2 = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );

        $this->repository->saveForTenant($assoc2, $this->tenant2);

        // Tenant 1 sees only its own (session already set to tenant1)
        $tenant1Result = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant1);
        $this->assertCount(1, $tenant1Result);
        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $this->tenant1->value(),
            'member_id' => $this->member1->value(),
            'status' => 'active',
        ]);

        // Tenant 2 sees only its own (switch session to tenant2)
        session(['current_organisation_id' => $this->tenant2->value()]);
        $tenant2Result = $this->repository->findActiveByMemberForTenant($this->member1, $this->tenant2);
        $this->assertCount(1, $tenant2Result);
        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $this->tenant2->value(),
            'member_id' => $this->member1->value(),
            'status' => 'active',
        ]);
    }

    public function test_committee_query_does_not_leak_cross_tenant_associations(): void
    {
        $tenantId3 = (string) \Illuminate\Support\Str::uuid();
        \DB::table('organisations')->insert([
            'id' => $tenantId3,
            'name' => 'Test Organisation 3',
            'slug' => 'test-org-3',
            'type' => 'tenant',
            'is_default' => false,
        ]);
        $tenant3 = TenantId::fromString($tenantId3);

        // Tenant 1 has association
        $assoc1 = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $this->committee1,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );
        $this->repository->saveForTenant($assoc1, $this->tenant1);

        // Tenant 3 (unrelated) also has association for same member but different committee
        $unrelatedCommittee = CommitteeId::fromString((string) \Illuminate\Support\Str::random(26));
        \DB::table('committees')->insert([
            'id' => $unrelatedCommittee->value(),
            'organisation_id' => $tenantId3,
            'name' => 'Unrelated Committee',
            'slug' => 'unrelated-committee',
            'code' => 'UNREL',
            'type' => 'local',
            'level' => 1,
            'status' => 'active',
        ]);

        $assoc3 = CommitteeAssociation::create(
            memberId: $this->member1,
            committeeId: $unrelatedCommittee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: \DateTimeImmutable::createFromMutable(now()),
            
        );
        $this->repository->saveForTenant($assoc3, $tenant3);

        // Tenant 1's query for its committee MUST NOT leak Tenant 3's associations
        // Session is still set to tenant1, so this should work
        $tenant1Members = $this->repository->findActiveByCommitteeForTenant($this->committee1, $this->tenant1);
        $this->assertCount(1, $tenant1Members);
        $this->assertSame($this->member1->value(), $tenant1Members[0]->memberId->value());

        // Verify cross-tenant association is isolated in DB
        $this->assertDatabaseHas('committee_associations', [
            'organisation_id' => $tenantId3,
            'member_id' => $this->member1->value(),
            'status' => 'active',
        ]);

        // Verify tenant 1 query doesn't see tenant 3's data
        $this->assertDatabaseMissing('committee_associations', [
            'organisation_id' => $this->tenant1->value(),
            'member_id' => $this->member1->value(),
            'committee_id' => $unrelatedCommittee->value(),
        ]);
    }
}
