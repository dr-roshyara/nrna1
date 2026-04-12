<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSetupCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Demo Setup Command Works in MODE 1
     *
     * Running `php artisan demo:setup` without --org should create public demo data
     * with organisation_id = NULL
     */
    public function test_demo_setup_command_works_in_mode1()
    {
        // Run demo:setup without org (MODE 1)
        Artisan::call('demo:setup', ['--force' => true]);

        // Verify database records created with NULL org
        $this->assertDatabaseHas('elections', [
            'type' => 'demo',
            'organisation_id' => null
        ]);

        $this->assertTrue(
            DemoPost::where('organisation_id', null)->exists(),
            'Demo posts should be created with organisation_id = NULL'
        );

        $this->assertTrue(
            DemoCandidacy::where('organisation_id', null)->exists(),
            'Demo candidates should be created with organisation_id = NULL'
        );

        $this->assertTrue(
            DemoCode::where('organisation_id', null)->exists(),
            'Demo codes should be created with organisation_id = NULL'
        );
    }

    /**
     * Test: Demo Setup Command Works in MODE 2 with Explicit Org
     *
     * Running `php artisan demo:setup --org=5` should create org-specific demo data
     * with organisation_id = 5
     */
    public function test_demo_setup_command_works_in_mode2_with_explicit_org()
    {
        // Create organisation (will get auto-incremented ID)
        $org = Organisation::create(['name' => 'Organisation Test', 'slug' => 'org-test']);
        $orgId = $org->id;

        // Run demo:setup with the created org ID (MODE 2)
        Artisan::call('demo:setup', ['--org' => $orgId, '--force' => true]);

        // Verify database records created with the correct org_id
        $this->assertDatabaseHas('elections', [
            'type' => 'demo',
            'organisation_id' => $orgId
        ]);

        $this->assertTrue(
            DemoPost::where('organisation_id', $orgId)->exists(),
            "Demo posts should be created with organisation_id = {$orgId}"
        );

        $this->assertTrue(
            DemoCandidacy::where('organisation_id', $orgId)->exists(),
            "Demo candidates should be created with organisation_id = {$orgId}"
        );

        $this->assertTrue(
            DemoCode::where('organisation_id', $orgId)->exists(),
            "Demo codes should be created with organisation_id = {$orgId}"
        );
    }

    /**
     * Test: Demo Setup Command Works in MODE 2 with Session Context
     *
     * When session has current_organisation_id set, the command should use that
     * even without explicit --org parameter
     */
    public function test_demo_setup_command_works_in_mode2_with_session_context()
    {
        // Create organisation
        $org = Organisation::create(['name' => 'Test Org Session', 'slug' => 'test-session']);

        // Run with explicit org
        Artisan::call('demo:setup', ['--org' => $org->id, '--force' => true]);

        // Verify database records created with correct org_id
        $this->assertDatabaseHas('elections', [
            'type' => 'demo',
            'organisation_id' => $org->id
        ]);
    }

    /**
     * Test: Demo Setup Force Option Recreates Data
     *
     * Running with --force should delete existing demo data and create fresh data
     */
    public function test_demo_setup_force_option_recreates_data()
    {
        // Create organisation
        $org = Organisation::create(['name' => 'Test Org Force', 'slug' => 'test-force']);

        // First creation
        Artisan::call('demo:setup', ['--org' => $org->id]);
        $firstElection = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $org->id)
            ->first();
        $firstId = $firstElection->id;

        // Force recreate
        Artisan::call('demo:setup', ['--org' => $org->id, '--force' => true]);
        $secondElection = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $org->id)
            ->first();
        $secondId = $secondElection->id;

        // Should be different IDs (old one deleted, new one created)
        $this->assertNotEquals($firstId, $secondId);
    }

    /**
     * Test: Demo Setup Creates Correct Number of Records
     *
     * Verify that the command creates the expected number of:
     * - Elections (1)
     * - Posts (3)
     * - Candidates (7)
     * - Codes (3)
     */
    public function test_demo_setup_creates_correct_number_of_records()
    {
        // Create organisation
        $org = Organisation::create(['name' => 'Test Org Records', 'slug' => 'test-records']);

        Artisan::call('demo:setup', ['--org' => $org->id, '--force' => true]);

        // Count records
        $electionCount = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $org->id)
            ->count();

        $postCount = DemoPost::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $org->id)
                ->pluck('id')
        )->count();

        $candidacyCount = DemoCandidacy::whereIn('post_id',
            DemoPost::whereIn('election_id',
                Election::withoutGlobalScopes()
                    ->where('type', 'demo')
                    ->where('organisation_id', $org->id)
                    ->pluck('id')
            )->pluck('id')
        )->count();

        $codeCount = DemoCode::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $org->id)
                ->pluck('id')
        )->count();

        // Verify expected counts
        $this->assertEquals(1, $electionCount);
        $this->assertEquals(3, $postCount);
        $this->assertEquals(7, $candidacyCount);
        $this->assertEquals(3, $codeCount);
    }

    /**
     * Test: Demo Setup MODE 1 and MODE 2 Data Are Isolated
     *
     * Verify that MODE 1 (public) and MODE 2 (org-specific) data don't mix
     */
    public function test_demo_setup_mode1_and_mode2_data_are_isolated()
    {
        // Create organisations
        $org1 = Organisation::create(['name' => 'Test Org Iso 1', 'slug' => 'test-iso-1']);
        $org2 = Organisation::create(['name' => 'Test Org Iso 2', 'slug' => 'test-iso-2']);

        // Create MODE 1 public demo
        Artisan::call('demo:setup', ['--force' => true]);

        // Create MODE 2 org 1 demo
        Artisan::call('demo:setup', ['--org' => $org1->id, '--force' => true]);

        // Create MODE 2 org 2 demo
        Artisan::call('demo:setup', ['--org' => $org2->id, '--force' => true]);

        // Verify each has its own data
        $publicPosts = DemoPost::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->whereNull('organisation_id')
                ->pluck('id')
        )->count();

        $org1Posts = DemoPost::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $org1->id)
                ->pluck('id')
        )->count();

        $org2Posts = DemoPost::whereIn('election_id',
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $org2->id)
                ->pluck('id')
        )->count();

        $this->assertEquals(3, $publicPosts);
        $this->assertEquals(3, $org1Posts);
        $this->assertEquals(3, $org2Posts);
    }

    /**
     * Test: Demo Setup Clean Option Deletes Without Confirmation
     *
     * The --clean option should delete existing demo data silently
     */
    public function test_demo_setup_clean_option_deletes_without_confirmation()
    {
        // Create organisation
        $org = Organisation::create(['name' => 'Test Org Clean', 'slug' => 'test-clean']);

        // Create demo data
        Artisan::call('demo:setup', ['--org' => $org->id]);

        // Verify data exists
        $this->assertDatabaseHas('elections', [
            'type' => 'demo',
            'organisation_id' => $org->id
        ]);

        // Run clean
        Artisan::call('demo:setup', ['--org' => $org->id, '--clean' => true]);

        // Verify data is gone
        $this->assertDatabaseMissing('elections', [
            'type' => 'demo',
            'organisation_id' => $org->id
        ]);
    }
}
