<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Models\Organization;
use Illuminate\Console\Command;

class SetupDemoElection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:setup {--org= : Organisation ID for MODE 2 scoped demo (optional)} {--force : Force recreation of existing demo election} {--clean : Delete existing demo data without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup demo election in MODE 1 (public) or MODE 2 (organisation-scoped). Production-safe alternative to seeder.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // STEP 1: Determine mode based on --org option
        $orgId = $this->option('org');
        $mode = $orgId ? 'MODE 2' : 'MODE 1';

        // MODE 2: Validate organization exists
        $targetOrganization = null;
        if ($orgId) {
            $targetOrganization = Organization::find($orgId);
            if (!$targetOrganization) {
                $this->error("❌ Organization with ID {$orgId} not found!");
                return 1;
            }
        }

        // STEP 2: Set session context before creating demo data
        session(['current_organisation_id' => $orgId ?? null]);

        $this->info('');
        $this->info('🚀 Setting up demo election (' . $mode . ')...');
        if ($mode === 'MODE 2') {
            $this->info('   Organization: ' . $targetOrganization->name . ' (ID: ' . $targetOrganization->id . ')');
        } else {
            $this->info('   Public demo - accessible to all users');
        }
        $this->info('🔍 Checking for existing demo election...');

        // CRITICAL: Use withoutGlobalScopes() because demo elections are accessible
        // to ALL users regardless of organisation context
        // For MODE 2, use unique slug per organization; for MODE 1, use 'demo-election'
        $demoSlug = $orgId ? 'demo-election-org-' . $orgId : 'demo-election';

        $query = Election::withoutGlobalScopes()
            ->where('slug', $demoSlug)
            ->where('type', 'demo');

        // If MODE 2, filter by organisation_id
        if ($orgId) {
            $query = $query->where('organisation_id', $orgId);
        } else {
            $query = $query->whereNull('organisation_id');
        }

        $existingElection = $query->first();

        // Demo election already exists
        if ($existingElection) {
            $posts = DemoPost::where('election_id', $existingElection->id)->count();
            $candidates = DemoCandidacy::where('election_id', $existingElection->id)->count();
            $codes = DemoCode::where('election_id', $existingElection->id)->count();

            $this->info("\n📋 Demo election already exists:");
            $this->info("  ID: {$existingElection->id}");
            $this->info("  Name: {$existingElection->name}");
            $this->info("  Posts: {$posts}");
            $this->info("  Candidates: {$candidates}");
            $this->info("  Codes: {$codes}");
            $this->info("  Organisation ID: " . ($existingElection->organisation_id ?? 'NULL (Public Demo)'));
            $this->info("  Mode: " . $mode);

            if ($this->option('force') || $this->option('clean')) {
                if ($this->option('force') && !$this->option('clean')) {
                    if (!$this->confirm('⚠️  This will DELETE the existing demo election and all its data. Continue?')) {
                        $this->warn('Aborted.');
                        return 1;
                    }
                }
                $this->info('Deleting existing demo election...');
                $existingElection->delete();
            } else {
                $this->info("\n💡 To recreate, use: php artisan demo:setup --force" . ($orgId ? " --org={$orgId}" : ''));
                return 0;
            }
        }

        // Create new demo election
        $this->info("\n📝 Creating demo election ({$mode})...");

        // For MODE 2, use unique slug per organization
        $demoSlug = $mode === 'MODE 2' ? 'demo-election-org-' . $orgId : 'demo-election';

        $election = Election::create([
            'name' => 'Demo Election',
            'slug' => $demoSlug,
            'type' => 'demo',
            'is_active' => true,
            'description' => $mode === 'MODE 2'
                ? 'Demo election for ' . $targetOrganization->name . ' - test voting before live elections'
                : 'Public demo election for testing the voting system without registration',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(365)->format('Y-m-d'),
            'organisation_id' => $orgId ? (int)$orgId : null,  // MODE 1: NULL, MODE 2: org_id
        ]);

        $this->info("✅ Created Demo Election: {$election->name}");
        $this->info("   ID: {$election->id}");
        $this->info("   Organisation ID: " . ($election->organisation_id ?? 'NULL (Public Demo)'));
        $this->info("   Mode: {$mode}");

        // Verify organisation_id is set correctly
        if ($mode === 'MODE 2') {
            if ($election->organisation_id === (int)$orgId) {
                $this->info('   ✓ Correctly scoped to organisation: ' . $targetOrganization->name);
            } else {
                $this->error('   ✗ ERROR: organisation_id should be ' . $orgId . ' for MODE 2!');
            }
        } else {
            if ($election->organisation_id === null) {
                $this->info('   ✓ Correctly set to NULL (MODE 1 - Public demo)');
            } else {
                $this->error('   ✗ ERROR: organisation_id should be NULL for MODE 1!');
            }
        }

