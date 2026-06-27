<?php

declare(strict_types=1);

namespace Tests\Support\Scenario;

use Tests\Support\Builders\OrganisationBuilder;
use Tests\Support\Builders\UserBuilder;
use Tests\Support\Builders\OrganisationUserBuilder;
use Tests\Support\Builders\MembershipTypeBuilder;
use Tests\Support\Builders\MemberBuilder;
use Tests\Support\Builders\FeeBuilder;
use Tests\Support\DomainIdFactory;
use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\MemberContextModel;
use Tests\Support\Builders\OrganisationModel;

/**
 * Scenario — Comprehensive test scenario builder
 *
 * Orchestrates:
 * - Organisation creation (FK root)
 * - Member creation (full aggregate with all constraints)
 * - Fee creation (domain aggregate)
 * - Proper tenant isolation
 *
 * Usage:
 *   $scenario = Scenario::create()
 *       ->bootstrapMembership();
 *
 *   $member = $scenario->member;
 *   $tenant = $scenario->tenantId;
 */
final class Scenario
{
    public ?OrganisationModel $organisation = null;
    public $user = null;
    public $organisationUser = null;
    public $membershipType = null;
    public ?MemberContextModel $member = null;
    public ?Fee $fee = null;
    public ?TenantId $tenantId = null;

    public static function create(): self
    {
        return new self();
    }

    /**
     * Bootstrap complete membership scenario
     * Creates: Organisation → User → OrganisationUser → MembershipType → Member → ready for Fee
     */
    public function bootstrapMembership(): self
    {
        // Step 1: Create organisation (FK root)
        $this->organisation = OrganisationBuilder::new()->persist();
        $this->tenantId = TenantId::fromString($this->organisation->id);

        // Step 2: Create user (scoped to organisation)
        $this->user = UserBuilder::forOrganisation($this->organisation->id)->persist();

        // Step 3: Create organisation_user (link user to organisation)
        $this->organisationUser = OrganisationUserBuilder::new(
            $this->organisation->id,
            $this->user->id
        )->persist();

        // Step 4: Create membership type
        $this->membershipType = MembershipTypeBuilder::forOrganisation(
            $this->organisation->id
        )->persist();

        // Step 5: Create member with full constraints
        $this->member = MemberBuilder::new()
            ->withTenant($this->tenantId)
            ->withMembershipType($this->membershipType->id)
            ->withOrganisationUserId($this->organisationUser->id) // Link to organisation_user
            ->persist();

        return $this;
    }

    /**
     * Create fee for current member
     * Requires: bootstrapMembership() called first
     */
    public function createFee(): self
    {
        if (!$this->member || !$this->tenantId) {
            throw new \RuntimeException('Must call bootstrapMembership() before createFee()');
        }

        $this->fee = FeeBuilder::new()
            ->forMember(
                MemberId::fromString($this->member->id),
                $this->tenantId
            )
            ->withPaymentMethod('bank_transfer')
            ->build();

        return $this;
    }

    /**
     * Mark fee as paid (domain operation)
     * Generates FeePaid event
     */
    public function markFeeAsPaid(string $method = 'bank_transfer'): self
    {
        if (!$this->fee) {
            throw new \RuntimeException('Must call createFee() before markFeeAsPaid()');
        }

        $this->fee->markAsPaid(
            new \App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails(
                method: $method,
                paidAt: new \DateTimeImmutable(),
                transactionReference: 'TX-TEST-' . uniqid(),
                recordedByUserId: 'test-user'
            )
        );

        return $this;
    }

    /**
     * Get recorded domain events from fee
     */
    public function getFeeEvents(): array
    {
        if (!$this->fee) {
            return [];
        }
        return $this->fee->pullEvents();
    }

    /**
     * Snapshot current member state
     */
    public function memberSnapshot(): array
    {
        if (!$this->member) {
            return [];
        }

        return [
            'id' => $this->member->id,
            'status' => $this->member->status,
            'fee_state' => $this->member->fee_state,
            'organisation_id' => $this->member->organisation_id,
        ];
    }
}
