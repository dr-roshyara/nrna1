<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Governance;

use App\Http\Controllers\Controller;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\CommitteeRepositoryInterface;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Governance\Application\Commands\RemoveCommitteeMemberCommand;
use App\Contexts\Governance\Application\Handlers\RemoveCommitteeMemberHandler;
use App\Contexts\Governance\Application\Queries\CommitteeMemberQueryService;
use App\Contexts\Governance\Application\DTOs\CommitteeMembersResponseDTO;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociationLifecyclePolicy;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Services\TenantContext;
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
        private CommitteeAssociationLifecyclePolicy $guard,
        private RemoveCommitteeMemberHandler $removeHandler
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
            \Log::info('[CommitteeMemberController] Received committee ID', ['committeeId' => $committeeId]);
            $committeeId = CommitteeId::fromString($committeeId);
            \Log::info('[CommitteeMemberController] Committee ID validated successfully');
        } catch (\Throwable $e) {
            \Log::error('[CommitteeMemberController] Committee ID validation failed', [
                'committeeId' => $committeeId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Invalid committee ID: ' . $e->getMessage()], 400);
        }

        // Get tenant context from middleware (IdentifyTenantFromHeader)
        $tenantIdValue = TenantContext::get();
        if (!$tenantIdValue) {
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
    public function store(Request $request, string $committeeId): \Illuminate\Http\JsonResponse
    {
        try {
            // Get tenant context from middleware (IdentifyTenantFromHeader)
            $tenantIdValue = TenantContext::get();
            if (!$tenantIdValue) {
                return response()->json(['error' => 'Missing tenant context'], 400);
            }

            \Log::info('[store] ===== START =====', [
                'organisation_id' => $tenantIdValue,
                'committeeId' => $committeeId,
                'memberId' => $request->input('memberId'),
            ]);

            // Step 1: Validate Committee ID format
            \Log::info('[store] Step 1: Validating committee ID');
            $committeeId = CommitteeId::fromString($committeeId);
            \Log::info('[store] Step 1: ✓ Committee ID valid');

            // Step 2: Resolve tenant
            \Log::info('[store] Step 2: Resolving tenant');
            $tenantId = TenantId::fromString($tenantIdValue);
            \Log::info('[store] Step 2: ✓ Tenant resolved', ['tenantId' => $tenantId->value()]);

            // Step 3: Validate member ID
            \Log::info('[store] Step 3: Validating member ID');
            $memberId = MemberId::fromString($request->input('memberId'));
            \Log::info('[store] Step 3: ✓ Member ID valid');

            // Step 4: Find member and resolve to user
            \Log::info('[store] Step 4: Finding member');
            $member = \App\Models\Member::withoutGlobalScopes()
                ->where('id', $memberId->value())
                ->where('organisation_id', $tenantIdValue)
                ->first();

            if (!$member) {
                \Log::warning('[store] Member not found', ['memberId' => $memberId->value()]);
                return response()->json(['error' => 'Member not found'], 404);
            }

            // Resolve member to user through organisation_user relationship
            $user = $member->user;
            if (!$user) {
                \Log::error('[store] User not found for member', ['memberId' => $memberId->value(), 'organisationUserId' => $member->organisation_user_id]);
                return response()->json(['error' => 'User not found for member'], 404);
            }
            \Log::info('[store] Step 4: ✓ Member resolved to user', ['user_id' => $user->id, 'member_id' => $member->id]);

            // Step 5: Validate role
            \Log::info('[store] Step 5: Validating role');
            $role = CommitteeRole::from($request->input('role', 'member'));
            \Log::info('[store] Step 5: ✓ Role valid');

            // Step 6: Load committee aggregate
            \Log::info('[store] Step 6: Loading committee');
            $committee = $this->repository->findById($committeeId, $tenantId);
            if (!$committee) {
                \Log::warning('[store] Committee not found', ['committeeId' => $committeeId->value()]);
                return response()->json(['error' => 'Committee not found'], 404);
            }
            \Log::info('[store] Step 6: ✓ Committee loaded');

            // Step 7: Add member to committee
            \Log::info('[store] Step 7: Adding member to committee');
            $committee->addMember($memberId, $role);
            \Log::info('[store] Step 7: ✓ Member added to aggregate');

            // Step 8: Save aggregate
            \Log::info('[store] Step 8: Saving aggregate');
            $this->repository->save($committee, $tenantId);
            \Log::info('[store] Step 8: ✓ Aggregate saved');

            \Log::info('[store] ===== SUCCESS =====');

            return response()->json([
                'status' => 'created',
                'committeeId' => $committeeId->value(),
                'memberId' => $memberId->value(),
                'memberName' => $user->name,
                'memberEmail' => $user->email,
                'role' => $role->value
            ], 201);

        } catch (\Throwable $e) {
            \Log::error('[store] ===== EXCEPTION =====', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Failed to add member: ' . $e->getMessage(),
                'exception_class' => get_class($e),
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/governance/committees/{committeeId}/members/{memberId}
     *
     * HTTP Adapter: Maps request → command → handler → response
     * The actual domain logic lives in RemoveCommitteeMemberHandler (pure PHP).
     */
    public function destroy(string $committeeId, string $memberId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // 1. Extract infrastructure context (tenant from middleware)
            $tenantIdValue = TenantContext::get();
            if (!$tenantIdValue) {
                return response()->json(['error' => 'Missing tenant context'], 400);
            }

            // 2. Map framework data → pure PHP Command
            $command = new RemoveCommitteeMemberCommand(
                tenantId: $tenantIdValue,
                committeeId: $committeeId,
                memberId: $memberId
            );

            // 3. Dispatch to pure PHP handler (no framework knowledge)
            $this->removeHandler->handle($command);

            // 4. Return infrastructure-specific response
            return response()->json([
                'success' => true,
                'message' => 'Member removal command accepted',
            ], 202);

        } catch (\DomainException $e) {
            // Domain rule violation (e.g., committee not found)
            return response()->json([
                'error' => $e->getMessage(),
            ], $e->getCode() ?: 422);
        } catch (\Throwable $e) {
            // Unexpected error
            return response()->json([
                'error' => 'An internal error occurred',
            ], 500);
        }
    }
}
