<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Fee;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Traits\RecordsEvents;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Domain\Fee\Events\FeeOverdue;
use DateTimeImmutable;

final class Fee
{
    use RecordsEvents;

    private FeeId $id;
    private MemberId $memberId;
    private FeeStatus $status;
    private TenantId $tenantId;
    private string $amount;
    private DateTimeImmutable $dueDate;

    private function __construct(
        FeeId $id,
        MemberId $memberId,
        FeeStatus $status,
        TenantId $tenantId,
        string $amount,
        DateTimeImmutable $dueDate
    ) {
        $this->id = $id;
        $this->memberId = $memberId;
        $this->status = $status;
        $this->tenantId = $tenantId;
        $this->amount = $amount;
        $this->dueDate = $dueDate;
    }

    public static function create(
        MemberId $memberId,
        TenantId $tenantId,
        string $amount,
        DateTimeImmutable $dueDate
    ): self {
        return new self(
            FeeId::generate(),
            $memberId,
            FeeStatus::pending(),
            $tenantId,
            $amount,
            $dueDate
        );
    }

    public static function reconstitute(
        FeeId $id,
        MemberId $memberId,
        FeeStatus $status,
        TenantId $tenantId,
        string $amount,
        DateTimeImmutable $dueDate
    ): self {
        return new self($id, $memberId, $status, $tenantId, $amount, $dueDate);
    }

    public function markAsPaid(): void
    {
        if ($this->status->isPaid()) {
            throw new \Exception('Fee is already paid');
        }

        $this->status = FeeStatus::paid();

        $this->recordThat(new FeePaid($this->id, new DateTimeImmutable()));
    }

    public function markAsOverdue(): void
    {
        if ($this->status->isOverdue()) {
            throw new \Exception('Fee is already marked overdue');
        }

        $this->status = FeeStatus::overdue();

        $this->recordThat(new FeeOverdue($this->id, new DateTimeImmutable()));
    }

    public function waive(): void
    {
        $this->status = FeeStatus::waived();
    }

    public function getId(): FeeId
    {
        return $this->id;
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
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

    public function pullEvents(): array
    {
        $events = $this->getRecordedEvents();
        $this->clearRecordedEvents();
        return $events;
    }
}
