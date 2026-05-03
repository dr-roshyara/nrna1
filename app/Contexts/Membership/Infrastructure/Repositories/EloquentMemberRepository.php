<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Member\MemberStatus;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Infrastructure\Models\MemberContextModel;
use DateTimeImmutable;

final class EloquentMemberRepository implements MemberRepositoryInterface
{
    public function __construct(private MemberContextModel $model) {}

    public function find(MemberId $id, TenantId $tenantId): ?Member
    {
        $record = $this->model
            ->withoutGlobalScopes()
            ->where('id', $id->toString())
            ->where('organisation_id', $tenantId->toString())
            ->first();

        if (!$record) {
            return null;
        }

        return $this->reconstitute($record);
    }

    public function save(Member $member, TenantId $tenantId): void
    {
        $personalInfo = $member->getPersonalInfo();
        
        $this->model->withoutGlobalScopes()->updateOrCreate(
            [
                'id' => $member->getId()->toString(),
                'organisation_id' => $tenantId->toString(),
            ],
            [
                'personal_info' => json_encode([
                    'fullName' => $personalInfo->getFullName(),
                    'email' => $personalInfo->getEmail(),
                    'phone' => $personalInfo->getPhone(),
                ]),
                'membership_type_id' => $member->getMembershipTypeId()->toString(),
                'status' => $member->getStatus()->value(),
            ]
        );
    }

    public function findByStatusForTenant(MemberStatus $status, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->toString())
            ->where('status', $status->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findExpiringForTenant(TenantId $tenantId, int $withinDays): array
    {
        $expiryDate = now()->addDays($withinDays)->toDateString();

        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->toString())
            ->where('status', 'active')
            ->whereDate('membership_expires_at', '<=', $expiryDate)
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    private function reconstitute(MemberContextModel $record): Member
    {
        $personalInfoData = json_decode($record->personal_info, true) ?? [];

        $personalInfo = PersonalInfo::create(
            $personalInfoData['fullName'] ?? '',
            $personalInfoData['email'] ?? '',
            $personalInfoData['phone'] ?? ''
        );

        return Member::reconstitute(
            MemberId::fromString($record->id),
            MemberStatus::fromString($record->status),
            $personalInfo,
            MembershipTypeId::fromString($record->membership_type_id),
            TenantId::fromString($record->organisation_id)
        );
    }
}
