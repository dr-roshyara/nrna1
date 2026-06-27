<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Fee;

use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\FeeStatus;
use App\Contexts\Membership\Domain\Fee\Services\PaymentPolicy;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PaymentPolicyTest extends TestCase
{
    private PaymentPolicy $policy;
    private FeeId $feeId;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new PaymentPolicy();
        $this->feeId = FeeId::generate();
        $this->memberId = MemberId::generate();
        $this->membershipTypeId = MembershipTypeId::generate();
        $this->tenantId = TenantId::fromOrganisationId('11111111-1111-1111-1111-111111111111');
    }

    /** @test */
    public function allows_pending_fee_for_matching_tenant(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Should not throw
        $this->policy->assertCanRecord(
            $fee,
            $this->tenantId,
            'TXN-123',
            $this->createMockRepository(null) // no existing payment
        );

        $this->assertTrue(true);
    }

    /** @test */
    public function allows_overdue_fee_for_matching_tenant(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Mark as overdue
        $reflectionProperty = new \ReflectionProperty($fee, 'status');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($fee, FeeStatus::overdue());

        // Should not throw
        $this->policy->assertCanRecord(
            $fee,
            $this->tenantId,
            'TXN-456',
            $this->createMockRepository(null)
        );

        $this->assertTrue(true);
    }

    /** @test */
    public function throws_when_fee_belongs_to_different_tenant(): void
    {
        $differentTenant = TenantId::fromOrganisationId('22222222-2222-2222-2222-222222222222');
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('does not belong to this tenant');

        $this->policy->assertCanRecord(
            $fee,
            $differentTenant,
            'TXN-123',
            $this->createMockRepository(null)
        );
    }

    /** @test */
    public function throws_when_fee_is_already_paid(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Mark as paid (set status via reflection since we can't call markAsPaid without PaymentDetails yet)
        $reflectionProperty = new \ReflectionProperty($fee, 'status');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($fee, FeeStatus::paid());

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('cannot be paid');

        $this->policy->assertCanRecord(
            $fee,
            $this->tenantId,
            'TXN-123',
            $this->createMockRepository(null)
        );
    }

    /** @test */
    public function throws_when_transaction_reference_already_exists(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $transactionRef = 'TXN-DUPLICATE';

        // Mock repository that finds existing fee by transaction reference
        $existingFee = Fee::create(
            MemberId::generate(),
            MembershipTypeId::generate(),
            $this->tenantId,
            '50.00',
            new DateTimeImmutable('2026-06-03')
        );

        $mockRepository = $this->createMock(\App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface::class);
        $mockRepository->method('findByTransactionReference')
            ->with($transactionRef, $this->tenantId)
            ->willReturn($existingFee);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('already recorded');

        $this->policy->assertCanRecord(
            $fee,
            $this->tenantId,
            $transactionRef,
            $mockRepository
        );
    }

    /** @test */
    public function allows_null_transaction_reference_without_duplicate_check(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Should not throw or check for duplicates
        $this->policy->assertCanRecord(
            $fee,
            $this->tenantId,
            null,
            $this->createMockRepository(null)
        );

        $this->assertTrue(true);
    }

    private function createMockRepository(?Fee $findByReferenceResult): \App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface
    {
        $mock = $this->createMock(\App\Contexts\Membership\Domain\Repositories\FeeRepositoryInterface::class);
        $mock->method('findByTransactionReference')->willReturn($findByReferenceResult);
        return $mock;
    }
}
