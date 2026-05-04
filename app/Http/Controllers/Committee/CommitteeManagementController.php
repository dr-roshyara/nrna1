<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\UpdateCommitteeDetailsCommand;
use App\Contexts\Membership\Application\Committee\UpdateCommitteeDetails;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Models\Organisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

final class CommitteeManagementController extends Controller
{
    public function update(Organisation $organisation, string $committeeId): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $validated = request()->validate([
                'name' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            $command = new UpdateCommitteeDetailsCommand(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: TenantId::fromString($organisation->id),
                name: $validated['name'] ?? null,
                status: $validated['status'] ?? null,
            );

            app(UpdateCommitteeDetails::class)->execute($command);

            \Log::info('Successfully updated committee', ['committee_id' => $committeeId]);
            return redirect()->back()->with('success', 'Committee updated successfully.');
        } catch (\Throwable $e) {
            \Log::error('Error updating committee', [
                'message' => $e->getMessage(),
                'class' => $e::class,
                'committee_id' => $committeeId,
            ]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
