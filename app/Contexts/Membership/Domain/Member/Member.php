<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member;

use App\Contexts\Membership\Domain\Member\ValueObjects\MemberResidenceGeoIdentity;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\Member\ValueObjects\FeeState;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\Member\Events\MemberRegistered;
use App\Contexts\Membership\Domain\Member\Events\MemberActivated;
use App\Contexts\Membership\Domain\Member\Events\MemberSuspended;
use App\Contexts\Membership\Domain\Member\Events\MemberArchived;
use App\Contexts\Membership\Domain\Traits\RecordsEvents;
use DateTimeImmutable;

final class Member
{
    use RecordsEvents;

    private MemberId $id;
    private MemberStatus $status;
    private PersonalInfo $personalInfo;
    private MembershipTypeId $membershipTypeId;
    private TenantId $tenantId;
    private ?MemberResidenceGeoIdentity $residenceGeoIdentity = null;
    private FeeState $feeState = FeeState::UNPAID;

    private function __construct(
        MemberId $id,
        MemberStatus $status,
        PersonalInfo $personalInfo,
        MembershipTypeId $membershipTypeId,
        TenantId $tenantId,
        ?MemberResidenceGeoIdentity $residenceGeoIdentity = null,
        FeeState $feeState = FeeState::UNPAID
    ) {
        $this->id = $id;
        $this->status = $status;
        $this->personalInfo = $personalInfo;
        $this->membershipTypeId = $membershipTypeId;
        $this->tenantId = $tenantId;
        $this->residenceGeoIdentity = $residenceGeoIdentity;
        $this->feeState = $feeState;
    }

    public static function register(
        TenantId $tenantId,
        PersonalInfo $personalInfo,
        MembershipTypeId $membershipTypeId,
        ?MemberResidenceGeoIdentity $residenceGeoIdentity = null
    ): self {
        $member = new self(
            MemberId::generate(),
            MemberStatus::active(),
            $personalInfo,
            $membershipTypeId,
            $tenantId,
            $residenceGeoIdentity
        );

        $member->recordThat(new MemberRegistered(
            $member->id,
            $tenantId,
            $personalInfo,
            $membershipTypeId,
            new DateTimeImmutable()
        ));

        return $member;
    }

    public static function reconstitute(
        MemberId $id,
        MemberStatus $status,
        PersonalInfo $personalInfo,
        MembershipTypeId $membershipTypeId,
        TenantId $tenantId,
        ?MemberResidenceGeoIdentity $residenceGeoIdentity = null,
        FeeState $feeState = FeeState::UNPAID
    ): self {
        return new self($id, $status, $personalInfo, $membershipTypeId, $tenantId, $residenceGeoIdentity, $feeState);
    }

    public function activate(): void
    {
        if (!$this->status->canTransitionTo(MemberStatus::active())) {
            throw new \Exception('Cannot transition to active status from ' . $this->status->value());
        }

        $this->status = MemberStatus::active();

        $this->recordThat(new MemberActivated($this->id, new DateTimeImmutable()));
    }

    public function suspend(string $reason = ''): void
    {
        if ($this->status->isSuspended()) {
            throw new \Exception('Member is already suspended');
        }

        if (!$this->status->canTransitionTo(MemberStatus::suspended())) {
            throw new \Exception('Cannot transition to suspended status from ' . $this->status->value());
        }

        $this->status = MemberStatus::suspended();

        $this->recordThat(new MemberSuspended($this->id, $reason, new DateTimeImmutable()));
    }

    public function reactivate(): void
    {
        if (!$this->status->isSuspended()) {
            throw new \Exception('Can only reactivate suspended members');
        }

        $this->status = MemberStatus::active();

        $this->recordThat(new MemberActivated($this->id, new DateTimeImmutable()));
    }

    public function archive(): void
    {
        if (!$this->status->canTransitionTo(MemberStatus::archived())) {
            throw new \Exception('Cannot transition to archived status from ' . $this->status->value());
        }

        $this->status = MemberStatus::archived();

        $this->recordThat(new MemberArchived($this->id, new DateTimeImmutable()));
    }

    public function getId(): MemberId
    {
        return $this->id;
    }

    public function getStatus(): MemberStatus
    {
        return $this->status;
    }

    public function getPersonalInfo(): PersonalInfo
    {
        return $this->personalInfo;
    }

    public function getMembershipTypeId(): MembershipTypeId
    {
        return $this->membershipTypeId;
    }

    public function getTenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function getResidenceGeoIdentity(): ?MemberResidenceGeoIdentity
    {
        return $this->residenceGeoIdentity;
    }

    public function setResidenceGeoIdentity(?MemberResidenceGeoIdentity $residenceGeoIdentity): void
    {
        $this->residenceGeoIdentity = $residenceGeoIdentity;
    }

    public function getFeeState(): FeeState
    {
        return $this->feeState;
    }

    public function updateFeeStateSnapshot(FeeState $feeState): void
    {
        $this->feeState = $feeState;
    }

    public function pullEvents(): array
    {
        $events = $this->getRecordedEvents();
        $this->clearRecordedEvents();
        return $events;
    }
}
