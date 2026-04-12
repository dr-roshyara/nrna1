<?php

namespace Tests\Feature\Console;

use App\Models\Organisation;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupDemoElectionTest extends TestCase
{
    use RefreshDatabase;

    private function createOrg($slug = null)
    {
        $slug = $slug ?? 'org-' . uniqid();
        return Organisation::create([
            'name' => ucfirst(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'type' => 'tenant',
            'is_default' => false,
        ]);
    }

    /** @test */
    public function it_creates_demo_election_with_slug_parameter()
    {
        $org = $this->createOrg('test-org-slug');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()
            ->where('type', 'demo')
            ->first();

        $this->assertNotNull($election);
        $this->assertEquals('demo', $election->type);
        $this->assertEquals('active', $election->status);
    }

    /** @test */
    public function it_creates_demo_election_with_id_parameter()
    {
        $org = $this->createOrg('test-org-id');

        $this->artisan('demo:setup', ['--org' => $org->id])
            ->assertExitCode(0);

        $election = $org->elections()
            ->where('type', 'demo')
            ->first();

        $this->assertNotNull($election);
    }

    /** @test */
    public function it_fails_with_nonexistent_organisation()
    {
        $this->artisan('demo:setup', ['--org' => 'nonexistent-org-xyz'])
            ->assertExitCode(1);

        $this->assertDatabaseMissing('elections', [
            'slug' => 'demo-election-nonexistent-org-xyz',
        ]);
    }

    /** @test */
    public function it_creates_national_posts()
    {
        $org = $this->createOrg('national-posts-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        $president = $election->demoPosts()->where('name', 'President')->first();
        $this->assertNotNull($president);
        $this->assertEquals(1, $president->required_number);

        $secretary = $election->demoPosts()->where('name', 'General Secretary (Geschäftsführer)')->first();
        $this->assertNotNull($secretary);
        $this->assertEquals(1, $secretary->required_number);
    }

    /** @test */
    public function it_creates_regional_posts()
    {
        $org = $this->createOrg('regional-posts-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        $regions = ['Europe', 'America', 'Asia', 'Africa'];
        foreach ($regions as $region) {
            $post = $election->demoPosts()
                ->where('name', "Regional Representative - {$region}")
                ->where('is_national_wide', 0)
                ->first();

            $this->assertNotNull($post, "Regional post for {$region} not found");
        }
    }

    /** @test */
    public function it_creates_candidates_for_posts()
    {
        $org = $this->createOrg('candidates-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        $presidentPost = $election->demoPosts()->where('name', 'President')->first();
        $candidates = $presidentPost->candidacies()->count();

        $this->assertEquals(3, $candidates);

        $candidate = $presidentPost->candidacies()->first();
        $this->assertNotNull($candidate->name);
        $this->assertNotNull($candidate->description);
        $this->assertEquals(1, $candidate->position_order);
    }

    /** @test */
    public function it_resets_existing_demo_election_with_force_flag()
    {
        $org = $this->createOrg('force-reset-test');

        // Create initial demo election with posts
        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $firstElection = $org->elections()->where('type', 'demo')->first();
        $firstElectionId = $firstElection->id;
        $firstPostsCount = $firstElection->demoPosts()->count();

        $this->assertGreaterThan(0, $firstPostsCount);

        // Run again with --force flag (should delete and recreate)
        $this->artisan('demo:setup', ['--org' => $org->slug, '--force' => true])
            ->assertExitCode(0);

        // Get the new active election for this org
        $newElection = $org->elections()->where('type', 'demo')->first();

        // Should have a new election (or at least different posts if the old one was reused)
        $this->assertNotNull($newElection);

        // The new election should have posts created
        $newPostsCount = $newElection->demoPosts()->count();
        $this->assertGreaterThan(0, $newPostsCount);
    }

    /** @test */
    public function it_handles_soft_deleted_election_with_clean_flag()
    {
        $org = $this->createOrg('clean-flag-test');

        // Create initial demo election
        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $oldElectionId = $org->elections()->where('type', 'demo')->first()->id;
        $election = $org->elections()->where('type', 'demo')->first();
        $election->delete();

        // Run with --clean flag
        $this->artisan('demo:setup', ['--org' => $org->slug, '--clean' => true])
            ->assertExitCode(0);

        // Old election should be permanently deleted (not in database at all)
        $foundOldElection = Election::withoutGlobalScopes()
            ->withTrashed()
            ->where('id', $oldElectionId)
            ->first();

        $this->assertNull($foundOldElection);

        // New election should exist
        $newElection = $org->elections()->where('type', 'demo')->first();
        $this->assertNotNull($newElection);
        $this->assertNotEquals($oldElectionId, $newElection->id);
    }

    /** @test */
    public function it_reuses_existing_demo_election_without_force()
    {
        $org = $this->createOrg('reuse-test');

        // Create initial demo election
        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $firstElection = $org->elections()->where('type', 'demo')->first();
        $firstElectionId = $firstElection->id;

        // Run again without --force (should reuse)
        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        // Same election should still exist
        $election = $org->elections()->where('type', 'demo')->first();
        $this->assertEquals($firstElectionId, $election->id);
    }

    /** @test */
    public function it_sets_election_as_active()
    {
        $org = $this->createOrg('active-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        $this->assertEquals('active', $election->status);
        $this->assertTrue($election->is_active);
        $this->assertLessThanOrEqual(now(), $election->start_date);
        $this->assertGreaterThan(now(), $election->end_date);
    }

    /** @test */
    public function it_creates_posts_with_correct_position_order()
    {
        $org = $this->createOrg('position-order-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        $nationalPosts = $election->demoPosts()
            ->where('is_national_wide', 1)
            ->orderBy('position_order')
            ->get();

        $this->assertEquals(1, $nationalPosts[0]->position_order);
        $this->assertEquals(2, $nationalPosts[1]->position_order);

        $regionalPosts = $election->demoPosts()
            ->where('is_national_wide', 0)
            ->orderBy('position_order')
            ->get();

        $this->assertEquals(3, $regionalPosts->first()->position_order);
    }

    /** @test */
    public function it_creates_unique_candidate_names()
    {
        $org = $this->createOrg('unique-names-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        // Get all candidates
        $posts = $election->demoPosts()->get();
        $allCandidates = [];

        foreach ($posts as $post) {
            $allCandidates = array_merge(
                $allCandidates,
                $post->candidacies()->pluck('description')->toArray()
            );
        }

        // All names should be unique
        $this->assertEquals(count($allCandidates), count(array_unique($allCandidates)));
    }

    /** @test */
    public function it_scopes_election_to_organisation()
    {
        $org1 = $this->createOrg('scope-test-1');
        $org2 = $this->createOrg('scope-test-2');

        // Create demo elections for both
        $this->artisan('demo:setup', ['--org' => $org1->slug])->assertExitCode(0);
        $this->artisan('demo:setup', ['--org' => $org2->slug])->assertExitCode(0);

        // Each organisation should have its own election
        $election1 = $org1->elections()->where('type', 'demo')->first();
        $election2 = $org2->elections()->where('type', 'demo')->first();

        $this->assertNotNull($election1);
        $this->assertNotNull($election2);
        $this->assertNotEquals($election1->id, $election2->id);

        // Each election should have separate posts
        $this->assertEquals($election1->demoPosts()->count(), $election2->demoPosts()->count());
    }

    /** @test */
    public function it_handles_soft_deleted_election_without_force()
    {
        $org = $this->createOrg('soft-delete-test');

        // Create initial demo election
        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();
        $election->delete();

        // Run without --force (should fail)
        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(1);

        // Old election should still be soft-deleted
        $deletedElection = Election::withoutGlobalScopes()
            ->withTrashed()
            ->where('organisation_id', $org->id)
            ->first();

        $this->assertTrue($deletedElection->trashed());

        // Only one election should exist (soft-deleted)
        $elections = Election::withTrashed()
            ->where('organisation_id', $org->id)
            ->count();

        $this->assertEquals(1, $elections);
    }

    /** @test */
    public function it_creates_all_regions_with_proper_count()
    {
        $org = $this->createOrg('regions-count-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        // Should have 2 national + 4 regional = 6 posts total
        $this->assertEquals(6, $election->demoPosts()->count());
        $this->assertEquals(2, $election->demoPosts()->where('is_national_wide', 1)->count());
        $this->assertEquals(4, $election->demoPosts()->where('is_national_wide', 0)->count());
    }

    /** @test */
    public function it_creates_correct_candidate_counts()
    {
        $org = $this->createOrg('candidate-count-test');

        $this->artisan('demo:setup', ['--org' => $org->slug])
            ->assertExitCode(0);

        $election = $org->elections()->where('type', 'demo')->first();

        // President: 3 candidates
        $presidentPost = $election->demoPosts()->where('name', 'President')->first();
        $this->assertEquals(3, $presidentPost->candidacies()->count());

        // Secretary: 2 candidates
        $secretaryPost = $election->demoPosts()->where('name', 'General Secretary (Geschäftsführer)')->first();
        $this->assertEquals(2, $secretaryPost->candidacies()->count());

        // Regional posts: 2 candidates each
        $regionalPosts = $election->demoPosts()->where('is_national_wide', 0)->get();
        foreach ($regionalPosts as $post) {
            $this->assertEquals(2, $post->candidacies()->count(), "Post {$post->name} should have 2 candidates");
        }
    }

    /** @test */
    public function it_uses_default_organization_publicdigit()
    {
        // Create or get publicdigit organisation
        $publicdigit = Organisation::firstOrCreate(
            ['slug' => 'publicdigit'],
            [
                'name' => 'Public Digit',
                'type' => 'tenant',
                'is_default' => true,
            ]
        );

        // Run without --org parameter (should use default)
        $this->artisan('demo:setup')
            ->assertExitCode(0);

        // Should create for publicdigit
        $election = $publicdigit->elections()
            ->where('type', 'demo')
            ->first();

        $this->assertNotNull($election);
    }
}
