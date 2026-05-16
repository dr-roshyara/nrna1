<?php

declare(strict_types=1);

namespace Tests\Support\Builders;

use Tests\Support\DomainIdFactory;
use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

/**
 * FeeBuilder — Creates valid Fee domain aggregates
 *
 * Enforces:
 * - Valid payment methods only: ['bank_transfer', 'cash', 'card']
 * - Complete amount and due date
 * - Proper tenant context
 */
final class FeeBuilder
{
    private FeeId $feeId;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;
    private TenantId $tenantId;
    private string $amount = '100.00';
    private string $paymentMethod = 'bank_transfer';
    private DateTimeImmutable $dueDate;

    public static function new(): self
    {
        return new self();
    }

    private function __construct()
    {
        $this->feeId = DomainIdFactory::fee();
        $this->memberId = DomainIdFactory::member();
        $this->membershipTypeId = DomainIdFactory::membershipType();
        $this->tenantId = DomainIdFactory::tenant();
        $this->dueDate = new DateTimeImmutable('2026-12-31');
    }

    public function forMember(MemberId $memberId, TenantId $tenantId): self
    {
        $this->memberId = $memberId;
        $this->tenantId = $tenantId;
        return $this;
    }

    public function withAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function withPaymentMethod(string $method): self
    {
        // Enforce domain validation
        if (!in_array($method, ['bank_transfer', 'cash', 'card'], true)) {
            throw new \DomainException("Invalid payment method: {$method}. Must be one of: bank_transfer, cash, card");
        }
        $this->paymentMethod = $method;
        return $this;
    }

    public function withDueDate(DateTimeImmutable $date): self
    {
        $this->dueDate = $date;
        return $this;
    }

    /**
     * Build domain aggregate (not persisted yet)
     * Returns Fee domain object ready for markAsPaid() or other operations
     */
    public function build(): Fee
    {
        return Fee::create(
            memberId: $this->memberId,
            membershipTypeId: $this->membershipTypeId,
            tenantId: $this->tenantId,
            amount: $this->amount,
            dueDate: $this->dueDate
        );
    }

    public function getFeeId(): FeeId
    {
        return $this->feeId;
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
