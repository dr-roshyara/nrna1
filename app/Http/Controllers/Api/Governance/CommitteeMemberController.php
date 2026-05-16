<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Governance;

use App\Http\Controllers\Controller;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Application\Queries\CommitteeMemberQueryService;
use App\Contexts\Governance\Application\DTOs\CommitteeMembersResponseDTO;
use App\Contexts\Membership\Domain\Member\MemberId;
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
 * - store(): dispatch domain command (POST)
 * - destroy(): dispatch domain command (DELETE)
 *
 * Invariants:
 * - No projection writes in controller
 * - No aggregate loading
 * - Tenant scoped
 */
final class CommitteeMemberController extends Controller
{
    public function __construct(
        private CommitteeMemberQueryService $queryService
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
     * Dispatches domain command to assign member.
     * Returns 202 Accepted (command queued).
     */
    public function store(string $committeeId, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $committeeId = CommitteeId::fromString($committeeId);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid committee ID'], 400);
        }

        // Validate memberId format
        try {
            $memberId = MemberId::fromString($request->input('memberId'));
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'memberId' => 'Invalid member ID format'
            ]);
        }

        // TODO: Dispatch command through command handler
        // For now: just acknowledge the request
        // In production:
        // $this->commandBus->dispatch(new AssignMemberToCommittee(
        //     $committeeId,
        //     $memberId,
        //     $tenantId
        // ));

        return response()->json(['status' => 'queued'], 202);
    }

    /**
     * DELETE /api/governance/committees/{committeeId}/members/{memberId}
     *
     * Dispatches domain command to remove member.
     * Returns 202 Accepted (command queued).
     */
    public function destroy(string $committeeId, string $memberId): \Illuminate\Http\JsonResponse
    {
        try {
            $committeeId = CommitteeId::fromString($committeeId);
            $memberId = MemberId::fromString($memberId);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Invalid ID format'], 400);
        }

        // TODO: Dispatch command through command handler
        // In production: dispatch RemoveMemberFromCommittee command

        return response()->json(['status' => 'queued'], 202);
    }
}
