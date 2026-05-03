<?php

namespace App\Contexts\Membership\Infrastructure\Jobs;

use App\Models\MembershipFee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class SendRenewalReminder implements ShouldQueue
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
            // TODO Phase 3C: Replace with FeeRepositoryInterface::findExpiringForTenant()
            // TODO Phase 3C: Iterate through TenantContext::allTenants() for multi-tenant

            $fourteenDaysFromNow = now()->addDays(14)->endOfDay();
            $tomorrowStart = now()->addDay()->startOfDay();

            $fees = MembershipFee::where('status', 'pending')
                ->where('due_date', '>=', $tomorrowStart)
                ->where('due_date', '<=', $fourteenDaysFromNow)
                ->with('member.organisationUser.user', 'membershipType')
                ->get();

            $sent = 0;
            foreach ($fees as $fee) {
                $user = $fee->member?->organisationUser?->user;
                if ($user && $user->email) {
                    try {
                        // TODO Phase 3C: Use MailService instead of Mail facade directly
                        Mail::to($user->email)->queue(
                            new \App\Mail\MembershipRenewalReminder($fee)
                        );
                        $sent++;
                    } catch (\Exception $e) {
                        Log::warning('Membership: Failed to queue renewal reminder', [
                            'fee_id' => $fee->id,
                            'member_id' => $fee->member_id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }

            Log::info('Membership: SendRenewalReminder queued ' . $sent . ' reminders');
        } catch (\Exception $e) {
            Log::error('Membership: SendRenewalReminder failed', [
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
