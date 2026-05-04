<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\RemoveMemberDto;
use App\Contexts\Membership\Application\Committee\RemoveMemberFromCommittee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Models\Organisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

final class CommitteeMemberController extends Controller
{
    public function remove(Organisation $organisation, string $committeeId, string $assignmentId): RedirectResponse
    {
        $this->authorize('manage-committee', $organisation);

        try {
            $dto = new RemoveMemberDto(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: TenantId::fromString($organisation->id),
                assignmentId: CommitteeAssignmentId::fromString($assignmentId),
            );

            app(RemoveMemberFromCommittee::class)->execute($dto);

            return redirect()->back()->with('success', 'Member removed from committee.');
        } catch (\Throwable $e) {
            \Log::error('Error removing member from committee: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
