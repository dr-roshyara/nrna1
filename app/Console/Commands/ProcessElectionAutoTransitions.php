<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\Candidacy;
use Illuminate\Console\Command;

class ProcessElectionAutoTransitions extends Command
{
    protected $signature = 'elections:process-auto-transitions';
    protected $description = 'Process automatic state transitions for elections based on grace periods';

    public function handle(): int
    {
        $count = 0;
        $elections = Election::withoutGlobalScopes()->get();

        foreach ($elections as $election) {
            $adminResult = $this->processAdminToNominationTransition($election);
            $count += $adminResult;

            if ($adminResult > 0) {
                $election->refresh();
            }

            $votingResult = $this->processNominationToVotingTransition($election);
            $count += $votingResult;

            $this->enforceVotingLock($election);
        }

        $this->info("Processed {$count} election(s) for automatic transitions");
        return 0;
    }

    private function processAdminToNominationTransition(Election $election): int
    {
        if (!$election->allow_auto_transition) {
            return 0;
        }

        if ($election->nomination_completed || !$election->administration_completed) {
            return 0;
        }

        $gracePeriodDays = $election->auto_transition_grace_days ?? 0;
        $completedAt = $election->administration_completed_at;

        if (!$completedAt) {
            return 0;
        }

        $graceDeadline = $completedAt->copy()->addDays($gracePeriodDays);
        if (now()->isBefore($graceDeadline)) {
            return 0;
        }

        if (!$this->hasRequiredPostsAndVoters($election)) {
            $this->warn("Election {$election->id}: Missing required posts or voters for admin→nomination transition");
            return 0;
        }

        $systemId = null;
        try {
            $approved = $election->candidacies()->withoutGlobalScopes()->where('status', 'approved')->count();
            $pending = $election->candidacies()->withoutGlobalScopes()->where('status', 'pending')->count();

            $election->completeNomination('Automatic transition after grace period', $systemId);
            return 1;
        } catch (\Exception $e) {
            $this->warn("Failed to transition election {$election->id} to nomination: {$e->getMessage()}");
            return 0;
        }
    }

    private function processNominationToVotingTransition(Election $election): int
    {
        if (!$election->allow_auto_transition) {
            return 0;
        }

        if (!$election->nomination_completed) {
            return 0;
        }

        $gracePeriodDays = $election->auto_transition_grace_days ?? 0;
        $completedAt = $election->nomination_completed_at;

        if (!$completedAt) {
            return 0;
        }

        $graceDeadline = $completedAt->copy()->addDays($gracePeriodDays);
        if (now()->isBefore($graceDeadline)) {
            return 0;
        }

        if ($this->hasPendingCandidates($election)) {
            return 0;
        }

        $systemId = null;
        try {
            $election->lockVoting($systemId);
            $election->logStateChange('auto_transition_voting_locked', [
                'grace_period_days' => $gracePeriodDays,
                'automation' => true,
            ]);
            return 1;
        } catch (\Exception $e) {
            $this->warn("Failed to lock voting for election {$election->id}: {$e->getMessage()}");
            return 0;
        }
    }

    private function enforceVotingLock(Election $election): void
    {
        if ($election->voting_locked) {
            return;
        }

        if (!$election->voting_ends_at || now()->isBefore($election->voting_ends_at)) {
            return;
        }

        try {
            $systemId = null;
            $election->lockVoting($systemId);
        } catch (\Exception $e) {
            $this->warn("Failed to enforce voting lock for election {$election->id}: {$e->getMessage()}");
        }
    }

    private function hasRequiredPostsAndVoters(Election $election): bool
    {
        $hasPost = $election->posts()->withoutGlobalScopes()->exists();
        $hasVoter = $election->memberships()
            ->withoutGlobalScopes()
            ->where('role', 'voter')
            ->where('status', 'active')
            ->exists();

        return $hasPost && $hasVoter;
    }

    private function hasPendingCandidates(Election $election): bool
    {
        return $election->posts()
            ->withoutGlobalScopes()
            ->join('candidacies', 'posts.id', '=', 'candidacies.post_id')
            ->where('candidacies.status', 'pending')
            ->exists();
    }
}
