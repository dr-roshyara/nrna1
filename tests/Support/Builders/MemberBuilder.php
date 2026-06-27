<?php

declare(strict_types=1);

namespace Tests\Support\Builders;

use Tests\Support\DomainIdFactory;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Infrastructure\Models\MemberContextModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * MemberBuilder — Creates valid Member aggregates with all required fields
 *
 * Enforces:
 * - Valid UUID/ULID for all IDs
 * - Required organisation_user_id (NOT NULL constraint)
 * - Complete personal info JSON
 * - Proper tenant isolation
 */
final class MemberBuilder
{
    private MemberId $memberId;
    private TenantId $tenantId;
    private string $membershipTypeId;
    private string $status = 'active';
    private string $organisationUserId;
    private string $fullName = 'Test Member';
    private string $email = 'test@example.com';
    private string $phone = '+1234567890';
    private string $feeState = 'unpaid';

    public static function new(): self
    {
        return new self();
    }

    private function __construct()
    {
        $this->memberId = DomainIdFactory::member();
        $this->tenantId = DomainIdFactory::tenant();
        $this->membershipTypeId = DomainIdFactory::membershipType()->value();
        $this->organisationUserId = DomainIdFactory::member()->value(); // Link to user
    }

    public function withMemberId(MemberId $id): self
    {
        $this->memberId = $id;
        return $this;
    }

    public function withTenant(TenantId $tenant): self
    {
        $this->tenantId = $tenant;
        return $this;
    }

    public function withMembershipType(string $typeId): self
    {
        $this->membershipTypeId = $typeId;
        return $this;
    }

    public function withStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function withOrganisationUserId(string $userId): self
    {
        $this->organisationUserId = $userId;
        return $this;
    }

    public function withPersonalInfo(string $name, string $email, string $phone): self
    {
        $this->fullName = $name;
        $this->email = $email;
        $this->phone = $phone;
        return $this;
    }

    public function withFeeState(string $state): self
    {
        $this->feeState = $state;
        return $this;
    }

    /**
     * Build and persist member
     * Required: TenantId must be valid and organisation must exist
     */
    public function persist(): MemberContextModel
    {
        return MemberContextModel::create([
            'id' => $this->memberId->value(),
            'organisation_id' => $this->tenantId->value(),
            'organisation_user_id' => $this->organisationUserId,
            'membership_type_id' => $this->membershipTypeId,
            'status' => $this->status,
            'fee_state' => $this->feeState,
            'personal_info' => json_encode([
                'fullName' => $this->fullName,
                'email' => $this->email,
                'phone' => $this->phone,
            ]),
        ]);
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }
}
