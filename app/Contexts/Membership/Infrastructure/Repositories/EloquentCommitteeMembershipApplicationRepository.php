<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipApplication;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeMembershipApplicationModel;

final class EloquentCommitteeMembershipApplicationRepository implements MembershipApplicationRepositoryPort
{
    public function saveForTenant(MembershipApplication $application): void
    {
        CommitteeMembershipApplicationModel::create([
            'id'                    => $this->getId($application),
            'organisation_id'       => $this->getTenantId($application),
            'member_id'             => $this->getMemberId($application),
            'committee_id'          => $this->getCommitteeId($application),
            'reason'                => $this->getReason($application),
            'exception_justification' => $this->getExceptionJustification($application),
            'status'                => $this->getStatus($application),
            'submitted_at'          => $this->getSubmittedAt($application),
        ]);
    }

    public function getOrFailForTenant(
        MembershipApplicationId $id,
        TenantId $tenantId,
    ): MembershipApplication {
        $model = CommitteeMembershipApplicationModel::where('organisation_id', $tenantId->value())
            ->where('id', $id->value())
            ->first();

        if ($model === null) {
            throw new \RuntimeException("MembershipApplication not found: {$id->value()}");
        }

        return $this->hydrate($model);
    }

    public function existsActiveForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): bool {
        return CommitteeMembershipApplicationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->where('committee_id', $committeeId->value())
            ->whereIn('status', [
                ApplicationStatus::SUBMITTED->value,
                ApplicationStatus::UNDER_REVIEW->value,
            ])
            ->exists();
    }

    private function hydrate(CommitteeMembershipApplicationModel $model): MembershipApplication
    {
        return MembershipApplication::reconstitute(
            id:                     MembershipApplicationId::fromString($model->id),
            tenantId:               TenantId::fromOrganisationId($model->organisation_id),
            memberId:               MemberId::fromString($model->member_id),
            committeeId:            CommitteeId::fromString($model->committee_id),
            reason:                 ApplicationReason::from($model->reason),
            exceptionJustification: $model->exception_justification,
            status:                 ApplicationStatus::from($model->status),
            submittedAt:            new \DateTimeImmutable($model->submitted_at->format('c')),
            reviewedBy:             $model->reviewed_by ? MemberId::fromString($model->reviewed_by) : null,
            reviewedAt:             $model->reviewed_at ? new \DateTimeImmutable($model->reviewed_at->format('c')) : null,
        );
    }

    // Reflection-based getters to access private readonly properties
    private function getId(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('id')->getValue($app)->value();
    }

    private function getTenantId(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('tenantId')->getValue($app)->value();
    }

    private function getMemberId(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('memberId')->getValue($app)->value();
    }

    private function getCommitteeId(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('committeeId')->getValue($app)->value();
    }

    private function getReason(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('reason')->getValue($app)->value;
    }

    private function getExceptionJustification(MembershipApplication $app): ?string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('exceptionJustification')->getValue($app);
    }

    private function getStatus(MembershipApplication $app): string
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('status')->getValue($app)->value;
    }

    private function getSubmittedAt(MembershipApplication $app): ?\DateTimeImmutable
    {
        $refl = new \ReflectionClass($app);
        return $refl->getProperty('submittedAt')->getValue($app);
    }
}
