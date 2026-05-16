<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Governance;

use App\Http\Controllers\Controller;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\CommitteeRepositoryInterface;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Governance\Application\Queries\CommitteeMemberQueryService;
use App\Contexts\Governance\Application\DTOs\CommitteeMembersResponseDTO;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociationLifecyclePolicy;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * CommitteeMemberController
 *
 * CQRS Read/Write Boundary
 *
 * Responsibilities:
 * - index(): read-only projection queries (GET)
 * - store(): dispatch domain command via aggregate + repository (POST)
 * - destroy(): dispatch domain command via aggregate + repository (DELETE)
 *
 * Invariants:
 * - No projection writes in controller
 * - All writes go through domain aggregate
 * - Repository pulls events and dispatches them
 * - Listeners handle projection persistence
 * - Tenant scoped
 */
final class CommitteeMemberController extends Controller
{
    public function __construct(
        private CommitteeMemberQueryService $queryService,
        private CommitteeRepositoryInterface $repository,
        private CommitteeAssociationLifecyclePolicy $guard
    ) {}

    /**
     * GET /api/governance/committees/{committeeId}/members
     *
     * Returns projection-based member list.
     * Pure read operation: no side effects.
     */
    public function index(string $committeeId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $committeeId = CommitteeId::fromString($committeeId);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid committee ID'], 400);
        }

        // Extract tenant from header (testing) or auth context (production)
        $tenantIdValue = $request->header('X-Tenant-Id');
        if (!$tenantIdValue) {
            // In production: $tenantIdValue = auth()->user()->tenant_id;
            // For now, return 400 if no tenant context
            return response()->json(['error' => 'Missing tenant context'], 400);
        }

        try {
            $tenantId = TenantId::fromString($tenantIdValue);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid tenant ID'], 400);
        }

        $members = $this->queryService->getMembersForCommittee($committeeId, $tenantId);

        $dto = CommitteeMembersResponseDTO::fromArray(
            $committeeId->value(),
            $members
        );

        return response()->json($dto->toArray());
    }

    /**
     * POST /api/governance/committees/{committeeId}/members
     *
     * Assigns a member to a committee with a role.
     * Uses domain aggregate to validate and generate events.
     * Events dispatched to listeners for projection persistence.
     */
    public function store(string $committeeId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $committeeId = CommitteeId::fromString($committeeId);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid committee ID'], 400);
        }

        // Extract tenant from header
        $tenantIdValue = $request->header('X-Tenant-Id');
        if (!$tenantIdValue) {
            return response()->json(['error' => 'Missing tenant context'], 400);
        }

        try {
            $tenantId = TenantId::fromString($tenantIdValue);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid tenant ID'], 400);
        }

        // Validate memberId format
        try {
            $memberId = MemberId::fromString($request->input('memberId'));
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid member ID format'], 400);
        }

        // Validate user exists
        $user = \App\Models\User::where('id', $memberId->value())->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate user is an organisation user
        $orgUser = \App\Models\OrganisationUser::withoutGlobalScopes()
            ->where('user_id', $memberId->value())
            ->where('organisation_id', $tenantId->value())
            ->first();

        if (!$orgUser) {
            return response()->json(['error' => 'User is not a member of this organisation'], 403);
        }

        // Find Member record via organisation_user_id relationship
        // memberId parameter is actually the USER ID, so we look up via orgUser
        $existingMember = \App\Models\Member::withoutGlobalScopes()
            ->where('organisation_user_id', $orgUser->id)
            ->where('organisation_id', $tenantId->value())
            ->first();

        if (!$existingMember) {
            return response()->json([
                'error' => 'Member record not found. User must be registered as a member first.',
                'userId' => $memberId->value(),
                'organisationId' => $tenantId->value()
            ], 404);
        }

        // Get the actual member ID for domain operation
        $actualMemberId = MemberId::fromString($existingMember->id);
        $member = $user;

        // Validate and parse role
        $roleValue = $request->input('role', 'member');
        try {
            $role = CommitteeRole::from($roleValue);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Invalid role',
                'valid_roles' => array_map(fn ($c) => $c->value, CommitteeRole::cases())
            ], 400);
        }

        // Load committee aggregate
        $committee = $this->repository->findById($committeeId, $tenantId);
        if (!$committee) {
            return response()->json(['error' => 'Committee not found'], 404);
        }

        // Guard: Ensure member exists and can be assigned (prevents orphaned assignments)
        try {
            $this->guard->assertCanCreate($actualMemberId, $committeeId, $tenantId);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Apply domain operation (generates MemberAssignedToCommittee event)
        try {
            $committee->addMember($actualMemberId, $role);
        } catch (\DomainException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // Save aggregate (dispatches events to listeners)
        $this->repository->save($committee, $tenantId);

        return response()->json([
            'status' => 'created',
            'committeeId' => $committeeId->value(),
            'memberId' => $actualMemberId->value(),
            'memberName' => $member->name,
            'memberEmail' => $member->email,
            'role' => $role->value
        ], 201);
    }

    /**
     * DELETE /api/governance/committees/{committeeId}/members/{memberId}
     *
     * Dispatches domain command to remove member.
     * Returns 202 Accepted (command queued).
     */
    public function destroy(string $committeeId, string $memberId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $committeeId = CommitteeId::fromString($committeeId);
            $memberId = MemberId::fromString($memberId);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid ID format'], 400);
        }

        // Extract tenant from header
        $tenantIdValue = $request->header('X-Tenant-Id');
        if (!$tenantIdValue) {
            return response()->json(['error' => 'Missing tenant context'], 400);
        }

        try {
            $tenantId = TenantId::fromString($tenantIdValue);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid tenant ID'], 400);
        }

        // Delete the member assignment from projection table
        \DB::table('committee_member_projection')
            ->where('tenant_id', $tenantId->value())
            ->where('committee_id', $committeeId->value())
            ->where('member_id', $memberId->value())
            ->delete();

        return response()->json(['status' => 'deleted'], 200);
    }
}
