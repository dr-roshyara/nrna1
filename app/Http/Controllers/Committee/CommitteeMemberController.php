<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\AssignMemberToCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\AssignMemberDto;
use App\Contexts\Membership\Application\Committee\DTOs\RemoveMemberDto;
use App\Contexts\Membership\Application\Committee\RemoveMemberFromCommittee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Models\Organisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

final class CommitteeMemberController extends Controller
{
    public function assign(Organisation $organisation, string $committeeId): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $validated = request()->validate([
                'member_id' => 'required|uuid|exists:users,id',
                'role_path' => 'required|string',
                'nomination_type' => 'required|string|in:elected,appointed,volunteered',
                'election_date' => 'nullable|date_format:Y-m-d',
                'term_end_date' => 'nullable|date_format:Y-m-d',
            ]);

            $dto = new AssignMemberDto(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: TenantId::fromString($organisation->id),
                memberId: $validated['member_id'],
                rolePath: RolePath::fromString($validated['role_path']),
                nominationType: $validated['nomination_type'],
                electionDate: $validated['election_date']
                    ? new DateTimeImmutable($validated['election_date'])
                    : null,
                termEndDate: $validated['term_end_date']
                    ? new DateTimeImmutable($validated['term_end_date'])
                    : null,
            );

            app(AssignMemberToCommittee::class)->execute($dto);

            return redirect()->back()->with('success', 'Member assigned to committee.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function remove(Organisation $organisation, string $committeeId, string $assignmentId): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $dto = new RemoveMemberDto(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: TenantId::fromString($organisation->id),
                assignmentId: CommitteeAssignmentId::fromString($assignmentId),
            );

            app(RemoveMemberFromCommittee::class)->execute($dto);

            return redirect()->back()->with('success', 'Member removed from committee.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
