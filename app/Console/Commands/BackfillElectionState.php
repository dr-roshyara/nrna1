<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackfillElectionState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-election-state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill explicit state column for all elections';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Backfilling election states...');

        $elections = \App\Models\Election::query()
            ->withoutGlobalScopes()
            ->get();

        $updated = 0;
        foreach ($elections as $election) {
            $state = $this->determineState($election);
            \Illuminate\Support\Facades\DB::table('elections')
                ->where('id', $election->id)
                ->update(['state' => $state]);
            $updated++;
        }

        $this->info("Updated {$updated} elections with explicit state values.");
    }

    /**
     * Determine the state of an election based on its attributes.
     * This replicates the original computed state logic.
     */
    private function determineState(\App\Models\Election $election): string
    {
        // RESULTS (final - overrides everything)
        if ($election->results_published_at) {
            return 'results';
        }

        // RESULTS_PENDING (voting window ended, waiting for counting/results)
        if ($election->voting_ends_at && now() > $election->voting_ends_at) {
            if ($election->voting_locked) {
                return 'results_pending';
            }
            return 'voting_ended_unlocked';
        }

        // VOTING (active voting window)
        if ($election->voting_starts_at && now() >= $election->voting_starts_at) {
            if ($election->canEnterVotingPhase()) {
                return 'voting';
            }
            return 'voting_blocked';
        }

        // NOMINATION COMPLETED (waiting for voting to start)
        if ($election->nomination_completed) {
            if (!$election->voting_starts_at || now()->lt($election->voting_starts_at)) {
                return 'nomination';
            }
        }

        // NOMINATION (after administration completed)
        if ($election->administration_completed) {
            if ($election->canEnterNominationPhase()) {
                return 'nomination';
            }
            return 'nomination_blocked';
        }

        // ADMINISTRATION (setup phase with requirements met)
        if ($election->canEnterAdministrationPhase()) {
            return 'administration';
        }

        // DRAFT (missing setup requirements)
        return 'draft';
    }
}
