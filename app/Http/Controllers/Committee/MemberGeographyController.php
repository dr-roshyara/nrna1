<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\Services\NearbyCommitteesQueryService;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Member\ValueObjects\MemberResidenceGeoIdentity;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Infrastructure\Models\MemberContextModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * MemberGeographyController — Manage member residence geography.
 *
 * Endpoints for viewing and updating a member's residence geo unit,
 * and finding nearby committees that cover the member's area.
 */
final class MemberGeographyController extends Controller
{
    public function __construct(
        private readonly MemberRepositoryInterface $members,
        private readonly GeoSemanticProjectionBuilder $projectionBuilder,
        private readonly NearbyCommitteesQueryService $nearbyCommittees,
    ) {}

    /**
     * Show member's residence geography.
     */
    public function show(Organisation $organisation, MemberContextModel $member): Response
    {
        $this->authorize('manageCommittee', $organisation);

        $tenantId = TenantId::fromOrganisationId($organisation->id);
        $memberId = MemberId::fromString($member->id);
        $domainMember = $this->members->find($memberId, $tenantId);

        $residenceGeo = null;
        if ($domainMember !== null && $domainMember->getResidenceGeoIdentity() !== null) {
            $geoUnitId = $domainMember->getResidenceGeoIdentity()->residenceGeoUnitId;
            $projection = $this->projectionBuilder->build($geoUnitId);
            $residenceGeo = $projection !== null ? [
                'geo_unit_id' => $projection->geoUnitId,
                'path' => $projection->path,
                'admin_level' => $projection->adminLevel,
                'region_code' => $projection->regionCode,
                'country_code' => $projection->countryCode,
            ] : ['geo_unit_id' => $geoUnitId];
        }

        return Inertia::render('Organisations/Members/Geo/Show', [
            'organisation' => $organisation,
            'member' => $member,
            'residenceGeo' => $residenceGeo,
        ]);
    }

    /**
     * Update member's residence geography.
     */
    public function update(Request $request, Organisation $organisation, MemberContextModel $member): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        $validated = $request->validate([
            'geo_unit_id' => 'required|integer|min:1|exists:geo_administrative_units,id',
        ]);

        $tenantId = TenantId::fromOrganisationId($organisation->id);
        $memberId = MemberId::fromString($member->id);
        $domainMember = $this->members->find($memberId, $tenantId);

        if ($domainMember === null) {
            return redirect()->back()->withErrors(['error' => 'Member not found.']);
        }

        $domainMember->setResidenceGeoIdentity(
            new MemberResidenceGeoIdentity((int) $validated['geo_unit_id'])
        );

        $this->members->save($domainMember, $tenantId);

        return redirect()->back()->with('success', __('geo.member_residence_updated'));
    }

    /**
     * Find nearby committees covering the member's residence geography.
     */
    public function nearbyCommittees(Organisation $organisation, MemberContextModel $member): \Illuminate\Http\JsonResponse
    {
        $this->authorize('manageCommittee', $organisation);

        $tenantId = TenantId::fromOrganisationId($organisation->id);
        $memberId = MemberId::fromString($member->id);
        $domainMember = $this->members->find($memberId, $tenantId);

        if ($domainMember === null || $domainMember->getResidenceGeoIdentity() === null) {
            return response()->json(['committees' => []]);
        }

        $geoUnitId = $domainMember->getResidenceGeoIdentity()->residenceGeoUnitId;
        $committees = $this->nearbyCommittees->getNearby($geoUnitId);

        return response()->json([
            'committees' => $committees->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'type' => $c->type,
                'slug' => $c->slug,
            ]),
        ]);
    }
}
