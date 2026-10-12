<?php

namespace App\Console\Commands;

use App\Exceptions\Voting\VoterSlugIssuanceConflict;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\Voting\VoterSlugIssuance;
use App\Services\Voting\VoterSlugIssuer;
use Illuminate\Console\Command;

/**
 * Recovery for a voter in ONE real election (PBDIGIT-274 S4; PO decision 2026-10-13). The voter's link comes
 * from VoterSlugIssuer with fresh entitlement: a valid link is returned unchanged; an expired or deactivated one
 * is replaced by a new row and kept as history; none after voting or without entitlement. It no longer checks
 * legacy user flags, deactivates all of the voter's links, or uses secureSlugGeneration.
 */
class GenerateRecoverySlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:recover-voter {user_id} {--election= : The id of the real election (required)} {--reason=Expired slug recovery} {--admin=Election Committee}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Provide a voter with a valid voting link for one real election (reuse, or replace an expired one)';

    public function handle(): int
    {
        $userId = $this->argument('user_id');
        $electionId = $this->option('election');
        $reason = $this->option('reason');
        $adminName = $this->option('admin');

        if (! $electionId) {
            $this->error('❌ --election=<id> is required: recovery is per election.');

            return 1;
        }

        $user = User::find($userId);
        if (! $user) {
            $this->error("❌ User with ID {$userId} not found.");

            return 1;
        }

        $election = Election::withoutGlobalScopes()->find($electionId);
        if (! $election) {
            $this->error("❌ Election with ID {$electionId} not found.");

            return 1;
        }
        if (trim((string) $election->type) !== 'real') {
            $this->error('❌ Recovery links apply to real elections only; demo elections use their own flow.');

            return 1;
        }

        $this->info('🔍 Voter:');
        $this->line("   Name: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   Election: {$election->name}");

        $current = VoterSlug::withoutGlobalScope('tenant')
            ->where('user_id', $user->id)->where('election_id', $election->id)->first();
        if ($current) {
            $expired = $current->expires_at < now() ? '(EXPIRED)' : '';
            $state = $current->is_active ? 'ACTIVE' : 'INACTIVE';
            $this->line("   Current link: step {$current->current_step}, {$state} {$expired}, created {$current->created_at}, expires {$current->expires_at}");
        } else {
            $this->line('   No current link.');
        }

        if (! $this->confirm("\n❓ Generate recovery link for {$user->name}?")) {
            $this->info('Operation cancelled.');

            return 0;
        }

        try {
            $issuance = VoterSlugIssuer::fresh()->issue($user, $election);
        } catch (VoterSlugIssuanceConflict $e) {
            $this->error('❌ '.$e->getUserMessage());

            return 1;
        }

        if ($issuance->outcome === VoterSlugIssuance::ALREADY_VOTED) {
            $this->error('❌ The voter has already voted in this election; no link.');

            return 1;
        }
        if (! $issuance->hasLink()) {
            $this->error('❌ The voter is not entitled to vote in this election; no link.');

            return 1;
        }

        $slug = $issuance->slug;
        $this->info($issuance->outcome === VoterSlugIssuance::REUSED
            ? "\n✅ The voter's current link is still valid."
            : "\n🎉 A new voting link was created (the expired one is kept as history).");
        $this->line("   Expires: {$slug->expires_at}");
        $this->info("\n🔗 Recovery URL (give it to the voter only):");
        $this->line('   '.url("/v/{$slug->slug}/code/create"));

        // Audit without the raw link (plan O-7): ids and outcome only.
        \Log::info('CLI recovery link provided', [
            'admin_name' => $adminName,
            'user_id' => $user->id,
            'election_id' => $election->id,
            'slug_id' => $slug->id,
            'outcome' => $issuance->outcome,
            'reason' => $reason,
            'method' => 'cli_command',
        ]);

        return 0;
    }
}
