<?php

namespace App\Contexts\Membership\Infrastructure\Jobs;

use App\Models\Member;
use App\Models\MembershipFee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

final class MarkOverdueMembers implements ShouldQueue
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
            // TODO Phase 3C: Replace with FeeRepositoryInterface::findOverdueForTenant()
            // TODO Phase 3C: Use FeeAggregate::markOverdue() method

            $now = now()->endOfDay();

            // Mark fees as overdue
            $updated = MembershipFee::where('status', 'pending')
                ->where('due_date', '<', $now)
                ->update([
                    'status' => 'overdue',
                    'updated_at' => now(),
                ]);

            // Update member fees_status to 'overdue' if they have any overdue fees
            $memberIds = MembershipFee::where('status', 'overdue')
                ->distinct('member_id')
                ->pluck('member_id')
                ->toArray();

            $memberCount = Member::whereIn('id', $memberIds)
                ->where('fees_status', '!=', 'overdue')
                ->update([
                    'fees_status' => 'overdue',
                    'updated_at' => now(),
                ]);

            Log::info('Membership: MarkOverdueMembers marked ' . $updated . ' fees overdue, updated ' . $memberCount . ' members');
        } catch (\Exception $e) {
            Log::error('Membership: MarkOverdueMembers failed', [
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
