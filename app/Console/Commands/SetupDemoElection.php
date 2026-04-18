<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\Organisation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupDemoElection extends Command
{
    protected $signature = 'demo:setup
                            {--org=publicdigit : Organisation slug or ID (default: publicdigit)}
                            {--force : Force recreation of existing demo election}
                            {--clean : Delete existing demo data without confirmation}';

    protected $description = 'Setup organisation-scoped demo election with national and regional candidates.';

    public function handle()
    {
        // STEP 1: Validate organisation parameter
        $orgIdentifier = $this->option('org');

        // STEP 2: Find the organisation (by slug OR ID for flexibility)
        // PostgreSQL requires strict type matching: only query ID if it's a valid UUID
        $organisation = Organisation::where('slug', $orgIdentifier);

        if (Str::isUuid($orgIdentifier)) {
            $organisation = $organisation->orWhere('id', $orgIdentifier);
        }

        $organisation = $organisation->first();

        if (!$organisation) {
            $this->error("❌ Organisation with slug/ID '{$orgIdentifier}' not found!");
            return 1;
        }

        $this->info('');
        $this->info('🚀 Setting up demo election for organisation:');
        $this->info("   📌 Organisation: {$organisation->name} (ID: {$organisation->id})");
        $this->info("   📌 Slug: {$organisation->slug}");
        $this->info('🔍 Checking for existing demo election...');

        // STEP 3: Find existing demo election for this organisation
        // Note: withTrashed() includes soft-deleted records to handle unique constraint violations
        $demoSlug = 'demo-election-' . $organisation->slug;

        $existingElection = Election::withoutGlobalScopes()
            ->withTrashed()  // Include soft-deleted elections
            ->where('slug', $demoSlug)
            ->where('type', 'demo')
            ->where('organisation_id', $organisation->id)
            ->first();

        // STEP 4: Handle existing election
        if ($existingElection) {
            // Only display info if election is not soft-deleted
            if (!$existingElection->trashed()) {
                $this->displayExistingElectionInfo($existingElection, $organisation);
            }

            if ($this->shouldDeleteExisting()) {
                $this->info('🗑️  Deleting existing demo election...');
                // Force-delete to remove from unique constraint
                if ($existingElection->trashed()) {
                    $this->line('  └─ (Soft-deleted record being permanently removed)');
                }
                $existingElection->forceDelete();
                $this->info('✅ Existing demo election deleted.');
            } else {
                // Only show "using existing" if not soft-deleted
                if (!$existingElection->trashed()) {
                    $this->info('✅ Using existing demo election.');
                    return 0;
                } else {
                    // Soft-deleted record exists but user doesn't want to force reset
                    // Cannot proceed without resetting
                    $this->warn('⚠️  A soft-deleted demo election exists. Use --force to reset.');
                    return 1;
                }
            }
        }

        // STEP 5: Create new demo election
        $election = $this->createDemoElection($organisation, $demoSlug);

        // STEP 6: Create posts with candidates
        $stats = $this->createPostsWithCandidates($election, $organisation);

        // STEP 7: Display summary
        // Note: Demo codes are NOT pre-generated here - they are created per-user
        // when a voter starts the demo voting flow (see DemoVotingController)
        $this->displaySummary($election, $organisation, $stats);

        return 0;
    }

    /**
     * Display existing election information
     */
    private function displayExistingElectionInfo($election, $organisation)
    {
        $posts = DemoPost::where('election_id', $election->id)->count();

        // Count candidates through demo_posts (since demo_candidacies doesn't have election_id)
        $postIds   = DemoPost::where('election_id', $election->id)->pluck('id')->toArray();
        $candidates = DemoCandidacy::whereIn('post_id', $postIds)->count();

        $this->info("\n📋 Demo election already exists for {$organisation->name}:");
        $this->info("  ID: {$election->id}");
        $this->info("  Name: {$election->name}");
        $this->info("  Slug: {$election->slug}");
        $this->info("  Posts: {$posts}");
        $this->info("  Candidates: {$candidates}");
    }

    /**
     * Check if we should delete existing election
     */
    private function shouldDeleteExisting()
    {
        if ($this->option('force') || $this->option('clean')) {
            if ($this->option('force') && !$this->option('clean')) {
                if (function_exists('posix_isatty') && posix_isatty(STDIN)) {
                    if (!$this->confirm('⚠️  This will DELETE the existing demo election. Continue?')) {
                        $this->warn('Aborted.');
                        return false;
                    }
                } else {
                    $this->warn('⚠️  --force flag used - deleting existing demo election');
                }
            }
            return true;
        }

        $this->info("\n💡 To reset, use: php artisan demo:setup --org={$this->option('org')} --force");
        return false;
    }

    /**
     * Create demo election scoped to organisation
     * Ensures election is active for hasActiveElection() relationship to work
     */
    private function createDemoElection($organisation, $demoSlug)
    {
        $this->info("\n📝 Creating demo election for {$organisation->name}...");

        $election = Election::create([
            'name'            => 'Demo Election - ' . $organisation->name,
            'slug'            => $demoSlug,
            'type'            => 'demo',
            'status'          => 'active',  // Updated for new model relationship
            'is_active'       => true,      // Keep for backwards compatibility
            'description'     => 'Demo election for ' . $organisation->name . ' - test voting before live elections',
            'start_date'      => now(),     // Start immediately
            'end_date'        => now()->addDays(365),  // Active for 1 year
            'organisation_id' => $organisation->id,
        ]);

        $this->info("✅ Created Demo Election:");
        $this->info("   ID: {$election->id}");
        $this->info("   Name: {$election->name}");
        $this->info("   Slug: {$election->slug}");
        $this->info("   Status: {$election->status}");
        $this->info("   Organisation: {$organisation->name} (ID: {$organisation->id})");
        $this->info("   Active Period: {$election->start_date->format('Y-m-d')} to {$election->end_date->format('Y-m-d')}");

        return $election;
    }

    /**
     * Create posts with candidates (national and regional)
     */
    private function createPostsWithCandidates($election, $organisation)
    {
        $stats = [
            'posts'          => 0,
            'candidates'     => 0,
            'national_posts' => 0,
            'regional_posts' => 0,
        ];

        // NATIONAL POSTS
        $nationalPosts          = $this->getNationalPosts();
        $stats['national_posts'] = count($nationalPosts);

        foreach ($nationalPosts as $postData) {
            $postStats = $this->createPost($election, $organisation, $postData, true, null);
            $stats['posts']++;
            $stats['candidates'] += $postStats['candidates'];
        }

        // REGIONAL POSTS
        $regions = $this->getRegionsForOrganisation($organisation);

        foreach ($regions as $region) {
            $regionalPosts           = $this->getRegionalPosts();
            $stats['regional_posts'] += count($regionalPosts);

            foreach ($regionalPosts as $postData) {
                $postStats = $this->createPost($election, $organisation, $postData, false, $region);
                $stats['posts']++;
                $stats['candidates'] += $postStats['candidates'];
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
                'name'            => 'President',
                'position_order'  => 1,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('president', 3),
            ],
            [
                'name'            => 'General Secretary (Geschäftsführer)',
                'position_order'  => 2,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('secretary', 3),
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
                'name'            => 'Regional Representative 1',
                'position_order'  => 3,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('regional', 3),
            ],
            [
                'name'            => 'Regional Representative 2',
                'position_order'  => 4,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('regional', 3),
            ],
        ];
    }

    /**
     * Generate random candidate names and slogans
     */
    private function generateRandomCandidates(string $type, int $count): array
    {
        $firstNames = [
            'James', 'John', 'Robert', 'Michael', 'William', 'David', 'Richard', 'Joseph', 'Thomas', 'Charles',
            'Mary', 'Patricia', 'Jennifer', 'Linda', 'Elizabeth', 'Barbara', 'Susan', 'Jessica', 'Sarah', 'Karen',
            'Hans', 'Klaus', 'Wolfgang', 'Friedrich', 'Heinrich', 'Anna', 'Maria', 'Christina', 'Ursula', 'Petra',
            'Rahul', 'Priya', 'Amit', 'Sunita', 'Bikash', 'Sita', 'Ram', 'Gita', 'Mohan', 'Laxmi',
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
            'Müller', 'Schmidt', 'Schneider', 'Fischer', 'Weber', 'Meyer', 'Wagner', 'Becker', 'Schulz', 'Hoffmann',
            'Sharma', 'Thapa', 'Gurung', 'Tamang', 'Shrestha', 'Rai', 'Limbu', 'Karki', 'Basnet', 'Adhikari',
        ];

        $sloganTemplates = [
            'president' => ['For a Stronger Future', 'Leadership You Can Trust', 'Putting People First', 'Building Together', 'Your Voice, Our Mission'],
            'secretary' => ['Efficient Administration', 'Streamlining Our Processes', 'Transparency & Accountability', 'Working for Members'],
            'regional'  => ['Local Voice, Global Vision', 'Building Our Community', 'Regional Strength', 'Your Area, Your Representative'],
        ];

        $templates = $sloganTemplates[$type] ?? $sloganTemplates['regional'];

        $candidates = [];
        $usedNames  = [];

        for ($i = 0; $i < $count; $i++) {
            // Ensure unique names
            do {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName  = $lastNames[array_rand($lastNames)];
                $fullName  = $firstName . ' ' . $lastName;
            } while (in_array($fullName, $usedNames));

            $usedNames[] = $fullName;
            $slogan      = $templates[$i % count($templates)];

            $candidates[] = [
                'name'           => $fullName,
                'candidacy_name' => $fullName . ' – ' . $slogan,
            ];
        }

        return $candidates;
    }

    /**
     * Get regions for organisation (configurable per organisation)
     */
    private function getRegionsForOrganisation($organisation)
    {
        return ['Europe', 'America', 'Asia', 'Africa'];
    }

    /**
     * Create a single post with its candidates
     */
    private function createPost($election, $organisation, $postData, $isNational, $region = null)
    {
        $candidates = $postData['candidates'];
        unset($postData['candidates']);

        // Create demo post with correct schema (no post_id field)
        $post = DemoPost::create([
            'name'             => $postData['name'] . ($region ? ' - ' . $region : ''),
            'position_order'   => $postData['position_order'],
            'required_number'  => $postData['required_number'],
            'is_national_wide' => $isNational ? 1 : 0,
            'state_name'       => $region,
            'election_id'      => $election->id,
            'organisation_id'  => $organisation->id,
        ]);

        $regionText = $region ? " (Region: {$region})" : " (National)";
        $this->line("  ├─ Created Demo Post: {$post->name}{$regionText}");

        $candidateCount = 0;

        foreach ($candidates as $index => $candidate) {
            $candidateCount++;

            // Create demo candidacy with correct schema
            DemoCandidacy::create([
                'post_id'         => $post->id,
                'organisation_id' => $organisation->id,
                'user_id'         => null,
                'name'            => $candidate['candidacy_name'],
                'description'     => $candidate['name'],
                'position_order'  => $index + 1,
            ]);
        }

        $this->line("  │  └─ Added {$candidateCount} demo candidates");

        return ['candidates' => $candidateCount];
    }

    /**
     * Display setup summary
     */
    private function displaySummary($election, $organisation, $stats)
    {
        // Check if election is active using new hasActiveElection logic
        $isActive = $election->status === 'active'
                 && $election->start_date <= now()
                 && $election->end_date >= now();

        $this->info("\n📊 Demo Election Summary for {$organisation->name}:");
        $this->info("  ✅ Election ID: {$election->id}");
        $this->info("  ✅ Election Name: {$election->name}");
        $this->info("  ✅ Slug: {$election->slug}");
        $this->info("  ✅ Status: " . ($isActive ? "🟢 ACTIVE" : "🔴 INACTIVE"));
        $this->info("  ✅ Date Range: {$election->start_date->format('Y-m-d')} to {$election->end_date->format('Y-m-d')}");
        $this->info("  ✅ Total Posts: {$stats['posts']}");
        $this->info("     ├─ National Posts: {$stats['national_posts']}");
        $this->info("     └─ Regional Posts: {$stats['regional_posts']}");
        $this->info("  ✅ Total Candidates: {$stats['candidates']}");

        // Show voter slug information
        $voterSlugsCount = \DB::table('voter_slugs')
            ->where('election_id', $election->id)
            ->count();
        $this->info("  ✅ Demo Voter Slugs: {$voterSlugsCount}");

        $this->info("\n📋 Candidate Breakdown:");
        $this->info("  ├─ President: 3 random candidates");
        $this->info("  ├─ General Secretary (Geschäftsführer): 3 random candidates");
        $this->info("  ├─ Regional Representative 1 – Europe: 3 random candidates");
        $this->info("  ├─ Regional Representative 2 – Europe: 3 random candidates");
        $this->info("  ├─ Regional Representative 1 – America: 3 random candidates");
        $this->info("  ├─ Regional Representative 2 – America: 3 random candidates");
        $this->info("  ├─ Regional Representative 1 – Asia: 3 random candidates");
        $this->info("  ├─ Regional Representative 2 – Asia: 3 random candidates");
        $this->info("  ├─ Regional Representative 1 – Africa: 3 random candidates");
        $this->info("  └─ Regional Representative 2 – Africa: 3 random candidates");

        $this->info("\n💡 Demo codes are created per-user when voters start the demo voting flow.");
        $this->info("💡 Voter progress is tracked via voter_slugs with current_step and step_meta.");

        $this->info("\n✅ Demo election setup complete for {$organisation->name}!");
        $this->info("📢 Access URL: " . url('/election/demo/start'));
        $this->info("   Organisation ID: {$organisation->id}");
        $this->info("   Organisation Slug: {$organisation->slug}");
        $this->info("   Election ID: {$election->id}");
        $this->info("   Election Slug: {$election->slug}\n");
    }
}
