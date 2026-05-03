<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\Traits\RecordsEvents;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Fee\Events\FeeOverdue;
use App\Contexts\Membership\Domain\Fee\Events\FeeWaived;
use App\Contexts\Membership\Domain\Fee\ValueObjects\PaymentDetails;
use DateTimeImmutable;

final class Fee
{
    use RecordsEvents;

    private FeeId $id;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;
    private FeeStatus $status;
    private TenantId $tenantId;
    private string $amount;
    private DateTimeImmutable $dueDate;
    private ?PaymentDetails $paymentDetails = null;

    private function __construct(
        FeeId $id,
        MemberId $memberId,
        MembershipTypeId $membershipTypeId,
        FeeStatus $status,
        TenantId $tenantId,
        string $amount,
        DateTimeImmutable $dueDate
    ) {
        $this->id = $id;
        $this->memberId = $memberId;
        $this->membershipTypeId = $membershipTypeId;
        $this->status = $status;
        $this->tenantId = $tenantId;
        $this->amount = $amount;
        $this->dueDate = $dueDate;
    }

    public static function create(
        MemberId $memberId,
        MembershipTypeId $membershipTypeId,
        TenantId $tenantId,
        string $amount,
        DateTimeImmutable $dueDate
    ): self {
        return new self(
            FeeId::generate(),
            $memberId,
            $membershipTypeId,
            FeeStatus::pending(),
            $tenantId,
            $amount,
            $dueDate
        );
    }

    public static function reconstitute(
        FeeId $id,
        MemberId $memberId,
        MembershipTypeId $membershipTypeId,
        FeeStatus $status,
        TenantId $tenantId,
        string $amount,
        DateTimeImmutable $dueDate,
        ?PaymentDetails $paymentDetails = null
    ): self {
        $instance = new self($id, $memberId, $membershipTypeId, $status, $tenantId, $amount, $dueDate);
        if ($paymentDetails !== null) {
            $instance->paymentDetails = $paymentDetails;
        }
        return $instance;
    }

    public function markAsPaid(PaymentDetails $payment): void
    {
        if (!$this->status->isPending() && !$this->status->isOverdue()) {
            throw new \DomainException('Fee cannot be paid in its current state');
        }

        $this->status = FeeStatus::paid();
        $this->paymentDetails = $payment;

        $this->recordThat(new FeePaid(
            feeId: $this->id,
            amount: $this->amount,
            paymentMethod: $payment->method,
            paidAt: $payment->paidAt,
            transactionReference: $payment->transactionReference,
            recordedByUserId: $payment->recordedByUserId,
            currency: 'EUR',
        ));
    }

    public function markAsOverdue(): void
    {
        if ($this->status->isOverdue()) {
            throw new \Exception('Fee is already marked overdue');
        }

        $this->status = FeeStatus::overdue();

        $this->recordThat(new FeeOverdue($this->id, new DateTimeImmutable()));
    }

    public function waive(string $reason = ''): void
    {
        if (!$this->status->isPending()) {
            throw new \DomainException('Only pending fees can be waived');
        }

        $this->status = FeeStatus::waived();

        $this->recordThat(new FeeWaived($this->id, $reason, new DateTimeImmutable()));
    }

    public function getId(): FeeId
    {
        return $this->id;
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }

    public function getStatus(): FeeStatus
    {
        return $this->status;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getDueDate(): DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getPaymentDetails(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }

    public function pullEvents(): array
    {
        $events = $this->getRecordedEvents();
        $this->clearRecordedEvents();
        return $events;
    }
}
