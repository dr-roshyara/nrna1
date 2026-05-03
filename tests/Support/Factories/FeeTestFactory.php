<?php

declare(strict_types=1);

namespace Tests\Support\Factories;

use App\Contexts\Membership\Application\Fee\UseCases\RecordFeePayment;
use App\Contexts\Membership\Application\Fee\UseCases\WaiveFee;
use App\Contexts\Membership\Application\Fee\DTOs\RecordFeePaymentCommand;
use App\Contexts\Membership\Application\Fee\DTOs\WaiveFeeCommand;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Models\Member;
use App\Models\MembershipType;
use DateTimeImmutable;
use Illuminate\Support\Str;

final class FeeTestFactory
{
    private function __construct() {}

    /**
     * Create a paid fee through the proper use case boundary.
     *
     * This ensures the entire domain flow is exercised, not just DB state.
     */
    public static function createPaidFee(array $overrides = []): void
    {
        $tenantId = $overrides['tenantId'] ?? self::defaultTenantId();
        $organisation = $overrides['organisation'] ?? self::defaultOrganisation($tenantId);
        $membershipType = $overrides['membershipType'] ?? self::defaultMembershipType($organisation);
        $member = $overrides['member'] ?? self::defaultMember($organisation, $membershipType);

        // Create fee through domain layer
        $fee = Fee::create(
            MemberId::fromString($member->id),
            MembershipTypeId::fromString($membershipType->id),
            $tenantId,
            $overrides['amount'] ?? '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // ONE baseline save — establishes fee in pending state
        app(FeeRepositoryInterface::class)->save($fee, $tenantId);

        // Record payment through use case (the ONLY valid write path for payments)
        app(RecordFeePayment::class)->execute(
            new RecordFeePaymentCommand(
                feeId: $fee->getId(),
                tenantId: $tenantId,
                paymentMethod: $overrides['paymentMethod'] ?? 'bank_transfer',
                paidAt: $overrides['paidAt'] ?? new DateTimeImmutable(),
                transactionReference: $overrides['transactionReference'] ?? (string) Str::uuid(),
                recordedByUserId: $overrides['recordedByUserId'] ?? null
            )
        );
    }

    /**
     * Create a waived fee through the proper use case boundary.
     */
    public static function createWaivedFee(array $overrides = []): void
    {
        $tenantId = $overrides['tenantId'] ?? self::defaultTenantId();
        $organisation = $overrides['organisation'] ?? self::defaultOrganisation($tenantId);
        $membershipType = $overrides['membershipType'] ?? self::defaultMembershipType($organisation);
        $member = $overrides['member'] ?? self::defaultMember($organisation, $membershipType);

        // Create fee through domain layer
        $fee = Fee::create(
            MemberId::fromString($member->id),
            MembershipTypeId::fromString($membershipType->id),
            $tenantId,
            $overrides['amount'] ?? '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Save to repository
        app(FeeRepositoryInterface::class)->save($fee, $tenantId);

        // Waive through use case (the ONLY valid way to create FeeWaived events)
        app(WaiveFee::class)->execute(
            new WaiveFeeCommand(
                feeId: $fee->getId(),
                tenantId: $tenantId,
                reason: $overrides['reason'] ?? 'Waived for testing',
                waivedByUserId: $overrides['waivedByUserId'] ?? null
            )
        );
    }

    // Private helpers for default test fixtures

    private static function defaultTenantId(): TenantId
    {
        return TenantId::fromOrganisationId('11111111-1111-1111-1111-111111111111');
    }

    private static function defaultOrganisation(TenantId $tenantId): \App\Models\Organisation
    {
        return \App\Models\Organisation::firstOrCreate(
            ['id' => $tenantId->value()],
            [
                'name' => 'Test Organisation',
                'slug' => 'test-organisation',
                'type' => 'tenant',
                'uses_full_membership' => true,
            ]
        );
    }

    private static function defaultMembershipType(\App\Models\Organisation $organisation): MembershipType
    {
        return MembershipType::firstOrCreate(
            ['organisation_id' => $organisation->id, 'slug' => 'standard-member'],
            [
                'name' => 'Standard Member',
                'description' => 'Standard membership type',
                'price' => '50.00',
            ]
        );
    }

    private static function defaultMember(\App\Models\Organisation $organisation, MembershipType $membershipType): Member
    {
        return Member::factory()->create([
            'organisation_id' => $organisation->id,
            'membership_type_id' => $membershipType->id,
            'status' => 'active',
            'fees_status' => 'unpaid',
        ]);
    }
}
