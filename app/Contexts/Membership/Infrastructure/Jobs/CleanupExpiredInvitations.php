<?php

namespace App\Contexts\Membership\Infrastructure\Jobs;

use App\Models\VoterInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class CleanupExpiredInvitations implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 1;
    public $timeout = 300;

    public function handle(): void
    {
        if (!app()->isProduction()) {
            return;
        }

        try {
            // TODO Phase 3C: Replace with InvitationRepositoryInterface::findExpiredForTenant()
            // TODO Phase 3C: Use InvitationAggregate for state management

            $now = now();

            // Soft-delete invitations that have:
            // 1. Been used (used_at is set)
            // 2. Expired (expires_at is in the past)
            $deleted = VoterInvitation::where(function ($query) use ($now) {
                $query->whereNotNull('used_at')
                    ->orWhere('expires_at', '<', $now);
            })
                ->whereNull('deleted_at')
                ->delete();

            Log::info('Membership: CleanupExpiredInvitations soft-deleted ' . $deleted . ' invitations');
        } catch (\Exception $e) {
            Log::error('Membership: CleanupExpiredInvitations failed', [
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
