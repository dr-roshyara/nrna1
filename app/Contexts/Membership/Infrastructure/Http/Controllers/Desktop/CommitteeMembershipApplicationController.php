<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Controllers\Desktop;

use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipCommand;
use App\Contexts\Membership\Application\Membership\ApplyForCommitteeMembership\ApplyForCommitteeMembershipHandler;
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationCommand;
use App\Contexts\Membership\Application\Membership\ReviewMembershipApplication\ReviewMembershipApplicationHandler;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\ApplyForCommitteeMembershipRequest;
use App\Contexts\Membership\Infrastructure\Http\Requests\Desktop\ReviewMembershipApplicationRequest;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CommitteeMembershipApplicationController
{
    public function __construct(
        private readonly ApplyForCommitteeMembershipHandler $applyHandler,
        private readonly ReviewMembershipApplicationHandler $reviewHandler,
    ) {
    }

    public function apply(
        Request $request,
        ApplyForCommitteeMembershipRequest $formRequest,
        \App\Models\Organisation $organisation,
    ): JsonResponse {
        try {
            $tenantId = TenantId::fromString($organisation->id);
            $user = $request->user();

            $memberId = MemberId::fromString($user->id);
            $committeeId = CommitteeId::fromString($formRequest->committee_id);

            $memberGeoPath = new GeoPathChain(
                geoUnitId: (int) ($user->geo_unit_id ?? 1),
                path: $user->geopath ?? '/',
                segments: json_decode($user->geopath_segments ?? '[]', true),
            );

            $committeeGeoPath = new GeoPathChain(
                geoUnitId: (int) $formRequest->committee_geo_unit_id,
                path: $formRequest->committee_geopath ?? '/',
                segments: json_decode($formRequest->committee_geopath_segments ?? '[]', true),
            );

            $command = new ApplyForCommitteeMembershipCommand(
                tenantId: $tenantId,
                memberId: $memberId->value(),
                committeeId: $committeeId->value(),
                reason: ApplicationReason::from(strtolower($formRequest->reason)),
                exceptionJustification: $formRequest->exception_justification,
                memberGeoPath: $memberGeoPath,
                committeeGeoPath: $committeeGeoPath,
            );

            $applicationId = $this->applyHandler->handle($command);

            return response()->json([
                'application_id' => $applicationId->value(),
                'message' => 'Application submitted successfully',
            ], 201);
        } catch (DomainException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => ['committee_id' => [$e->getMessage()]],
            ], 422);
        }
    }

    public function review(
        Request $request,
        ReviewMembershipApplicationRequest $formRequest,
        \App\Models\Organisation $organisation,
    ): JsonResponse {
        try {
            $tenantId = TenantId::fromString($organisation->id);
            $user = $request->user();

            $command = new ReviewMembershipApplicationCommand(
                tenantId: $tenantId,
                applicationId: $formRequest->application_id,
                action: $formRequest->action,
                reviewedBy: $user->id,
            );

            $association = $this->reviewHandler->handle($command);

            return response()->json([
                'association' => $association ? $this->formatAssociation($association) : null,
                'message' => strtolower($formRequest->action) === 'approve'
                    ? 'Application approved'
                    : 'Application rejected',
            ], 200);
        } catch (DomainException $e) {
            return response()->json([
                'message' => 'Review failed',
                'errors' => ['application_id' => [$e->getMessage()]],
            ], 422);
        }
    }

    private function formatAssociation($association): array
    {
        return [
            'member_id' => $association->memberId->value(),
            'committee_id' => $association->committeeId->value(),
            'association_type' => $association->associationType->value,
            'associated_at' => $association->associatedAt->format(\DateTimeInterface::ATOM),
            'status' => $association->status->value,
        ];
    }
}
