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

final class GenerateAnnualMembershipFees implements ShouldQueue
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
            // Get all active members whose membership expires in 30 days
            $thirtyDaysFromNow = now()->addDays(30)->endOfDay();

            $members = Member::where('status', 'active')
                ->where('membership_expires_at', '<=', $thirtyDaysFromNow)
                ->whereDoesntHave('fees', function ($query) {
                    $query->where('status', '!=', 'paid')
                        ->whereYear('due_date', now()->year)
                        ->whereMonth('due_date', now()->month + 1);
                })
                ->with('membershipType')
                ->get();

            $created = 0;
            foreach ($members as $member) {
                MembershipFee::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'member_id' => $member->id,
                    'organisation_id' => $member->organisation_id,
                    'membership_type_id' => $member->membership_type_id,
                    'amount' => $member->membershipType?->annual_fee ?? 0,
                    'due_date' => $member->membership_expires_at,
                    'description' => "Annual Membership Fee - {$member->membership_expires_at?->format('Y')}",
                    'status' => 'pending',
                ]);
                $created++;
            }

            Log::info('Membership: GenerateAnnualMembershipFees created ' . $created . ' fees');
        } catch (\Exception $e) {
            Log::error('Membership: GenerateAnnualMembershipFees error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
