<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Application;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Traits\RecordsEvents;
use App\Contexts\Membership\Domain\Application\Events\ApplicationSubmitted;
use App\Contexts\Membership\Domain\Application\Events\ApplicationApproved;
use App\Contexts\Membership\Domain\Application\Events\ApplicationRejected;
use DateTimeImmutable;

final class Application
{
    use RecordsEvents;

    private ApplicationId $id;
    private ApplicationStatus $status;
    private TenantId $tenantId;
    private MemberId $memberId;
    private MembershipTypeId $membershipTypeId;

    private function __construct(
        ApplicationId $id,
        ApplicationStatus $status,
        TenantId $tenantId,
        MemberId $memberId,
        MembershipTypeId $membershipTypeId
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->tenantId = $tenantId;
        $this->memberId = $memberId;
        $this->membershipTypeId = $membershipTypeId;
    }

    public static function submit(
        TenantId $tenantId,
        MemberId $memberId,
        MembershipTypeId $membershipTypeId
    ): self {
        $application = new self(
            ApplicationId::generate(),
            ApplicationStatus::submitted(),
            $tenantId,
            $memberId,
            $membershipTypeId
        );

        $application->recordThat(new ApplicationSubmitted(
            $application->id,
            $tenantId,
            $memberId,
            $membershipTypeId,
            new DateTimeImmutable()
        ));

        return $application;
    }

    public static function reconstitute(
        ApplicationId $id,
        ApplicationStatus $status,
        TenantId $tenantId,
        MemberId $memberId,
        MembershipTypeId $membershipTypeId
    ): self {
        return new self($id, $status, $tenantId, $memberId, $membershipTypeId);
    }

    public function approve(): void
    {
        if (!$this->status->isSubmitted()) {
            throw new \Exception('Only submitted applications can be approved');
        }

        $this->status = ApplicationStatus::approved();

        $this->recordThat(new ApplicationApproved(
            $this->id,
            new DateTimeImmutable()
        ));
    }

    public function reject(string $reason = ''): void
    {
        if ($this->status->isRejected()) {
            throw new \Exception('Application is already rejected');
        }

        $this->status = ApplicationStatus::rejected();

        $this->recordThat(new ApplicationRejected(
            $this->id,
            $reason,
            new DateTimeImmutable()
        ));
    }

    public function getId(): ApplicationId
    {
        return $this->id;
    }

    public function getStatus(): ApplicationStatus
    {
        return $this->status;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getMemberId(): MemberId
    {
        return $this->memberId;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }

    public function pullEvents(): array
    {
        $events = $this->getRecordedEvents();
        $this->clearRecordedEvents();
        return $events;
    }
}