        // Create posts
        $posts = [
            [
                'post_id' => 'president-' . $election->id,
                'name' => 'President',
                'nepali_name' => 'राष्ट्रपति',
                'position_order' => 1,
                'candidates' => [
                    ['user_name' => 'Alice Johnson', 'candidacy_name' => 'Alice Johnson - Progressive Platform', 'proposer_name' => 'John Doe', 'supporter_name' => 'Jane Smith'],
                    ['user_name' => 'Bob Smith', 'candidacy_name' => 'Bob Smith - Economic Growth', 'proposer_name' => 'Michael Brown', 'supporter_name' => 'Sarah Wilson'],
                    ['user_name' => 'Carol Williams', 'candidacy_name' => 'Carol Williams - Community First', 'proposer_name' => 'David Lee', 'supporter_name' => 'Emma Davis'],
                ]
            ],
            [
                'post_id' => 'vice-president-' . $election->id,
                'name' => 'Vice President',
                'nepali_name' => 'उप-राष्ट्रपति',
                'position_order' => 2,
                'candidates' => [
                    ['user_name' => 'Daniel Miller', 'candidacy_name' => 'Daniel Miller - Innovation Leader', 'proposer_name' => 'Robert Johnson', 'supporter_name' => 'Patricia Garcia'],
                    ['user_name' => 'Eva Martinez', 'candidacy_name' => 'Eva Martinez - Social Justice', 'proposer_name' => 'Kevin Brown', 'supporter_name' => 'Lisa Anderson'],
                    ['user_name' => 'Frank Wilson', 'candidacy_name' => 'Frank Wilson - Infrastructure Expert', 'proposer_name' => 'Paul Taylor', 'supporter_name' => 'Mary Thomas'],
                ]
            ],
            [
                'post_id' => 'secretary-' . $election->id,
                'name' => 'Secretary',
                'nepali_name' => 'सचिव',
                'position_order' => 3,
                'candidates' => [
                    ['user_name' => 'Grace Lee', 'candidacy_name' => 'Grace Lee - Administration Expert', 'proposer_name' => 'James Harris', 'supporter_name' => 'Nancy Clark'],
                    ['user_name' => 'Henry White', 'candidacy_name' => 'Henry White - Organization Specialist', 'proposer_name' => 'Christopher Lewis', 'supporter_name' => 'Jennifer Martin'],
                    ['user_name' => 'Iris Walker', 'candidacy_name' => 'Iris Walker - Communications Lead', 'proposer_name' => 'Daniel Hall', 'supporter_name' => 'Michelle Moore'],
                ]
            ],
        ];

        $totalCandidates = 0;
        $totalCodes = 0;
        $globalCandidateCounter = 0;

        foreach ($posts as $postData) {
            $candidates = $postData['candidates'];
            unset($postData['candidates']);

            // Create demo post with election_id and organisation_id
            $post = DemoPost::create([
                ...$postData,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id,  // MODE 1: NULL, MODE 2: org_id
                'state_name' => 'National',
                'required_number' => 1,
            ]);

            $this->info("  ├─ Created Demo Post: {$post->name} ({$post->nepali_name})");

            foreach ($candidates as $index => $candidate) {
                $globalCandidateCounter++;
                DemoCandidacy::create([
                    'user_id' => "demo-{$post->post_id}-" . ($index + 1),
                    'post_id' => $post->post_id,
                    'election_id' => $election->id,
                    'organisation_id' => $election->organisation_id,  // MODE 1: NULL, MODE 2: org_id
                    'candidacy_id' => "demo-{$post->post_id}-" . ($index + 1),
                    'user_name' => $candidate['user_name'],
                    'candidacy_name' => $candidate['candidacy_name'],
                    'proposer_name' => $candidate['proposer_name'],
                    'supporter_name' => $candidate['supporter_name'],
                    'position_order' => $index + 1,
                    'image_path_1' => "candidate_" . $globalCandidateCounter . ".png",
                ]);
                $totalCandidates++;

                // Create demo verification codes for each demo candidate
                // Note: user_id is NULL initially - it gets populated when a voter uses the codes
                DemoCode::create([
                    'user_id' => null,  // Anonymous demo code - no voter assigned yet
                    'election_id' => $election->id,
                    'organisation_id' => $election->organisation_id,  // MODE 1: NULL, MODE 2: org_id
                    'code1' => 'DEMO' . strtoupper(substr(md5($globalCandidateCounter . 'code1'), 0, 8)),
                    'code2' => 'DEMO' . strtoupper(substr(md5($globalCandidateCounter . 'code2'), 0, 8)),
                    'code3' => 'DEMO' . strtoupper(substr(md5($globalCandidateCounter . 'code3'), 0, 8)),
                    'code4' => 'DEMO' . strtoupper(substr(md5($globalCandidateCounter . 'code4'), 0, 8)),
                    'is_code1_usable' => true,
                    'is_code2_usable' => true,
                    'is_code3_usable' => true,
                    'is_code4_usable' => true,
                    'can_vote_now' => false,
                    'voting_time_in_minutes' => 30,
                    'code1_sent_at' => now(),
                ]);
                $totalCodes++;
            }

            $this->info("  │  ├─ Added " . count($candidates) . " demo candidates");
            $this->info("  │  └─ Added " . count($candidates) . " demo verification codes");
        }

        $this->info("\n📊 Demo Election Summary:");
        $this->info("  ✅ Election: {$election->name}");
        $this->info("  ✅ Posts: " . count($posts));
        $this->info("  ✅ Total Candidates: {$totalCandidates}");
        $this->info("  ✅ Verification Codes: {$totalCodes}");
        $this->info("  ✅ Mode: {$mode}");
        $this->info("  ✅ Organisation ID: " . ($election->organisation_id ?? 'NULL (Public)'));

        if ($mode === 'MODE 2') {
            $this->info("\n🚀 Accessing MODE 2 Demo Election:");
            $this->info("   Users from {$targetOrganization->name} can access:");
            $this->info("   http://localhost:8000/election/demo/start");
            $this->info("   Other users will see permission errors (correct behavior).\n");
        } else {
            $this->info("\n🚀 Access at: http://localhost:8000/election/demo/start");
            $this->info("📢 This is a PUBLIC demo election!");
            $this->info("   Any user can participate in voting.\n");
        }

        $this->info("✅ Setup complete!\n");

        return 0;
    }
}
