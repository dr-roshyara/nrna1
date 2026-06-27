<?php

declare(strict_types=1);

namespace Tests\Unit\Membership\Application;

use App\Contexts\Membership\Application\Projections\MemberDirectoryProjector;
use App\Contexts\Membership\Domain\Events\MemberRegistered;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

/**
 * MemberDirectoryProjection — TDD RED Phase
 *
 * This test suite defines the contract for the projection layer.
 * All tests must FAIL before implementation begins.
 */
final class MemberDirectoryProjectionTest extends TestCase
{
    private MemberDirectoryProjector $projector;
    private TenantId $tenantId;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;

    protected function setUp(): void
    {
        $this->projector = new MemberDirectoryProjector();
        $this->tenantId = TenantId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $this->memberId = MemberId::fromString('650e8400-e29b-41d4-a716-446655440000');
        $this->membershipTypeId = MembershipTypeId::fromString('750e8400-e29b-41d4-a716-446655440000');
    }

    /**
     * TEST 1: Event → Projection Model (Core Contract)
     *
     * Given: MemberRegistered event
     * When: Projector transforms it
     * Then: Returns MemberDirectory object with correct fields
     */
    public function test_projector_transforms_member_registered_to_directory_model(): void
    {
        $event = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'John Doe',
            email: 'john.doe@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $model = $this->projector->fromMemberRegistered($event);

        $this->assertNotNull($model);
        $this->assertEquals($this->memberId->value(), $model->memberId);
        $this->assertEquals($this->tenantId->value(), $model->tenantId);
        $this->assertEquals('John Doe', $model->displayName);
        $this->assertEquals('john.doe@example.com', $model->email);
        $this->assertEquals('ACTIVE', $model->status);
        $this->assertEquals($this->membershipTypeId->value(), $model->membershipTypeId);
        $this->assertEquals('Standard', $model->membershipTypeName);
    }

    /**
     * TEST 2: Model Has Correct Default State
     *
     * Given: Newly projected member
     * Then: Default status is ACTIVE
     */
    public function test_newly_projected_member_has_active_status(): void
    {
        $event = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'Jane Doe',
            email: 'jane.doe@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Premium',
            organisationUserId: '950e8400-e29b-41d4-a716-446655440000',
        );

        $model = $this->projector->fromMemberRegistered($event);

        $this->assertEquals('ACTIVE', $model->status);
    }

    /**
     * TEST 3: Tenant Isolation in Projection
     *
     * Given: Two events from different tenants
     * When: Projected
     * Then: Each has correct tenant_id
     */
    public function test_projection_respects_tenant_isolation(): void
    {
        $tenant1 = TenantId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $tenant2 = TenantId::fromString('660e8400-e29b-41d4-a716-446655440000');

        $event1 = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $tenant1,
            displayName: 'Member 1',
            email: 'member1@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $event2 = new MemberRegistered(
            memberId: MemberId::fromString('770e8400-e29b-41d4-a716-446655440000'),
            tenantId: $tenant2,
            displayName: 'Member 2',
            email: 'member2@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '950e8400-e29b-41d4-a716-446655440000',
        );

        $model1 = $this->projector->fromMemberRegistered($event1);
        $model2 = $this->projector->fromMemberRegistered($event2);

        $this->assertEquals($tenant1->value(), $model1->tenantId);
        $this->assertEquals($tenant2->value(), $model2->tenantId);
        $this->assertNotEquals($model1->tenantId, $model2->tenantId);
    }

    /**
     * TEST 4: Email Normalization (Lowercase)
     *
     * Given: Event with mixed-case email
     * Then: Projection stores lowercase
     */
    public function test_projection_normalizes_email_to_lowercase(): void
    {
        $event = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'John Doe',
            email: 'John.Doe@Example.COM',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $model = $this->projector->fromMemberRegistered($event);

        $this->assertEquals('john.doe@example.com', $model->email);
    }

    /**
     * TEST 5: All Required Fields Present
     *
     * Given: MemberRegistered event with all fields
     * Then: Projection model has all fields (no nulls)
     */
    public function test_projection_model_has_all_required_fields(): void
    {
        $event = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'John Doe',
            email: 'john.doe@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $model = $this->projector->fromMemberRegistered($event);

        $this->assertNotNull($model->memberId);
        $this->assertNotNull($model->tenantId);
        $this->assertNotNull($model->displayName);
        $this->assertNotNull($model->email);
        $this->assertNotNull($model->status);
        $this->assertNotNull($model->membershipTypeId);
        $this->assertNotNull($model->membershipTypeName);
        $this->assertNotNull($model->organisationUserId);
    }

    /**
     * TEST 6: Immutability of Projection Model
     *
     * Given: Projected model
     * Then: Properties cannot be changed (readonly)
     */
    public function test_projection_model_is_immutable(): void
    {
        $event = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'John Doe',
            email: 'john.doe@example.com',
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $model = $this->projector->fromMemberRegistered($event);

        $this->expectException(\Error::class);
        $model->displayName = 'Jane Doe';
    }

    /**
     * TEST 7: Event Contract Validation
     *
     * Given: Event with missing required field
     * Then: Projection raises exception
     */
    public function test_projector_rejects_event_with_missing_email(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'John Doe',
            email: '',  // Empty email — should reject
            membershipTypeId: $this->membershipTypeId,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );
    }

    /**
     * TEST 8: No Domain Logic Leakage
     *
     * Given: Projector
     * Then: It has NO dependency on Member aggregate, repository, or service
     */
    public function test_projector_has_no_domain_service_dependencies(): void
    {
        // Verify projector constructor has zero dependencies
        $reflection = new \ReflectionClass(MemberDirectoryProjector::class);
        $constructor = $reflection->getConstructor();

        $this->assertNull($constructor, 'Projector must have no constructor dependencies');
    }

    /**
     * TEST 9: Different Membership Types Preserved
     *
     * Given: Events for different membership types
     * When: Projected
     * Then: Each preserves its type and name
     */
    public function test_projection_preserves_membership_type_variations(): void
    {
        $standardType = MembershipTypeId::fromString('750e8400-e29b-41d4-a716-446655440000');
        $premiumType = MembershipTypeId::fromString('860e8400-e29b-41d4-a716-446655440000');

        $standardEvent = new MemberRegistered(
            memberId: $this->memberId,
            tenantId: $this->tenantId,
            displayName: 'Standard Member',
            email: 'standard@example.com',
            membershipTypeId: $standardType,
            membershipTypeName: 'Standard',
            organisationUserId: '850e8400-e29b-41d4-a716-446655440000',
        );

        $premiumEvent = new MemberRegistered(
            memberId: MemberId::fromString('970e8400-e29b-41d4-a716-446655440000'),
            tenantId: $this->tenantId,
            displayName: 'Premium Member',
            email: 'premium@example.com',
            membershipTypeId: $premiumType,
            membershipTypeName: 'Premium',
            organisationUserId: '950e8400-e29b-41d4-a716-446655440000',
        );

        $standardModel = $this->projector->fromMemberRegistered($standardEvent);
        $premiumModel = $this->projector->fromMemberRegistered($premiumEvent);

        $this->assertEquals('Standard', $standardModel->membershipTypeName);
        $this->assertEquals('Premium', $premiumModel->membershipTypeName);
        $this->assertNotEquals($standardModel->membershipTypeId, $premiumModel->membershipTypeId);
    }
}
