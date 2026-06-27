<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Fee;

use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\FeeStatus;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Fee\Events\FeeWaived;
use App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FeeTest extends TestCase
{
    private FeeId $feeId;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->feeId = FeeId::generate();
        $this->memberId = MemberId::generate();
        $this->membershipTypeId = MembershipTypeId::generate();
        $this->tenantId = TenantId::fromOrganisationId('11111111-1111-1111-1111-111111111111');
    }

    /** @test */
    public function fee_starts_as_pending(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $this->assertTrue($fee->getStatus()->isPending());
    }

    /** @test */
    public function mark_as_paid_with_payment_details_transitions_status(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: 'TXN-123',
            recordedByUserId: 'user-456'
        );

        $fee->markAsPaid($payment);

        $this->assertTrue($fee->getStatus()->isPaid());
    }

    /** @test */
    public function mark_as_paid_stores_payment_details_on_fee(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $payment = new PaymentDetails(
            method: 'card',
            paidAt: $paidAt = new DateTimeImmutable(),
            transactionReference: 'TXN-789',
            recordedByUserId: 'user-111'
        );

        $fee->markAsPaid($payment);

        $this->assertNotNull($fee->getPaymentDetails());
        $this->assertEquals('card', $fee->getPaymentDetails()->method);
        $this->assertEquals($paidAt, $fee->getPaymentDetails()->paidAt);
        $this->assertEquals('TXN-789', $fee->getPaymentDetails()->transactionReference);
        $this->assertEquals('user-111', $fee->getPaymentDetails()->recordedByUserId);
    }

    /** @test */
    public function mark_as_paid_fires_fee_paid_event(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $payment = new PaymentDetails(
            method: 'cash',
            paidAt: new DateTimeImmutable(),
            transactionReference: null,
            recordedByUserId: null
        );

        $fee->markAsPaid($payment);

        $events = $fee->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(FeePaid::class, $events[0]);
    }

    /** @test */
    public function mark_as_paid_on_already_paid_fee_throws_domain_exception(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: null,
            recordedByUserId: null
        );

        $fee->markAsPaid($payment);

        $this->expectException(\DomainException::class);
        $fee->markAsPaid($payment);
    }

    /** @test */
    public function mark_as_paid_on_overdue_fee_is_allowed(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Simulate fee being marked overdue (domain logic elsewhere)
        $reflectionProperty = new \ReflectionProperty($fee, 'status');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($fee, FeeStatus::overdue());

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: null,
            recordedByUserId: null
        );

        // Should not throw
        $fee->markAsPaid($payment);

        $this->assertTrue($fee->getStatus()->isPaid());
    }

    /** @test */
    public function waive_transitions_status_to_waived(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $fee->waive('Test waiver reason');

        $this->assertTrue($fee->getStatus()->isWaived());
    }

    /** @test */
    public function waive_on_paid_fee_throws_domain_exception(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: null,
            recordedByUserId: null
        );

        $fee->markAsPaid($payment);

        $this->expectException(\DomainException::class);
        $fee->waive('Cannot waive paid fee');
    }

    /** @test */
    public function waive_on_overdue_fee_throws_domain_exception(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        // Simulate fee being marked overdue
        $reflectionProperty = new \ReflectionProperty($fee, 'status');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($fee, FeeStatus::overdue());

        $this->expectException(\DomainException::class);
        $fee->waive('Cannot waive overdue fee');
    }

    /** @test */
    public function waive_fires_fee_waived_event_with_reason(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $reason = 'Hardship exemption';
        $fee->waive($reason);

        $events = $fee->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(FeeWaived::class, $events[0]);
        $this->assertEquals($reason, $events[0]->getReason());
    }

    /** @test */
    public function paid_fee_has_payment_details(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $this->assertNull($fee->getPaymentDetails());

        $payment = new PaymentDetails(
            method: 'bank_transfer',
            paidAt: new DateTimeImmutable(),
            transactionReference: null,
            recordedByUserId: null
        );

        $fee->markAsPaid($payment);

        $this->assertNotNull($fee->getPaymentDetails());
    }

    /** @test */
    public function unpaid_fee_has_no_payment_details(): void
    {
        $fee = Fee::create(
            $this->memberId,
            $this->membershipTypeId,
            $this->tenantId,
            '100.00',
            new DateTimeImmutable('2026-06-03')
        );

        $this->assertNull($fee->getPaymentDetails());
    }
}
