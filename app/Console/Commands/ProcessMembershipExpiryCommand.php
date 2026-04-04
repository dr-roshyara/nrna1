<?php

namespace App\Console\Commands;

use App\Models\MembershipApplication;
use Illuminate\Console\Command;

class ProcessMembershipExpiryCommand extends Command
{
    protected $signature   = 'membership:process-expiry';
    protected $description = 'Auto-reject expired membership applications and mark overdue fees.';

    public function handle(): int
    {
        $count = MembershipApplication::query()
            ->whereIn('status', ['submitted', 'under_review', 'draft'])
            ->where('expires_at', '<', now())
            ->update([
                'status'           => 'rejected',
                'rejection_reason' => 'Application expired automatically.',
                'reviewed_at'      => now(),
            ]);

        $this->info("Rejected {$count} expired membership application(s).");

        $overdueFees = \App\Models\MembershipFee::overdue()->update(['status' => 'overdue']);
        $this->info("Marked {$overdueFees} overdue fee(s).");

        return Command::SUCCESS;
    }
}
