<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;

class ExpireMemberships extends Command
{
    protected $signature   = 'membership:expire';
    protected $description = 'Mark active members with a past expiry date as expired';

    public function handle(): int
    {
        $count = Member::withoutGlobalScopes()
            ->where('status', 'active')
            ->whereNotNull('membership_expires_at')
            ->where('membership_expires_at', '<', today())
            ->update(['status' => 'expired']);

        $this->info("Expired {$count} membership(s).");

        return Command::SUCCESS;
    }
}
