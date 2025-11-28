<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Vote;
use App\Models\Code;
use App\Models\Candidacy;
use App\Models\VoterSlug;
use App\Models\User;

class ResetElection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:reset
                            {--votes : Reset only votes and codes}
                            {--candidates : Reset only candidates}
                            {--all : Reset everything (votes, codes, candidates)}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset election data (votes, codes, candidates) for a fresh start';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('╔═══════════════════════════════════════════════════════════╗');
        $this->info('║          NRNA Election System - Data Reset Tool          ║');
        $this->info('╚═══════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Determine what to reset
        $resetVotes = $this->option('votes') || $this->option('all');
        $resetCandidates = $this->option('candidates') || $this->option('all');

        // If no options specified, ask user
        if (!$resetVotes && !$resetCandidates) {
            $this->warn('⚠️  No reset options specified. What would you like to reset?');
            $this->newLine();

            $choice = $this->choice(
                'Select reset scope',
                [
                    'votes' => 'Votes & Codes only (keeps candidates)',
                    'candidates' => 'Candidates only (keeps votes)',
                    'all' => 'Everything (complete reset)',
                    'cancel' => 'Cancel operation'
                ],
                'cancel'
            );

            if ($choice === 'cancel') {
                $this->info('Operation cancelled.');
                return 0;
            }

            $resetVotes = in_array($choice, ['votes', 'all']);
            $resetCandidates = in_array($choice, ['candidates', 'all']);
        }

        // Show what will be reset
        $this->newLine();
        $this->warn('═══ The following data will be PERMANENTLY DELETED ═══');
        $this->newLine();

        if ($resetVotes) {
            $votesCount = Vote::count();
            $codesCount = Code::count();
            $slugsCount = VoterSlug::count();

            $this->line("  📊 Votes:        {$votesCount} records");
            $this->line("  🔐 Codes:        {$codesCount} records");
            $this->line("  🔗 Voter Slugs:  {$slugsCount} records");
            $this->line("  👤 User flags:   Reset can_vote, has_voted, etc.");
        }

        if ($resetCandidates) {
            $candidatesCount = Candidacy::count();
            $this->line("  🎯 Candidates:   {$candidatesCount} records");
        }

        $this->newLine();

        // Confirmation
        if (!$this->option('force')) {
            $this->error('⚠️  WARNING: This action CANNOT be undone!');
            $this->newLine();

            if (!$this->confirm('Are you absolutely sure you want to proceed?', false)) {
                $this->info('Operation cancelled.');
                return 0;
            }

            // Double confirmation for complete reset
            if ($resetVotes && $resetCandidates) {
                $this->newLine();
                $this->error('🚨 FINAL WARNING: You are about to delete ALL election data!');
                $this->newLine();

                $confirmText = 'DELETE ALL';
                $userInput = $this->ask("Type '{$confirmText}' to confirm complete reset");

                if ($userInput !== $confirmText) {
                    $this->error('Confirmation failed. Operation cancelled.');
                    return 1;
                }
            }
        }

        $this->newLine();
        $this->info('Starting reset process...');
        $this->newLine();

        try {
            DB::beginTransaction();

            // Reset votes and related data
            if ($resetVotes) {
                $this->resetVotesAndCodes();
            }

            // Reset candidates
            if ($resetCandidates) {
                $this->resetCandidates();
            }

            DB::commit();

            $this->newLine();
            $this->info('╔═══════════════════════════════════════════════════════════╗');
            $this->info('║              ✅ Reset Completed Successfully              ║');
            $this->info('╚═══════════════════════════════════════════════════════════╝');
            $this->newLine();
            $this->info('Your election system is now ready for a fresh start!');

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();

            $this->newLine();
            $this->error('╔═══════════════════════════════════════════════════════════╗');
            $this->error('║                   ❌ Reset Failed                         ║');
            $this->error('╚═══════════════════════════════════════════════════════════╝');
            $this->newLine();
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('No changes were made to the database (transaction rolled back).');

            return 1;
        }
    }

    /**
     * Reset votes, codes, and user voting flags
     */
    private function resetVotesAndCodes()
    {
        $bar = $this->output->createProgressBar(5);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

        // Step 1: Delete all votes
        $bar->setMessage('Deleting votes...');
        $bar->start();
        $votesDeleted = Vote::count();
        Vote::truncate();
        $bar->advance();

        // Step 2: Delete all codes
        $bar->setMessage('Deleting verification codes...');
        $codesDeleted = Code::count();
        Code::truncate();
        $bar->advance();

        // Step 3: Delete all voter slugs
        $bar->setMessage('Deleting voter slugs...');
        $slugsDeleted = VoterSlug::count();
        VoterSlug::truncate();
        $bar->advance();

        // Step 4: Reset user voting flags
        $bar->setMessage('Resetting user flags...');
        $usersUpdated = DB::table('users')->where('can_vote', 1)->count();

        DB::table('users')->update([
            'has_voted' => 0,
            'vote_last_seen' => null,
            'voting_started_at' => null,
            'vote_submitted_at' => null,
            'vote_completed_at' => null,
        ]);
        $bar->advance();

        // Step 5: Clear sessions (if session table exists)
        $bar->setMessage('Clearing sessions...');
        if (Schema::hasTable('sessions')) {
            DB::table('sessions')->delete();
        }
        $bar->advance();

        $bar->finish();
        $this->newLine(2);

        $this->info("  ✓ Deleted {$votesDeleted} votes");
        $this->info("  ✓ Deleted {$codesDeleted} verification codes");
        $this->info("  ✓ Deleted {$slugsDeleted} voter slugs");
        $this->info("  ✓ Reset {$usersUpdated} user voting flags");
        $this->info("  ✓ Cleared session data");
    }

    /**
     * Reset all candidates
     */
    private function resetCandidates()
    {
        $bar = $this->output->createProgressBar(1);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %message%');

        $bar->setMessage('Deleting candidates...');
        $bar->start();

        $candidatesDeleted = Candidacy::count();
        Candidacy::truncate();

        $bar->finish();
        $this->newLine(2);

        $this->info("  ✓ Deleted {$candidatesDeleted} candidates");
    }
}
