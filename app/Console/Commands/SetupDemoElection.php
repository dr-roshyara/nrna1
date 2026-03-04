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
                            {--org=1 : Organisation ID to scope the demo election (default: 1)}
                            {--force : Force recreation of existing demo election}
                            {--clean : Delete existing demo data without confirmation}';

    protected $description = 'Setup organisation-scoped demo election with national and regional candidates.';

    public function handle()
    {
        // STEP 1: Validate organisation parameter
        $orgId = $this->option('org');

        // STEP 2: Find the organisation
        $organisation = Organisation::find($orgId);
        if (!$organisation) {
            $this->error("❌ Organisation with ID {$orgId} not found!");
            return 1;
        }

        $this->info('');
        $this->info('🚀 Setting up demo election for organisation:');
        $this->info("   📌 Organisation: {$organisation->name} (ID: {$organisation->id})");
        $this->info("   📌 Slug: {$organisation->slug}");
        $this->info('🔍 Checking for existing demo election...');

        // STEP 3: Find existing demo election for this organisation
        $demoSlug = 'demo-election-' . $organisation->slug;

        $existingElection = Election::withoutGlobalScopes()
            ->where('slug', $demoSlug)
            ->where('type', 'demo')
            ->where('organisation_id', $organisation->id)
            ->first();

        // STEP 4: Handle existing election
        if ($existingElection) {
            $this->displayExistingElectionInfo($existingElection, $organisation);

            if ($this->shouldDeleteExisting()) {
                $this->info('🗑️  Deleting existing demo election...');
                $existingElection->delete();
                $this->info('✅ Existing demo election deleted.');
            } else {
                $this->info('✅ Using existing demo election.');
                return 0;
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
        $posts      = DemoPost::where('election_id', $election->id)->count();
        $candidates = DemoCandidacy::where('election_id', $election->id)->count();

        $this->info("\n📋 Demo election already exists for {$organisation->name}:");
        $this->info("  ID: {$election->id}");
        $this->info("  Name: {$election->name}");
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
     */
    private function createDemoElection($organisation, $demoSlug)
    {
        $this->info("\n📝 Creating demo election for {$organisation->name}...");

        $election = Election::create([
            'name'            => 'Demo Election - ' . $organisation->name,
            'slug'            => $demoSlug,
            'type'            => 'demo',
            'is_active'       => true,
            'description'     => 'Demo election for ' . $organisation->name . ' - test voting before live elections',
            'start_date'      => now()->format('Y-m-d'),
            'end_date'        => now()->addDays(365)->format('Y-m-d'),
            'organisation_id' => $organisation->id,
        ]);

        $this->info("✅ Created Demo Election:");
        $this->info("   ID: {$election->id}");
        $this->info("   Name: {$election->name}");
        $this->info("   Organisation: {$organisation->name} (ID: {$organisation->id})");

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
                'post_id_prefix'  => 'president',
                'name'            => 'President',
                'nepali_name'     => 'राष्ट्रपति',
                'position_order'  => 1,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('president', 3),
            ],
            [
                'post_id_prefix'  => 'general_secretary',
                'name'            => 'General Secretary (Geschäftsführer)',
                'nepali_name'     => 'महासचिव',
                'position_order'  => 2,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('secretary', 2),
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
                'post_id_prefix'  => 'regional_rep',
                'name'            => 'Regional Representative',
                'nepali_name'     => 'क्षेत्रीय प्रतिनिधि',
                'position_order'  => 3,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('regional', 2),
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

        $postId = $postData['post_id_prefix'] . '-' . $election->id . ($region ? '-' . Str::slug($region) : '');

        $post = DemoPost::create([
            'post_id'         => $postId,
            'name'            => $postData['name'] . ($region ? ' - ' . $region : ''),
            'nepali_name'     => $postData['nepali_name'],
            'position_order'  => $postData['position_order'],
            'required_number' => $postData['required_number'],
            'is_national_wide' => $isNational ? 1 : 0,
            'state_name'      => $region,
            'election_id'     => $election->id,
            'organisation_id' => $organisation->id,
        ]);

        $regionText = $region ? " (Region: {$region})" : " (National)";
        $this->line("  ├─ Created Demo Post: {$post->name}{$regionText}");

        $candidateCount = 0;

        foreach ($candidates as $index => $candidate) {
            $candidateCount++;

            $candidacyNameSlug = Str::slug($candidate['name']);
            $postNameSlug      = Str::slug($postData['name']);
            $regionSlug        = $region ? '-' . Str::slug($region) : '';
            $imagePath         = "candidates/{$candidacyNameSlug}_{$postNameSlug}{$regionSlug}_"
                               . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ".png";

            DemoCandidacy::create([
                'user_id'         => null,
                'post_id'         => $post->id,
                'election_id'     => $election->id,
                'organisation_id' => $organisation->id,
                'candidacy_id'    => "demo-{$postId}-" . ($index + 1),
                'user_name'       => $candidate['name'],
                'candidacy_name'  => $candidate['candidacy_name'],
                'proposer_name'   => $this->getProposerName($index),
                'supporter_name'  => $this->getSupporterName($index),
                'position_order'  => $index + 1,
                'image_path_1'    => $imagePath,
            ]);
        }

        $this->line("  │  └─ Added {$candidateCount} demo candidates");

        return ['candidates' => $candidateCount];
    }

    /**
     * Get proposer name
     */
    private function getProposerName($index)
    {
        $proposers = ['John Doe', 'Michael Brown', 'Robert Johnson', 'David Lee', 'James Harris',
                      'William Clark', 'Richard Lewis', 'Joseph Walker', 'Thomas Hall', 'Charles Allen'];
        return $proposers[$index % count($proposers)];
    }

    /**
     * Get supporter name
     */
    private function getSupporterName($index)
    {
        $supporters = ['Jane Smith', 'Sarah Wilson', 'Emma Davis', 'Nancy Clark', 'Jennifer Martin',
                       'Lisa Anderson', 'Margaret Taylor', 'Betty Moore', 'Dorothy Jackson', 'Helen White'];
        return $supporters[$index % count($supporters)];
    }

    /**
     * Display setup summary
     */
    private function displaySummary($election, $organisation, $stats)
    {
        $this->info("\n📊 Demo Election Summary for {$organisation->name}:");
        $this->info("  ✅ Election ID: {$election->id}");
        $this->info("  ✅ Election Name: {$election->name}");
        $this->info("  ✅ Total Posts: {$stats['posts']}");
        $this->info("     ├─ National Posts: {$stats['national_posts']}");
        $this->info("     └─ Regional Posts: {$stats['regional_posts']}");
        $this->info("  ✅ Total Candidates: {$stats['candidates']}");

        $this->info("\n📋 Candidate Breakdown:");
        $this->info("  ├─ President: 3 random candidates");
        $this->info("  ├─ General Secretary (Geschäftsführer): 2 random candidates");
        $this->info("  ├─ Regional Representative – Europe: 2 random candidates");
        $this->info("  ├─ Regional Representative – America: 2 random candidates");
        $this->info("  ├─ Regional Representative – Asia: 2 random candidates");
        $this->info("  └─ Regional Representative – Africa: 2 random candidates");

        $this->info("\n💡 Demo codes are created per-user when voters start the demo voting flow.");

        $this->info("\n✅ Demo election setup complete for {$organisation->name}!");
        $this->info("📢 Access URL: " . url('/election/demo/start'));
        $this->info("   Organisation ID: {$organisation->id}");
        $this->info("   Election ID: {$election->id}\n");
    }
}
