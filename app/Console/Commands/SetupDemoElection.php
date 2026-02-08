<?php

namespace App\Console\Commands;

use App\Models\Election;
use App\Models\Post;
use App\Models\DemoCandidate;
use Illuminate\Console\Command;

class SetupDemoElection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:setup {--force : Force recreation of existing demo election}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup demo election (one-time or with --force flag). Production-safe alternative to seeder.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Checking for existing demo election...');

        $existingElection = Election::where('slug', 'demo-election')
            ->where('type', 'demo')
            ->first();

        // Demo election already exists
        if ($existingElection) {
            $posts = Post::where('post_id', 'like', '%-' . $existingElection->id)->count();
            $candidates = DemoCandidate::where('election_id', $existingElection->id)->count();

            $this->info("\n📋 Demo election already exists:");
            $this->info("  ID: {$existingElection->id}");
            $this->info("  Name: {$existingElection->name}");
            $this->info("  Posts: {$posts}");
            $this->info("  Candidates: {$candidates}");

            if ($this->option('force')) {
                if (!$this->confirm('⚠️  This will DELETE the existing demo election and all its data. Continue?')) {
                    $this->warn('Aborted.');
                    return 1;
                }
                $this->info('Deleting existing demo election...');
                $existingElection->delete();
            } else {
                $this->info("\n💡 To recreate, use: php artisan demo:setup --force");
                return 0;
            }
        }

        // Create new demo election
        $this->info("\n📝 Creating demo election...");

        $election = Election::create([
            'name' => 'Demo Election',
            'slug' => 'demo-election',
            'type' => 'demo',
            'is_active' => true,
            'description' => 'Public demo election for testing the voting system without registration',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(365)->format('Y-m-d'),
        ]);

        $this->info("✅ Created Demo Election: {$election->name} (ID: {$election->id})");

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

        foreach ($posts as $postData) {
            $candidates = $postData['candidates'];
            unset($postData['candidates']);

            $post = Post::create([
                ...$postData,
                'state_name' => 'National',
                'required_number' => 1,
            ]);

            $this->info("  ├─ Created Post: {$post->name} ({$post->nepali_name})");

            foreach ($candidates as $index => $candidate) {
                DemoCandidate::create([
                    'user_id' => "demo-{$post->post_id}-" . ($index + 1),
                    'post_id' => $post->post_id,
                    'election_id' => $election->id,
                    'candidacy_id' => "demo-{$post->post_id}-" . ($index + 1),
                    'user_name' => $candidate['user_name'],
                    'candidacy_name' => $candidate['candidacy_name'],
                    'proposer_name' => $candidate['proposer_name'],
                    'supporter_name' => $candidate['supporter_name'],
                ]);
                $totalCandidates++;
            }

            $this->info("  │  └─ Added " . count($candidates) . " candidates");
        }

        $this->info("\n📊 Demo Election Summary:");
        $this->info("  ✅ Election: {$election->name}");
        $this->info("  ✅ Posts: " . count($posts));
        $this->info("  ✅ Total Candidates: {$totalCandidates}");
        $this->info("\n🚀 Access at: http://localhost:8000/election/demo/start");
        $this->info("✅ Setup complete!\n");

        return 0;
    }
}
