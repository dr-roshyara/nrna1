<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackfillMemberDirectories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-member-directories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $members = \App\Contexts\Membership\Infrastructure\Models\MemberContextModel::withoutGlobalScopes()
            ->whereNotNull('organisation_user_id')
            ->get();

        $this->info("Found {$members->count()} members to backfill");

        $inserted = 0;
        foreach ($members as $member) {
            $personalInfo = json_decode($member->personal_info, true) ?? [];

            \Illuminate\Support\Facades\DB::table('member_directories')
                ->updateOrInsert(
                    ['member_id' => $member->id],
                    [
                        'member_id' => $member->id,
                        'organisation_id' => $member->organisation_id,
                        'organisation_user_id' => $member->organisation_user_id,
                        'display_name' => $personalInfo['fullName'] ?? 'Unknown',
                        'email' => $personalInfo['email'] ?? '',
                        'status' => $member->status ?? 'ACTIVE',
                        'membership_type_id' => $member->membership_type_id,
                        'membership_type_name' => $this->getMembershipTypeName($member->membership_type_id),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            $inserted++;
        }

        $this->info("✓ Backfilled {$inserted} member directory entries");
    }

    private function getMembershipTypeName(?string $typeId): ?string
    {
        if (!$typeId) {
            return null;
        }

        $type = \App\Models\MembershipType::withoutGlobalScopes()->find($typeId);
        return $type?->name;
    }
}
