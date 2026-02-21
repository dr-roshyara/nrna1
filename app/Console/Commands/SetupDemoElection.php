<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Models\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
    protected $description = 'Setup demo election in MODE 1 (public) or MODE 2 (organisation-scoped) with national and regional candidates. Production-safe alternative to seeder.';

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
        $demoSlug = $orgId ? 'demo-election-org-' . $orgId : 'demo-election';

        $query = Election::withoutGlobalScopes()
            ->where('slug', $demoSlug)
            ->where('type', 'demo');

        if ($orgId) {
            $query = $query->where('organisation_id', $orgId);
        } else {
            $query = $query->whereNull('organisation_id');
        }

        $existingElection = $query->first();

        // Handle existing election
        if ($existingElection) {
            $this->displayExistingElectionInfo($existingElection, $mode);

            if ($this->shouldDeleteExisting()) {
                $this->info('Deleting existing demo election...');
                $existingElection->delete();
            } else {
                return 0;
            }
        }

        // Create new demo election
        $election = $this->createDemoElection($orgId, $mode, $demoSlug, $targetOrganization);

        // Create posts (national and regional)
        $stats = $this->createPostsWithCandidates($election, $mode, $targetOrganization);

        // Display summary
        $this->displaySummary($election, $mode, $stats, $targetOrganization);

        return 0;
    }

    /**
     * Display existing election information
     */
    private function displayExistingElectionInfo($election, $mode)
    {
        $posts = DemoPost::where('election_id', $election->id)->count();
        $candidates = DemoCandidacy::where('election_id', $election->id)->count();
        $codes = DemoCode::where('election_id', $election->id)->count();

        $this->info("\n📋 Demo election already exists:");
        $this->info("  ID: {$election->id}");
        $this->info("  Name: {$election->name}");
        $this->info("  Posts: {$posts}");
        $this->info("  Candidates: {$candidates}");
        $this->info("  Codes: {$codes}");
        $this->info("  Organisation ID: " . ($election->organisation_id ?? 'NULL (Public Demo)'));
        $this->info("  Mode: " . $mode);
    }

    /**
     * Check if we should delete existing election
     */
    private function shouldDeleteExisting()
    {
        if ($this->option('force') || $this->option('clean')) {
            if ($this->option('force') && !$this->option('clean')) {
                if (!$this->confirm('⚠️  This will DELETE the existing demo election and all its data. Continue?')) {
                    $this->warn('Aborted.');
                    return false;
                }
            }
            return true;
        }

        $this->info("\n💡 To recreate, use: php artisan demo:setup --force" . ($this->option('org') ? " --org={$this->option('org')}" : ''));
        return false;
    }

    /**
     * Create demo election
     */
    private function createDemoElection($orgId, $mode, $demoSlug, $targetOrganization)
    {
        $this->info("\n📝 Creating demo election ({$mode})...");

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
            'organisation_id' => $orgId ? (int)$orgId : null,
        ]);

        $this->info("✅ Created Demo Election: {$election->name}");
        $this->info("   ID: {$election->id}");
        $this->info("   Organisation ID: " . ($election->organisation_id ?? 'NULL (Public Demo)'));
        $this->info("   Mode: {$mode}");

        // Verify organisation_id
        $this->verifyOrganisationId($election, $mode, $orgId, $targetOrganization);

        return $election;
    }

    /**
     * Verify organisation_id is set correctly
     */
    private function verifyOrganisationId($election, $mode, $orgId, $targetOrganization)
    {
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
    }

    /**
     * Create posts with candidates (national and regional)
     */
    private function createPostsWithCandidates($election, $mode, $targetOrganization)
    {
        $stats = [
            'posts' => 0,
            'candidates' => 0,
            'codes' => 0,
            'national_posts' => 0,
            'regional_posts' => 0,
        ];

        // NATIONAL POSTS
        $nationalPosts = $this->getNationalPosts();
        $stats['national_posts'] = count($nationalPosts);

        foreach ($nationalPosts as $postData) {
            $postStats = $this->createPost($election, $postData, true, null);
            $stats['posts']++;
            $stats['candidates'] += $postStats['candidates'];
            $stats['codes'] += $postStats['codes'];
        }

        // REGIONAL POSTS (if MODE 2 with organization, or for public demo)
        $regions = $this->getRegions($mode, $targetOrganization);

        foreach ($regions as $region) {
            $regionalPosts = $this->getRegionalPosts();
            $stats['regional_posts'] += count($regionalPosts);

            foreach ($regionalPosts as $postData) {
                $postStats = $this->createPost($election, $postData, false, $region);
                $stats['posts']++;
                $stats['candidates'] += $postStats['candidates'];
                $stats['codes'] += $postStats['codes'];
            }
        }

        return $stats;
    }

    /**
     * Get national posts configuration
     */
    private function getNationalPosts()
    {
        return [
            [
                'post_id_prefix' => 'president',
                'name' => 'President',
                'nepali_name' => 'राष्ट्रपति',
                'position_order' => 1,
                'required_number' => 1,
                'candidates' => [
                    ['name' => 'Alice Johnson', 'candidacy_name' => 'Alice Johnson - Progressive Platform'],
                    ['name' => 'Bob Smith', 'candidacy_name' => 'Bob Smith - Economic Growth'],
                    ['name' => 'Carol Williams', 'candidacy_name' => 'Carol Williams - Community First'],
                ]
            ],
            [
                'post_id_prefix' => 'vice_president',
                'name' => 'Vice President',
                'nepali_name' => 'उप-राष्ट्रपति',
                'position_order' => 2,
                'required_number' => 1,
                'candidates' => [
                    ['name' => 'Daniel Miller', 'candidacy_name' => 'Daniel Miller - Innovation Leader'],
                    ['name' => 'Eva Martinez', 'candidacy_name' => 'Eva Martinez - Social Justice'],
                    ['name' => 'Frank Wilson', 'candidacy_name' => 'Frank Wilson - Infrastructure Expert'],
                ]
            ],
        ];
    }

    /**
     * Get regional posts configuration
     */
    private function getRegionalPosts()
    {
        return [
            [
                'post_id_prefix' => 'state_rep',
                'name' => 'State Representative',
                'nepali_name' => 'प्रदेश सभा सदस्य',
                'position_order' => 3,
                'required_number' => 2,
                'candidates' => [
                    ['name' => 'Hans Mueller', 'candidacy_name' => 'Hans Mueller - Local Development'],
                    ['name' => 'Anna Schmidt', 'candidacy_name' => 'Anna Schmidt - Education Focus'],
                    ['name' => 'Klaus Weber', 'candidacy_name' => 'Klaus Weber - Infrastructure'],
                ]
            ],
            [
                'post_id_prefix' => 'district_rep',
                'name' => 'District Representative',
                'nepali_name' => 'जिल्ला सभा सदस्य',
                'position_order' => 4,
                'required_number' => 1,
                'candidates' => [
                    ['name' => 'Maria Fischer', 'candidacy_name' => 'Maria Fischer - Health Services'],
                    ['name' => 'Thomas Wagner', 'candidacy_name' => 'Thomas Wagner - Youth Empowerment'],
                ]
            ],
        ];
    }

    /**
     * Get regions based on mode
     */
    private function getRegions($mode, $targetOrganization)
    {
        // For MODE 2, return regions from the organization
        if ($mode === 'MODE 2' && $targetOrganization) {
            // This could come from organization settings
            return ['Bayern', 'Baden-Württemberg', 'North Rhine-Westphalia'];
        }

        // For public demo, return a few sample regions
        return ['Bayern', 'Baden-Württemberg'];
    }

    /**
     * Create a single post with candidates
     */
    private function createPost($election, $postData, $isNational, $region = null)
    {
        $candidates = $postData['candidates'];
        unset($postData['candidates']);

        $postId = $postData['post_id_prefix'] . '-' . $election->id . ($region ? '-' . Str::slug($region) : '');

        $post = DemoPost::create([
            'post_id' => $postId,
            'name' => $postData['name'] . ($region ? ' - ' . $region : ''),
            'nepali_name' => $postData['nepali_name'],
            'position_order' => $postData['position_order'],
            'required_number' => $postData['required_number'],
            'is_national_wide' => $isNational ? 1 : 0,
            'state_name' => $region, // NULL for national, region name for regional
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
        ]);

        $regionText = $region ? " (Region: {$region})" : " (National)";
        $this->info("  ├─ Created Demo Post: {$post->name}{$regionText}");

        $candidateCount = 0;
        $codeCount = 0;

        foreach ($candidates as $index => $candidate) {
            $candidateCount++;

            // Generate candidate image filename: CandidacyName_PostName_01.png
            $candidacyNameSlug = Str::slug($candidate['name']);
            $postNameSlug = Str::slug($postData['name']);
            $regionSlug = $region ? '-' . Str::slug($region) : '';
            $imagePath = "candidates/{$candidacyNameSlug}_{$postNameSlug}{$regionSlug}_" .
                        str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ".png";

            DemoCandidacy::create([
                'user_id' => "demo-{$postId}-" . ($index + 1),
                'post_id' => $post->post_id,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id,
                'candidacy_id' => "demo-{$postId}-" . ($index + 1),
                'user_name' => $candidate['name'],
                'candidacy_name' => $candidate['candidacy_name'],
                'proposer_name' => $this->getProposerName($index),
                'supporter_name' => $this->getSupporterName($index),
                'position_order' => $index + 1,
                'image_path_1' => $imagePath,
            ]);

            // Create demo verification codes
            DemoCode::create([
                'user_id' => null,
                'election_id' => $election->id,
                'organisation_id' => $election->organisation_id,
                'code1' => 'DEMO' . strtoupper(substr(md5($candidateCount . 'code1' . $region), 0, 8)),
                'code2' => 'DEMO' . strtoupper(substr(md5($candidateCount . 'code2' . $region), 0, 8)),
                'code3' => 'DEMO' . strtoupper(substr(md5($candidateCount . 'code3' . $region), 0, 8)),
                'code4' => 'DEMO' . strtoupper(substr(md5($candidateCount . 'code4' . $region), 0, 8)),
                'is_code1_usable' => true,
                'is_code2_usable' => true,
                'is_code3_usable' => true,
                'is_code4_usable' => true,
                'can_vote_now' => false,
                'voting_time_in_minutes' => 30,
                'code1_sent_at' => now(),
            ]);
            $codeCount++;
        }

        $this->info("  │  ├─ Added " . $candidateCount . " demo candidates");
        $this->info("  │  └─ Added " . $codeCount . " demo verification codes");

        return [
            'candidates' => $candidateCount,
            'codes' => $codeCount,
        ];
    }

    /**
     * Get proposer name (random for demo)
     */
    private function getProposerName($index)
    {
        $proposers = ['John Doe', 'Michael Brown', 'Robert Johnson', 'David Lee', 'James Harris'];
        return $proposers[$index % count($proposers)];
    }

    /**
     * Get supporter name (random for demo)
     */
    private function getSupporterName($index)
    {
        $supporters = ['Jane Smith', 'Sarah Wilson', 'Emma Davis', 'Nancy Clark', 'Jennifer Martin'];
        return $supporters[$index % count($supporters)];
    }

    /**
     * Display summary of created data
     */
    private function displaySummary($election, $mode, $stats, $targetOrganization)
    {
        $this->info("\n📊 Demo Election Summary:");
        $this->info("  ✅ Election: {$election->name}");
        $this->info("  ✅ Total Posts: {$stats['posts']}");
        $this->info("     ├─ National Posts: {$stats['national_posts']}");
        $this->info("     └─ Regional Posts: {$stats['regional_posts']}");
        $this->info("  ✅ Total Candidates: {$stats['candidates']}");
        $this->info("  ✅ Verification Codes: {$stats['codes']}");
        $this->info("  ✅ Mode: {$mode}");
        $this->info("  ✅ Organisation ID: " . ($election->organisation_id ?? 'NULL (Public)'));

        if ($mode === 'MODE 2') {
            $this->info("\n🚀 Accessing MODE 2 Demo Election:");
            $this->info("   Users from {$targetOrganization->name} can access:");
            $this->info("   http://localhost:8000/election/demo/start");
            $this->info("   Regional candidates will be filtered by user's region");
            $this->info("   Users will see ONLY candidates from their region\n");
        } else {
            $this->info("\n🚀 Access at: http://localhost:8000/election/demo/start");
            $this->info("📢 This is a PUBLIC demo election!");
            $this->info("   Users can test with sample regions: Bayern, Baden-Württemberg");
            $this->info("   Regional candidates are shown based on user's selected region\n");
        }

        $this->info("✅ Setup complete!\n");
    }
}
