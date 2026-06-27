<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\Organisation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupPublicDemoElection extends Command
{
    protected $signature = 'public-demo:setup
                            {--force : Force recreation of existing demo election}
                            {--clean : Delete existing demo data without confirmation}';

    protected $description = 'Setup public demo election (anonymous, no persistence) for the PublicDigit platform.';

    public function handle()
    {
        // Get or create the PublicDigit platform organisation
        $organisation = Organisation::where('slug', 'publicdigit')
            ->orWhere('slug', 'public-digit')
            ->first();

        if (!$organisation) {
            $this->error("❌ PublicDigit platform organisation not found!");
            $this->info("   Please ensure the platform organisation exists with slug 'publicdigit'");
            return 1;
        }

        $this->info('');
        $this->info('🚀 Setting up PUBLIC DEMO election (anonymous, non-persistent):');
        $this->info("   📌 Organisation: {$organisation->name} (ID: {$organisation->id})");
        $this->info("   📌 Type: PUBLIC DEMO (no vote persistence)");
        $this->info('🔍 Checking for existing public demo election...');

        // Find existing public demo election
        $demoSlug = 'public-demo-election';

        $existingElection = Election::withoutGlobalScopes()
            ->withTrashed()
            ->where('slug', $demoSlug)
            ->where('type', 'demo')
            ->where('organisation_id', $organisation->id)
            ->first();

        // Handle existing election
        if ($existingElection) {
            if (!$existingElection->trashed()) {
                $this->displayExistingElectionInfo($existingElection, $organisation);
            }

            if ($this->shouldDeleteExisting()) {
                $this->info('🗑️  Deleting existing public demo election...');
                if ($existingElection->trashed()) {
                    $this->line('  └─ (Soft-deleted record being permanently removed)');
                }
                $existingElection->forceDelete();
                $this->info('✅ Existing public demo election deleted.');
            } else {
                if (!$existingElection->trashed()) {
                    $this->info('✅ Using existing public demo election.');
                    return 0;
                } else {
                    $this->warn('⚠️  A soft-deleted demo election exists. Use --force to reset.');
                    return 1;
                }
            }
        }

        // Create new public demo election
        $election = $this->createDemoElection($organisation, $demoSlug);

        // Create posts with candidates
        $stats = $this->createPostsWithCandidates($election, $organisation);

        // Display summary
        $this->displaySummary($election, $organisation, $stats);

        return 0;
    }

    /**
     * Display existing election information
     */
    private function displayExistingElectionInfo($election, $organisation)
    {
        $posts = DemoPost::where('election_id', $election->id)->count();
        $postIds = DemoPost::where('election_id', $election->id)->pluck('id')->toArray();
        $candidates = DemoCandidacy::whereIn('post_id', $postIds)->count();

        $this->info("\n📋 Public demo election already exists:");
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
                    if (!$this->confirm('⚠️  This will DELETE the existing public demo election. Continue?')) {
                        $this->warn('Aborted.');
                        return false;
                    }
                } else {
                    $this->warn('⚠️  --force flag used - deleting existing public demo election');
                }
            }
            return true;
        }

        $this->info("\n💡 To reset, use: php artisan public-demo:setup --force");
        return false;
    }

    /**
     * Create public demo election
     */
    private function createDemoElection($organisation, $demoSlug)
    {
        $this->info("\n📝 Creating PUBLIC demo election...");

        $election = Election::create([
            'name'            => 'Public Demo Election',
            'slug'            => $demoSlug,
            'type'            => 'demo',
            'status'          => 'active',
            'is_active'       => true,
            'description'     => 'Public demo election - anonymous voting without persistence',
            'start_date'      => now(),
            'end_date'        => now()->addDays(365),
            'organisation_id' => $organisation->id,
        ]);

        $this->info("✅ Created Public Demo Election:");
        $this->info("   ID: {$election->id}");
        $this->info("   Name: {$election->name}");
        $this->info("   Slug: {$election->slug}");
        $this->info("   Type: ANONYMOUS (no persistence)");
        $this->info("   Active Period: {$election->start_date->format('Y-m-d')} to {$election->end_date->format('Y-m-d')}");

        return $election;
    }

    /**
     * Create posts with candidates
     */
    private function createPostsWithCandidates($election, $organisation)
    {
        $stats = [
            'posts'      => 0,
            'candidates' => 0,
        ];

        // National posts only for public demo (no regional voting)
        $nationalPosts = $this->getNationalPosts();

        foreach ($nationalPosts as $postData) {
            $postStats = $this->createPost($election, $organisation, $postData);
            $stats['posts']++;
            $stats['candidates'] += $postStats['candidates'];
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
                'candidates'      => $this->generateRandomCandidates('president', 5),
            ],
            [
                'name'            => 'Vice President',
                'position_order'  => 2,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('vp', 4),
            ],
            [
                'name'            => 'Secretary General',
                'position_order'  => 3,
                'required_number' => 1,
                'candidates'      => $this->generateRandomCandidates('secretary', 4),
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
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
            'Müller', 'Schmidt', 'Schneider', 'Fischer', 'Weber', 'Meyer', 'Wagner', 'Becker', 'Schulz', 'Hoffmann',
        ];

        $sloganTemplates = [
            'president' => ['For a Stronger Future', 'Leadership You Can Trust', 'Putting People First', 'Building Together'],
            'vp'        => ['Supporting Leadership', 'Your Voice Matters', 'Together We Succeed', 'United for Change'],
            'secretary' => ['Efficient Administration', 'Transparency & Accountability', 'Working for All', 'Clear Communication'],
        ];

        $templates = $sloganTemplates[$type] ?? $sloganTemplates['president'];

        $candidates = [];
        $usedNames = [];

        for ($i = 0; $i < $count; $i++) {
            do {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = $firstName . ' ' . $lastName;
            } while (in_array($fullName, $usedNames));

            $usedNames[] = $fullName;
            $slogan = $templates[$i % count($templates)];

            $candidates[] = [
                'name'           => $fullName,
                'candidacy_name' => $fullName . ' – ' . $slogan,
            ];
        }

        return $candidates;
    }

    /**
     * Create a single post with its candidates
     */
    private function createPost($election, $organisation, $postData)
    {
        $candidates = $postData['candidates'];
        unset($postData['candidates']);

        $post = DemoPost::create([
            'name'             => $postData['name'],
            'position_order'   => $postData['position_order'],
            'required_number'  => $postData['required_number'],
            'is_national_wide' => true,
            'state_name'       => null,
            'election_id'      => $election->id,
            'organisation_id'  => $organisation->id,
        ]);

        $this->line("  ├─ Created Post: {$post->name}");

        $candidateCount = 0;

        foreach ($candidates as $index => $candidate) {
            $candidateCount++;

            DemoCandidacy::create([
                'post_id'         => $post->id,
                'election_id'     => $election->id,
                'organisation_id' => $organisation->id,
                'user_id'         => null,
                'name'            => $candidate['candidacy_name'],
                'description'     => $candidate['name'],
                'position_order'  => $index + 1,
            ]);
        }

        $this->line("  │  └─ Added {$candidateCount} candidates");

        return ['candidates' => $candidateCount];
    }

    /**
     * Display setup summary
     */
    private function displaySummary($election, $organisation, $stats)
    {
        $isActive = $election->status === 'active'
                 && $election->start_date <= now()
                 && $election->end_date >= now();

        $this->info("\n📊 Public Demo Election Summary:");
        $this->info("  ✅ Election ID: {$election->id}");
        $this->info("  ✅ Election Name: {$election->name}");
        $this->info("  ✅ Slug: {$election->slug}");
        $this->info("  ✅ Status: " . ($isActive ? "🟢 ACTIVE" : "🔴 INACTIVE"));
        $this->info("  ✅ Type: 📢 PUBLIC (anonymous, no persistence)");
        $this->info("  ✅ Date Range: {$election->start_date->format('Y-m-d')} to {$election->end_date->format('Y-m-d')}");
        $this->info("  ✅ Total Posts: {$stats['posts']}");
        $this->info("  ✅ Total Candidates: {$stats['candidates']}");

        $this->info("\n💡 Features:");
        $this->info("  • Anonymous voting (no user tracking)");
        $this->info("  • No vote persistence to database");
        $this->info("  • Session-based demo data only");
        $this->info("  • Public access without authentication");

        $this->info("\n✅ Public demo election setup complete!");
        $this->info("📢 Access URL: " . url('/public-demo/start'));
        $this->info("   Election ID: {$election->id}");
        $this->info("   Election Slug: {$election->slug}\n");
    }
}
