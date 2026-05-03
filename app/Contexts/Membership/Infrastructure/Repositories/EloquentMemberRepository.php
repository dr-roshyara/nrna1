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
            ->where('id', $id->value())
            ->where('organisation_id', $tenantId->value())
            ->first();

        if (!$record) {
            return null;
        }

        return $this->reconstitute($record);
    }

    public function save(Member $member, TenantId $tenantId): void
    {
        $personalInfo = $member->getPersonalInfo();

        $model = $this->model
            ->withoutGlobalScopes()
            ->where('id', $member->getId()->value())
            ->where('organisation_id', $tenantId->value())
            ->firstOrCreate(
                [
                    'id' => $member->getId()->value(),
                    'organisation_id' => $tenantId->value(),
                ]
            );

        $model->personal_info = json_encode([
            'fullName' => $personalInfo->getFullName(),
            'email' => $personalInfo->getEmail(),
            'phone' => $personalInfo->getPhone(),
        ]);
        $model->membership_type_id = $member->getMembershipTypeId()->value();
        $model->status = $member->getStatus()->value();

        $model->save();
    }

    public function findByStatusForTenant(MemberStatus $status, TenantId $tenantId): array
    {
        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
            ->where('status', $status->value())
            ->get();

        return $records->map(fn($record) => $this->reconstitute($record))->all();
    }

    public function findExpiringForTenant(TenantId $tenantId, int $withinDays): array
    {
        $expiryDate = now()->addDays($withinDays)->toDateString();

        $records = $this->model
            ->withoutGlobalScopes()
            ->where('organisation_id', $tenantId->value())
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
            TenantId::fromOrganisationId($record->organisation_id)
        );
    }
}
